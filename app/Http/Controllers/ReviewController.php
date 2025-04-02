<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048'
        ]);
        
        $review = new Review();
        $review->user_id = auth()->id();
        $review->product_id = $product->id;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        
        // Handle review images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
        }
        $review->images = $images;
        
        $review->save();
        
        // Update product review count and average rating
        $product->review_count = $product->reviews()->count();
        $product->average_rating = $product->reviews()->avg('rating');
        $product->save();
        
        return back()->with('success', 'Your review has been posted successfully!');
    }
    
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048'
        ]);
        
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        
        // Handle review images
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            // Store new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
            $review->images = $images;
        }
        
        $review->save();
        
        // Update product average rating
        $product = $review->product;
        $product->average_rating = $product->reviews()->avg('rating');
        $product->save();
        
        return back()->with('success', 'Your review has been updated successfully!');
    }
    
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        
        // Delete review images
        foreach ($review->images as $image) {
            Storage::disk('public')->delete($image);
        }
        
        $product = $review->product;
        $review->delete();
        
        // Update product review count and average rating
        $product->review_count = $product->reviews()->count();
        $product->average_rating = $product->reviews()->avg('rating');
        $product->save();
        
        return back()->with('success', 'Your review has been deleted successfully!');
    }
    
    public function report(Request $request, Review $review)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255'
        ]);
        
        $review->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $validated['reason']
        ]);
        
        return back()->with('success', 'Thank you for reporting this review. We will review it shortly.');
    }
    
    public function helpful(Request $request, Review $review)
    {
        $review->helpful_votes()->attach(auth()->id());
        
        return response()->json([
            'helpful_count' => $review->helpful_votes()->count()
        ]);
    }
    
    public function notHelpful(Request $request, Review $review)
    {
        $review->helpful_votes()->detach(auth()->id());
        
        return response()->json([
            'helpful_count' => $review->helpful_votes()->count()
        ]);
    }
} 