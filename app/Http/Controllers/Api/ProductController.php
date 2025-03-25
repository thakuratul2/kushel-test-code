<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    /**
     * Add construce to load middleware.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
{
    // Check if product with the same name already exists
    $existing_product = Product::where('name', $request->name)->first();

    if ($existing_product) {
        return response()->json([
            'message' => 'Product with this name already exists!',
        ], 409);
    }

   
    $product = Product::create($request->validated());

    return new ProductResource($product);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    return new ProductResource($product);
}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)

    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(request()->all());

        return new ProductResource($product);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json('Product Deleted Successfully', 200);
    }
}
