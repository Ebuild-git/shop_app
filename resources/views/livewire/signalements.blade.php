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
                     <th></th>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($posts as $post)
                     <tr>
                         <td class="avatar">
                             <div class="avatar me-3">
                                 @if ($post->user_info->avatar != '')
                                     <img src="{{ Storage::url($post->user_info->avatar) }}" alt="..."
                                         class="rounded-circle">
                                 @else
                                     <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                         alt="" class="rounded-circle">
                                 @endif
                             </div>
                         </td>
                         <td>
                             {{ $post->titre }}
                             <div class="small">
                                <i class="bi bi-heart-fill text-danger"></i> {{ $post->getLike->count() }} likes
                             </div>
                         </td>
                         <td>
                            <a href="/admin/client/{{  $post->user_info->id }}/view">
                                {{  $post->user_info->username }}
                            </a>
                         </td>
                         <td>
                             <b class="text-danger">
                                 <i class="bi bi-exclamation-octagon"></i>
                                 {{ $post->signalements_count }}
                             </b>
                         </td>
                         <td>
                             <a href="/admin/publication/{{ $post->id }}/view">
                                 <button class="btn btn-sm btn-primary">
                                     Voir la publication
                                 </button>
                             </a>
                         </td>
                         <td>
                             <a href="/admin/post/{{ $post->id }}/signalement">
                                 <button class="btn btn-sm btn-danger">
                                     Voir les sginalements
                                 </button>
                             </a>
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
     </div>
     <!--/ Ajax Sourced Server-side -->
