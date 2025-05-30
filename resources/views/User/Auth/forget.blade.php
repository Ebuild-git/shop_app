@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="col-sm-6 mx-auto card border border-1 p-3 ">
            <div class=" p-3 ">
                <div class="h3">
                    {{ __('forgot_password1')}}
                </div>
            </div>
            <br>
            @livewire('User.ResetPassword')
        </div>
    </div>
@endsection
