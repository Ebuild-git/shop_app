<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Component;

class ListeUtilisateurs extends Component
{
    public $type, $list, $key, $statut,$etat;
    public $locked = "no";
    public $showTrashed = "no";
    public $verified = null;
    public $photo_verified = null;
    public $deletedBy = '';

    use WithPagination;

    public function mount($type, $locked = null, $showTrashed = null, $verified = null, $photo_verified = null)
    {
        if ($type == "shop") {
            $this->type = "shop";
        } else {
            $this->type = "user";
        }
        $this->locked = $locked;
        $this->showTrashed = $showTrashed ?? "no";
        $this->verified = $verified ?? null;
        $this->photo_verified = $photo_verified ?? null;
    }

    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }

    public function updatedDeletedBy($value)
    {
        $this->resetPage();
    }


    public function render()
    {
        $users = User::where("type", $this->type)->where("role", "!=", "admin");

        if ($this->showTrashed === 'yes') {
            $users = $users->onlyTrashed()->orderBy('deleted_at', 'desc');

            if ($this->deletedBy === 'shopin') {
                $users->whereNotNull('email')
                    ->whereNotNull('username');
            } elseif ($this->deletedBy === 'self') {
                $users->whereNotNull('email_deleted')
                    ->whereNotNull('username_deleted');
            }
        } else {
            // Only apply locked filter for non-trashed users
            if ($this->locked === 'yes') {
                $users->where('locked', true)->orderBy("locked_at", "desc");
            } else {
                $users->where('locked', false)
                ->orderBy("id", "desc");
            }
        }

        if ($this->verified === 'no') {
            $users->where('cin_approved', false)
                ->whereNotNull('cin_img');
        }

        if ($this->photo_verified === 'no') {
            $users->whereNull('photo_verified_at')
                ->whereNotNull('avatar');
        }

        // if ($this->locked === 'yes') {
        //     $users->where('locked', true);
        // }else {
        //     $users->where('locked', false);
        // }

        if (!empty($this->key)) {
            $users = $users->where(function ($query) {
                $query->where('lastname', 'like', '%' . $this->key . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->key . '%')
                    ->orWhere('firstname', 'like', '%' . $this->key . '%')
                    ->orWhere('username', 'like', '%' . $this->key . '%')
                    ->orWhere('rue', 'like', '%' . $this->key . '%')
                    ->orWhere('nom_batiment', 'like', '%' . $this->key . '%')
                    ->orWhere('etage', 'like', '%' . $this->key . '%')
                    ->orWhere('num_appartement', 'like', '%' . $this->key . '%')
                    ->orWhere('email', 'like', '%' . $this->key . '%');

                    if (is_numeric($this->key)) {
                        $query->orWhere('id', $this->key)
                              ->orWhere('id', $this->key - 1000);
                    } elseif (preg_match('/^U\d+$/i', $this->key)) {
                        $rawId = (int)substr($this->key, 1) - 1000;
                        $query->orWhere('id', $rawId);
                    }


            });
            $users = $users->orWhereHas('region_info', function ($query) {
                $query->where('nom', 'like', '%' . $this->key . '%'); // Adjust 'name' to the actual column name
            });
        }

        // if (strlen($this->etat) > 0) {
        //     $users->where('locked', $this->etat);
        // }

        // if ($this->statut !='') {
        //     if ($this->statut == 1) {
        //         $users->where("email_verified_at", "!=", null);
        //     } else {
        //         $users->where("email_verified_at", null);
        //     }
        // }
        if ($this->statut != '') {
            if ($this->statut == 1) {
                $users->whereNotNull('email_verified_at')
                    ->where('cin_approved', true);
            } else {
                $users->where(function($q) {
                    $q->whereNull('email_verified_at')
                    ->orWhere('cin_approved', false);
                });
            }
        }
        $users = $users->paginate(50);
        $venduCountPerUser = [];

        foreach ($users as $user) {
            $venduCountPerUser[$user->id] = $user->GetPosts()->where('statut', 'vendu')->count();
        }
        return view('livewire.liste-utilisateurs',  compact('users', 'venduCountPerUser'));
    }


    public function filtre()
    {
        $this->resetPage();
    }


    public function confirmDelete($id)
    {
        $this->dispatch('show-delete-confirmation', ['userId' => $id]);
    }
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            session()->flash('message', 'Utilisateur supprimé avec succès !');
        } catch (\Throwable $th) {
            session()->flash('error', 'Impossible de supprimer cet utilisateur !');
        }
    }


    public function toggleLock($id)
    {
        $user = User::findOrFail($id);

        if ($user->locked == false) {
            $user->update([
                'locked' => true,
                'locked_at' => now()
            ]);
        } else {
            $user->update([
                'locked' => false,
                'locked_at' => null
            ]);
        }
    }

    public function restore($id)
    {
        User::withTrashed()->where('id', $id)->restore();
        $this->dispatch('swal:success', [
            'title' => 'Restauré !',
            'text' => 'Utilisateur restauré avec succès.',
            'icon' => 'success'
        ]);
    }

    public function forceDelete($id)
    {
        User::withTrashed()->where('id', $id)->forceDelete();
        $this->dispatch('swal:success', [
            'title' => 'Supprimé !',
            'text' => 'Utilisateur supprimé définitivement.',
            'icon' => 'success'
        ]);
    }

    public function approveCin($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->cin_approved = true;
            $user->save();

            event(new \App\Events\UserEvent($user->id));

            $userLocale = $user->locale ?? config('app.locale');
            \App::setLocale($userLocale);

            $notification = new \App\Models\notifications();
            $notification->titre = __('cin_notification_title');
            $notification->id_user_destination = $user->id;
            $notification->type = "cin_approved";
            $notification->destination = "user";
            $notification->message = __('cin_notification_message');
            $notification->save();

            $fcmService = app(\App\Services\FcmService::class);
            $fcmService->sendToUser(
                $user->id,
                "Your national identity card has been approved!",
                "We inform you that your national identity card has been approved by the administrators.",
                [
                    'type' => 'alerte',
                    'notification_id' => $notification->id,
                    'destination' => 'user',
                    'action' => 'cin_approved',
                ]
            );

            \App::setLocale(config('app.locale'));

            $this->dispatch('swal:success', [
                'title' => 'Approuvé !',
                'text' => 'La carte d\'identité a été approuvée.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('approveCin error: ' . $e->getMessage());
            $this->dispatch('swal:success', [
                'title' => 'Erreur',
                'text' => 'Une erreur est survenue.',
                'icon' => 'error'
            ]);
        }
    }

    public function rejectCin($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->cin_approved = false;
            $user->cin_img = null;
            $user->save();

            event(new \App\Events\UserEvent($user->id));

            $userLocale = $user->locale ?? config('app.locale');
            \App::setLocale($userLocale);

            $notification = new \App\Models\notifications();
            $notification->titre = __('cin_reject_notification_title');
            $notification->id_user_destination = $user->id;
            $notification->type = "cin_rejected";
            $notification->destination = "user";
            $notification->message = __('cin_reject_notification_message');
            $notification->save();

            $fcmService = app(\App\Services\FcmService::class);
            $fcmService->sendToUser(
                $user->id,
                "Your national identity card has been rejected.",
                "We inform you that your national identity card was not accepted by the administrators. Please upload a clear and valid photo.",
                [
                    'type' => 'alerte',
                    'notification_id' => $notification->id,
                    'destination' => 'user',
                    'action' => 'cin_rejected',
                ]
            );

            \App::setLocale(config('app.locale'));

            $this->dispatch('swal:success', [
                'title' => 'Rejeté !',
                'text' => 'La carte d\'identité a été rejetée.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('rejectCin error: ' . $e->getMessage());
            $this->dispatch('swal:success', [
                'title' => 'Erreur',
                'text' => 'Une erreur est survenue.',
                'icon' => 'error'
            ]);
        }
    }



}
