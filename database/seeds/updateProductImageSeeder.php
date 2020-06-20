<?php

use Illuminate\Database\Seeder;

class updateProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $products =App\Product::all();
        foreach ($products as $product) {
          // code...
          DB::table('products')
          ->where('id',$product->id)
          ->update([
            'image'=>'products/June2020/'.$product->slug.'.jpg'
          ]);
        }
    }
}
