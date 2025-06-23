@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="position-absolute">
                </div>
                <img src="/icons/login.png"
                    class="img" alt="" srcset="">
            </div>
            <div class="col-sm-6" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
                <hr>
                <h4>
                    {{ __('login') }}
                </h4>

                @if(session('success'))
                <div class="custom-teal-alert">
                    {!! session('success') !!}
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif
                @livewire('User.connexion')
            </div>
        </div>
    </div>

    <style>
        .img {
            width: 100%;
            border-radius: 05%;
        }

        .custom-teal-alert {
            background-color: rgba(0, 128, 128, 0.1);
            color: #008080;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 400;
            text-align: left;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-teal-alert::before {
            font-size: 20px;
            margin-right: 8px;
            color: #008080;
        }

    </style>
@endsection
