<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;

class ProductController extends Controller
{
    private function rules(string $currentGtin = ''): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_fr' => 'required|string',
            'gtin' => 'required|string|regex:/^\d{13,14}$/|unique:products,gtin,' . $currentGtin . ',gtin',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'country' => 'required|string|max:255',
            'gross_weight' => 'required|numeric',
            'net_weight' => 'required|numeric',
            'unit_weight' => 'required|string|max:20',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ];
    }

    // Primary Function
    public function index()
    {
        return view('admin.products.index', [
            'products' => Product::with(['category', 'company'])
                ->orderBy('name_en')
                ->get()
        ]);
    }

    public function show(string $gtin)
    {
        return view('admin.products.show', [
            'product' => Product::with(['category', 'company', 'reviews.user'])
                ->where('gtin', $gtin)
                ->firstOrFail()
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            "companies" => Company::where('is_active', true)->orderBy('name')->get(),
            "categories" => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('products', 'public');
        }

        Product::create([
            ...$data,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product success added.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'companies' => Company::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules($product->gtin));

        $imagePath = null;

        if ($request->hasFile('image_path')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image_path')->store('products', 'public');
        }

        if ($product->image_path) {
            $imagePath = $product->image_path;
        }

        $product->update([
            ...$data,
            'image_path' => $imagePath,
        ]);
        return redirect()->route('products.index')->with('success', 'Product success updated.');
    }

    public function togglehidden(Product $product)
    {
        $product->update(['is_hidden' => !$product->is_hidden]);
        $message = $product->is_hidden ? "Product successfully to hide" : "Product successfully to show";

        return back()->with('success', $message);
    }

    public function destroy(Product $product)
    {
        if (!$product->is_hidden) {
            return back()->with('error', 'The product must be hidden. Otherwise, it cannot be deleted.');
        }
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        return back()->with('success', 'Product success deleted.');
    }

    public function destroyimage(Product $product)
    {
        if (!$product->image_path) {
            return back()->with('error', 'This product does not have an image');
        }

        Storage::disk('public')->delete($product->image_path);
        $product->update([
            'image_path' => null,
        ]);
        return back()->with('success', 'Image success be deleted.');
    }

    // JSON API
    private function formatproduct(Product $product): array
    {
        return [
            'name' => [
                'en' => $product->name_en,
                'fr' => $product->name_fr
            ],
            'description' => [
                'en' => $product->description_en,
                'fr' => $product->description_fr
            ],
            'gtin' => $product->gtin,
            'brand' => $product->brand,
            'category' => $product->category->name,
            'countryOfOrigin' => $product->country,
            'weight' => [
                'gross' => $product->gross_weight,
                'net' => $product->net_weight,
                'uni' => $product->unit_weight,
            ],
            'company' => [
                'companyName' => $product->company->name,
                'companyAddress' => $product->company->address,
                'companyTelephone' => $product->company->phone,
                'companyEmail' => $product->company->email,
                'owner' => [
                    'name' => $product->company->owner_name,
                    'mobileNumber' => $product->company->owner_mobile,
                    'email' => $product->company->owner_email,
                ],
                'contact' => [
                    'name' => $product->company->contact_name,
                    'mobileNumber' => $product->company->contact_mobile,
                    'email' => $product->company->contact_email,
                ],
            ],
        ];
    }

    public function indexjson(Request $request)
    {
        $keyword = $request->query('search');
        $search = "%{$keyword}%";

        $products = Product::visible()
            ->with(['category', 'company'])
            ->when($keyword, function ($q) use ($search) {
                $q->where('name_en', 'like', $search)
                    ->orWhere('name_fr', 'like', $search)
                    ->orWhere('description_en', 'like', $search)
                    ->orWhere('description_fr', 'like', $search);
            });

        $paginated = $products->paginate(10);
        $baseURL = url('/products.json');

        return response()->json([
            'data' => $paginated->map(function ($p) {
                return $this->formatproduct($p);
            }),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'total_pages' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'next_page_url' => $paginated->hasMorePages() ? $baseURL . '?page=' . ($paginated->currentPage() + 1) : null,
                'prev_page_url' => $paginated->currentPage() > 1 ? $baseURL . '?page=' . ($paginated->currentPage() - 1) : null,
            ],
        ]);
    }

    public function showjson(string $gtin)
    {
        $product = Product::visible()
            ->with(['category', 'company'])
            ->where('gtin', $gtin)
            ->firstOrFail();

        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($this->formatproduct($product));
    }
}
