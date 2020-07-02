<?php

namespace App\Http\Controllers;

use auth;
use App\Order;
use App\Product;
use \Cart as Cart;
use App\OrderProduct;
use \Stripe as Stripe;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;


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
          Stripe\Stripe::setApiKey('sk_test_2xE4yd9jqiumURFpGTuOW5xA0017pMXL9R');
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
          
          $this->addToOrdersTables($request, null);
          Mail::send(new OrderPlaced);
          Cart::instance('default')->destroy();
          session()->forget('coupon');

          //insert into orders table
          

          //insert into pivot table

          //return back()->with('success_message','thank you your payment has been successfully accepted :) ');
          return redirect()->route('confirmation.index')->with('success_message','thank you your payment has been successfully accepted :) ');
        } catch (\Stripe\Exception\CardException $e) {
          $this->addToOrdersTables($request, $e->getMessage());
         
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

    protected function addToOrdersTables($request,$error){
      $order = Order::create([
        'user_id' => auth()->user() ? auth()->user()->id : null,
        'billing_email' =>$request->email ,
        'billing_name' =>$request->name,
        'billing_address' =>$request->address,
        'billing_city' =>$request->city,
        'billing_province' =>$request->province,
        'billing_postalcode' =>$request->postalcode,
        'billing_phone' =>$request->phone,
        'billing_name_on_card' =>$request->name_on_card,
        'billing_discount' =>$this->getNumbers()->get('discount'),
        'billing_discount_code'=>$this->getNumbers()->get('code'),
        'billing_subtotal'=>$this->getNumbers()->get('newSubtotal'),
        'billing_tax'=>$this->getNumbers()->get('newTax'),
        'billing_total'=>$this->getNumbers()->get('newTotal'),
        'error'=> $error,

      ]);
      //insert into pivot table
      foreach (Cart::content() as $item){
        OrderProduct::create([
          'order_id' =>$order->id,
          'product_id' =>$item->model->id,
          'quantity' =>$item->qty,
        ]);
      }
    }

    private function getNumbers(){

      $tax = config('cart.tax')/100;
      $discount = session()->get('coupon')['discount'] ?? 0;
      $code = session()->get('coupon')['name'] ?? null;
      $newSubtotal = (Cart::subtotal() - $discount);
      $newTax = $newSubtotal * $tax;
      $newTotal = $newSubtotal * (1+$tax);

      return collect([
        'Tax' =>$tax,
        'discount' =>$discount,
        'code'=>$code,
        'newTax' =>$newTax,
        'newTotal' =>$newTotal,
        'newSubtotal' =>$newSubtotal,
      ]);

    }
}
