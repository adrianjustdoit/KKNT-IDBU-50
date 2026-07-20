<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function video()
    {
        $heroVideoUrl = SiteSetting::get('hero_video_url');
        $profilVideoUrl = SiteSetting::get('profil_video_url');
        return view('admin.media.video', compact('heroVideoUrl', 'profilVideoUrl'));
    }

    public function updateVideo(Request $request)
    {
        $request->validate([
            'hero_video_url' => ['nullable', 'url', 'regex:/^https?:\/\/([\w-]+\.)*(' . implode('|', array_map('preg_quote', [
                'youtube.com', 'youtu.be', 'drive.google.com', 'vimeo.com',
            ])) . '|' . preg_quote(parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost') . ')\//i'],
            'profil_video_url' => ['nullable', 'url', 'regex:/^https?:\/\/([\w-]+\.)*(' . implode('|', array_map('preg_quote', [
                'youtube.com', 'youtu.be', 'drive.google.com', 'vimeo.com',
            ])) . ')\//i'],
        ], [
            'hero_video_url.regex' => 'URL video harus dari YouTube, Google Drive, Vimeo, atau domain website ini.',
            'profil_video_url.regex' => 'URL video harus dari YouTube, Google Drive, atau Vimeo.',
        ]);

        SiteSetting::set('hero_video_url', $request->hero_video_url, 'url');
        SiteSetting::set('profil_video_url', $request->profil_video_url, 'url');

        return back()->with('success', 'Video berhasil diperbarui!');
    }

    public function gallery()
    {
        $photos = GalleryPhoto::ordered()->get();
        return view('admin.media.gallery', compact('photos'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=10,min_height=10',
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photos')) {
            $maxOrder = GalleryPhoto::max('sort_order') ?? -1;
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('gallery', 'public');
                GalleryPhoto::create([
                    'title' => $request->title,
                    'image_path' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Foto berhasil ditambahkan!');
    }

    public function destroyGallery(GalleryPhoto $photo)
    {
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }

    public function bulkDestroyGallery(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:gallery_photos,id'
        ]);

        $photos = GalleryPhoto::whereIn('id', $request->photo_ids)->get();
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->image_path);
            $photo->delete();
        }

        return back()->with('success', count($photos) . ' foto berhasil dihapus!');
    }
}
