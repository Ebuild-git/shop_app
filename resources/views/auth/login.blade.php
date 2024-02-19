<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

   @livewire('LoginAdminForm')
</x-guest-layout>
