<?php

namespace App\Livewire\User;

use App\Models\History_change_price;
use App\Models\posts;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdatePrix extends Component
{
    public $post, $prix, $old_price;
    public $postId;

    protected $listeners = ['setPostId'];

    public function setPostId($id)
    {
        $post = posts::where('id', $id)->where('id_user', Auth::user()->id)->first();
        if ($post) {
            $this->old_price = $post->prix;
            $this->post = $post;
        }
    }

    public function render()
    {
        return view('livewire.user.update-prix');
    }

    public function form_update_prix()
    {
        //vlidation
        $this->validate([
            'prix' => 'required|numeric|min:1',
        ], [
            'prix.required' => 'Le prix est obligatoire',
            'prix.numeric' => 'Le prix doit être un nombre',
            'prix.min' => 'Le prix doit être supérieur a 1 DH',
        ]);

        $oneWeekAgo = Carbon::now()->subWeek();
        $post = posts::find($this->post->id);
        if ($post) {
            $old_price = $post->prix;

            // Rechercher le dernier changement de prix
            $lastChange = History_change_price::where('id_post', $post->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastChange && $lastChange->created_at >= $oneWeekAgo) {
                // Calculer le temps restant avant de pouvoir effectuer un nouveau changement
                $nextChangeAllowed = $lastChange->created_at->addWeek();
                $now = Carbon::now();
                $remainingTime = $now->diffForHumans($nextChangeAllowed, [
                    'parts' => 3, // Afficher les 3 parties les plus significatives (ex: 6 days 23 hours 59 minutes)
                    'short' => true, // Utiliser le format court
                ]);

                // Flash message pour informer du temps restant
                session()->flash('warning', "Vous avez déjà fait un changement de prix dans les 7 derniers jours. Vous pourrez effectuer un nouveau changement dans $remainingTime.");
            } else {
                // Aucun changement de prix n'a été effectué dans les 7 derniers jours
                $post->old_prix = $post->prix;
                $post->prix = $this->prix;
                $post->updated_price_at = now();
                $post->save();


                //enregistrer le changement
                $history = new History_change_price();
                $history->id_post = $post->id;
                $history->old_price = $old_price;
                $history->new_price = $this->prix;
                $history->save();


                // Message de succès flash
                session()->flash('success', 'Le prix a été mis à jour avec succès');
                //$this->dispatch('update-price');
            }
        }
    }
}
