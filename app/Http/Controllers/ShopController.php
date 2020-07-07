<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Cartalyst\Stripe\Api\Products;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $pagination = 9;

      $categories= Category::all();
      if (request()->category) {
        $products= Product::with('categories')->whereHas('categories',function ($query){
          $query->where('slug',request()->category);

        });

        $categoryName= optional($categories->where('slug',request()->category)->first())->name;
      }else{
      $products = Product::where('featured',true);

      $categoryName = 'Featured Items';
      }

      if (request()->sort == 'low_high') {
        $products=$products->orderBy('price')->paginate($pagination);
      }elseif (request()->sort == 'high_low') {
        $products=$products->orderBy('price','desc')->paginate($pagination);
      }else {
        $products=$products->paginate($pagination);
      }

      return view('shop')->with(
        [
        'products'=>$products,
        'categories'=>$categories,
        'categoryName'=>$categoryName
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
      $product = Product::where('slug',$slug)->firstOrFail();
      $mightAlsoLike = Product::MightAlsoLike()->get();
      return view ('product')->with(['product'=>$product, 'mightAlsoLike'=>$mightAlsoLike]);
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
    public function search(Request $request)
    {
      $request->validate([
        'query' => 'required|min:3'
      ]);

      $query = $request->input('query');
      $products= Product::where('name', 'LIKE', "%{$query}%")->paginate(10);
     // $products = Product::search($query)->paginate(10);
      return view('search-results')->with('products',$products);
    }
}
