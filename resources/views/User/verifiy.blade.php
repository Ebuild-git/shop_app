@extends('User.fixe')
@section('titre', 'Vérification du compte')
@section('body')




    <div class="container pt-5 pb-5">
        <div class="col-sm-6 mx-auto">
            <div>
                <h4 class="text-center">
                    Vérification du compte
                </h4>
            </div>

            <br>
            <div>
                @isset($error)
                    
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/256/1933/1933606.png"
                            alt="" style="width: 20%" srcset="">
                        <br>
                        <h4>{{ $error }}</h4>
                    </div>
                @else
                    <div class="text-center">
                        <img src="https://c0.klipartz.com/pngpicture/296/544/gratis-png-felicitaciones-multicoloras-concurso-de-youtube-de-la-escuela-dunottar-icono-de-felicitaciones-de-s-thumbnail.png"
                            alt="" style="width: 50%" srcset="">
                        <br>
                        <h4>Félicitation</h4>
                        <p>
                            Votre compte a été vérifié .
                        </p>
                        <br>
                        <a href="/connexion">
                            <button class="btn btn-danger bg-red my-2 my-sm-0" type="button">
                                Se connecter Maintenant
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </button>
                        </a>
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endsection
