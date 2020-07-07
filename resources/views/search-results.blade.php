@extends('layout')

@section('title', 'Search Results')

@section('extra-css')

@endsection

@section('content')


    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Search</span>
    @endcomponent
    <div class="container">
        @if (session()->has('success_message'))
            <div class="spacer"></div>
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="spacer"></div>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="search-results-container container">
        <h1>Search Results</h1>
        <p class="search-results-count">{{$products->total()}} result(s) for the '{{ request()->input('query')}}'</p>   

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Details</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                

                @foreach($products as $product)
                <tr>    
                    <td><a href="{{route('shop.show',$product->slug)}}">{{$product->name}}</a></td>
                    <td>{{Illuminate\Support\Str::limit( $product->details, 50)}}</td>
                    <td>{{Illuminate\Support\Str::limit( $product->description, 50) }}</td>
                    <td>${{$product->presentPrice()}}</td>
                </tr>    
                @endforeach        
            
                
            </tbody>
        </table>
        {{ $products->appends(request()->input())->links()}}
        
    </div> <!-- end serarch-container -->

  


@endsection

@section('extra-js')
  
  

@endsection
