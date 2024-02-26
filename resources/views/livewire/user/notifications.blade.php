<div class="row">
    <div class="col-sm-3">
        <div class="card p2 card-notification-1">
            <div class="text-center">
                <h5>
                    <i class="bi bi-bell"></i>
                    Notifications
                </h5>
                <br>
                ({{ $notifications->count() }})
            </div>
        </div>
    </div>
    <div class="col-sm-8 ">
        <div class="card p-2">
            <table>
                @forelse ($notifications as $item)
                    <tr class="border-bottom">
                        <td>
                            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/notification-bell-5469639-4573720.png" height="40" width="40" alt="">
                        </td>
                        <td>
                            <h6>
                                {{ $item->titre }}
                            </h6>
                            <i>{{ $item->message }}</i>
                            <div style="text-align: right">
                                <span class="small">
                                    <i class="bi bi-app-indicator"></i>
                                    il y'a de cela
                                    {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <i class="bi bi-x-lg text-danger" wire:click = "delete({{ $item->id }})"></i>
                        </td>
                    </tr>
                @empty
                    <div class="pb-5 pt-5 text-center">
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/023/570/826/small/still-empty-no-notification-yet-concept-illustration-line-icon-design-eps10-modern-graphic-element-for-landing-page-empty-state-ui-infographic-vector.jpg" alt="">
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
