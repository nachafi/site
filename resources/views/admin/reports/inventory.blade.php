@extends('admin.app')

@section('content')
	<div class="content">
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-default">
					<div class="card-header card-header-border-bottom">
						<h2>Inventory Report</h2>
					</div>
					<div class="card-body">
						@include('admin.partials.flash')
						
							<div class="form-group mb-2">
							@foreach($exports as $export)
								{{$export}}
								@endforeach
							</div>
							<div class="form-group mx-sm-3 mb-2">
								<button type="submit" class="btn btn-primary btn-default">Go</button>
							</div>
						
						<table class="table table-hover table-bordered" id="sampleTable">
							<thead>
								<th>Name</th>
								<th>SKU</th>
								<th>Stock</th>
							</thead>
							<tbody>
								@forelse ($products as $product)
									<tr>    
										<td>{{ $product->name }}</td>
										<td>{{ $product->sku }}</td>
										<td>{{ $product->stock }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3">No records found</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush