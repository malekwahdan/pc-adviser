<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the wishlist items.
     */
    public function index()
    {
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if product already in wishlist
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Product is already in your wishlist.');
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(Wishlist $wishlist)
    {
       
        if ($wishlist->user_id !== Auth::id()) {
            abort(404);
        }

        $wishlist->delete();

        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist.');
    }

    /**
     * Clear all items from the wishlist.
     */
    public function clearAll()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return redirect()->route('wishlist.index')->with('success', 'Your wishlist has been cleared.');
    }



}
