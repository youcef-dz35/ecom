
<ul>
  @foreach($items as $menu_item)
    <li>
      @if($menu_item->title=='Follow Me:')
        <li>{{$menu_item->title}}</li>
      @endif
      <a href="{{$menu_item->link()}}"> <i class="fa {{$menu_item->title}}"></i> </a>

    </li>
  @endforeach
</ul>


<!-- <ul>
    <li>Follow Me: </li>

    <li><a href="https://www.facebook.com/youce.kebir"><i class="fa fa-facebook"></i></a></li>
    <li><a href="https://github.com/youcef-dz35"><i class="fa fa-github"></i></a></li>
    <li><a href="https://twitter.com/drehimself"><i class="fa fa-twitter"></i></a></li>
</ul> -->
