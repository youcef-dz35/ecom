<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $now= Carbon::now()->toDateTimeString();

        Category::insert([
          ['name'=>'Laptops', 'slug'=>'laptops', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Desktops', 'slug'=>'desktops', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Moblie Phones', 'slug'=>'moblie-phones', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Tablets', 'slug'=>'tablets', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Tvs', 'slug'=>'tvs', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Digital Cameras', 'slug'=>'degital-cameras', 'created_at'=>$now, 'updated_at'=>$now],
          ['name'=>'Appliances', 'slug'=>'appliances', 'created_at'=>$now, 'updated_at'=>$now],
        ]);
    }
}
