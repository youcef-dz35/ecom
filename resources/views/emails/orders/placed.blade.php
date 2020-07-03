@component('mail::message')
# Order Received

Thank you For your Order.

    **Order ID** : {{$order->id}} 
    **Order Email** : {{$order->billing_email}} 
    **Order Billing Name** : {{$order->billing_name}} 
    **Order Total** : ${{round($order->billing_total)/100,2}} 
    **Items Ordered**

    @foreach($order->products as $product)
    Name: {{$product->name}} <br>
    Price: ${{round($product->price/100,2)}} <br>
    Quantitiy: {{$product->pivot->quantity}} <br>
    @endforeach


    You Can get further details about your order by logging into our website

@component('mail::button',['url'=> config('app.url'),'color'=>'green'])
Go to Website
@endcomponent
Thank you again for choosing us.
Regards, <br>
{{config('app.name')}}

@endcomponent
