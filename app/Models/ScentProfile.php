<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class ScentProfile extends Model
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
        return $this->belongsToMany(Product::class, 'product_scent_profiles')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getIntensityAttribute()
    {
        return $this->pivot ? $this->pivot->intensity : null;
    }

    public function getProductsByIntensityAttribute()
    {
        return $this->products()
            ->orderByPivot('intensity', 'desc')
            ->get();
    }

    public function getCompatibleProfilesAttribute()
    {
        return static::where('id', '!=', $this->id)
            ->whereHas('products', function($query) {
                $query->whereIn('id', $this->products->pluck('id'));
            })
            ->get();
    }
} 