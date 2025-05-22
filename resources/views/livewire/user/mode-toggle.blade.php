<div>
    <li class="mode-toggle">
        <span class="medium text-medium">{{ __('Mode voyage') }}</span>
        <label class="switch" style="margin-right: 5px; margin-top: 10px;">
            <input type="checkbox" wire:click="toggleVoyageMode" {{ $isVoyageMode ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </li>
    <script>
        window.translations = {
        voyage_activated_title: @json(__('voyage.activated.title')),
        voyage_activated_description: @json(__('voyage.activated.description')),
        voyage_deactivated_title: @json(__('voyage.deactivated.title')),
        voyage_deactivated_description: @json(__('voyage.deactivated.description'))
        };
    </script>
</div>



