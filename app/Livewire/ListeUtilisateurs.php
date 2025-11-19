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
    public $deletedBy = '';

    use WithPagination;

    public function mount($type, $locked = null, $showTrashed = null)
    {
        if ($type == "shop") {
            $this->type = "shop";
        } else {
            $this->type = "user";
        }
        $this->locked = $locked;
        $this->showTrashed = $showTrashed ?? "no";
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
        $users = User::orderBy("id", "desc")->where("type", $this->type)->where("role", "!=", "admin");

        if ($this->showTrashed === 'yes') {
            $users = $users->onlyTrashed();

            if ($this->deletedBy === 'shopin') {
                $users->whereNotNull('email')
                    ->whereNotNull('username');
            } elseif ($this->deletedBy === 'self') {
                $users->whereNotNull('email_deleted')
                    ->whereNotNull('username_deleted');
            }
        }

        if ($this->locked === 'yes') {
            $users->where('locked', true);
        }else {
            $users->where('locked', false);
        }

        if (!empty($this->key)) {
            $users = $users->where(function ($query) {
                $query->where('lastname', 'like', '%' . $this->key . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->key . '%')
                    ->orWhere('firstname', 'like', '%' . $this->key . '%')
                    ->orWhere('username', 'like', '%' . $this->key . '%')
                    ->orWhere('address', 'like', '%' . $this->key . '%')
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

        if ($this->statut != '') {
            if ($this->statut == 1) {
                $users->where("email_verified_at", "!=", null);
            } else {
                $users->where("email_verified_at", null);
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
            $user->update(['locked' => true]);
        } else {
            $user->update(['locked' => false]);
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



}
