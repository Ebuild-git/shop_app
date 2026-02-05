@if(!isset($_COOKIE['cookie_consent']))
<div id="cookie-consent-banner" class="cookie-consent-banner" style="{{ app()->getLocale() == 'ar' ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
    <div class="cookie-consent-content">
        <div class="cookie-consent-text">
            <h5>{{ __('cookies_title_1') }}</h5>
            <p>
                {!! __('cookies_message', [
                    'privacy_url' => url('/politique-confidentialite'),
                    'cookies_url' => url('/politique-cookies')
                ]) !!}
            </p>
        </div>
        <div class="cookie-consent-buttons">
            <button type="button" class="btn btn-secondary" onclick="rejectCookies()">
                {{ __('cookies_reject') }}
            </button>
            <button type="button" class="btn btn-primary" onclick="acceptCookies()">
                {{ __('cookies_accept') }}
            </button>
        </div>
    </div>
</div>

<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    padding: 20px;
    animation: slideUp 0.5s ease-out;
    border-top: 3px solid #008080;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}

.cookie-consent-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.cookie-consent-text {
    flex: 1;
    min-width: 300px;
}

.cookie-consent-text h5 {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.cookie-consent-text p {
    margin: 0;
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.cookie-consent-text a {
    color: #008080;
    text-decoration: underline;
    font-weight: 500;
}

.cookie-consent-text a:hover {
    color: #006666;
}

.cookie-consent-buttons {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

.cookie-consent-buttons .btn {
    padding: 12px 24px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.cookie-consent-buttons .btn-secondary {
    background: #6c757d;
    color: white;
}

.cookie-consent-buttons .btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.cookie-consent-buttons .btn-primary {
    background: #008080;
    color: white;
}

.cookie-consent-buttons .btn-primary:hover {
    background: #006666;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 128, 128, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .cookie-consent-banner {
        padding: 15px;
    }

    .cookie-consent-content {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .cookie-consent-text h5 {
        font-size: 16px;
    }

    .cookie-consent-text p {
        font-size: 13px;
    }

    .cookie-consent-buttons {
        width: 100%;
        flex-direction: column;
    }

    .cookie-consent-buttons .btn {
        width: 100%;
        padding: 12px;
    }
}

/* Support RTL pour l'arabe */
[dir="rtl"] .cookie-consent-content {
    direction: rtl;
}

[dir="rtl"] .cookie-consent-buttons {
    flex-direction: row-reverse;
}

@media (max-width: 768px) {
    [dir="rtl"] .cookie-consent-buttons {
        flex-direction: column;
    }
}
</style>

<script>
function acceptCookies() {
    // Enregistrer le consentement
    setCookie('cookie_consent', 'accepted', 365);

    // Enregistrer les préférences détaillées
    setCookie('cookie_preferences', JSON.stringify({
        necessary: true,
        analytics: true,
        marketing: true,
        timestamp: new Date().toISOString()
    }), 365);

    // Déclencher les événements analytics si acceptés
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'granted',
            'ad_storage': 'granted'
        });
    }

    hideBanner();
}

function rejectCookies() {
    // Enregistrer le refus
    setCookie('cookie_consent', 'rejected', 365);

    // Enregistrer uniquement les cookies nécessaires
    setCookie('cookie_preferences', JSON.stringify({
        necessary: true,
        analytics: false,
        marketing: false,
        timestamp: new Date().toISOString()
    }), 365);

    // Désactiver les analytics si présents
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied'
        });
    }

    hideBanner();
}

function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();

    // Ajouter Secure si HTTPS
    const secure = window.location.protocol === 'https:' ? ';Secure' : '';

    document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax" + secure;
}

function hideBanner() {
    const banner = document.getElementById('cookie-consent-banner');
    if (banner) {
        banner.style.animation = 'slideDown 0.5s ease-out';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 500);
    }
}

// Empêcher le banner de réapparaître pendant la navigation
document.addEventListener('DOMContentLoaded', function() {
    // Si déjà consenti, ne rien faire
    if (document.cookie.indexOf('cookie_consent') !== -1) {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }
});
</script>
@endif
