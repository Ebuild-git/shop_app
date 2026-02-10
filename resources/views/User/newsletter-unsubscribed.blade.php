@extends('User.fixe')

@section('titre', __('newsletter_unsubscribed'))

@section('body')
    <section class="gray py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-10 col-sm-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="bi bi-envelope-slash" style="font-size: 4rem; color: #6c757d;"></i>
                            </div>

                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Error Message -->
                            @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Heading -->
                            <h2 class="mb-3" style="color: #008080;">{{ __('newsletter_unsubscribed_title') }}</h2>

                            <!-- Message -->
                            <p class="text-muted mb-4">
                                {{ __('newsletter_unsubscribed_message') }}
                            </p>

                            <!-- Feedback Section -->
                            <div class="bg-light p-4 rounded mb-4">
                                <p class="mb-2">
                                    <strong>{{ __('newsletter_unsubscribed_feedback') }}</strong>
                                </p>
                                <p class="small text-muted mb-0">
                                    {{ __('newsletter_unsubscribed_feedback_text') }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                <a href="{{ url('/') }}" class="btn btn-primary"
                                    style="background-color: #008080; border-color: #008080;">
                                    <i class="bi bi-house-door me-2"></i>
                                    {{ __('back_to_home') }}
                                </a>
                                <a href="{{ url('/shop') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-shop me-2"></i>
                                    {{ __('continue_shopping') }}
                                </a>
                            </div>

                            <!-- Resubscribe Option -->
                            <div class="mt-4 pt-4 border-top">
                                <p class="small text-muted mb-2">
                                    {{ __('newsletter_changed_mind') }}
                                </p>
                                <a href="{{ url('/#newsletter') }}" class="text-decoration-none" style="color: #008080;">
                                    <strong>{{ __('newsletter_resubscribe') }}</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection