<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class ReviewController extends Controller
{
    // public function create(string $gtin)
    // {
    //     if (!Auth::check()) {
    //         return back()->with('error', 'Login before to give reviews this product.');
    //     }

    //     return view('user.addreview', [
    //         'user' => Auth::user(),
    //         'product' => Product::visible()->where('gtin', $gtin)->first(),
    //     ]);
    // }

    public function store(Request $request, string $gtin)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Login before to give reviews this product.');
        }

        $data = $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'body' => 'nullable|string|max:200',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $product = Product::visible()->where('gtin', $gtin)->firstOrFail();

        $product->reviews()->create([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Review has been sent.');
    }
}
