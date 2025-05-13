<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::where('featured', true)
                                 ->take(4)
                                 ->get();

        $featuredProducts = Product::where('featured', true)
            ->where('status', 'in_stock')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();


        return view('welcome', compact('featuredProducts','featuredCategories'));
    }
}
