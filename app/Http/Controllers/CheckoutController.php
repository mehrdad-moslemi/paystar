<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function buyProduct(Request $request){
        try {
            Cookie::queue('productId' , $request->product_id , 60);
            return redirect(route('get.checkout'));
        } catch (\Exception $e) {
            return redirect()->route('all-products')->with('error' , 'Something went wrong');
            // debug
            // return redirect()->route('all-products')->with( 'error' , json_encode($e->getMessage()) );
        }
    }

    public function checkoutIndex(){
        $product = Product::find(request()->cookie('productId'));

        if(! $product || is_null($product)) return redirect()->route('all-products')->with('error' , 'There is no product to buy');

        return view('pages.checkout.checkout' , compact('product'));
    }
}
