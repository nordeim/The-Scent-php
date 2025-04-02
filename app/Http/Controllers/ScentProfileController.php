<?php

namespace App\Http\Controllers;

use App\Models\ScentProfile;
use Illuminate\Http\Request;

class ScentProfileController extends Controller
{
    public function index()
    {
        $profiles = ScentProfile::withCount('products')
            ->orderBy('featured', 'desc')
            ->orderBy('name')
            ->get();

        return view('scent-profiles.index', compact('profiles'));
    }

    public function show(ScentProfile $profile)
    {
        $products = $profile->products()
            ->with(['primaryImage'])
            ->paginate(12);

        return view('scent-profiles.show', compact('profile', 'products'));
    }
}