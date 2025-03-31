<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoodController extends Controller
{
    public function index()
    {
        $moods = Mood::withCount('products')
            ->latest()
            ->paginate(20);
            
        return view('admin.moods.index', compact('moods'));
    }
    
    public function create()
    {
        return view('admin.moods.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:moods',
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
                $data['image'] = $request->file('image')->store('moods', 'public');
            }
            
            $mood = Mood::create($data);
            
            // Store benefits
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $mood->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Store recommended products
            if ($request->has('recommended_products')) {
                $mood->recommendedProducts()->attach($request->recommended_products);
            }
            
            DB::commit();
            
            return redirect()->route('admin.moods.index')
                ->with('success', 'Mood created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the mood.');
        }
    }
    
    public function edit(Mood $mood)
    {
        $products = Product::with(['category', 'reviews'])
            ->latest()
            ->get();
            
        return view('admin.moods.edit', compact('mood', 'products'));
    }
    
    public function update(Request $request, Mood $mood)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:moods,slug,' . $mood->id,
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
                if ($mood->image) {
                    Storage::disk('public')->delete($mood->image);
                }
                $data['image'] = $request->file('image')->store('moods', 'public');
            }
            
            $mood->update($data);
            
            // Update benefits
            $mood->benefits()->delete();
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $mood->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Update recommended products
            $mood->recommendedProducts()->sync($request->recommended_products ?? []);
            
            DB::commit();
            
            return redirect()->route('admin.moods.index')
                ->with('success', 'Mood updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the mood.');
        }
    }
    
    public function destroy(Mood $mood)
    {
        if ($mood->products()->exists()) {
            return back()->with('error', 'Cannot delete mood with associated products.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete image
            if ($mood->image) {
                Storage::disk('public')->delete($mood->image);
            }
            
            // Delete benefits and relationships
            $mood->benefits()->delete();
            $mood->recommendedProducts()->detach();
            
            $mood->delete();
            
            DB::commit();
            
            return back()->with('success', 'Mood deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the mood.');
        }
    }
    
    public function show(Mood $mood)
    {
        $products = $mood->products()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        $recommendedProducts = $mood->recommendedProducts()
            ->with(['reviews', 'category'])
            ->latest()
            ->take(4)
            ->get();
            
        return view('moods.show', compact('mood', 'products', 'recommendedProducts'));
    }
    
    public function toggleFeatured(Mood $mood)
    {
        $mood->update(['is_featured' => !$mood->is_featured]);
        
        return back()->with('success', 'Mood featured status updated successfully.');
    }
    
    public function getRecommendedProducts(Mood $mood)
    {
        $products = $mood->recommendedProducts()
            ->with(['reviews', 'category'])
            ->latest()
            ->take(4)
            ->get();
            
        return response()->json($products);
    }
    
    public function finder()
    {
        $moods = Mood::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        $scentProfiles = ScentProfile::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        return view('moods.finder', compact('moods', 'scentProfiles'));
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'mood' => 'required|exists:moods,id',
            'scent_profile' => 'required|exists:scent_profiles,id',
            'intensity' => 'required|in:light,medium,strong'
        ]);
        
        $products = Product::query()
            ->with(['reviews', 'category'])
            ->whereHas('moods', function($q) use ($request) {
                $q->where('moods.id', $request->mood);
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
            
        return view('moods.results', compact('products'));
    }
} 