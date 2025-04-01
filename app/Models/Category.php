<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url'
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
        return $this->hasMany(Product::class);
    }

    public function essentialOils()
    {
        return $this->products()->essentialOils();
    }

    public function soaps()
    {
        return $this->products()->soaps();
    }

    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    public function getEssentialOilCountAttribute()
    {
        return $this->essentialOils()->count();
    }

    public function getSoapCountAttribute()
    {
        return $this->soaps()->count();
    }
} 