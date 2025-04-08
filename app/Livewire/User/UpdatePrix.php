<?php

namespace App\Livewire\User;

use App\Models\History_change_price;
use App\Models\posts;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdatePrix extends Component
{
    public $post, $prix, $old_price,$titre;
    public $postId;
    public $show = true;
    public $changed = false;
    public $can_change = false;
    public $loading = true;

    protected $listeners = ['setPostId'];

    public function setPostId($id)
    {
        $this->reset();
        $post = posts::where('id', $id)
        ->select("id","prix","old_prix","titre","id_sous_categorie","updated_price_at")
        ->where('id_user', Auth::user()->id)
        ->first();
        if ($post) {
            $this->old_price = $post->prix;
            $this->titre = $post->titre;
            $this->post = $post;
            $this->show = true;
            $this->can_change = $post->next_time_to_edit_price();
            $this->loading = false;
        }else{
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.user.update-prix');
    }

    public function form_update_prix()
    {
        // Validation
        $this->validate([
            'prix' => 'required|numeric|min:1',
        ], [
            'prix.required' => 'Le prix est obligatoire',
            'prix.numeric' => 'Le prix doit être un nombre',
            'prix.min' => 'Le prix doit être supérieur à 1 DH',
        ]);

        $oneWeekAgo = Carbon::now()->subWeek();
        $post = posts::find($this->post->id);
        $now = Carbon::now();

        if ($post) {
            $old_price = $post->prix;

            if ($this->prix == $old_price) {
                $this->addError('prix', 'Le nouveau prix doit être différent du prix actuel <br> Veuillez réduire le prix.');
                return;
            }

            $lastChange = History_change_price::where('id_post', $post->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastChange && $lastChange->created_at >= $oneWeekAgo) {
                $nextChangeAllowed = $lastChange->created_at->addWeek();
                $daysRemaining = $now->diffInDays($nextChangeAllowed);
                $hoursRemaining = $now->copy()->addDays($daysRemaining)->diffInHours($nextChangeAllowed);

                $this->show = false;
                session()->flash('warning', "Vous avez déjà fait un changement de prix dans les 7 derniers jours. Vous pourrez effectuer un nouveau changement dans $daysRemaining jours et $hoursRemaining heures.");
            } else {
                if ($this->prix > $old_price) {
                    session()->flash('error', "Veuillez entrer un prix inférieur à votre prix actuel.");
                    return;
                }

                if ($post->old_prix === null) {
                    $post->old_prix = $old_price; // Set old_prix to the current prix the first time
                }

                $post->prix = $this->prix;
                $post->updated_price_at = now();
                $post->save();

                $history = new History_change_price();
                $history->id_post = $post->id;
                $history->old_price = $old_price;
                $history->new_price = $this->prix;
                $history->save();
                session()->flash(
                    'success-special',
                    __('price_reduction_success', [
                        'price' => $this->prix,
                        'countdown' => '6j 23h et 59 min'
                    ])
                );
                $this->show = false;
                $this->changed = true;
                $this->prix = "";
            }
        }
    }


}
