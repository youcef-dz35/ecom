<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use \Cart as Cart;


class saveForLaterController extends Controller
{

    public function switchToCart($id)
    {
      $item= Cart::instance('saveForLater')->get($id);
      Cart::instance('saveForLater')->remove($id);



      $duplicates = Cart::instance('default')->search(function($cartItem, $rowId) use ($id){
        return $rowId === $id;
      });
      if ($duplicates->isNotEmpty()) {
        return redirect()->route('cart.index')->with('success_message','Item is already in your cart ');
      }

      Cart::instance('default')->add($item->id,$item->name, 1 ,$item->price)->associate('App\Product');
      return redirect()->route('cart.index')->with('success_message','Item was added to your cart ');

    }

    public function destroy($id)
    {
      Cart::instance('saveForLater')->remove($id);

      return back()->with('success_message','Item has been removed');
    }

}
