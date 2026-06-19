<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Events\AdminEvent;
use App\Models\notifications;
use Illuminate\Validation\ValidationException;


class UpdateCordonnées extends Component
{
    use WithFileUploads;

    public $rib_number;
    public $bank_name;
    public $titulaire_name;
    public $cin_img;
    public $cin_img2;

    public $existingCinImg;
    public $existingCinImg2;


    public function mount()
    {
        $user = User::find(Auth::id());
        if ($user) {
            // $this->rib_number = $user->rib_number;
            try {
                $this->rib_number = $user->rib_number
                    ? Crypt::decryptString($user->rib_number)
                    : null;
            } catch (\Exception $e) {
                $this->rib_number = $user->rib_number;
            }
            $this->rib_number = substr(preg_replace('/[^0-9]/', '', $this->rib_number ?? ''), 0, 24);
            $this->bank_name = $user->bank_name;
            $this->titulaire_name = $user->titulaire_name;
            $this->existingCinImg = $user->cin_img;
            $this->existingCinImg2 = $user->cin_img2;
        }
    }

    public function render()
    {
        $user = User::find(Auth::id());
        $hasExistingInfo = $user && $user->rib_number && $user->bank_name && $user->titulaire_name;
        return view('livewire..user.update-cordonnées', compact('hasExistingInfo'));
    }

    public function update()
    {
        try {
            $this->validate([
                'rib_number'    => 'nullable|string|size:24',
                'bank_name'     => 'nullable|string',
                'titulaire_name' => 'nullable|string',
                'cin_img'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'cin_img2'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            ], [
                'rib_number.size'          => __('rib_number_size'),
                'bank_name.required'       => __('bank_name_required'),
                'titulaire_name.required'  => __('titulaire_name_required'),
                'cin_img.image'            => __('cin_img_image'),
                'cin_img2.image'           => __('cin_img_image'),
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('alert', ['message' => $firstError, 'type' => 'danger']);
            throw $e;
        }

        $user = User::find(Auth::id());

        if ($user) {
            $changes = false;
            $changedFields = [];

            // --- Banking fields ---
            if ($user->rib_number) {
                try {
                    $current_rib_number = Crypt::decryptString($user->rib_number);
                } catch (\Exception $e) {
                    $current_rib_number = null;
                }
            } else {
                $current_rib_number = null;
            }

            if ($current_rib_number !== $this->rib_number) {
                $user->rib_number = Crypt::encryptString($this->rib_number);
                $changes = true;
                $changedFields[] = 'RIB';
            }

            if ($user->bank_name !== $this->bank_name) {
                $user->bank_name = $this->bank_name;
                $changes = true;
                $changedFields[] = 'Banque';
            }

            if ($user->titulaire_name !== $this->titulaire_name) {
                $user->titulaire_name = $this->titulaire_name;
                $changes = true;
                $changedFields[] = 'Titulaire';
            }

            if (!empty($changedFields)) {
                event(new AdminEvent("Un utilisateur a mis à jour ses informations bancaires."));
                $notification = new notifications();
                $notification->type = "rib_updated";
                $notification->titre = Auth::user()->username . " a mis à jour ses informations bancaires.";
                $notification->url = "/admin/client/" . $user->id . "/view";
                $notification->message = "Champs modifiés : " . implode(', ', $changedFields) . ". En attente de vérification.";
                $notification->id_user = Auth::user()->id;
                $notification->destination = "admin";
                $notification->save();
            }

            // --- CIN images — single notification covering both ---
            $cinChanged = [];

            if ($this->cin_img) {
                $path = \App\Services\ImageService::uploadAndConvert($this->cin_img, 'cin_images');

                if ($user->cin_img) {
                    $oldCinImages = $user->old_cin_images;
                    if (is_string($oldCinImages)) {
                        $oldCinImages = json_decode($oldCinImages, true);
                    }
                    if (!is_array($oldCinImages)) {
                        $oldCinImages = [];
                    }
                    $oldCinImages[] = $user->cin_img;
                    $user->old_cin_images = $oldCinImages;
                }

                $user->cin_img = $path;
                $user->cin_approved = false;
                $changes = true;
                $cinChanged[] = 'Recto';
            }

            if ($this->cin_img2) {
                $path2 = \App\Services\ImageService::uploadAndConvert($this->cin_img2, 'cin_images');

                if ($user->cin_img2) {
                    $oldCinImages = $user->old_cin_images;
                    if (is_string($oldCinImages)) {
                        $oldCinImages = json_decode($oldCinImages, true);
                    }
                    if (!is_array($oldCinImages)) {
                        $oldCinImages = [];
                    }
                    $oldCinImages[] = $user->cin_img2;
                    $user->old_cin_images = $oldCinImages;
                }

                $user->cin_img2 = $path2;
                $user->cin_approved = false;
                $changes = true;
                $cinChanged[] = 'Verso';
            }

            if (!empty($cinChanged)) {
                $cinLabel = implode(' & ', $cinChanged);
                event(new AdminEvent('Un utilisateur a mis à jour sa carte d\'identité.'));
                $notification = new notifications();
                $notification->type = "cin_updated";
                $notification->titre = Auth::user()->username . " a mis à jour sa carte d'identité ({$cinLabel}).";
                $notification->url = "/admin/client/" . $user->id . "/view";
                $notification->message = "Carte d'identité ({$cinLabel}) en attente de validation.";
                $notification->id_user = Auth::user()->id;
                $notification->destination = "admin";
                $notification->save();
            }

            if ($changes) {
                $user->save();
                $this->existingCinImg = $user->cin_img;
                $this->existingCinImg2 = $user->cin_img2;
                $this->dispatch('cin-updated');
                $alertMessage = !empty($cinChanged)
                    ? __('info_updated') . '<br>' . __('cin_pending_warning')
                    : __('info_updated');
                $this->dispatch('alert', ['message' => $alertMessage, 'type' => 'info']);
                $this->dispatch('redirect-home2');
            } else {
                $this->dispatch('alert', ['message' => __('no_changes_made'), 'type' => 'info']);
            }

            $this->dispatch('refreshAlluser-information');
        } else {
            $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'warning']);
        }
    }

}
