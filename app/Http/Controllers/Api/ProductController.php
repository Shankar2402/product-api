<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Cache::remember('products', 60, function () {
            return Product::all();
        });
        
        if($products){
            return response()->json([
                'Status' => 'True',
                'Message' => 'Product list fatched successfully',
                'Data' => $products
            ], 200);
        }else{
            return response()->json([
                'Status' => 'False',
                'Message' => 'Product list not found'
            ], 404);
        }
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products',
            'category' => 'nullable|string',
        ]);

        $product = Product::create($validated);
        Cache::forget('products');

        if( $product){
            return response()->json([
                'Status' => 'True',
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);
        }else{
            return response()->json([
                'Status' => 'False',
                'message' => 'Product not created'
            ], 404);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'Status' => 'True',
                'message' => 'Product found successfully',
                'Data' => $product
            ], 200);
        } else {
            return response()->json([
                'Status' => 'False',
                'message' => 'Product not found'
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:products,name,' . $id,
            'sku' => 'sometimes|required|string|max:100|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category' => 'nullable|string',
        ]);

        $product->update($validated);

        Cache::forget('products');

        return response()->json([
            'Status' => 'True',
            'message' => 'Product updated successfully',
            'Data' => $product
        ]);
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'Status' => 'False',
                'message' => 'Product not found.'
            ], 404);
        }

        $product->delete();
        Cache::forget('products');

        return response()->json([
            'Status' => 'True',
            'message' => 'Product deleted successfully.'
        ], 200);
    }

}
