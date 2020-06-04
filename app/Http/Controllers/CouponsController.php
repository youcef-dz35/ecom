<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use \Cart as Cart;

class CouponsController extends Controller
{

    public function store(Request $request)
    {
        //
        $coupon = Coupon::where('code',$request->coupon_code)->first();
        if (!$coupon) {
           return redirect()->route('checkout.index')->withErrors('Invalid Coupon Code. Please try again.');
        }

        session()->put('coupon',[
            'name' => $coupon->code,
            'discount' => $coupon->discount(Cart::subtotal()),
        ]);
        return redirect()->route('checkout.index')->with('success_message','coupon has been applied');

    }

    public function destroy()
    {
        //
        session()->forget('coupon');

        return redirect()->route('checkout.index')->with('success_message', 'The Coupon has been remouved');
    }
}
