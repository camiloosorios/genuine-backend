<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FulfillmentController extends Controller
{
    public function getProductsIntent(Request $request)
    {
        $categoryName = $request->input('queryResult.parameters.category');

        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json(['error' => "The category '$categoryName' was not found."], 404);
        }

        $products = $category->products()->pluck('name')->toArray();

        if (empty($products)) {
            return response()->json(['error' => "No products found in the '$categoryName' category."]);
        }

        return response()->json(['products' => $products]);
    }


    public function getProductsCountIntent(Request $request)
    {
        $categoryName = $request->input('queryResult.parameters.category');

        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([
                'error' => "The category '$categoryName' was not found."
            ], 404);
        }

        $count = $category->products()->count();

        if ($count === 0) {
            return response()->json([
                'error' => "No products found in the '$categoryName' category."
            ]);
        }

        return response()->json([
            'products_count' => $count
        ]);
    }
}
