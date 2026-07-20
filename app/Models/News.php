<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'event_date', 
        'location', 'image_path', 'author_id', 'category_id', 'is_published', 'published_at'
    ];

    protected $casts = [
        'event_date' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => strip_tags($this->content),
            'location' => $this->location,
        ];
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function getExcerptAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        return Str::limit(strip_tags($this->content), 150);
    }

    protected static function booted()
    {
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $slug = Str::slug($news->title);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $news->slug = $slug;
            }
        });
    }
}
