<?php

namespace App\Livewire\User;

use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListMesPosts extends Component
{
    use WithPagination;

    public  $date, $etat,$filter,$titre,$statut;


    
    public function mount($titre,$filter,$statut){
            $this->titre = $titre;
            $this->filter=$filter;
            $this->statut=$statut;
            if(isset($statut)  && !empty($statut)){
                $this->etat=$statut;
            }
    }



    public function render()
    {
        $Query = posts::where("id_user", Auth::user()->id)->Orderby("id", "Desc");


        if (!empty($this->date)) {
            $Query->whereDate('Created_at', $this->date);
        }

        if (!empty($this->etat)) {
            switch ($this->etat) {
                case 'En modération':
                    $postsQuery = $Query->where('verified_at', null);
                    break;
                case 'Rejetée':
                    $postsQuery = $Query->where('reject_at', '!=', null);
                    break;
                case 'Supprimée':
                    $postsQuery = $Query->onlyTrashed();
                    break;
                case 'Vendue':
                    $postsQuery = $Query->where('sell_at', '!=', null);
                    break;
                case 'Active':
                    $postsQuery = $Query->where('sell_at', null);
                    break;
            }
        }
        $posts =  $Query
            ->paginate("30");
        return view('livewire.user.list-mes-posts', compact('posts'));
    }


    public function filtrer()
    {
        $this->resetPage();
    }


    public function delete($id)
    {
        //verifier que le poost existe et et l'oeuvre de luser connecter
        $post = Posts::findOrFail($id);
        if (!$post) {
            session()->flash('error', __('Oups! La publication n\'existe pas .'));
            return;
        }
        if (Auth::user()->id != $post->id_user) {
            session()->flash('error', __('Cette action est interdite ! Vous ne pouvez supprimer une publication qui ne vous appartient pas.'));
            return;
        }

        if ($post->sell_at != null) {
            session()->flash('error', __('Cette publication a été vendue !'));
            return;
        }

        // supprimer toutes les images du post dans le serveurs
        $photos = $post->photos;
        foreach ($photos as $photo) {
            Storage::delete('/public/img/' . $photo);
        }
        $post->delete();
        session()->flash('success', __('La publication a bien été supprimée !'));
    }
}
