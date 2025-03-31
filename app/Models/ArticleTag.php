<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class ArticleTag extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type', // category or tag
        'parent_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag_pivot')
                    ->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(ArticleTag::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ArticleTag::class, 'parent_id');
    }

    public function scopeCategories($query)
    {
        return $query->where('type', 'category');
    }

    public function scopeTags($query)
    {
        return $query->where('type', 'tag');
    }

    public function scopeRootCategories($query)
    {
        return $query->categories()->whereNull('parent_id');
    }

    public function getArticleCountAttribute()
    {
        return $this->articles()->count();
    }

    public function getChildCategoriesAttribute()
    {
        return $this->children()->categories()->get();
    }

    public function getRelatedTagsAttribute()
    {
        return static::where('type', 'tag')
            ->whereHas('articles', function($query) {
                $query->whereIn('id', $this->articles->pluck('id'));
            })
            ->where('id', '!=', $this->id)
            ->get();
    }
} 