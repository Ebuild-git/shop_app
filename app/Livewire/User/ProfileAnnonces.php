<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileAnnonces extends Component
{

    use WithPagination;

    public  $user, $key, $order;
    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $postsQuery = posts::where("id_user", $this->user->id);


        // Filtrage par mot-clé
        if (strlen($this->key) > 0) {
            $postsQuery->where('titre', 'like', '%' . $this->key . "%")
                ->OrWhere('description', 'like', '%' . $this->key . "%");
        }

        // Filtrage par catégories
        if (strlen($this->order) > 0) {
            if ($this->order == "Asc") {
                $postsQuery->Orderby('titre', "Asc");
            } else {
                $postsQuery->Orderby('titre', "Desc");
            }
        }

        $posts = $postsQuery->get();

        return view('livewire.user.profile-annonces')->with("posts", $posts);
    }

    public function filtrer()
    {
        $this->resetPage();
    }
}
