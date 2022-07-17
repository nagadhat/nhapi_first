<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class TempController extends Controller
{
    public function tempBarcodeFix(Request $request)
    {
        $request->validate([
            "product_id" => 'required',
            "outlet_id" => 'required',
            "barcode" => 'required|unique:products,product_sku,' . $request->product_id
        ]);
        $product = Product::select('id', 'product_sku', 'author_id')->where('id', $request->product_id)->first();
        if ($product) {
            if ($product->author_id != $request->outlet_id) {
                return response()->json([
                    'message' => 'invalid outlet_id',
                    'errors' => 400
                ]);
            }

            $product->update([
                'product_sku' => $request->barcode
            ]);
            return response()->json([
                'message' => 'updated successfully',
                'data' => $product
            ]);
        } else {
            return response()->json([
                'message' => 'product not found',
                'errors' => 404
            ]);
        }
    }
}
