@extends('User.fixe')
@section('titre', $user->name)
@section('content')
@section('body')

<div class="container pt-5 pb-5">
    <div class="col-sm-4">
        <img src="{{ Storage::url($user->avatar)}}" alt="" srcset="" class="avatar-profil">
        <br>
    </div>
    <div class="col-sm-8"></div>
</div>



<style>
    .avatar-profil{
        height: 100px;
        width: 100px;
        border-radius: 100%
    }
</style>
@endsection