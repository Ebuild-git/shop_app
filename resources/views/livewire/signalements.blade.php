 <div class="card">
     <div class="row p-2">
         <div class="col-sm-6 my-auto">
             <h5 class="card-header">
                 Publications signalées
             </h5>
         </div>
         <div class="col-sm-6 my-auto">
             <form wire:submit="filtre">
                 <div class="input-group mb-3">
                     <input type="date" class="form-control" wire:model="date" placeholder="titre">
                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <span wire:loading>
                             <x-loading></x-loading>
                         </span>
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>
                 </div>
             </form>
         </div>
     </div>
     <div class="card-datatable text-nowrap">
         <table class="datatables-ajax table">
             <thead class="table-dark">
                 <tr>
                     <th></th>
                     <th>Titre</th>
                     <th>Auteur</th>
                     <th>Signalements</th>
                     <th></th>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($posts as $post)
                     <tr>
                         <td class="avatar">
                             <div class="avatar me-3">
                                 <img src="{{ $post->user_info->getAvatar() }}" alt="..." class="rounded-circle">
                             </div>
                         </td>
                         <td>
                             {{ $post->titre }}
                             <div class="small">
                                 <i class="bi bi-heart-fill text-danger"></i> {{ $post->getLike->count() }} likes
                             </div>
                         </td>
                         <td>
                             <a href="/admin/client/{{ $post->user_info->id }}/view">
                                 {{ $post->user_info->username }}
                             </a>
                         </td>
                         <td>
                             <b class="text-danger">
                                 <i class="bi bi-exclamation-octagon"></i>
                                 {{ $post->signalements_count }}
                             </b>
                         </td>
                         <td style="text-align: right;">
                             <a href="/admin/publication/{{ $post->id }}/view">
                                 <button class="btn btn-sm btn-primary">
                                     Voir la publication
                                 </button>
                             </a>
                             <a href="/admin/post/{{ $post->id }}/signalement">
                                 <button class="btn btn-sm btn-dark">
                                     Voir les sginalements
                                 </button>
                             </a>
                             <button class="btn btn-sm btn-danger" onclick="toggle_confirmation({{ $post->id }})">
                                 <i class="bi bi-trash3"></i>
                             </button>
                             <button class="btn btn-sm btn-success d-none" id="confirmBtn{{ $post->id }}" wire:click="delete({{ $post->id }})">
                                 <i class="bi bi-check-circle"></i>
                                 &nbsp;
                                 Confirmer
                             </button>
                         </td>

                     </tr>
                 @empty
                     <tr>
                         <td colspan="5">
                             <div class="p-3">
                                 Aucun publication trouvé!
                             </div>
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
         <div class="p-3" {{ $posts->links('pagination::bootstrap-4') }} </div>
         </div>




         <script>
             function toggle_confirmation(productId) {
                 const confirmBtn = document.getElementById('confirmBtn' + productId);
                 if (!confirmBtn.classList.contains('d-none')) {
                     confirmBtn.classList.add('d-none');
                 } else {
                     // Masquer tous les autres boutons de confirmation s'ils sont visibles
                     document.querySelectorAll('.confirm-btn').forEach(btn => {
                         if (!btn.classList.contains('d-none')) {
                             btn.classList.add('d-none');
                         }
                     });
                     confirmBtn.classList.remove('d-none');
                 }
             }
         </script>


     </div>
     <!--/ Ajax Sourced Server-side -->
