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
        'review_count' => 'integer'
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
                    ->withPivot('intensity');
    }

    public function moods()
    {
        return $this->belongsToMany(Mood::class, 'product_moods')
                    ->withPivot('effectiveness');
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
} 