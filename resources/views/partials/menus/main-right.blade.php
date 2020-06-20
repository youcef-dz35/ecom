
<ul>
  @guest
  <li><a href="{{route('register')}}">Sign Up</a> </li>
  <li><a href="{{route('login')}}">Log In</a> </li>
  @else
  <li>
    <a href="{{route('logout')}}"
       onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
        Logout <span class="caret"></span>
    </a>
  </li>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

  @endguest

  <li><a href="{{route('cart.index')}}">Cart
     @if (Cart::instance('default')->count() > 0)
    <span class="cart-count"><span>{{ Cart::instance('default')->count() }}</span></span>
    @endif
      </a>
  </li>
{{--  @foreach($items as $menu_item)
    <li>
      <a href="{{ $menu_item->link() }}">
        {{$menu_item->title}}
        @if($menu_item->title == 'Cart')

          @if (Cart::instance('default')->count() > 0)
            <span class="cart-count"><span>{{ Cart::instance('default')->count() }}</span></span>
          @endif

        @endif
      </a>
    </li>
  @endforeach --}}
</ul>
