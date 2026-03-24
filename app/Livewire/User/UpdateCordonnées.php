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

    public $existingCinImg;

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
                'rib_number' => 'required|string|size:24',
                'bank_name' => 'required|string',
                'titulaire_name' => 'required|string',
                'cin_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            ], [
                'rib_number.required' => __('rib_number_required'),
                'rib_number.min' => __('rib_number_min'),
                'rib_number.size' => __('rib_number_size'),
                'rib_number.max' => __('rib_number_max'),
                'bank_name.required' => __('bank_name_required'),
                'titulaire_name.required' => __('titulaire_name_required'),
                'cin_img.image' => __('cin_img_image'),
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('alert', ['message' => $firstError, 'type' => 'danger']);
            throw $e; // re-throw so Livewire still renders inline errors
        }

        $user = User::find(Auth::id());
        if ($user) {
            $changes = false;

            // if ($user->rib_number) {
            //     $current_rib_number = $user->rib_number;
            // } else {
            //     $current_rib_number = null;
            // }
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

                event(new AdminEvent("Un utilisateur a mis à jour son RIB."));
                $notification = new notifications();
                $notification->type = "rib";
                $notification->titre = Auth::user()->username . " a mis à jour son RIB.";
                $notification->url = "/admin/client/" . $user->id . "/view";
                $notification->message = "RIB en attente de vérification.";
                $notification->id_user = Auth::user()->id;
                $notification->destination = "admin";
                $notification->save();
            }

            if ($user->bank_name !== $this->bank_name) {
                $user->bank_name = $this->bank_name;
                $changes = true;
            }
            if ($user->titulaire_name !== $this->titulaire_name) {
                $user->titulaire_name = $this->titulaire_name;
                $changes = true;
            }

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

                event(new AdminEvent('Un utilisateur a mis à jour sa carte d\'identité.'));
                $notification = new notifications();
                $notification->type = "photo";
                $notification->titre = Auth::user()->username . " a mis à jour sa carte d'identité.";
                $notification->url = "/admin/client/" . $user->id . "/view";
                $notification->message = "Carte d'identité en attente de validation.";
                $notification->id_user = Auth::user()->id;
                $notification->destination = "admin";
                $notification->save();

            }

            if ($changes) {
                $user->save();
                $this->existingCinImg = $user->cin_img;
                $this->dispatch('cin-updated');
                $this->dispatch('alert', ['message' => __('info_updated'), 'type' => 'info']);
            } else {
                $this->dispatch('alert', ['message' => __('no_changes_made'), 'type' => 'info']);
            }

            $this->dispatch('refreshAlluser-information');
        } else {
            $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'warning']);
        }
    }

}
