@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="col-sm-6 mx-auto border border-1 p-3 rounded card">
            <div class="bg-red p-3 rounded">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="/">
                            <button class="back-btn shadow-none">
                                <i class="bi bi-arrow-left-circle"></i>
                            </button>
                        </a>
                    </div>
                    <div>
                        <h4 class="text-center">
                            <img src="/icons/logo-version-blanc.png"height="30" alt="">
                        </h4>
                    </div>
                    <div></div>
                </div>
                <br>
            </div>
            <br>
            @isset($user)
                @livewire('User.reset', ['user' => $user])
            @else
             {{ $message }}
            @endisset
        </div>
    </div>
@endsection
