<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products', 'essentialOils', 'soaps'])
            ->latest()
            ->paginate(20);
            
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except('image');
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('categories', 'public');
            }
            
            Category::create($data);
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the category.');
        }
    }
    
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->whereNotIn('id', $category->descendants()->pluck('id'))
            ->get();
            
        return view('admin.categories.edit', compact('category', 'categories'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except('image');
            
            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $request->file('image')->store('categories', 'public');
            }
            
            $category->update($data);
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the category.');
        }
    }
    
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete category image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $category->delete();
            
            DB::commit();
            
            return back()->with('success', 'Category deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the category.');
        }
    }
    
    public function show(Category $category)
    {
        $products = $category->products()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        $subcategories = $category->children()
            ->withCount(['products', 'essentialOils', 'soaps'])
            ->get();
            
        return view('categories.show', compact('category', 'products', 'subcategories'));
    }
    
    public function essentialOils(Category $category)
    {
        $products = $category->essentialOils()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        return view('categories.essential-oils', compact('category', 'products'));
    }
    
    public function soaps(Category $category)
    {
        $products = $category->soaps()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        return view('categories.soaps', compact('category', 'products'));
    }
    
    public function lifestyle(Category $category)
    {
        $products = $category->lifestyle()
            ->with(['reviews', 'category'])
            ->latest()
            ->paginate(12);
            
        return view('categories.lifestyle', compact('category', 'products'));
    }
    
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);
        
        foreach ($request->categories as $position => $id) {
            Category::where('id', $id)->update(['position' => $position]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Categories reordered successfully.'
        ]);
    }
    
    public function toggleFeatured(Category $category)
    {
        $category->update(['is_featured' => !$category->is_featured]);
        
        return back()->with('success', 'Category featured status updated successfully.');
    }
    
    public function getSubcategories(Category $category)
    {
        $subcategories = $category->children()
            ->withCount(['products', 'essentialOils', 'soaps'])
            ->get();
            
        return response()->json($subcategories);
    }
} 