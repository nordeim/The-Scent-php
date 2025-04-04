<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name', 'slug', 'price', 'description', 'short_description',
        'image_url', 'featured', 'category_id', 'stock', 'sku',
        'product_type', 'origin_country', 'extraction_method', 'botanical_name',
        'safety_notes', 'usage_instructions', 'shelf_life', 'is_customizable'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_customizable' => 'boolean',
        'price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'stock' => 'integer',
        'review_count' => 'integer',
        'benefits' => 'array',
        'ingredients' => 'array',
        'is_natural' => 'boolean',
        'stress_relief_level' => 'integer'
    ];

    protected $appends = [
        'formatted_price',
        'average_rating',
        'stress_relief_score',
        'wellness_benefits'
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customizationOptions()
    {
        return $this->hasMany(SoapCustomizationOption::class);
    }

    public function essentialOilProperties()
    {
        return $this->hasMany(EssentialOilProperty::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function benefits()
    {
        return $this->hasMany(ProductBenefit::class);
    }

    public function additionalImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scentProfiles()
    {
        return $this->belongsToMany(ScentProfile::class, 'product_scent_profiles')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }

    public function moods()
    {
        return $this->belongsToMany(Mood::class, 'product_moods')
                    ->withPivot('effectiveness')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getIngredientsListAttribute()
    {
        return $this->ingredients->pluck('ingredient')->implode(', ');
    }

    public function getCustomizationOptionsByTypeAttribute()
    {
        return $this->customizationOptions()
            ->get()
            ->groupBy('option_type');
    }

    public function getEssentialOilPropertiesListAttribute()
    {
        return $this->essentialOilProperties()
            ->get()
            ->pluck('property_value', 'property_name');
    }

    public function calculateCustomPrice($options = [])
    {
        if (!$this->is_customizable || empty($options)) {
            return $this->price;
        }

        $adjustments = $this->customizationOptions()
            ->whereIn('id', $options)
            ->sum('price_adjustment');

        return $this->price + $adjustments;
    }

    public function isEssentialOil()
    {
        return $this->product_type === 'essential_oil';
    }

    public function isSoap()
    {
        return $this->product_type === 'soap';
    }

    public function scopeEssentialOils($query)
    {
        return $query->where('product_type', 'essential_oil');
    }

    public function scopeSoaps($query)
    {
        return $query->where('product_type', 'soap');
    }

    public function scopeCustomizable($query)
    {
        return $query->where('is_customizable', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '<=', 10);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->primary();
    }

    public function getMoodEffectivenessAttribute()
    {
        return $this->moods->mapWithKeys(function($mood) {
            return [$mood->id => $mood->effectiveness];
        });
    }

    public function getScentIntensityAttribute()
    {
        return $this->scentProfiles->mapWithKeys(function($profile) {
            return [$profile->id => $profile->intensity];
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->primaryImage?->url ?? $this->images->first()?->url;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->primaryImage?->thumbnail_url ?? $this->images->first()?->thumbnail_url;
    }

    public function getGalleryImagesAttribute()
    {
        return $this->images->map(function($image) {
            return [
                'url' => $image->url,
                'thumbnail' => $image->thumbnail_url,
                'alt' => $image->alt_text
            ];
        });
    }

    public function getMoodRecommendationsAttribute()
    {
        return $this->moods()
            ->orderByPivot('effectiveness', 'desc')
            ->take(3)
            ->get();
    }

    public function getScentRecommendationsAttribute()
    {
        return $this->scentProfiles()
            ->orderByPivot('intensity', 'desc')
            ->take(3)
            ->get();
    }

    public function getRelatedProductsAttribute()
    {
        return static::where('id', '!=', $this->id)
            ->where(function($query) {
                $query->whereHas('moods', function($q) {
                    $q->whereIn('id', $this->moods->pluck('id'));
                })
                ->orWhereHas('scentProfiles', function($q) {
                    $q->whereIn('id', $this->scentProfiles->pluck('id'));
                });
            })
            ->active()
            ->take(4)
            ->get();
    }

    public function getWellnessBenefitsAttribute()
    {
        return [
            'stress_relief' => $this->stress_relief_level,
            'mood_enhancement' => $this->moods()->avg('effectiveness'),
            'aromatherapy_rating' => $this->calculateAromatherapyScore(),
            'natural_score' => $this->calculateNaturalScore()
        ];
    }

    protected function calculateAromatherapyScore()
    {
        $baseScore = $this->scentProfiles()->avg('intensity');
        $moodScore = $this->moods()->avg('effectiveness');
        return round(($baseScore + $moodScore) / 2, 1);
    }

    protected function calculateNaturalScore()
    {
        return $this->is_natural ? 100 : 
               (count($this->ingredients) > 0 ? 
                round(collect($this->ingredients)->where('natural', true)->count() / count($this->ingredients) * 100) : 
                0);
    }
}