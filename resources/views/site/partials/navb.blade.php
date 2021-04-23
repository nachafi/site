<style>

</style>


@foreach($childrens as $children)


<a class="dropdown-item {{ count($children->children) ? 'dropdown-toggle' :'' }}" data-toggle="dropdown" href="{{ route('category.show', $children->slug) }}" style="border:0px solid #ccc;">{{ $children->name }}</a>

       @if(count($children->children))
	   <ul class="dropdown-menu" aria-labelledby="{{ $category->slug }}">
       <li class=" dropdown-submenu">
      
      

	   
                 @include('site.partials.navb',['childrens' => $children->children])
               
	
       </li>
        </ul>
                   
        @endif
       
		
@endforeach
