<div class="row">
	@forelse ($products as $product)
		@include('site.pages.list_box')
	@empty
		No product found!
	@endforelse
</div>