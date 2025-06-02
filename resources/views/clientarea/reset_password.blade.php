<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v1.599') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v1.599') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ url('assets/css/icons.min.css?v1.599') }}" rel="stylesheet">
    <link href="{{ url('assets/css/bootstrap.min.css?v1.599') }}" rel="stylesheet">
    <link href="{{ url('assets/plugins/metismenu/css/metisMenu.min.css?v1.599') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css?v1.599') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/app.min.css?v1.599') }}" rel="stylesheet">
    <script src="{{ url('assets/js/new.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/metismenu/js/metisMenu.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v1.599') }}"></script>
</head>
<body>
	<div class="modal fade show d-block" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content rounded-lg shadow-lg border-0">
				<div class="modal-header bg-blue-600 text-white rounded-t-lg">
					<h5 class="modal-title font-semibold" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
					<button type="button" class="text-white bg-transparent border-0" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" class="text-2xl">&times;</span>
					</button>
				</div>
				<div class="modal-body p-4">
					<form action="{{ route('client.reset.password') }}" method="POST">
						@csrf
						<div class="mb-4">
							<label for="current_password" class="block text-gray-700 font-medium mb-2">{{__('web.current_password')}}</label>
							<input type="password" name="current_password" id="current_password"
								class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
						</div>
						<div class="mb-4">
							<label for="new_password" class="block text-gray-700 font-medium mb-2">{{__('web.new_password')}}</label>
							<input type="password" name="new_password" id="new_password"
								class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
						</div>
						<div class="mb-4">
							<label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2">{{__('web.confirm_new_password')}}</label>
							<input type="password" name="new_password_confirmation" id="new_password_confirmation"
								class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
						</div>
						<div class="mt-4 flex justify-end">
							<button type="submit" class="btn-primary btn me-2">{{__('web.reset_password')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>