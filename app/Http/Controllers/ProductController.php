<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;

class ProductController extends Controller
{
    // List all products
    public function index(Request $request)
    {
        $categoryId = $request->query('category'); // get ?category= from URL
        $products = Product::with('category', 'brand')
        ->when($categoryId, function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->latest()
        ->get();
        $category=Category::all();
        return view('user.home', compact('products', 'category'));
    }

    // Show form to create new product
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.create', compact('categories', 'brands'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.store')->with('success', 'Product created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => "required|unique:products,slug,$id",
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Show single product (optional)
    public function show($id)
    {
        $product = Product::with('category', 'brand')->findOrFail($id);
        return view('products.show', compact('product'));
    }
//ajax product list
    public function filter(Request $request)
{
    
    $categoryId = $request->query('category');

    $products = Product::with('category', 'brand')
        ->when($categoryId, function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->latest()
        ->get();
    return view('user.partials.product-list', compact('products'))->render();
}

}