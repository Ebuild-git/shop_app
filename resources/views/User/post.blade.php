@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 mx-auto p-3">
                <div class="d-flex justify-content-around timeline">
                    <button id="showArticle" class="step">
                        <i class="fas fa-plus"></i>
                        Publier un article
                    </button>
                    <button id="showRib" class="step">
                        <i class="fas fa-credit-card"></i>
                        Coordonnées Bancaires
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Step Content -->
<div class="container pt-5 pb-5">
    <div id="articleSection" class="content-section">
        <!-- Publier un article section -->
        <div class="text-center">
            <h2 class="mb-2 ft-bold">Publier un article ?</h2>
        </div>
        <div>
            @livewire('User.CreatePost', ['id' => $id ?? ''])
        </div>
    </div>

    <div id="ribSection" class="content-section">
        <div class="form-container">
            <div class="text-center mb-4">
                <h2 class="ft-bold mb-2">Coordonnées Bancaires</h2>
                <p class="text-muted">Veuillez remplir les informations bancaires pour compléter votre profil.</p>
            </div>
            <div id="ribMessage" class="mb-3"></div>
            <form id="ribForm">
                @csrf
                <div class="mb-4 position-relative">
                    {{-- <i class="fas fa-user-circle form-icon"></i> --}}
                    <label for="titulaireName" class="form-label">Nom du Titulaire</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="titulaireName"
                        name="titulaireName"
                        value="{{ old('titulaireName', Auth::user()->titulaire_name ?? '') }}"
                        required
                        placeholder="Entrez le nom du titulaire"
                    >
                </div>

                <div class="mb-4 position-relative">
                    {{-- <i class="fas fa-building form-icon"></i> --}}
                    <label for="bankName" class="form-label">Nom de la Banque</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="bankName"
                        name="bankName"
                        value="{{ old('bankName', Auth::user()->bank_name ?? '') }}"
                        required
                        placeholder="Entrez le nom de la banque"
                    >
                </div>

                <div class="mb-4 position-relative">
                    {{-- <i class="fas fa-credit-card form-icon"></i> --}}
                    <label for="ribNumber" class="form-label">Numéro RIB</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="ribNumber"
                        name="ribNumber"
                        value="{{ old('ribNumber', Auth::user()->rib_number ? Crypt::decryptString(Auth::user()->rib_number) : '') }}"
                        required
                        placeholder="Entrez votre numéro RIB"
                    >
                </div>
                @php
                $isDataAvailable = Auth::user()->titulaire_name && Auth::user()->bank_name && Auth::user()->rib_number;
                @endphp
                <button type="submit" class="btn-prim w-30-custom">
                    {{ $isDataAvailable ? 'Modifier' : 'Sauvegarder' }}
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // AJAX form submission
        $('#ribForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: '{{ route('rib.submit') }}',
                type: 'POST',
                data: formData,
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
<style>
.step {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    background-color: #008080;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.step i {
    margin-right: 8px;
}

.step:hover {
    background-color: #006666;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.step:active {
    background-color: #004d4d;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.timeline {
    gap: 20px;
}

.content-section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background-color: #ffffff;
}

.form-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    box-sizing: border-box;
    margin: 0 auto;
}

.form-icon {
    position: absolute;
    top: 65%;
    left: 15px;
    transform: translateY(-50%);
    color: #008080;
    font-size: 1.5rem;
    opacity: 0.5;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333333;
}

.btn-prim {
    background-color: #008080;
    border: none;
    border-radius: 0.5rem;
    padding: 12px 20px;
    font-weight: 600;
    transition: background-color 0.3s, box-shadow 0.3s;
    margin-left: auto;
    display: block;
    color: white;
}

.btn-prim:hover {
    background-color: #008080;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

</style>

@endsection
