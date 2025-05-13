<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function processMessage(Request $request)
    {
        $userMessage = $request->input('message');
       

        try {
            // Get product data
            $products = Product::with(['brand', 'category'])
                ->where('status', 'in_stock')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'description' => $product->description,
                        'brand' => $product->brand->name ?? 'Unknown',
                        'category' => $product->category->name ?? 'Unknown',
                        'features' => $this->extractFeatures($product->description),
                        'url' => url('/products/' . $product->slug),
                    ];
                });




            $model = env('HUGGING_FACE_MODEL', 'mistralai/Mixtral-8x7B-Instruct-v0.1');



            $prompt = "### Instruction:
You are a helpful PC sales assistant. Given the user's request and available products, recommend 1-3 suitable PCs. Include each product's ID, name, price, and a brief explanation of why it suits their needs.

IMPORTANT: For each product you recommend, put the product name in square brackets [like this] so it can be made clickable in the interface.

Example format of your response:
1. [Product Name] (ID: 123) - $1299
   This computer has excellent specs for gaming with its powerful GPU and fast processor.

Keep your response concise and focused.

User request: $userMessage

Available PCs: " . json_encode($products, JSON_PRETTY_PRINT) . "

### Response:";

            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . env('HUGGING_FACE_API_KEY', ''),
                'Content-Type' => 'application/json',
            ])->post('https://api-inference.huggingface.co/models/' . $model, [
                'inputs' => $prompt,
                'parameters' => [
                    'max_new_tokens' => 500,
                    'temperature' => 0.7,
                    'return_full_text' => false
                ]
            ]);



            if ($response->successful()) {

                $responseData = $response->json();

                if (isset($responseData[0]['generated_text'])) {
                    $aiResponse = $responseData[0]['generated_text'];
                } else {

                    $aiResponse = is_string($responseData) ? $responseData : json_encode($responseData);
                }


                $aiResponse = $this->makeProductNamesClickable($aiResponse, $products);


                return response()->json(['response' => $aiResponse]);
            } else {



                if (env('OPENAI_API_KEY')) {

                    return $this->getOpenAIResponse($userMessage, $products);
                }

                return response()->json([
                    'response' => "I'm sorry, I couldn't process your request. Here are some options that might interest you:\n\n" .
                    $this->getFallbackRecommendations($products)
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'response' => "Sorry for the inconvenience. Here are some products that might match your needs:\n\n" .
                $this->getFallbackRecommendations($products)
            ]);
        }
    }


    private function getOpenAIResponse($userMessage, $products)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful PC sales assistant. Recommend suitable products based on customer needs. For each product recommendation, put the product name in square brackets [like this] so it can be made clickable. Be concise and specific.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "The customer said: \"$userMessage\"\n\nAvailable products: " . json_encode($products)
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                $aiResponse = $response->json()['choices'][0]['message']['content'];

                $aiResponse = $this->makeProductNamesClickable($aiResponse, $products);
                return response()->json(['response' => $aiResponse]);
            } else {

                return response()->json([
                    'response' => "I'm sorry, I couldn't process your request. Here are some options that might interest you:\n\n" .
                    $this->getFallbackRecommendations($products)
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'response' => "Sorry for the inconvenience. Here are some products that might match your needs:\n\n" .
                $this->getFallbackRecommendations($products)
            ]);
        }
    }


private function makeProductNamesClickable($response, $products)
{
    return preg_replace_callback('/\[([^\]]+)\]/', function ($matches) use ($products) {
        $aiName = strtolower($matches[1]);


        $product = $products->first(function ($item) use ($aiName) {
            $productName = strtolower($item['name']);


            if (stripos($productName, $aiName) !== false) {
                return true;
            }


            $aiKeywords = explode(' ', preg_replace('/[^a-zA-Z0-9 ]/', '', $aiName));
            $matchCount = 0;
            foreach ($aiKeywords as $keyword) {
                if (strlen($keyword) > 2 && str_contains($productName, $keyword)) {
                    $matchCount++;
                }
            }


            return $matchCount >= 3;
        });

        if ($product) {
            return '<a href="' . $product['url'] . '" class="product-link">' . $matches[1] . '</a>';
        }

        return $matches[0];
    }, $response);
}



    /**
     * Extract key features from product description
     */
    private function extractFeatures($description)
    {
        $features = [];
        $patterns = [
            'processor' => '/(?:intel|amd|ryzen|core i[3579]|i[3579]-\d{4,5})/i',
            'ram' => '/\b(?:\d+\s*GB|\d+\s*TB)\s+(?:RAM|Memory)\b/i',
            'storage' => '/\b(?:\d+\s*GB|\d+\s*TB)\s+(?:SSD|HDD|Storage|Drive)\b/i',
            'gpu' => '/(?:nvidia|geforce|radeon|rtx|gtx)\s+\d+/i',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $description, $matches)) {
                $features[$key] = trim($matches[0]);
            }
        }

        return $features;
    }


    private function getFallbackRecommendations($products)
    {

        if ($products->isEmpty()) {
            return "I'm sorry, we don't have any PC products in stock at the moment.";
        }


        $budget = $products->where('price', '<', 800)->shuffle()->first();
        $midRange = $products->whereBetween('price', [800, 1500])->shuffle()->first();
        $highEnd = $products->where('price', '>', 1500)->shuffle()->first();

        $recommendations = collect([$budget, $midRange, $highEnd])
            ->filter()
            ->take(3);


        if ($recommendations->isEmpty()) {
            $recommendations = $products->shuffle()->take(3);
        }

        $response = "";
        foreach ($recommendations as $index => $product) {
            $priceInfo = $product['sale_price']
                ? "\${$product['sale_price']} (was \${$product['price']})"
                : "\${$product['price']}";


            $description = "";
            if (isset($product['features']['processor'])) {
                $description .= " with " . $product['features']['processor'];
            }
            if (isset($product['features']['ram'])) {
                $description .= ", " . $product['features']['ram'];
            }


            $productLink = '<a href="' . $product['url'] . '" class="product-link">' . $product['name'] . '</a>';

            $response .= ($index+1) . ". {$productLink} (ID: {$product['id']}) - {$priceInfo}{$description}\n";
        }

        return $response;
    }
}
