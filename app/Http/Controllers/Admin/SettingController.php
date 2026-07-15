<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_headline' => 'nullable|string|max:255',
            'hero_subheadline' => 'nullable|string|max:500',
            'tentang_text' => 'nullable|string|max:2000',
            'tentang_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'stat_sampah' => 'nullable|integer|min:0|max:9999999',
            'stat_produk' => 'nullable|integer|min:0|max:9999999',
            'stat_warga' => 'nullable|integer|min:0|max:9999999',
            'contact_address' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_instagram' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:30',
        ]);

        $fields = [
            'hero_headline' => 'text',
            'hero_subheadline' => 'text',
            'tentang_text' => 'textarea',
            'stat_sampah' => 'text',
            'stat_produk' => 'text',
            'stat_warga' => 'text',
            'contact_address' => 'text',
            'contact_email' => 'text',
            'contact_instagram' => 'text',
            'contact_phone' => 'text',
        ];

        foreach ($fields as $key => $type) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key), $type);
            }
        }

        if ($request->hasFile('tentang_image')) {
            $oldImage = SiteSetting::get('tentang_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('tentang_image')->store('settings', 'public');
            SiteSetting::set('tentang_image', $path, 'image');
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
