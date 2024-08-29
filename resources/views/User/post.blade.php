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
                        Remplir le RIB
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

    <div id="ribSection" class="content-section" style="display: none;">
        <!-- Remplir le RIB section -->
        <div class="text-center">
            <h2 class="mb-2 ft-bold">Remplir le RIB</h2>
        </div>
        <div class="col-md-8 mx-auto p-4 shadow-sm rounded bg-light">
            <div id="ribMessage" class="mt-2"></div>
            <form id="ribForm">
                @csrf
                <div class="mb-4 position-relative">
                    <label for="ribNumber" class="form-label fw-bold text-muted">Numéro RIB</label>
                    <input
                        type="text"
                        class="form-control form-control-lg border-0 border-bottom rounded-0 shadow-sm pe-5"
                        id="ribNumber"
                        name="ribNumber"
                        value="{{ old('ribNumber', Auth::user()->rib_number ? Crypt::decryptString(Auth::user()->rib_number) : '') }}"
                        required
                        placeholder="Entrez votre numéro RIB"
                        style="background-color: #f9f9f9; transition: border-color 0.3s;"
                    >

                </div>
                <button type="submit" class="btn btn-primary" style="background-color: #008080; border: none;">Sauvegarder</button>
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

        showArticleButton.addEventListener('click', function () {
            articleSection.style.display = 'block';
            ribSection.style.display = 'none';
        });

        showRibButton.addEventListener('click', function () {
            articleSection.style.display = 'none';
            ribSection.style.display = 'block';
        });

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
<style>
.step {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    background-color: #008080; /* Teal color */
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.step i {
    margin-right: 8px; /* Space between icon and text */
}

.step:hover {
    background-color: #006666; /* Darker teal on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.step:active {
    background-color: #004d4d; /* Even darker teal on click */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.timeline {
    gap: 20px; /* Space between buttons */
}

</style>

@endsection
