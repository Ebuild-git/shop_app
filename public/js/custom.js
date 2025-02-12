function confirmDeleteAccount(userId, livewireInstance) {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#008080',
        cancelButtonColor: '#000',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            livewireInstance.call('delete', userId);
            Swal.fire(
                'Supprimé!',
                'Votre compte a été supprimé.',
                'success'
            );
        }
    });
}
