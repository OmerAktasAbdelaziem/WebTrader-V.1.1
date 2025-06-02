@extends('layouts.client')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%; border-radius: 10px; ">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="text-center mb-4">
            <h4 class="mb-0 font-weight-bold">{{__('web.kyc_verification')}}</h4>
            <p class="text-muted small">{{__('web.vaild_id')}}</p>
        </div>
        <form method="POST" action="{{ route('client.kyc.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group text-center">
                <label class="font-weight-bold d-block mb-2">{{__('web.upload_id')}}</label>
                <div id="dropZone" class="border p-4 d-flex flex-column justify-content-center align-items-center rounded text-center" style="border: 2px dashed #ccc; background: #fafafa; cursor: pointer; width: 100%; min-height: 150px;">
                    <span class="text-muted">{{__('web.drag_drop')}} <span class="text-primary font-weight-bold">{{__('web.browse')}}</span></span>
                    <input type="file" name="kyc" class="d-none" id="fileUpload" accept="image/*">
                </div>
            </div>
            
            <div id="previewContainer" class="text-center mt-3" style="display: none;">
                <div class="position-relative d-inline-block">
                    <img id="previewImage" src="" alt="Selected Image" class="rounded shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd;">
                    <button type="button" id="removeImage" class="btn btn-danger btn-sm position-absolute" style="top: -10px; right: -10px; border-radius: 50%;">❌
                    </button>
                </div>
            </div>
            <div class="form-group mt-4 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">{{__('web.submit')}}</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client.reset_password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700">{{__('web.new_password')}}</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-700">{{__('web.confirm_new_password')}}</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{__('web.reset_password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('dropZone').addEventListener('click', function() {
        document.getElementById('fileUpload').click();
    });

    document.getElementById('fileUpload').addEventListener('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('removeImage').addEventListener('click', function() {
        document.getElementById('fileUpload').value = "";
        document.getElementById('previewContainer').style.display = 'none';
    });
</script>
@endsection
