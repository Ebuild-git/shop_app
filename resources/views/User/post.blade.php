@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

<!-- ======================= Shop Style 1 ======================== -->
<section class="bg-cover" style="background:url('/icons/post.jpg') no-repeat;">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="text-left py-5 mt-3 mb-3">
                    <h1 class="ft-medium mb-3 text-white">Vendre un article</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Shop Style 1 ======================== -->

<div class="container pt-5 pb-5">
    
    <div >
        @livewire('User.CreatePost', ['id' => $id ?? ""])
    </div>
    
</div>
@endsection