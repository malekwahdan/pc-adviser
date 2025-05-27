<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {   $categories = Category::all();
        $query = Product::with(['category', 'brand']);

        // Apply search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }



        $products = $query->paginate(10);


        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();


        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate all input fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:in_stock,out_of_stock,discontinued',
            'featured' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'array|min:1|nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',

        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $validated['featured'] = $request->has('featured') ? 1 : 0;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
         if ($request->hasFile('images') && count($request->file('images')) > 0) {

            $firstImage = $request->file('thumbnail');
            $validated['thumbnail'] = $firstImage->store('products', 'public');
        }


        // Create the product
        $product = Product::create($validated);

        // Store product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }



        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['images']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();

        $product->load(['images']);

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }


public function update(Request $request, Product $product)
{
    // Validation remains the same
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|unique:products,slug,'.$product->id,
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0',
        'cost' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id',
        'status' => 'required|in:in_stock,out_of_stock,discontinued',
        'featured' => 'boolean',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'images' => 'sometimes|array|nullable',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
    ]);

    // Start database transaction
    DB::beginTransaction();

    try {

        $validated['featured'] = $request->has('featured') ? true : false;


        if ($request->hasFile('thumbnail')) {

            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }


        $product->update($validated);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }


        DB::commit();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Error updating product: '.$e->getMessage());
    }
}





    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
    return back()->with('error', 'Cannot delete product linked to orders.');
}

       

        foreach ($product->images as $image) {
              if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }
            $image->delete();
        }
        if ($product->thumbnail) {
        Storage::disk('public')->delete($product->thumbnail);
    }



        // Delete the product
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }




}
