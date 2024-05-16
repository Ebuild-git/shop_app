 <!-- Product View Modal -->
 <div class="modal fade lg-modal" id="quickview-{{ $id_post }}" tabindex="-1" role="dialog"
     aria-labelledby="quickviewmodal" aria-hidden="true">
     <div class="modal-dialog modal-xl login-pop-form" role="document">
         <div class="modal-content" id="quickviewmodal">
             <div class="modal-headers">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span class="ti-close"></span>
                 </button>
             </div>

             <div class="modal-body">
                 <div class="quick_view_wrap">

                     <div class="quick_view_thmb">
                         <img src="{{ Storage::url($post->photos[0] ?? '') }}" class="w-100" alt="" />
                     </div>

                     <div class="quick_view_capt">
                         <div class="prd_details">

                             <div class="prt_01 mb-1"><span class="text-light bg-info rounded px-2 py-1">
                                     {{ $post->sous_categorie_info->titre }}
                                 </span>
                             </div>
                             <div class="prt_02 mb-2">
                                 <h2 class="ft-bold mb-1">
                                     {{ $post->titre }}
                                 </h2>
                                 <div class="text-left">
                                     <div class="elis_rty mt-2">
                                         @if ($post->old_prix)
                                             <span class="ft-bold color fs-lg">
                                                 {{ $post->getPrix() }} DH
                                             </span>
                                             <br>
                                             <strike class="text-danger">
                                                 {{ $post->getOldPrix() }} DH
                                             </strike>
                                         @else
                                             <span class="ft-bold color fs-lg">
                                                 {{ $post->getPrix() }} DH
                                             </span>
                                         @endif
                                     </div>
                                 </div>
                             </div>

                             <div class="prt_03 mb-3">
                                 <p>
                                     {{ $post->description }}
                                 </p>
                             </div>
                             
                             <div class="prt_05 mb-4">
                                 <!-- Submit -->
                                 <button type="button" class="btn btn-block custom-height bg-dark mb-2 "
                                     onclick="add_cart({{ $post->id }})">
                                     <i class="lni lni-shopping-basket mr-2"></i>
                                     Ajouter au panier
                                 </button>
                                 <button
                                     class="btn btn-default btn-block btn-add-favoris"
                                     type="button" @guest data-toggle="modal" data-target="#login" @endguest
                                     data-id="{{ $post->id }}">
                                     <i class="lni lni-heart mr-2"></i>
                                     Ajouter aux favoris
                                 </button>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- End Modal -->
