<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::active()->ordered()->with(['images', 'primaryImage'])->get();
        $gallery = GalleryPhoto::ordered()->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        
        $latestNews = \App\Models\News::published()->latest('published_at')->take(3)->get();

        return view('home', compact('products', 'gallery', 'settings', 'latestNews'));
    }

    public function productDetail(Product $product)
    {
        // Only show active products to the public
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('images');
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'category' => $product->category,
            'price' => $product->formatted_price,
            'shopee_link' => $product->shopee_link,
            'tokopedia_link' => $product->tokopedia_link,
            'model_type' => $product->model_type,
            'waste_composition' => $product->waste_composition,
            'images' => $product->images->map(fn($img) => [
                'url' => asset('storage/' . $img->image_path),
                'is_primary' => $img->is_primary,
            ]),
        ]);
    }

    public function struktur()
    {
        $dpl = \App\Models\Member::where('role', 'DPL')->orderBy('created_at')->get();
        $bph = \App\Models\Member::whereIn('role', ['Koordes', 'Wakoordes', 'Sekretaris', 'Bendahara'])
                    ->orderBy('hierarchy_level')
                    ->orderBy('created_at')
                    ->get();
        
        $divisions = \App\Models\Member::whereIn('role', ['Kadiv', 'Anggota'])
                    ->orderBy('hierarchy_level')
                    ->orderBy('created_at')
                    ->get()
                    ->groupBy('division');

        return view('struktur', compact('dpl', 'bph', 'divisions'));
    }
}
