<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Company;

class PublicProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::visible()->with(['company', 'category']);

        if ($request->filled('company_id')) {
            $products->where('company_id', $request->company_id);
        }
        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        $getProducts = $products->orderBy('name_en')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('global.index', [
            'products' => $getProducts,
            'companies' => $companies,
            'categories' => $categories,
        ]);
    }

    /* public function show(string $gtin)
    {
        return view('global.product-show', [
            'product' => Product::visible()
                ->with(['category', 'company', 'reviews.user'])
                ->where('gtin', $gtin)
                ->firstOrFail()
        ]);
    } */

    public function productpage(string $gtin)
    {
        return view('global.product-page', [
            'product' => Product::visible()
                ->with(['category', 'company', 'reviews.user'])
                ->where('gtin', $gtin)
                ->firstOrFail()
        ]);
    }
}
