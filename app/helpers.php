<?php

function presentPrice($price)
{
  return round($price /100, 2);
}

function setActiveCategory($category, $output='active')
{
  return request()->category == $category ? $output:'';
}
