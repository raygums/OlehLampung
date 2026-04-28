<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Category filter
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        $query = match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('review_count', 'desc'),
            'rating' => $query->orderBy('rating', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $query = Product::active()->with('category');

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('short_description', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Sort
        $sort = $request->get('sort', 'relevance');
        if ($sort !== 'relevance') {
            $query = match ($sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'popular' => $query->orderBy('review_count', 'desc'),
                'rating' => $query->orderBy('rating', 'desc'),
                default => $query->orderBy('created_at', 'desc'),
            };
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'searchQuery' => $q,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('category')
            ->paginate(9);

        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }
}
