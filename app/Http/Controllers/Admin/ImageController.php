<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        $productId = $product->id;
        $imageId = $image->id;



        if ($image->product_id !== $product->id) {

            return back()->with('error', 'Image does not belong to this product.');
        }

        DB::beginTransaction();

        try {

            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $image->delete();


            $productAfterDelete = Product::find($productId);

            if (!$productAfterDelete) {
                DB::rollBack();
                return back()->with('error', 'Product was unexpectedly deleted during image removal.');
            }

            DB::commit();

            return back()->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting image: ' . $e->getMessage());
        }
    }



}
