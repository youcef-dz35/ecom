@extends('layout')

@section('title', $product->name)

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="/">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <a href="{{ route('shop.index') }}">Shop</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Macbook Pro</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="product-section container">
        <div class="product-section-image">
            <img src="{{ asset('img/products/'.$product->slug.'.jpg') }}" alt="product">
        </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name }}</h1>
            <div class="product-section-subtitle">{{ $product->details }}</div>
            <div class="product-section-price">${{ $product->price/100 }}</div>

            <p>
                {{ $product->description }}
            </p>

            <p>&nbsp;</p>

            <!-- <a href="#" class="button">Add to Cart</a> -->
            <form class="" action="{{route('cart.store')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="id" value="{{$product->id}}">
              <input type="hidden" name="name" value="{{$product->name}}">
              <input type="hidden" name="price" value="{{$product->price}}">
              <button class="button button-plain" type="submit" name="button">Add To Cart</button>

            </form>
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')


@endsection
