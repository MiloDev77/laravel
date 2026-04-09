<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class GtinController extends Controller
{
    public function show()
    {
        return view('global.gtin-validate');
    }
    public function validate(Request $request)
    {
        $data = $request->validate([
            'gtins' => 'required|string'
        ]);

        $gtinlists = array_filter(
            array_map(
                'trim',
                explode("\n", $data['gtins'])
            )
        );

        $existinggtins = Product::visible()
            ->whereIn('gtin', $gtinlists)
            ->pluck('gtin')
            ->toArray();

        $results = [];
        foreach ($gtinlists as $gtin) {
            $results[] = [
                'gtin' => $gtin,
                'valid' => in_array($gtin, $existinggtins),
            ];
        }

        // $results = [];
        // foreach ($gtinlists as $gtin) {
        //     $exists = Product::visible()->where('gtin', $gtin)->exists();
        //     $results[] = [
        //         'gtin' => $gtin,
        //         'valid' => $exists,
        //     ];
        // }

        $allvalid = collect($results)->every(function ($r) {
            return $r['valid'];
        });

        return view('global.gtin-validate', ['results' => $results, 'allvalid' => $allvalid]);
    }
}
