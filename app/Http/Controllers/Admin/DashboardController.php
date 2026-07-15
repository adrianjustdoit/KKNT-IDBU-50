<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\GalleryPhoto;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'gallery_photos' => GalleryPhoto::count(),
        ];

        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }
}
