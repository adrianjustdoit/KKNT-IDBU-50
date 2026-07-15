<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()->latest('published_at')->paginate(9);
        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        if (!$news->is_published || ($news->published_at && $news->published_at > now())) {
            abort(404);
        }

        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
