
function zoom(e) {
    var zoomer = e.currentTarget;
    var offsetX = e.offsetX || e.touches[0].pageX;
    var offsetY = e.offsetY || e.touches[0].pageY;

    var x = offsetX / zoomer.offsetWidth * 100;
    var y = offsetY / zoomer.offsetHeight * 100;
    zoomer.style.backgroundPosition = x + '% ' + y + '%';
}

function change_principal_image(url) {
    document.getElementById("imgPrincipale").src = url;
    document.getElementById("figure").style.backgroundImage = "url('" + url + "')";
    document.getElementById("figure").setAttribute("data-url", url);
}
function confirmDeleteAccount(userId, livewireInstance) {
    Swal.fire({
        title: translations.confirm_title,
        text: translations.confirm_text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#008080',
        cancelButtonColor: '#000',
        confirmButtonText: translations.confirm_button,
        cancelButtonText: translations.cancel_button
    }).then((result) => {
        if (result.isConfirmed) {
            livewireInstance.call('delete', userId);
            Swal.fire(
                translations.deleted_title,
                translations.deleted_text,
                'success'
            );
        }
    });
}

document.addEventListener('livewire:init', function () {
    Livewire.on('voyage-mode-activated', function () {
        Swal.fire({
            title: `<strong>${window.translations.voyage_activated_title}</strong>`,
            html: `<span style='font-size: 16px;'>${window.translations.voyage_activated_description}</span>`,
            showCloseButton: true,
            showConfirmButton: false,
            iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',

        });
    });
    Livewire.on('voyage-mode-deactivated', function () {
        Swal.fire({
            title: window.translations.voyage_deactivated_title,
            text: window.translations.voyage_deactivated_description,
            iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });
});

// fixe script

function showPassword(id) {
    var x = document.getElementById("password-" + id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
document.addEventListener('livewire:init', () => {
    Livewire.on('alert', (parametres) => {
        const message = parametres[0].message;
        const type = parametres[0].type;
        Swal.fire({
            position: "center",
            icon: type,
            title: message,
            showConfirmButton: true,
            timer: 10000,
            customClass: "swal-wide",
            confirmButtonColor: "#008080"
        });
    });
});
document.addEventListener('livewire:init', () => {
    Livewire.on('alert2', (parametres) => {
        console.log(parametres);
        const message = parametres[0].message;
        const type = parametres[0].type;
        const time = parametres[0].time;

        Swal.fire({
            position: "center",
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: time,
            customClass: "swal-wide",
        });
    });
});

document.addEventListener('livewire:init', () => {
    Livewire.on('openmodalpreview', (data) => {
        console.log(data);
        $("#modal_motifs_preview_post").modal("toggle");
    });
});

function formatTelephone(input) {
    var phoneNumber = input.value.replace(/\D/g, '');
    var formattedPhoneNumber = '';
    for (var i = 0; i < phoneNumber.length; i++) {
        formattedPhoneNumber += phoneNumber[i];
        if ((i + 1) % 2 === 0 && i < phoneNumber.length - 1) {
            formattedPhoneNumber += ' ';
        }
    }
    input.value = formattedPhoneNumber;
}

// ##############
window.onload = function() {
    document.getElementById('agreeConditionButton').classList.add('pulsing');
}

// ###############

document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');
    const closeMenu = document.querySelector('.close-menu');

    navToggle.addEventListener('click', function() {
        sidebarWrapper.classList.toggle('open');
    });

    closeMenu.addEventListener('click', function() {
        sidebarWrapper.classList.remove('open');
    });
});

$(window).scroll(function() {
    var elementToHide = $('.elementToHideBeforeScroll');
    var icons_position = $('#icons_position');
    var comment_position = $('#comment_position');
    var scrollPosition = $(window).scrollTop();

    if (scrollPosition === 0) {
        elementToHide.addClass('d-none');
        comment_position.addClass("comment-position").removeClass("comment-position-top");
        icons_position.removeClass("comment-position").addClass("comment-position-top");
        icons_position.find('.icon-icon-header').css('margin-left', '3px');
    } else {
        icons_position.addClass("comment-position").removeClass("comment-position-top");
        icons_position.find('.icon-icon-header').css('margin-left', '-10px');
        comment_position.removeClass("comment-position").addClass("comment-position-top");
        elementToHide.removeClass('d-none');
    }
});

// Close the navbar when any link is clicked unless it's within a modal
document.querySelectorAll('.nav-menu a').forEach(item => {
    item.addEventListener('click', function (event) {
        if (!event.target.closest('.modal')) {
            document.querySelector('.nav-menus-wrapper').classList.remove('nav-menus-wrapper-open');
        }
    });
});

function close_update_price() {
    location.reload();
}

function openWishlist() {
    document.getElementById("Wishlist").style.display = "block";
}

function closeWishlist() {
    document.getElementById("Wishlist").style.display = "none";
}
function openCart() {
    document.getElementById("Cart").style.display = "block";
}

function closeCart() {
    document.getElementById("Cart").style.display = "none";
}
