<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{

    public function index(Request $request)
    {
        



        $shippingMethods = ShippingMethod::all();

        return view('admin.shipping_methods.index', compact('shippingMethods'));
    }


    public function create()
    {
        return view('admin.shipping_methods.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_delivery_time' => 'nullable|string|max:255',

        ]);

        ShippingMethod::create($validated);

        return redirect()
            ->route('shipping-methods.index')
            ->with('success', 'Shipping method created successfully.');
    }


    public function show(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping_methods.show', compact('shippingMethod'));
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping_methods.edit', compact('shippingMethod'));
    }


    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_delivery_time' => 'nullable|string|max:255',

        ]);

        $shippingMethod->update($validated);

        return redirect()
            ->route('shipping-methods.index')
            ->with('success', 'Shipping method updated successfully.');
    }


    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return redirect()
            ->route('shipping-methods.index')
            ->with('success', 'Shipping method deleted successfully.');
    }
}
