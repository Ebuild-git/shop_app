@extends('User.fixe')
@section('titre', 'Conditions générales')
@section('content')
@section('body')

    <div class="container pt-5 pb-5" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">

        {{-- importation des conditions generales qui sont utilisé sur plusieurs pages --}}
        @include('User.composants.text-conditions')

    </div>
@endsection
