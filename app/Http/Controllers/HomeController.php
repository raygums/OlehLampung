<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->take(3)->get();
        $featuredProducts = Product::active()->featured()->with('category')->take(4)->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
