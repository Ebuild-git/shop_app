 <div class="card" wire:submit="update">
     <form>
         <div class="row p-2">
             <div class="col-sm-4 my-auto">
                 <h5 class="card-header">
                     Configuration des informations du site.
                 </h5>
             </div>
             <div class="col-sm-8 my-auto">

             </div>
         </div>
         <div class="row p-3">
             <div class="col-sm-8">
                 <h5>Contacts direct</h5>
                 <hr>
                 <div class="row">
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Numéro de téléphone</label>
                             <input type="tel" wire:model="telephone" class="form-control">
                             @error('telephone')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Adresse E-mail</label>
                             <input type="email" wire:model="email" class="form-control">
                             @error('email')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                 </div>
                 <div class="form-group">
                     <label for="">Adresse de localisation</label>
                     <input type="text" wire:model="adresse" class="form-control">
                     @error('adresse')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
                 <h5>Réseau sociaux</h5>
                 <hr>
                 <div class="row">
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Url facebook</label>
                             <input type="url" wire:model="facebook" class="form-control">
                             @error('facebook')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Url tiktok</label>
                             <input type="url" wire:model="tiktok" class="form-control">
                             @error('tiktok')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Url instagram</label>
                             <input type="url" wire:model="instagram" class="form-control">
                             @error('instagram')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label for="">Url linkedin</label>
                             <input type="url" wire:model="linkedin" class="form-control">
                             @error('linkedin')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                 </div>



             </div>
             <div class="col-sm-4">
                 <h5>Configuration</h5>
                 <hr>
                 <div class="form-group mb-3">
                    <input type="checkbox" class="form-check-input" id="" wire:model='valider_photo' @checked($valider_photo)>
                    Valider les photos de profils des utilisateurs a chaque changement.
                    @error('valider_photo')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <input type="checkbox" class="form-check-input" id="" wire:model='valider_publication' @checked($valider_publication)>
                    Valider toutes les nouvelles publications des utilisateurs.
                    @error('valider_publication')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
         </div>


         <div class="modal-footer">
            @include('components.alert-livewire')
             <button type="submit" class="btn btn-primary">
                 <span wire:loading>
                    <x-loading></x-loading>
                 </span>
                 <i class="bi bi-plus-circle"></i> &nbsp;
                 Enregistrer les modifications
             </button>
 </div>
</form>
</div>
