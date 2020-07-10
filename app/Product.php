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

    $extraFields = [
        'categories' => $this->categories->pluck('name')->toArray(),
    ];

    return array_merge($array, $extraFields);
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
