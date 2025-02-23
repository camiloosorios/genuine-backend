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
        $categoryName = $request->input('queryResult.parameters.Category');
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([
                'fulfillmentText' => "The category '$categoryName' was not found."
            ]);
        }

        $products = $category->products()->get(['id', 'name', 'quantity']);

        if ($products->isEmpty()) {
            return response()->json([
                'fulfillmentText' => "No products found in the '$categoryName' category."
            ]);
        }

        $message = "Products in the '$categoryName' category:\n";
        foreach ($products as $product) {
            $message .= "ID: {$product->id} - Name: {$product->name} - Quantity: {$product->quantity}\n";
        }

        return response()->json(['fulfillmentText' => $message]);
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
