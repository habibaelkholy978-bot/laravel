<?php

namespace App\Http/Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('categories.index');
    }

    public function show($id)
    {
    
        $category = Category::with(['products', 'products.orderItems.order.user'])->findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        Category::findOrFail($id)->update($request->validated());
        return redirect()->route('categories.index');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index');
    }
}