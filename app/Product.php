<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Laravel\Scout\Searchable;

class Product extends Model
{
  use SearchableTrait ,Searchable;

  /**
   * Searchable rules.
   *
   * @var array
   */
  public function toSearchableArray()
  {
      $array = $this->toArray();

      // Customize array...
      $arrey['name']= $this->name;

      return $array;
  }
    //
    public function categories()
    {
      return $this->belongsToMany('App\Category');
    }


    public function presentPrice()
    {
      return Number_format($this->price/100);
    }

    public function scopeMightAlsoLike($query)
    {
      return $query->inRandomOrder()->take(4);
    }
    
}
