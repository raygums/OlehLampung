<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::whereIn('slug', ['kopi', 'makanan'])->orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereIn('slug', ['kopi', 'makanan'])->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('images');
        $data['slug'] = $this->uniqueSlug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_sale'] = $request->boolean('is_sale');
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
            $data['images'] = $imagePaths;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereIn('slug', ['kopi', 'makanan'])->orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('images');
        $data['slug'] = $this->uniqueSlug($request->name, $product->id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_sale'] = $request->boolean('is_sale');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
            $data['images'] = $imagePaths;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Generate a unique slug, appending -2, -3, etc. if needed.
     */
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 2;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
