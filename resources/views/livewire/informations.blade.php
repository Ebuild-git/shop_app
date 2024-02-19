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
             <div class="col-sm-6">
                 <h5>Contacts direct</h5>
                 <hr>
                 <div class="form-group">
                     <label for="">Numéro de téléphone</label>
                     <input type="tel" wire:model="telephone" class="form-control">
                     @error('telephone')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
                 <div class="form-group">
                     <label for="">Adresse E-mail</label>
                     <input type="email" wire:model="email" class="form-control">
                     @error('email')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
                 <br>
                 <br>
                 <h5>Logo</h5>
                 <hr>
                 <div class="row">
                     <div class="col-sm-8">
                         <div class="form-group">
                             <label for="">fichier image</label>
                             <input type="file" wire:model="logo" class="form-control">
                             @error('logo')
                                 <div class="text-danger">
                                     {{ $message }}
                                 </div>
                             @enderror
                         </div>
                     </div>
                     <div class="col-sm-4">
                        @if ($logo2)
                            <img src="{{ Storage::url($logo2) }}" alt="logo" style="max-width: 90%">
                        @endif
                     </div>
                 </div>
             </div>
             <div class="col-sm-6">
                 <h5>Réseau sociaux</h5>
                 <hr>
                 <div class="form-group">
                     <label for="">Url facebook</label>
                     <input type="url" wire:model="facebook" class="form-control">
                     @error('facebook')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
                 <div class="form-group">
                     <label for="">Url tiktok</label>
                     <input type="url" wire:model="tiktok" class="form-control">
                     @error('tiktok')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
                 <div class="form-group">
                     <label for="">Url instagram</label>
                     <input type="url" wire:model="instagram" class="form-control">
                     @error('instagram')
                         <div class="text-danger">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>
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

         <div class="modal-footer">
             @if (session()->has('error'))
                 <span class="text-danger small">
                     {{ session('error') }}
                 </span><br>
             @enderror
             @if (session()->has('success'))
                 <span class="text-success small">
                     {{ session('success') }}
                 </span><br>
             @enderror
             <button type="submit" class="btn btn-primary">
                 <x-loading></x-loading>
                 Enregistrer les modifications
             </button>
 </div>
</form>
</div>
