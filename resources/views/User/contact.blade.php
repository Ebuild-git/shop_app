@extends('User.fixe')
@section('titre', 'Contact')
@section('body')

    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('contact')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Contact Page Detail ======================== -->
    <section class="middle">
        <div class="container">

            <div class="row align-items-start justify-content-between" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="card-wrap-body mb-4">
                        <p>{{ $configuration->adresse ?? '' }}</p>
                    </div>

                    <div class="card-wrap-body mb-3">
                        <table>
                            <tr >
                                <td class="pr-2">
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios-filled/50/008080/chat.png" alt="chat" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('chat') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->phone_number ?? 'xxxxxxx' }}</b>
                                </td>
                            </tr>
                        </table> <br>
                        <table>
                            <tr>
                                <td>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios-filled/50/008080/circled-envelope.png"
                                        alt="circled-envelope" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('email1') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->email ?? 'xxxxx@gmail.com' }}</b>
                                </td>
                            </tr>
                        </table> <br>
                        <table>
                            <tr>
                                <td>
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/glyph-neue/50/008080/ringer-volume.png"
                                        alt="ringer-volume" />
                                </td>
                                <td>
                                    <span class="h6">
                                        <b>{{ __('call1') }}</b><br>
                                    </span>
                                    <b>{{ $configuration->phone_number ?? 'xxxxxxx' }}</b>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card-wrap-body mb-3">
                        <h4 class="ft-medium mb-3 ">
                            {!! __('find_shopiner_on') !!}
                        </h4>
                        <div class="text-center">
                            @if ($configuration->facebook)
                                <a href="{{ $configuration->facebook }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/facebook--v1.png"
                                        alt="facebook--v1" />
                                </a>
                            @endif
                            @if ($configuration->linkedin)
                                <a href="{{ $configuration->linkedin }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/linkedin.png" alt="linkedin" />
                                </a>
                            @endif
                            @if ($configuration->tiktok)
                                <a href="{{ $configuration->tiktok }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/tiktok--v1.png" alt="tiktok--v1" />
                                </a>
                            @endif
                            @if ($configuration->instagram)
                                <a href="{{ $configuration->instagram }}">
                                    <img width="30" height="30"
                                        src="https://img.icons8.com/ios-filled/30/008080/instagram-new--v1.png"
                                        alt="instagram-new--v1" />
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                    <div class="sec_title position-relative ">
                        <h2 class="ft-bold pt-3">{{ __('leave_us_message') }}</h2>
                    </div>

                    <form id="contactForm" class="row" action="{{ route('contact.send') }}" method="POST">
                        @csrf

                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('your_name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('your_email_address') }}</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('subject') }}</label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="small text-dark ft-medium">{{ __('message') }}</label>
                                <textarea name="message" class="form-control ht-80" required></textarea>
                            </div>
                        </div>

                        {{-- CASES À COCHER CNDP - OBLIGATOIRE --}}
                        <div class="col-xl-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="consent_rgpd"
                                           name="consent_rgpd" required>
                                    <label class="custom-control-label small text-dark" for="consent_rgpd">
                                        {!! __('contact_consent_rgpd', [
                                            'privacy_url' => url('/politique-confidentialite')
                                        ]) !!}
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- NEWSLETTER - OPTIONNEL --}}
                        <div class="col-xl-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="consent_newsletter"
                                           name="consent_newsletter">
                                    <label class="custom-control-label small text-dark" for="consent_newsletter">
                                        {{ __('contact_consent_newsletter') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="form-group">
                                <button id="submitBtn" type="submit" class="btn bg-red" disabled>
                                    {{ __('send_message') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="successMessage" class="alert alert-success mt-3 d-none">
                        {{ __('Your message has been sent successfully!') }}
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ======================= Contact Page End ======================== -->

<style>
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #008080;
    border-color: #008080;
}

.custom-control-label {
    cursor: pointer;
    line-height: 1.6;
}

.custom-control-label a {
    color: #008080;
    text-decoration: underline;
    font-weight: 500;
}

.custom-control-label a:hover {
    color: #006666;
}

[dir="rtl"] .custom-control {
    padding-right: 1.5rem;
    padding-left: 0;
}

[dir="rtl"] .custom-control-label::before,
[dir="rtl"] .custom-control-label::after {
    right: -1.5rem;
    left: auto;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const successMsg = document.getElementById('successMessage');
    const consentCheckbox = document.getElementById('consent_rgpd');

    // Activer/désactiver le bouton submit selon la case RGPD
    consentCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Vérifier que le consentement RGPD est coché
        if (!consentCheckbox.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez accepter la politique de confidentialité',
                confirmButtonColor: '#008080',
                confirmButtonText: 'OK'
            });
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Envoi en cours...
        `;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw {
                        status: response.status,
                        data: data
                    };
                }
                return data;
            });
        })
        .then(data => {
            // Succès
            form.classList.add('d-none');
            successMsg.classList.remove('d-none');

            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'Votre message a été envoyé avec succès!',
                confirmButtonColor: '#008080',
                confirmButtonText: 'OK'
            });
        })
        .catch(error => {
            console.error('Error:', error);

            let errorMessage = 'Erreur lors de l\'envoi du message';
            let errorTitle = 'Erreur';

            if (error.data) {
                if (error.data.errors) {
                    const firstError = Object.values(error.data.errors)[0][0];
                    errorMessage = firstError;
                }
                else if (error.data.message) {
                    // Vérifier les erreurs SMTP spécifiques
                    if (error.data.message.includes('Domain not found') ||
                        error.data.message.includes('Recipient address rejected') ||
                        error.data.message.includes('L\'adresse email de destination est invalide')) {
                        errorTitle = 'Erreur d\'email';
                        errorMessage = 'L\'adresse email de destination est invalide ou le domaine n\'existe pas. Veuillez vérifier l\'email et réessayer.';
                    } else if (error.data.message.includes('450') ||
                               error.data.message.includes('550') ||
                               error.data.message.includes('SMTP')) {
                        errorTitle = 'Erreur d\'email';
                        errorMessage = 'Erreur serveur lors de l\'envoi de l\'email. Veuillez réessayer plus tard.';
                    } else {
                        errorMessage = error.data.message;
                    }
                }
            }

            Swal.fire({
                icon: 'error',
                title: errorTitle,
                html: errorMessage.replace(/\n/g, '<br>'),
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            if (!form.classList.contains('d-none')) {
                submitBtn.disabled = !consentCheckbox.checked;
                submitBtn.innerHTML = originalText;
            }
        });
    });
});
</script>
@endsection
