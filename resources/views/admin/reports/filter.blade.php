<form action="" method="GET" class="input-daterange form-inline" >
 @csrf
	<div class="form-group mb-2">
		<input type="text" class="form-control datepicker" readonly="" value="{{ !empty(request()->input('start')) ? request()->input('start') : '' }}" name="start" placeholder="from">
	</div>
	<div class="form-group mx-sm-3 mb-2">
		<input type="text" class="form-control datepicker" readonly="" value="{{ !empty(request()->input('end')) ? request()->input('end') : '' }}" name="end" placeholder="to">
	</div>

	<div class="form-group mx-sm-3 mb-2">
		hg
	</div>
	<div class="form-group mx-sm-3 mb-2">
		<button type="submit" class="btn btn-primary btn-default">Go</button>
	</div>
	</form>
	@push('scripts')


<script type="text/javascript" src="{{ asset('backend/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/daterangepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
		$('#sl').click(function () {
			$('#tl').loadingBtn();
			$('#tb').loadingBtn({ text: "Signing In" });
		});

		$('#el').click(function () {
			$('#tl').loadingBtnComplete();
			$('#tb').loadingBtnComplete({ html: "Sign In" });
		});
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
			todayHighlight: true
		});

		$('#demoDate').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			todayHighlight: true
		});

		$('#demoSelect').select2();
	</script>
@endpush