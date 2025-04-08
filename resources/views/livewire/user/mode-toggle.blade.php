<div>
    <li class="mode-toggle">
        <span class="medium text-medium">{{ __('Mode voyage') }}</span>
        <label class="switch" style="margin-right: 5px; margin-top: 10px;">
            <input type="checkbox" wire:click="toggleVoyageMode" {{ $isVoyageMode ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </li>
    <style>
        .custom-info-icon {
            color: #008080; /* Change the color to what you want */
            font-size: 80px; /* Adjust size */
        }

        .swal2-icon {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }
    </style>
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('voyage-mode-activated', function () {
                Swal.fire({
                    title: '<strong>Mode voyage activé</strong>',
                    html: "<span style='font-size: 16px;'>Vos annonces seront temporairement invisibles pour les autres utilisateurs. Elles réapparaîtront automatiquement lorsque vous désactiverez ce mode.</span>",
                    showCloseButton: true,
                    showConfirmButton: false,
                    iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',

                });
            });

            // When voyage mode is deactivated
            Livewire.on('voyage-mode-deactivated', function () {
                Swal.fire({
                    title: 'Vous avez quitté le Mode voyage',
                    text: 'Vos annonces sont de nouveau visibles.',
                    iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',
                    showConfirmButton: false,
                    showCloseButton: true,
                });
            });
        });
    </script>
</div>



