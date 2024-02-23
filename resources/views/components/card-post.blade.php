@php
    $photo = json_decode($post->photos, true);
@endphp
<div class="col-sm-{{$col}} col-12 col-md-3 col-lg-4 col-xl-3 mb-3 ">
    <div class="card card-shadow border-color">
        <div class="home-post-cart">
            <img class="" alt="{{ $post->titre }}" src="{{ Storage::url($photo[1] ?? "") }}">
        </div>
        <div class="card-body">
            <span class="text-red">
                <strong>{{ $post->prix }}</strong> Dt
            </span>
            <h6 class="card-title" onclick="document.location.href='post/{{ $post->id }}'">
                {{ $post->titre }}
            </h6>
            <p class="card-text small text-muted">
                <b>
                    <i class="bi bi-geo-alt"></i>
                </b> : {{ $post->ville }}<br>
                <b>
                    <i class="bi bi-grid-1x2"></i>
                </b> : {{ $post->categorie_info->titre }},
                {{ $post->created_at }}
            </p>

        </div>

    </div>
</div>
