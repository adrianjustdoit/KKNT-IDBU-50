<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductShowController extends Controller
{
    public function show(Product $product)
    {
        // Only show active products
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('images');

        return view('product-3d', compact('product'));
    }

    public function eksplorasiKompos()
    {
        $totalFrames = count(glob(public_path('images/kompos-frames/frame-*.png')));

        return view('kompos-eksplorasi', [
            'totalFrames' => $totalFrames,
        ]);
    }
}
