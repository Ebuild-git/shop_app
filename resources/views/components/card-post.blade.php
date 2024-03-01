@php
    $photo = json_decode($post->photos, true);
@endphp
<div class="{{ $class }} cursor-pointer" onclick="document.location.href='/post/{{ $post->id }}'">
    <div class="  p-1">
        <div class="home-post-cart">
            <img class="" alt="{{ $post->titre }}" src="{{ Storage::url($photo[0] ?? "") }}">
        </div>
        <div class="p-1">
            <span class="text-red">
                <strong>{{ $post->prix }}</strong>  DH
            </span>
            <h6 class="card-title" >
                {{ $post->titre }}
            </h6>
            <p class="card-text small text-muted">
                <b>
                    <i class="bi bi-geo-alt"></i>
                </b> : {{ $post->ville }}<br>
                <b>
                    <i class="bi bi-grid-1x2"></i>
                </b> : {{ $post->categorie_info->titre }},{{ $post->sous_categorie_info->titre }} <br>
                <i class="bi bi-calendar3"></i> : {{ $post->created_at }}
                
            </p>

        </div>

    </div>
</div>
