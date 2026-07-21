<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use App\Http\Requests\NewsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_published', $request->status == 'published' ? true : false);
        }

        $news = $query->latest()->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(NewsRequest $request)
    {
        $data = $request->sanitized();
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('news', 'public');
            \App\Jobs\OptimizeImage::dispatch($data['image_path'], 800);
        }

        $data['author_id'] = auth()->id();

        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        $news = News::create($data);
        
        $this->syncTags($news, $request->tags);

        return redirect()->route((auth()->user()->isAdmin() ? 'admin.' : 'author.') . 'news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $data = $request->sanitized();

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $data['image_path'] = $request->file('image')->store('news', 'public');
            \App\Jobs\OptimizeImage::dispatch($data['image_path'], 800);
        }

        if ($data['is_published'] && !$news->is_published && !$news->published_at) {
            $data['published_at'] = now();
        }

        // If title changes, user wants to manually regenerate slug, they can just empty it via some custom logic,
        // but as requested, slug doesn't auto-change unless emptied. (Handled in Boot method if we empty it)
        
        $news->update($data);
        
        $this->syncTags($news, $request->tags);

        return redirect()->route((auth()->user()->isAdmin() ? 'admin.' : 'author.') . 'news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }
        
        $news->delete();

        return redirect()->route((auth()->user()->isAdmin() ? 'admin.' : 'author.') . 'news.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function syncTags(News $news, ?string $tagsJson)
    {
        if (!$tagsJson) {
            $news->tags()->sync([]);
            return;
        }

        $tagsArray = json_decode($tagsJson, true);
        if (!is_array($tagsArray)) {
            $news->tags()->sync([]);
            return;
        }

        $tagIds = [];
        foreach ($tagsArray as $tagData) {
            if (isset($tagData['value'])) {
                $tagName = $tagData['value'];
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }

        $news->tags()->sync($tagIds);
    }

    public function uploadContentImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('news-content', 'public');
        
        // Dispatch job to optimize the content image in the background (max width 1200px)
        \App\Jobs\OptimizeImage::dispatch($path, 1200);

        // Return relative URL so it works regardless of APP_URL config
        $url = '/storage/' . $path;

        return response()->json(['url' => $url]);
    }
}
