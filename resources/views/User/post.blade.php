@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 mx-auto p-3">
                <div class="d-flex justify-content-around timeline">
                    <button id="showArticle" class="step active">
                        <i class="fas fa-plus"></i>
                        {{ __('publish_article')}}
                    </button>
                    <button id="showRib" class="step">
                        <i class="fas fa-credit-card"></i>
                        {{ __('personal_details')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Step Content -->
<div class="container pt-5 pb-5" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    <div id="articleSection" class="content-section">
        <!-- Publier un article section -->
        <div class="text-center">
            <h2 class="mb-2 ft-bold">{{ __('publish_article1')}}</h2>
        </div>
        <div>
            @livewire('User.CreatePost', ['id' => $id ?? ''])
        </div>
    </div>

    <div id="ribSection" class="content-section">
        <div class="form-container">
            <div class="text-center mb-4">
                <h2 class="ft-bold mb-2">{{ __('personal_details')}}</h2>
                <p class="text-muted">{{ __('subtitle')}}</p>
            </div>
            <form id="ribForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4 position-relative">
                    <label for="titulaireName" class="form-label">{{ __('account_holder_name')}}</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="titulaireName"
                        name="titulaireName"
                        value="{{ old('titulaireName', Auth::user()->titulaire_name ?? '') }}"
                        required
                        placeholder="{{ __('account_holder_name')}}"
                    >
                </div>

                <div class="mb-4 position-relative">
                    {{-- <i class="fas fa-building form-icon"></i> --}}
                    <label for="bankName" class="form-label">{{ __('bank_name')}}</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="bankName"
                        name="bankName"
                        value="{{ old('bankName', Auth::user()->bank_name ?? '') }}"
                        required
                        placeholder="{{ __('bank_name')}}"
                    >
                </div>

                <div class="mb-4 position-relative">
                    <label for="ribNumber" class="form-label">{{ __('rib_number')}}</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="ribNumber"
                        name="ribNumber"
                        value="{{ old('ribNumber', Auth::user()->rib_number ? Crypt::decryptString(Auth::user()->rib_number) : '') }}"
                        required
                        placeholder="{{ __('rib_number')}}"
                    >
                </div>
                <div class="mb-4 position-relative">
                    <label for="cinImg" class="form-label">{{ __('cin_upload')}}</label>
                    <div class="file-upload-container">
                        @if(Auth::user()->cin_img)
                            <div class="file-upload-preview">
                                <img id="imagePreview" src="{{ asset('storage/' . Auth::user()->cin_img) }}" alt="Image Preview">
                                <a href="{{ asset('storage/' . Auth::user()->cin_img) }}" download class="btn mt-2">{{ __('download_image')}}</a>
                            </div>
                        @else
                            <div class="file-upload-preview">
                                <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                            </div>
                        @endif

                        <!-- File input hidden -->
                        <input
                            type="file"
                            class="file-input"
                            id="cinImg"
                            name="cin_img"
                            accept="image/*"
                        >

                        <!-- Custom upload button -->
                        <label for="cinImg" class="upload-icon-label">
                            <i class="fas fa-upload"></i>
                            <span>{{ __('choose_file')}}</span>
                        </label>
                    </div>
                </div>

                @php
                $isDataAvailable = Auth::user()->titulaire_name && Auth::user()->bank_name && Auth::user()->rib_number;
                @endphp
                <button type="submit" class="btn-prim w-30-custom">
                    {{ $isDataAvailable ? __('update') : __('save') }}
                </button>
                <div id="ribMessage" class="mt-4"></div>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.step').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.step').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('cinImg').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showArticleButton = document.getElementById('showArticle');
        const showRibButton = document.getElementById('showRib');
        const articleSection = document.getElementById('articleSection');
        const ribSection = document.getElementById('ribSection');

        // Function to update the URL without reloading the page
        function updateUrl(step) {
            const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?step=${step}`;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        // Show the article section and hide the rib section
        showArticleButton.addEventListener('click', function () {
            articleSection.style.display = 'block';
            ribSection.style.display = 'none';
            updateUrl('step-1');  // Update the URL to step-1
        });

        // Show the rib section and hide the article section
        showRibButton.addEventListener('click', function () {
            articleSection.style.display = 'none';
            ribSection.style.display = 'block';
            updateUrl('step-2');  // Update the URL to step-2
        });

        // Check the URL on page load and show the correct section
        const urlParams = new URLSearchParams(window.location.search);
        const step = urlParams.get('step');

        if (step === 'step-2') {
            articleSection.style.display = 'none';
            ribSection.style.display = 'block';
        } else {
            articleSection.style.display = 'block';
            ribSection.style.display = 'none';
        }

        $('#ribForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '{{ route('rib.submit') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#ribMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                },
                error: function (xhr) {
                    $('#ribMessage').html('<div class="alert alert-danger">Erreur : ' + xhr.responseJSON.message + '</div>');
                }
            });
        });

    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


@endsection
