@extends('User.fixe')
@section('titre', 'Mes notifications')
@section('content')
@section('body')
    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="gray py-3">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> Notifications </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-12">
                <div class="card p-2">
                    @if ($notifications->count() > 0)
                        <div class="text-end">
                            <button class="btn btn-sm btn-danger" onclick="delete_all_notification()">
                                <i class="bi bi-x-lg " ></i>
                                Tout supprimer
                            </button>
                        </div>
                    @endif
                    <table>
                        @forelse ($notifications as $item)
                            <tr class="border-bottom" id="tr-{{$item->id }}">
                                <td style="width: 60px;" class="my-auto">
                                    <img width="45" height="45" src="/icons/alarm--v1.png" alt="alarm--v1"/>
                                </td>
                                <td>
                                    <h6>
                                        <b>
                                            @if ($item->url != null)
                                                <a href="{{ $item->url }}" class="link">
                                                @else
                                                    <a href="#" class="link">
                                            @endif
                                            {{ $item->titre }}
                                            </a>
                                        </b>
                                    </h6>
                                    <i>{!! $item->message !!}</i>
                                    <div style="text-align: right">
                                        <span class="small">
                                           <span class="text-danger cusor" onclick="delete_notification({{ $item->id }})">
                                            <i class="bi bi-x-lg text-danger" ></i> Supprimer
                                           </span>
                                            <i class="bi bi-app-indicator"></i>
                                            il y'a de cela
                                            {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                        @empty
                            <div class="pb-5 pt-5 text-center">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/023/570/826/small/still-empty-no-notification-yet-concept-illustration-line-icon-design-eps10-modern-graphic-element-for-landing-page-empty-state-ui-infographic-vector.jpg"
                                    alt="">
                                <h6 class="text-center">No Notifications</h6>
                                <span class="text-muted">
                                    <i> vous n'avez pas de notification actuellement.</i>
                                </span>
                            </div>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
