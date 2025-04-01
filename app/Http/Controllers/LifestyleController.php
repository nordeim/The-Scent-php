<?php

namespace App\Http\Controllers;

use App\Models\Lifestyle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LifestyleController extends Controller
{
    public function index()
    {
        $lifestyles = Lifestyle::withCount('products')
            ->latest()
            ->paginate(20);
            
        return view('admin.lifestyles.index', compact('lifestyles'));
    }
    
    public function create()
    {
        return view('admin.lifestyles.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lifestyles',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'notes' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'recommended_products' => 'nullable|array',
            'recommended_products.*' => 'exists:products,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['image', 'benefits', 'recommended_products']);
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('lifestyles', 'public');
            }
            
            $lifestyle = Lifestyle::create($data);
            
            // Store benefits
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $lifestyle->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Store recommended products
            if ($request->has('recommended_products')) {
                $lifestyle->recommendedProducts()->attach($request->recommended_products);
            }
            
            DB::commit();
            
            return redirect()->route('admin.lifestyles.index')
                ->with('success', 'Lifestyle item created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the lifestyle item.');
        }
    }
    
    public function edit(Lifestyle $lifestyle)
    {
        $products = Product::with(['category', 'reviews'])
            ->latest()
            ->get();
            
        return view('admin.lifestyles.edit', compact('lifestyle', 'products'));
    }
    
    public function update(Request $request, Lifestyle $lifestyle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lifestyles,slug,' . $lifestyle->id,
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'notes' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'recommended_products' => 'nullable|array',
            'recommended_products.*' => 'exists:products,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['image', 'benefits', 'recommended_products']);
            
            if ($request->hasFile('image')) {
                // Delete old image
                if ($lifestyle->image) {
                    Storage::disk('public')->delete($lifestyle->image);
                }
                $data['image'] = $request->file('image')->store('lifestyles', 'public');
            }
            
            $lifestyle->update($data);
            
            // Update benefits
            $lifestyle->benefits()->delete();
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $lifestyle->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Update recommended products
            $lifestyle->recommendedProducts()->sync($request->recommended_products ?? []);
            
            DB::commit();
            
            return redirect()->route('admin.lifestyles.index')
                ->with('success', 'Lifestyle item updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the lifestyle item.');
        }
    }
    
    public function destroy(Lifestyle $lifestyle)
    {
        if ($lifestyle->products()->exists()) {
            return back()->with('error', 'Cannot delete lifestyle item with associated products.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete image
            if ($lifestyle->image) {
                Storage::disk('public')->delete($lifestyle->image);
            }
            
            // Delete benefits and relationships
            $lifestyle->benefits()->delete();
            $lifestyle->recommendedProducts()->detach();
            
            $lifestyle->delete();
            
            DB::commit();
            
            return back()->with('success', 'Lifestyle item deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the lifestyle item.');
        }
    }
    
    public function show(Lifestyle $lifestyle)
    {
        $products = $lifestyle->products()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        $recommendedProducts = $lifestyle->recommendedProducts()
            ->with(['reviews', 'category'])
            ->latest()
            ->take(4)
            ->get();
            
        return view('lifestyles.show', compact('lifestyle', 'products', 'recommendedProducts'));
    }
    
    public function toggleFeatured(Lifestyle $lifestyle)
    {
        $lifestyle->update(['is_featured' => !$lifestyle->is_featured]);
        
        return back()->with('success', 'Lifestyle item featured status updated successfully.');
    }
    
    public function getRecommendedProducts(Lifestyle $lifestyle)
    {
        $products = $lifestyle->recommendedProducts()
            ->with(['reviews', 'category'])
            ->latest()
            ->take(4)
            ->get();
            
        return response()->json($products);
    }
    
    public function finder()
    {
        $lifestyles = Lifestyle::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        $scentProfiles = ScentProfile::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        return view('lifestyles.finder', compact('lifestyles', 'scentProfiles'));
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'lifestyle' => 'required|exists:lifestyles,id',
            'scent_profile' => 'required|exists:scent_profiles,id',
            'intensity' => 'required|in:light,medium,strong'
        ]);
        
        $products = Product::query()
            ->with(['reviews', 'category'])
            ->whereHas('lifestyles', function($q) use ($request) {
                $q->where('lifestyles.id', $request->lifestyle);
            })
            ->whereHas('scentProfiles', function($q) use ($request) {
                $q->where('scent_profiles.id', $request->scent_profile);
            })
            ->when($request->intensity === 'light', function($q) {
                $q->where('intensity', '<=', 2);
            })
            ->when($request->intensity === 'medium', function($q) {
                $q->whereBetween('intensity', [3, 4]);
            })
            ->when($request->intensity === 'strong', function($q) {
                $q->where('intensity', '>=', 5);
            })
            ->latest()
            ->paginate(12);
            
        return view('lifestyles.results', compact('products'));
    }
} 