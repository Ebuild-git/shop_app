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
