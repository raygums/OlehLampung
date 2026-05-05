<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereIn('slug', ['kopi', 'makanan'])
            ->orderBy('sort_order')
            ->get();
        $featuredProducts = Product::active()->featured()
            ->whereHas('category', fn($q) => $q->whereIn('slug', ['kopi', 'makanan']))
            ->with('category')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
