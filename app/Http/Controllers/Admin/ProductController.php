<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::ordered()->with('primaryImage')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Preprocess links to prepend https:// if missing
        if ($request->filled('shopee_link')) {
            $shopee_link = $request->input('shopee_link');
            if (!preg_match('~^(?:f|ht)tps?://~i', $shopee_link)) {
                $request->merge(['shopee_link' => 'https://' . $shopee_link]);
            }
        }
        if ($request->filled('tokopedia_link')) {
            $tokopedia_link = $request->input('tokopedia_link');
            if (!preg_match('~^(?:f|ht)tps?://~i', $tokopedia_link)) {
                $request->merge(['tokopedia_link' => 'https://' . $tokopedia_link]);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|in:organik,kriya',
            'price' => 'required|numeric|min:0|max:999999999',
            'shopee_link' => 'nullable|url|max:500',
            'tokopedia_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=10,min_height=10',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Preprocess links to prepend https:// if missing
        if ($request->filled('shopee_link')) {
            $shopee_link = $request->input('shopee_link');
            if (!preg_match('~^(?:f|ht)tps?://~i', $shopee_link)) {
                $request->merge(['shopee_link' => 'https://' . $shopee_link]);
            }
        }
        if ($request->filled('tokopedia_link')) {
            $tokopedia_link = $request->input('tokopedia_link');
            if (!preg_match('~^(?:f|ht)tps?://~i', $tokopedia_link)) {
                $request->merge(['tokopedia_link' => 'https://' . $tokopedia_link]);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|in:organik,kriya',
            'price' => 'required|numeric|min:0|max:999999999',
            'shopee_link' => 'nullable|url|max:500',
            'tokopedia_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=10,min_height=10',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $product->id);
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $maxOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function deleteImage(ProductImage $image)
    {
        // Verify the image belongs to a product (authorization check)
        if (!$image->product) {
            abort(404);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }

    /**
     * Generate a unique slug, appending -2, -3, etc. if needed.
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 2;

        while (Product::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
