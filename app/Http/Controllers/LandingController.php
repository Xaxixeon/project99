<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class LandingController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('products')) {
            return view('landing', [
                'featuredProducts' => collect(),
            ]);
        }

        $featuredProducts = Product::where('is_featured', true)
            ->take(6)
            ->get();

        return view('landing', compact('featuredProducts'));
    }
}
