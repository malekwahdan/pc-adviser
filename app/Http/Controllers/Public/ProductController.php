<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   


    public function index(Request $request)
{
    // Get all brands and categories for the filter sidebar
    $brands = Brand::all();
    $categories = Category::all();

    // Get max price for the price filter
    $maxPrice = Product::max('price');

    // Start building the query
    $query = Product::query()->where('status', 'in_stock');

    // Store active category for display
    $activeCategory = null;

    // Check if coming from category link (using slug)
    if ($request->has('category')) {
        $categorySlug = $request->category;

        // Find the category by slug
        $category = Category::where('slug', $categorySlug)->first();

        if ($category) {
            // Filter products by this category
            $query->where('category_id', $category->id);

            // Store active category
            $activeCategory = $category;
        }
    }

    // Apply search filter
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Apply price filter
    if ($request->has('max_price')) {
        $query->where(function($q) use ($request) {
            $q->where('price', '<=', $request->max_price)
              ->orWhere(function($sq) use ($request) {
                  $sq->whereNotNull('sale_price')
                     ->where('sale_price', '<=', $request->max_price);
              });
        });
    }

    // Apply brand filter
    if ($request->has('brands') && !empty($request->brands)) {
        $query->whereIn('brand_id', $request->brands);
    }


    if (!$request->has('category') && $request->has('categories') && !empty($request->categories)) {
        $query->whereIn('category_id', $request->categories);
    }

    // Apply sorting
    if ($request->has('sort')) {
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popularity':
            default:
                $query->orderBy('featured', 'desc')
                      ->orderBy('stock_quantity', 'desc');
                break;
        }
    } else {
        // Default sorting
        $query->orderBy('featured', 'desc')
              ->orderBy('stock_quantity', 'desc');
    }

    // Get paginated results
    $products = $query->paginate(9);

    return view('products.index', [
        'products' => $products,
        'brands' => $brands,
        'categories' => $categories,
        'maxPrice' => $maxPrice,
        'activeCategory' => $activeCategory
    ]);
}

    /**
     * Display the product details
     */
    public function show($slug)
{
    $product = Product::where('slug', $slug)
        ->with(['brand', 'category', 'Images', 'reviews.user'])
        ->firstOrFail();

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();

    $shippingMethods = ShippingMethod::all();

    return view('products.show', compact('product', 'relatedProducts', 'shippingMethods'));
}


}
