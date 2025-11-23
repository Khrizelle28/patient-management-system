<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->hashName();
            if(env('APP_URL') == 'http://localhost') {
                $path = $request->file('image')->storeAs('product/image', $fileName, 'public');
            } else {
                $path = $request->file('image')->storeAs('product/image', $fileName, 'public_s3');
            }
            $data['image'] = '/storage/'.$path;
        }

        Product::create($data);

        return redirect()->route('product.index');
    }

    public function addStock($id)
    {
        $product = Product::find($id);

        return view('product.add-stock', compact('product'));
    }

    public function updateStock($id) {}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $data = $request->except(['token']);
        if ($request->hasFile('profile_pic')) {
            $fileName = $request->file('profile_pic')->hashName();
            $path = $request->file('profile_pic')->storeAs('images', $fileName, 'public');
            $data['image'] = '/storage/'.$path;
        }
        $product->update($data);

        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
