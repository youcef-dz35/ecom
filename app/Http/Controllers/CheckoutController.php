<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use \Cart as Cart;
use \Stripe as Stripe;
use \Stripe\Exeption\CardErrorException;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        if (Cart::instance('default')->count()==0) {
          return redirect('')->route('shop.index');
        }
        if(auth()->user() && request()->is('guestCheckout')){
          return redirect()->route('checkout.index');
        }


        return view('checkout')->with([

          'discount' =>$this->getNumbers()->get('discount'),
          'newTax' =>$this->getNumbers()->get('newTax'),
          'newTotal' =>$this->getNumbers()->get('newTotal'),
          'newSubtotal' =>$this->getNumbers()->get('newSubtotal'),

          ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {

         $contents = Cart::content()->map(function ($item){
           return $item->model->slug.','.$item->qty;
         })->values()->toJson();
         try {
          Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
          Stripe\Charge::create ([
              'amount' => presentPrice($this->getNumbers()->get('newSubtotal'))*100,
              'currency' => 'CAD',
              'source' => $request->stripeToken,
              'description' => 'Order',
              'receipt_email' => $request->email,
              'metadata' => [
                  //change to Order ID after we start using DB
                  'contents' => $contents,
                  'quantity' => Cart::instance('default')->count(),
                  'discount' => collect(session()->get('coupon'))->toJson(),
              ],
          ]);
          Cart::instance('default')->destroy();
          session()->forget('coupon');

          //return back()->with('success_message','thank you your payment has been successfully accepted :) ');
          return redirect()->route('confirmation.index')->with('success_message','thank you your payment has been successfully accepted :) ');
        } catch (CardErrorException $e) {

          return back()->withErrors('Error'.$e->getMessage());
        }


       //  Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
       //  Stripe\Charge::create ([
       //         "amount" => 100 * 100,
       //         "currency" => "usd",
       //         "source" => $request->stripeToken,
       //         "description" => "Test payment from itsolutionstuff.com."
       // ]);
       //
       // return back()->with('success_message','thank you your payment has been successfully accepted :) ');



    }

//
// public function store(CheckoutRequest $request)
//     {
//         $contents = Cart::content()->map(function ($item) {
//             return $item->model->slug.', '.$item->qty;
//         })->values()->toJson();
//
//         try {
//             $charge = Stripe::charges()->create([
//                 'amount' => Cart::total() / 100,
//                 'currency' => 'CAD',
//                 'source' => $request->stripeToken,
//                 'description' => 'Order',
//                 'receipt_email' => $request->email,
//                 'metadata' => [
//                     //change to Order ID after we start using DB
//                     'contents' => $contents,
//                     'quantity' => Cart::instance('default')->count(),
//                 ],
//             ]);
//
//             // SUCCESSFUL
//             Cart::instance('default')->destroy();
//             // return back()->with('success_message', 'Thank you! Your payment has been successfully accepted!');
//             return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted!');
//         } catch (CardErrorException $e) {
//             return back()->withErrors('Error! ' . $e->getMessage());
//         }
//     }
//


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    private function getNumbers(){

      $tax = config('cart.tax')/100;
      $discount = session()->get('coupon')['discount'] ?? 0;
      $newSubtotal = (Cart::subtotal() - $discount);
      $newTax = $newSubtotal * $tax;
      $newTotal = $newSubtotal * (1+$tax);

      return collect([

        'discount' =>$discount,
        'newTax' =>$newTax,
        'newTotal' =>$newTotal,
        'newSubtotal' =>$newSubtotal,
      ]);

    }
}
