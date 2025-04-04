<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\WellnessLog;
use Illuminate\Http\Request;

class WellnessTrackerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $recentProducts = $user->orders()
            ->with('items.product.moods')
            ->latest()
            ->take(5)
            ->get()
            ->pluck('items.*.product')
            ->flatten();
            
        $moodStats = $user->wellnessLogs()
            ->selectRaw('mood, AVG(rating) as average_rating')
            ->groupBy('mood')
            ->get();
            
        $recommendations = $this->getPersonalizedRecommendations($user);

        return view('wellness.dashboard', compact(
            'recentProducts',
            'moodStats',
            'recommendations'
        ));
    }

    protected function getPersonalizedRecommendations(User $user)
    {
        $preferredMoods = $user->wellnessLogs()
            ->selectRaw('mood, COUNT(*) as frequency')
            ->groupBy('mood')
            ->orderByDesc('frequency')
            ->limit(3)
            ->pluck('mood');

        return Product::whereHas('moods', function($query) use ($preferredMoods) {
            $query->whereIn('name', $preferredMoods);
        })
        ->with(['moods', 'scentProfiles'])
        ->take(4)
        ->get();
    }
}
