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

            // Ensure the new price is different from the old price
            if ($this->prix == $old_price) {
                $this->addError('prix', 'Le nouveau prix doit être différent du prix actuel <br> Veuillez réduire le prix.');
                return;
            }

            // Get the last price change
            $lastChange = History_change_price::where('id_post', $post->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Check if the price was changed within the last 7 days
            if ($lastChange && $lastChange->created_at >= $oneWeekAgo) {
                // Calculate the time remaining before a new price change is allowed
                $nextChangeAllowed = $lastChange->created_at->addWeek();
                $daysRemaining = $now->diffInDays($nextChangeAllowed);
                $hoursRemaining = $now->copy()->addDays($daysRemaining)->diffInHours($nextChangeAllowed);

                // Flash message to inform the user of the remaining time
                $this->show = false;
                session()->flash('warning', "Vous avez déjà fait un changement de prix dans les 7 derniers jours. Vous pourrez effectuer un nouveau changement dans $daysRemaining jours et $hoursRemaining heures.");
            } else {
                // Ensure the new price is lower than the old price
                if ($this->prix > $old_price) {
                    session()->flash('error', "Veuillez entrer un prix inférieur à votre prix actuel.");
                    return;
                }

                if ($post->old_prix === null) {
                    $post->old_prix = $post->prix;  // Set the original base price only once
                }

                // Update the current price
                $post->prix = $this->prix;
                $post->updated_price_at = now();
                $post->save();

                // Log the price change in history
                $history = new History_change_price();
                $history->id_post = $post->id;
                $history->old_price = $old_price;
                $history->new_price = $this->prix;
                $history->save();

                // Success message and reset the form
                session()->flash('success-special', "Le nouveau prix de $this->prix DH est accpeté. <br/> Vous pourrez réduire le prix de cet article de nouveau dans 6j 23h et 59 min");
                $this->show = false;
                $this->changed = true;
                $this->prix = "";
            }
        }
    }

}
