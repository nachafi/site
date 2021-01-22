<div class="row">
    @forelse ($products as $product)
        @include('site.pages.grid_box')
    @empty
        No product found!
    @endforelse
</div>