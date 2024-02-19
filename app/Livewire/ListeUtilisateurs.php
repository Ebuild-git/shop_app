<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Component;

class ListeUtilisateurs extends Component
{
    public $type, $list, $key, $statut;
    use WithPagination;


    public function mount($type)
    {
        if ($type == "shop") {
            $this->type = "shop";
        } else {
            $this->type = "user";
        }
    }

    public function render()
    {
        $users = User::orderBy("id", "desc")->where("type", $this->type)->where("role", "!=", "admin");

        if (!empty($this->key)) {
            $users = $users->where(function ($query) {
                $query->where('name', 'like', '%' . $this->key . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->key . '%')
                    ->orWhere('email', 'like', '%' . $this->key . '%');
            });
        }

        if ($this->statut != '') {
            if ($this->statut == 1) {
                $users->where("email_verified_at", "!=", null);
            } else {
                $users->where("email_verified_at", null);
            }
        }
        $users = $users->paginate(50);

        return view('livewire.liste-utilisateurs',  compact('users'));
    }


    public function filtre()
    {
        $this->resetPage();
    }


    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            session()->flash('message', 'Utilisateur supprimé avec succès !');
        } catch (\Throwable $th) {
            //throw $th;
            session()->flash('error', 'Impossible de supprimer cet utilisateur !');
        }
    }
}
