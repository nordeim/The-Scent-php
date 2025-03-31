<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class Mood extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon_class',
        'featured'
    ];

    protected $casts = [
        'featured' => 'boolean'
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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_moods')
                    ->withPivot('effectiveness')
                    ->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getEffectivenessAttribute()
    {
        return $this->pivot ? $this->pivot->effectiveness : null;
    }

    public function getProductsByEffectivenessAttribute()
    {
        return $this->products()
            ->orderByPivot('effectiveness', 'desc')
            ->get();
    }
} 