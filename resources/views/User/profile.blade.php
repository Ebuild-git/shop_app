@extends('User.fixe')
@section('titre', $user->name)
@section('content')
@section('body')

    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-sm-3 ">
                <div class="card p-3">
                    <div class="text-center">
                        <img src="{{ Storage::url($user->avatar) }}" alt="" srcset="" class="avatar-profil">
                        <br>
                        <span class="h5">
                            {{ $user->name }}
                        </span>
                    </div>
                    <hr>
                    <div>
                        <i class="bi bi-card-list"></i>
                        {{ $user->GetPosts->count() }} Annonces <br>
                        <i class="bi bi-geo-alt"></i>
                        {{ $user->adress }} <br>
                        <i class="bi bi-house"></i>
                        {{ $user->region->nom ?? '' }}<br>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                @livewire('User.ProfileAnnonces', ['user' => $user])
            </div>
        </div>
    </div>



    <style>
        .avatar-profil {
            height: 100px;
            width: 100px;
            border-radius: 100%
        }

        .couverture {
            background: url("https://cdn.pixabay.com/photo/2014/02/27/16/10/flowers-276014_640.jpg")no-repeat;
            background-size: cover;
            height: 300px;
        }
    </style>
@endsection
