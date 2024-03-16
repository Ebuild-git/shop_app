<span>
    @if (is_null(Auth::user()->photo_verified_at))
        <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg" class="rounded-circle avatar-user-head">
    @else
        <img src="{{ Storage::url(Auth::user()->avatar) }}" class="rounded-circle avatar-user-head">
    @endif
</span>