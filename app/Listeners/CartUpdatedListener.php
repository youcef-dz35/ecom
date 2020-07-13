<?php

namespace App\Listeners;


use App\Coupon;

use Gloudemans\Shoppingcart\Facades\Cart;


class CartUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {   $CouponName= session()->get('coupon')['name'];
        
        if ($CouponName) {
            $coupon = Coupon::where('code',$CouponName)->first();
    
            session()->put('coupon',[
                'name' => $coupon->code,
                'discount' => $coupon->discount(Cart::subtotal()),
            ]);
            
        }

    }
}
