{{-- resources/views/User/post-deleted.blade.php --}}
@extends('User.fixe')
@section('titre', __('post_deleted'))
@section('body')
<div class="container py-5 text-center">
    <i class="bi bi-exclamation-circle" style="font-size: 64px; color: #dc3545;"></i>

    @if($post->motif_suppression)
        <h3 class="mt-3">{{ __('deleted_by_shopin') }}</h3>
        <p class="text-muted">{{ \App\Traits\TranslateTrait::TranslateText($post->motif_suppression) }}</p>
    @else
        <h3 class="mt-3">{{ __('deleted_by_me') }}</h3>
        <p class="text-muted">{{ __('post_deleted_by_me_message') }}</p>
    @endif

    <a href="/contact" class="btn btn-outline-danger mt-2">
        <i class="bi bi-envelope"></i> {{ __('contact_us') }}
    </a>
</div>
@endsection
