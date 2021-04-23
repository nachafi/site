<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu  a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
   
  
}


.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-left: .1rem;
  margin-right: .1rem;
}



   

}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
                aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav">
            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}">all</a>
                            </li>
                @foreach($categories as $cat)
                    @foreach($cat->items as $category)
                        @if ($category->items->count() > 0)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ route('category.show', $category->slug) }}" id="{{ $category->slug }}"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $category->name }}</a>
                                <ul class="dropdown-menu" aria-labelledby="{{ $category->slug }}">
                                
                        <li class=" dropdown-submenu">
                          
                        
                                @if(count($category->children))
                                
                                 @include('site.partials.navb',['childrens' => $category->children])
                            @endif 
                        
                            </li> 
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                            
                        @endif
                        
                    @endforeach
                @endforeach
                
            </ul>
            
        </div>
    </div>
</nav>

@push('scripts')


<script>

$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
  if (!$(this).next().hasClass('show')) {
    $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
  }
  var $subMenu = $(this).next('.dropdown-menu');
  $subMenu.toggleClass('show');


  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
    $('.dropdown-submenu .show').removeClass('show');
  });


  return false;
});
</script>
@endpush