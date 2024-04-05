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
                        <img src="{{ Storage::url($post->photos[0] ?? '') }}" class="img-fluid"
                        alt="" />
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
                                     <div class="elis_rty">
                                         <span class="ft-bold color fs-lg mr-2">
                                             {{ $post->getPrix() }} DH
                                         </span>
                                     </div>
                                 </div>
                             </div>

                             <div class="prt_03 mb-3">
                                 <p>
                                     {{ $post->description }}
                                 </p>
                             </div>



                             <div class="prt_05 mb-4">
                                 <div class="form-row mb-7">
                                     <div class="col-12 col-lg">
                                         <!-- Submit -->
                                         @guest
                                             @livewire('User.ButtonAddPanier', ['id_post' => $post->id])
                                         @endguest
                                         @auth
                                             @if (Auth::user()->id != $post->id_user)
                                                 @livewire('User.ButtonAddPanier', ['id_post' => $post->id])
                                             @endif
                                         @endauth
                                     </div>
                                     <div class="col-12 col-lg-auto">
                                         <!-- Wishlist -->
                                         @livewire('User.ButtonAddLike', ['id_post' => $post->id])
                                     </div>
                                 </div>
                             </div>



                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- End Modal -->
