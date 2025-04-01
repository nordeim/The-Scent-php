<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class Article extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'published_at',
        'status'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean'
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ArticleTag::class, 'article_tag_pivot')
                    ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? Storage::url($this->featured_image) : null;
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // Assuming average reading speed of 200 words per minute
    }

    public function getRelatedArticlesAttribute()
    {
        return static::where('id', '!=', $this->id)
            ->whereHas('tags', function($query) {
                $query->whereIn('id', $this->tags->pluck('id'));
            })
            ->published()
            ->latest()
            ->take(3)
            ->get();
    }
} 