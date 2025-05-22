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
