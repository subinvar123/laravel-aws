<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $items = $cart->items()->with('product')->get();
        return view('products.cart', compact('items'));
    }

    public function add(Request $request, $productId)
    {
        
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function remove($productId)
    {
        $item = CartItem::findOrFail($productId);
        $item->delete();
        return redirect()->back()->with('success', 'Item removed.');
    }

    public function removeAjax($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();
    
        // Return JSON so Ajax can update cart UI
        return response()->json([
            'success' => true,
            'message' => 'Item removed successfully.'
        ]);
    }
    
}
