<?php

namespace App\Http\Controllers;

use App\Models\ScentProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScentProfileController extends Controller
{
    public function index()
    {
        $scentProfiles = ScentProfile::withCount('products')
            ->latest()
            ->paginate(20);
            
        return view('admin.scent-profiles.index', compact('scentProfiles'));
    }
    
    public function create()
    {
        return view('admin.scent-profiles.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:scent_profiles',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'notes' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'blends_well_with' => 'nullable|array',
            'blends_well_with.*' => 'exists:scent_profiles,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['image', 'benefits', 'blends_well_with']);
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('scent-profiles', 'public');
            }
            
            $scentProfile = ScentProfile::create($data);
            
            // Store benefits
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $scentProfile->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Store blend relationships
            if ($request->has('blends_well_with')) {
                $scentProfile->blendsWith()->attach($request->blends_well_with);
            }
            
            DB::commit();
            
            return redirect()->route('admin.scent-profiles.index')
                ->with('success', 'Scent profile created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the scent profile.');
        }
    }
    
    public function edit(ScentProfile $scentProfile)
    {
        $scentProfiles = ScentProfile::where('id', '!=', $scentProfile->id)->get();
        
        return view('admin.scent-profiles.edit', compact('scentProfile', 'scentProfiles'));
    }
    
    public function update(Request $request, ScentProfile $scentProfile)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:scent_profiles,slug,' . $scentProfile->id,
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'notes' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'blends_well_with' => 'nullable|array',
            'blends_well_with.*' => 'exists:scent_profiles,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['image', 'benefits', 'blends_well_with']);
            
            if ($request->hasFile('image')) {
                // Delete old image
                if ($scentProfile->image) {
                    Storage::disk('public')->delete($scentProfile->image);
                }
                $data['image'] = $request->file('image')->store('scent-profiles', 'public');
            }
            
            $scentProfile->update($data);
            
            // Update benefits
            $scentProfile->benefits()->delete();
            if ($request->has('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $scentProfile->benefits()->create(['description' => $benefit]);
                }
            }
            
            // Update blend relationships
            $scentProfile->blendsWith()->sync($request->blends_well_with ?? []);
            
            DB::commit();
            
            return redirect()->route('admin.scent-profiles.index')
                ->with('success', 'Scent profile updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the scent profile.');
        }
    }
    
    public function destroy(ScentProfile $scentProfile)
    {
        if ($scentProfile->products()->exists()) {
            return back()->with('error', 'Cannot delete scent profile with associated products.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete image
            if ($scentProfile->image) {
                Storage::disk('public')->delete($scentProfile->image);
            }
            
            // Delete benefits and relationships
            $scentProfile->benefits()->delete();
            $scentProfile->blendsWith()->detach();
            
            $scentProfile->delete();
            
            DB::commit();
            
            return back()->with('success', 'Scent profile deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the scent profile.');
        }
    }
    
    public function show(ScentProfile $scentProfile)
    {
        $products = $scentProfile->products()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        return view('scent-profiles.show', compact('scentProfile', 'products'));
    }
    
    public function toggleFeatured(ScentProfile $scentProfile)
    {
        $scentProfile->update(['is_featured' => !$scentProfile->is_featured]);
        
        return back()->with('success', 'Scent profile featured status updated successfully.');
    }
    
    public function getBlends(ScentProfile $scentProfile)
    {
        $blends = $scentProfile->blendsWith()
            ->withCount('products')
            ->get();
            
        return response()->json($blends);
    }
    
    public function finder()
    {
        $scentProfiles = ScentProfile::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        $moods = Mood::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();
            
        return view('scent-profiles.finder', compact('scentProfiles', 'moods'));
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
            
        return view('scent-profiles.results', compact('products'));
    }
} 