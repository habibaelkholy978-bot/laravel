<?php

namespace App\Http/Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->route('products.index');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'orderItems.order.user'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, $id)
    {
        Product::findOrFail($id)->update($request->validated());
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index');
    }
}