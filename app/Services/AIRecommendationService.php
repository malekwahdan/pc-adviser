<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;

class AIRecommendationService
{
    protected $mistralApiKey;
    protected $mistralApiUrl = 'https://api.mistral.ai/v1/chat/completions';

    public function __construct()
    {
        $this->mistralApiKey = env('MISTRAL_API_KEY');
    }

    /**
     * Get PC recommendations based on user query
     *
     * @param string $userQuery The user's query about what PC they need
     * @return array Array of recommended products and reasoning
     */
    public function getRecommendations(string $userQuery): array
    {
        // Step 1: Extract user requirements using Mistral AI
        $requirements = $this->extractRequirements($userQuery);

        // Step 2: Query products based on extracted requirements
        $products = $this->findMatchingProducts($requirements);

        // Step 3: Rank products using Mistral AI
        $recommendations = $this->rankProducts($products, $requirements, $userQuery);

        return $recommendations;
    }

    /**
     * Extract requirements from user query using Mistral AI
     */
    private function extractRequirements(string $userQuery): array
    {
        $systemPrompt = "You are a PC expert helping customers find the right computer.
        Extract the following information from the user query:
        1. Use case (gaming, video editing, programming, office work, etc.)
        2. Budget range (if mentioned)
        3. Performance requirements (high-end, mid-range, entry-level)
        4. Any specific brand preferences
        5. Any specific features mentioned (graphics card, RAM, storage type, etc.)

        Return the extracted information as a JSON object with these keys:
        use_case, budget_min, budget_max, performance_level, preferred_brands, required_features";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->mistralApiKey,
            'Content-Type' => 'application/json',
        ])->post($this->mistralApiUrl, [
            'model' => 'mistral-large-latest',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userQuery]
            ],
            'temperature' => 0.2,
            'max_tokens' => 500,
        ]);

        if ($response->successful()) {
            $content = $response->json()['choices'][0]['message']['content'];
            // Extract JSON from the response
            preg_match('/\{.*\}/s', $content, $matches);
            if (!empty($matches)) {
                return json_decode($matches[0], true);
            }
        }

        // Fallback default requirements
        return [
            'use_case' => 'general',
            'budget_min' => 0,
            'budget_max' => 10000,
            'performance_level' => 'mid-range',
            'preferred_brands' => [],
            'required_features' => []
        ];
    }

    /**
     * Find products matching the extracted requirements
     */
    private function findMatchingProducts(array $requirements): Collection
    {
        $query = Product::query()
            ->where('status', 'in_stock')
            ->where('stock_quantity', '>', 0);

        // Filter by budget if specified
        if (!empty($requirements['budget_max'])) {
            $query->where('price', '<=', $requirements['budget_max']);
        }

        if (!empty($requirements['budget_min'])) {
            $query->where('price', '>=', $requirements['budget_min']);
        }

        // Filter by brands if specified
        if (!empty($requirements['preferred_brands'])) {
            $query->whereHas('brand', function ($q) use ($requirements) {
                $q->whereIn('name', $requirements['preferred_brands']);
            });
        }

        // Get category ID based on use case
        if (!empty($requirements['use_case'])) {
            $categoryMappings = [
                'gaming' => 'Gaming PCs',
                'video editing' => 'Workstation PCs',
                'programming' => 'Development PCs',
                'office work' => 'Office PCs',
                'graphic design' => 'Workstation PCs',
                '3d modeling' => 'Workstation PCs',
                'streaming' => 'Gaming PCs',
                'general' => null
            ];

            $categoryName = $categoryMappings[$requirements['use_case']] ?? null;

            if ($categoryName) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Get products with metadata for feature matching
        $products = $query->with(['specifications', 'brand', 'category'])->get();

        // If we have too few results, remove category filter and try again
        if ($products->count() < 3) {
            $query = Product::query()
                ->where('status', 'in_stock')
                ->where('stock_quantity', '>', 0);

            if (!empty($requirements['budget_max'])) {
                $query->where('price', '<=', $requirements['budget_max']);
            }

            if (!empty($requirements['budget_min'])) {
                $query->where('price', '>=', $requirements['budget_min']);
            }

            $products = $query->with(['specifications', 'brand', 'category'])->get();
        }

        return $products;
    }

    /**
     * Rank products using Mistral AI
     */
    private function rankProducts(Collection $products, array $requirements, string $userQuery): array
    {
        // Convert products to JSON for the AI
        $productsJson = $products->map(function ($product) {
            $specs = [];
            if ($product->specifications) {
                foreach ($product->specifications as $spec) {
                    $specs[$spec->name] = $spec->value;
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'brand' => $product->brand ? $product->brand->name : 'Unknown',
                'category' => $product->category ? $product->category->name : 'Unknown',
                'description' => $product->description,
                'specifications' => $specs
            ];
        })->toArray();

        $systemPrompt = "You are a PC expert helping customers find the right computer.
        Based on the user's query and the available products, rank the top 3 products that best match their needs.
        Explain why each product is recommended, highlighting how it matches their specific requirements.

        Return your response in JSON format:
        {
            \"recommendations\": [
                {
                    \"product_id\": 1,
                    \"reasoning\": \"This PC is recommended because...\",
                    \"match_score\": 95
                },
                ...
            ]
        }";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->mistralApiKey,
            'Content-Type' => 'application/json',
        ])->post($this->mistralApiUrl, [
            'model' => 'mistral-large-latest',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => "User Query: {$userQuery}\n\nUser Requirements: " . json_encode($requirements) . "\n\nAvailable Products: " . json_encode($productsJson)]
            ],
            'temperature' => 0.2,
            'max_tokens' => 1000,
        ]);

        if ($response->successful()) {
            $content = $response->json()['choices'][0]['message']['content'];
            // Extract JSON from the response
            preg_match('/\{.*\}/s', $content, $matches);
            if (!empty($matches)) {
                $recommendations = json_decode($matches[0], true);

                // Enhance response with full product details
                if (isset($recommendations['recommendations'])) {
                    foreach ($recommendations['recommendations'] as $key => $recommendation) {
                        $productId = $recommendation['product_id'];
                        $product = $products->firstWhere('id', $productId);

                        if ($product) {
                            $recommendations['recommendations'][$key]['product'] = [
                                'id' => $product->id,
                                'name' => $product->name,
                                'slug' => $product->slug,
                                'price' => $product->price,
                                'sale_price' => $product->sale_price,
                                'thumbnail' => $product->thumbnail,
                                'description' => $product->description,
                                'brand' => $product->brand ? $product->brand->name : null,
                                'category' => $product->category ? $product->category->name : null
                            ];
                        }
                    }
                }

                return $recommendations;
            }
        }

        // Fallback manual ranking if AI ranking fails
        $result = ['recommendations' => []];

        foreach ($products->take(3) as $index => $product) {
            $result['recommendations'][] = [
                'product_id' => $product->id,
                'reasoning' => "This product matches your general requirements.",
                'match_score' => 100 - ($index * 10),
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'thumbnail' => $product->thumbnail,
                    'description' => $product->description,
                    'brand' => $product->brand ? $product->brand->name : null,
                    'category' => $product->category ? $product->category->name : null
                ]
            ];
        }

        return $result;
    }
}
