<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $path = $request->file('image')->storeAs('product/image', $fileName, 'public');
            $data['image'] = Storage::disk('public')->url($path);
        }

        Product::create($data);

        return redirect()->route('product.index');
    }

    public function addStock($id)
    {
        $product = Product::find($id);

        return view('product.add-stock', compact('product'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->stock = $product->stock + $request->stock;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Stock updated successfully');
    }

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
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->hashName();
            $path = $request->file('image')->storeAs('product/image', $fileName, 'public');
            $data['image'] = Storage::disk('public')->url($path);
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
