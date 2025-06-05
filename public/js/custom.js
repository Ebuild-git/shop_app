// function zoom(e) {
//     var zoomer = e.currentTarget;
//     e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
//     e.offsetY ? offsetY = e.offsetY : offsetY = e.touches[0].pageY;

//     x = offsetX / zoomer.offsetWidth * 100
//     y = offsetY / zoomer.offsetHeight * 100
//     zoomer.style.backgroundPosition = x + '% ' + y + '%';
// }
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
