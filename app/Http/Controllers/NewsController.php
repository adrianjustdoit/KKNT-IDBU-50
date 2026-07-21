<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::has('news')->get();
        $tags = Tag::has('news')->get();

        if ($request->has('q') && $request->q !== '') {
            $scout = News::search($request->q);
            $scout->query(function ($query) use ($request) {
                $query->where('is_published', true)->with(['category', 'tags']);
                
                if ($request->has('category') && $request->category !== '') {
                    $query->whereHas('category', function ($q) use ($request) {
                        $q->where('slug', $request->category);
                    });
                }

                if ($request->has('tag') && $request->tag !== '') {
                    $query->whereHas('tags', function ($q) use ($request) {
                        $q->where('slug', $request->tag);
                    });
                }
            });
            
            $news = $scout->paginate(9)->withQueryString();
        } else {
            $query = News::published()->with(['category', 'tags']);

            if ($request->has('category') && $request->category !== '') {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            if ($request->has('tag') && $request->tag !== '') {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->where('slug', $request->tag);
                });
            }

            $news = $query->latest('published_at')->paginate(9)->withQueryString();
        }
        
        return view('news.index', compact('news', 'categories', 'tags'));
    }

    public function show(News $news)
    {
        if (!$news->is_published || ($news->published_at && $news->published_at > now())) {
            abort(404);
        }

        $news->increment('view_count');

        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->when($news->category_id, function($q) use ($news) {
                $q->where('category_id', $news->category_id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
