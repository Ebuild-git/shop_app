<span title="{{ $avis }} avis .">
    @if ($avis > 0)
        <!-- Étoiles notées -->
        @for ($i = 0; $i < $count; $i++)
            <i class="bi bi-star-fill" style="color: {{ $user && $user->id === auth()->id() ? '#fab005' : '#018d8d' }};"></i>
        @endfor
        <!-- Étoiles non notées -->
        @for ($i = $count; $i < 5; $i++)
            <i class="bi bi-star-fill" style="color: #828282;"></i>
        @endfor
    @else
        <!-- 5 étoiles grises si pas d'avis -->
        @for ($i = 0; $i < 5; $i++)
            <i class="bi bi-star-fill" style="color: #828282;"></i>
        @endfor
    @endif
</span>


