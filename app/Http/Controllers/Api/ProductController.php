<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        // Get all products
        $products = Product::with(['category', 'tags'])->latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'List Data Products',
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }


    /**
     * store
     * 
     * @param mixed $request
     * @return void
     * 
     */
    public function store(Request $request)
    {
        try {
            // Define validation rules
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'category_id' => 'required',
                'tags' => 'required|array',
                'tags.*' => 'exists:tags,id',
                'title' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric'
            ]);
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            
            // Upload image
            $image = $request->file('image');
            $image->storeAs('products', $image->hashName());

            // Create Product
            $product = Product::create([
                'image' => $image->hashName(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            // Attach tags to the product
            $product->tags()->attach($request->tags);

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Product berhasil ditambahkan',
                'data' => new ProductResource($product),
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * show
     * 
     * @param mixed $id
     * @return void
     */
    public function show($id)
    {
        try {
            // Find product by product_id
            $product = Product::find($id);

            // Return single product as a resource
            return new ProductResource(true, 'Detail Data Product!', $product);
        } catch (\Exception $e) {
            return response->json([$e->getMessage()], 500);
        }
    }

    /**
     * update
     * 
     * @param mixed $request
     * @param mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            
            // Define validation rules
            $validator = Validator::make($request->all(), [
                'image'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'title'         => 'required',
                'description'   => 'required',
                'price'         => 'required|numeric',
                'stock'         => 'required|numeric'
            ]);
            
            // Check if validation fail
            if ($validator->fails()) {
                return response->json($validator->errors(), 422);
            }
            

            // Find product by ID
            $product = Product::find($id);


            // Check if image has not empty
            if ($request->hasFile('image')) {
                // Delete old image
                Storage::delete('products/' . basename($product->image));

                // Upload image
                $image = $request->file('image');
                $image->storeAs('products', $image->hashName());

                // Update product with new image
                $product->update([
                    'image'         => $image->hashName(),
                    'title'         => $request->title,
                    'description'   => $request->description,
                    'price'         => $request->price,
                    'stock'         => $request->stock
                ]);
            } else {
                // Update product without image
                $product->update([
                    'title'         => $request->title,
                    'description'   => $request->description,
                    'price'         => $request->price,
                    'stock'         => $request->stock
                ]);
            }

            return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
        } catch (\Exception $e) {
            return response->json([$e->getMessage()], 500);
        }
    }

    /**
     * destroy
     * 
     * @param mixed $id
     * @return void
     */
    public function destroy($id) 
    {
        try {
            // Find Product By Id
            $product = Product::find($id);
            if (!$product){
                return response->json(['success' => false, 'message' => 'Product not found!'], 404);
            }
            
            // Delete image
            Storage::delete('products/' . basename($product->image));

            // Delete product
            $product->delete();

            // Return response
            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        } catch (\Exception $e) {
            return response->json([$e->getMessage()], 500);
        }
    }
}
