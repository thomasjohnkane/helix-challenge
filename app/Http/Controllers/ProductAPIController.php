<?php

namespace App\Http\Controllers;

use App\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Validator;
 
class ProductAPIController extends Controller
{
    public function index(Request $request)
    {
        return new ProductCollection(Product::paginate());
    }

    public function showForAuthenticatedUser(Request $request)
    {
        return new ProductCollection($request->user()->products()->paginate());
    }
 
    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return new ProductResource($product->load(['user']));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ])->validate();

        // Note: I would probably normally use an observer
        // To add the user_id at time of creating
        return new ProductResource(
            Product::create(
                collect($request->all())
                    ->put('user_id', $request->user()->id)
                    ->toArray()
            )
        );
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->all());

        if ($request->hasFile('image')) {
            $this->uploadImage($request, $product);
        }

        return new ProductResource($product);
    }

    public function uploadImage(Request $request, Product $product)
    {
        $path = $request->file('image')->store('products');
        $product->image = $path;
        $product->save();

        if ($request->expectsJson()) {
            return new ProductResource($product);
        } else {
            return true;
        }
    }

    public function attach(Request $request, Product $product)
    {
        $product->user_id = $request->user()->id;
        $product->save();

        return new ProductResource($product);
    }

    public function detach(Request $request, Product $product)
    {
        $product->user_id = null;
        $product->save();

        return new ProductResource($product);
    }

    public function destroy(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([], \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }
}
