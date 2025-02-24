<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class FulfillmentController extends Controller
{
    public function productIntents(Request $request)
    {
        $intent = $request->input('queryResult.intent.displayName');

        switch ($intent) {
            case 'GetProductsIntent':
                return $this->getProductsByCategory($request);
            case 'GetProductsCountIntent':
                return $this->getProductsCountByCategory($request);
            default:
                return response()->json([
                    'fulfillmentText' => "Oops, I haven't learned how to handle that request yet. Could you try something else?"
                ]);
        }
    }

    private function getProductsByCategory(Request $request)
    {
        $categoryName = $request->input('queryResult.parameters.Category');
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([
                'fulfillmentText' => "I'm sorry, but I couldn't find the category '$categoryName'. Please double-check the name or try another category."
            ]);
        }

        $products = $category->products()->get(['id', 'name', 'quantity']);

        if ($products->isEmpty()) {
            return response()->json([
                'fulfillmentText' => "It looks like there are no products available in the '$categoryName' category at the moment."
            ]);
        }

        $message = "Here are the products I found in the '$categoryName' category:\n\n";
        foreach ($products as $product) {
            $message .= "• ID: {$product->id} - Name: {$product->name} - Quantity: {$product->quantity}\n";
        }
        $message .= "\nI hope this helps! Let me know if you need anything else.";

        return response()->json(['fulfillmentText' => $message]);
    }

    private function getProductsCountByCategory(Request $request)
    {
        $categoryName = $request->input('queryResult.parameters.Category');
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([
                'fulfillmentText' => "I couldn't locate the category '$categoryName'. Could you please verify the category name and try again?"
            ]);
        }

        $productsCount = $category->products()->count();

        $message = "Great news! The '$categoryName' category currently has $productsCount product";
        $message .= $productsCount == 1 ? "" : "s";
        $message .= ". If you'd like to see the details of each product, just ask me for the list.";

        return response()->json([
            'fulfillmentText' => $message
        ]);
    }
}
