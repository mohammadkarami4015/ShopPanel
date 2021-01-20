@extends('layouts.app')
@section('title')
    shop panel
@stop
@section('content')
    <div class="container">
        <div id="getCode">

        </div>
        <div class="row justify-content-center" dir="rtl" style="font-size: 13px">
            <div class="col-md-6">
                <div id class="card" style="text-align: right">
                    <div class="card-header">{{ __('فرم ورود') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf


                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-3 col-form-label text-md-right">{{ __('شماره تلفن') }}</label>

                                <div class="col-md-6">
                                    <input id="phone_number" type="text" maxlength="11" minlength="11"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           name="phone_number" required autocomplete="current-password">

                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-3 col-form-label text-md-right">{{ __('کد') }}</label>

                                <div class="col-md-6">
                                    <input id="code" type="password"
                                           class="form-control @error('code') is-invalid @enderror" name="code" required
                                           autocomplete="current-password">

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div  class="col-md-6" style="margin-right: 20%">
                                <div class="form-group row" style="float: right;">
                                    <div class="col-md-8 offset-md-4">
                                        <a onclick="sendCode()" class="btn btn-sm btn-danger" style="color: white">
                                            ارسال کد
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group row" style="float: right;margin-right:1%">
                                    <div class="col-md-8 offset-md-6">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                                 ورود
                                        </button>

                                        @if (Route::has('password.request'))

                                        @endif
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="{!! asset('js/sweetalert.min.js') !!}"></script>
    @include('flash')
    <script>
        function sendCode() {
            var value = $('#phone_number').val();

            $.get(`/auth/send-number`, {phone_number: value}, function (result) {
                $('#getCode').html(result)
            });
        }
    </script>
@endsection
