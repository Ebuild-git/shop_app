


<div id="table-wrapper" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    <div id="table-scroll">
        <table class="table">
            <thead class="tb-head">
                <tr>
                    <th scope="col" style="width: 51px;"></th>
                    <th scope="col">{{ __('article') }}</th>
                    <th scope="col">{{ __('discount_price') }}</th>
                    <th scope="col">{{ __('base_price') }}</th>
                    <th scope="col">{{ __('last_price_update') }}</th>
                    @if($showRemainingTimeColumn)
                        <th scope="col">{{ __('remaining_time_to_update') }}</th>
                    @endif
                    <th scope="col">{{ __('ad_status') }}</th>
                    <th scope="col">{{ __('deletion_reason') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $item)
                <tr id="tr-post-{{ $item->id }}">
                    <th scope="row">
                        <div class="avatar-small-product">
                            <img src="{{ $item->FirstImage() }}" alt="avatar">
                        </div>
                    </th>
                    <td>
                        <b>
                            <a href="/post/{{ $item->id }}" class="link h6">
                                {{ Str::limit($item->titre, 20) }}
                            </a>
                        </b>
                        <br>
                        <span class="small">
                            <i>{{ __('Publié le') }} :</i>
                            <br>
                            <i>{{ $item->created_at->format('d-m-Y') . ' ' . __('at') . ' ' . $item->created_at->format('H:i') }}
                            </i>
                        </span>


                    </td>
                    <td class="strong" style="color: {{ $item->getOldPrix() && $item->getOldPrix() > $item->getPrix() ? '#008080' : '' }}; font-size: 12px;">
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            {{ $item->getPrix() }} <sup>{{ __('currency') }}</sup>
                        @endif
                    </td>
                    <td class="strong" style="color: {{ $item->getOldPrix() && $item->getOldPrix() > $item->getPrix() ? '#808080' : '#008080' }}; font-size: 12px;">
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            {{ $item->getOldPrix() }} <sup>{{ __('currency') }}</sup>
                        @else
                            {{ $item->getPrix() }} <sup>{{ __('currency') }}</sup>
                        @endif
                    </td>
                    <td>
                        <span class="small">
                            {{ $item->updated_price_at
                                ? \Carbon\Carbon::parse($item->updated_price_at)->format('d-m-Y') . ' ' . __('at') . ' ' . \Carbon\Carbon::parse($item->updated_price_at)->format('H:i')
                                : '-' }}
                        </span>
                    </td>
                    @if($showRemainingTimeColumn)
                    <td>
                        <span class="small">
                            {{ $item->next_time_to_edit_price() }}
                        </span>
                    </td>
                    @endif
                    @php
                        $isUserDeleted = $item->user_info && $item->user_info->deleted_at;
                        $hasDeletedOrder = $item->hasDeletedOrders();
                    @endphp
                    <td class="text-capitalize my-auto">
                        @if (!$item->motif_suppression)
                        <x-AnnonceStatut :statut="$item->statut" :sellAt="$item->sell_at" :verifiedAt="$item->verified_at" :voyageMode="$item->user_info->voyage_mode"></x-AnnonceStatut>

                        @if ($item->sell_at)
                            <div class="small">
                                {{ \Carbon\Carbon::parse($item->sell_at)->format('d-m-Y') . ' ' . __('at') . ' ' . \Carbon\Carbon::parse($item->sell_at)->format('H:i') }}
                            </div>
                        @endif
                            @if ($isUserDeleted || $hasDeletedOrder)
                            <span class="badge bg-danger">
                                {{ __('commande annulée') }}
                            </span>
                            @endif
                        @else
                            <span class="badge" style="background-color:#ce0000; ">
                                {{ __('deleted_by_shopin') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($item->motif_suppression)
                        {{ \App\Traits\TranslateTrait::TranslateText($item->motif_suppression) }}
                        @else
                        -
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @if (!$item->id_user_buy && $item->statut !== 'validation')
                            <button class="btn btn-sm  bg-red" onclick="Update_post_price({{ $item->id }})">
                                <i class="bi bi-graph-down-arrow"></i>
                                {{ __('Réduire le prix') }}
                            </button> &nbsp;
                            @endif
                            @if ($item->statut == 'validation' || $item->statut == 'vente')
                            <button class="btn btn-sm btn-danger" type="button"
                                onclick="delete_my_post({{ $item->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <th colspan="9">
                        <div class="alert alert-info text-center">
                            <img width="100" height="100" src="https://img.icons8.com/ios/100/008080/empty-box.png"
                                alt="empty-box" />
                            <br>
                            {{ __('no_items_found') }}
                        </div>
                    </th>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="sticky-pagination-container">
    <div class="modern-pagination">
        <span class="page-indicator">Page {{ $currentPage }} of {{ $lastPage }}</span>
        <button class="page-button" {{ $currentPage == 1 ? 'disabled' : '' }} onclick="location.href='{{ $previousPageUrl }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-button" {{ $currentPage == $lastPage ? 'disabled' : '' }} onclick="location.href='{{ $nextPageUrl }}'">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<script>
    const deletePostTranslation = {
        title: @json(__('delete_post_title')),
        text: @json(__('delete_post_text')),
        confirm: @json(__('delete_post_confirm')),
        cancel: @json(__('delete_post_cancel')),
        success_title: @json(__('delete_post_success_title')),
        success_text: @json(__('delete_post_success_text')),
        error_title: @json(__('delete_post_error_title')),
        error_text: @json(__('delete_post_error_text')),
    };
</script>
