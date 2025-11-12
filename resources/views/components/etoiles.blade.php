<span title="{{ $avis }} avis .">
    @if ($avis > 0)
        @for ($i = 0; $i < $count; $i++)
            <i class="bi bi-star-fill" style="color: #fab005;"></i>
        @endfor
        @for ($i = $count; $i < 5; $i++)
            <i class="bi bi-star-fill" style="color: #828282;"></i>
        @endfor
    @else
        @for ($i = 0; $i < 5; $i++)
            <i class="bi bi-star-fill" style="color: #828282;"></i>
        @endfor
    @endif
</span>


