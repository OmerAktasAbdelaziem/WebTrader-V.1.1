@extends('layouts.client')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    .page-wrapper{
        background-image: url("{{url('assets/images/background2.png')}}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
@section('title', 'Client Dashboard')

@section('content')
    <div class="page-wrapper">
        <div class="page-content p-2">
            @if (Auth::guard('client')->check())
                <div class="row justify-content-center m-0">
                    <div class="col-lg-8 col-md-10 p-0">
                        <div class="p-2">
                            <h1 class="text-white fw-bold text-center mb-4">{{__('web.welcome')}}, {{ Auth::guard('client')->user()->first_name }} {{ Auth::guard('client')->user()->last_name }}</h1>
                            <hr>
                            <form>
                                <div class="row mb-3 g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.account_number')}}</label>
                                        <input type="text" class="form-control" value="#{{ Auth::guard('client')->user()->broker_id }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.full_name')}}</label>
                                        <input type="text" class="form-control" value="{{ Auth::guard('client')->user()->first_name }} {{ Auth::guard('client')->user()->last_name }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.email')}}</label>
                                        <input type="text" class="form-control" value="{{ Auth::guard('client')->user()->email }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.phone_number')}}</label>
                                        <input type="text" class="form-control" value="{{ Auth::guard('client')->user()->phone1 }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.currency')}}</label>
                                        <input type="text" class="form-control" value="USD" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.country')}}</label>
                                        <input type="text" class="form-control" value="{{ Auth::guard('client')->user()->country }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-white">{{__('web.leverage')}}</label>
                                        <input type="text" class="form-control" value="1:500" readonly>
                                    </div>
                                </div>
                                <div class="mt-4 hidden lg:block">
                                    <!-- Reset Password Button -->
                                    <button type="button" class="text-white px-4 py-2 rounded hover:bg-blue-600" data-toggle="modal" data-target="#resetPasswordModal" style="background-color: #475BB2;">
                                        {{__('web.reset_password')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="text-center">
                            <p class="card-text">{{__('web_information')}}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">{{__('web.login_now')}}</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

<!-- Reset Password Modal -->
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
<!-- End Reset Password Modal -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection