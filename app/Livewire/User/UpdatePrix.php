<?php

namespace App\Livewire\User;

use App\Models\History_change_price;
use App\Models\posts;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Events\AdminEvent;
use App\Models\notifications;

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
            'prix.required' => __('prix.required'),
            'prix.numeric' => __('prix.numeric'),
            'prix.min' => __('prix.min'),
        ]);

        $oneWeekAgo = Carbon::now()->subWeek();
        $post = posts::find($this->post->id);
        $now = Carbon::now();

        if ($post) {
            $old_price = $post->prix;

            if ($this->prix == $old_price) {
                $this->addError('prix', __('price_change_error'));
                return;
            }

            $lastChange = History_change_price::where('id_post', $post->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastChange && $lastChange->created_at >= $oneWeekAgo) {
                $nextChangeAllowed = $lastChange->created_at->addWeek();
                $daysRemaining = $now->diffInDays($nextChangeAllowed);
                $hoursRemaining = $now->copy()->addDays($daysRemaining)->diffInHours($nextChangeAllowed);

                $daysText = trans_choice('days_remaining', $daysRemaining, ['count' => $daysRemaining]);
                $hoursText = trans_choice('hours_remaining', $hoursRemaining, ['count' => $hoursRemaining]);
                $this->show = false;
                session()->flash('warning', __("price_change_limit", [
                    'daysRemaining' => $daysText,
                    'hoursRemaining' => $hoursText,
                ]));
            } else {
                if ($this->prix > $old_price) {
                    session()->flash('error', __("price_change_error"));
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
                        'price' => $this->prix
                    ])
                );
                $this->show = false;
                $this->changed = true;
                $this->prix = "";

                event(new AdminEvent('Un utilisateur a réduit le prix de son article.'));

                $notification = new notifications();
                $notification->type = "photo";
                $notification->titre = Auth::user()->username . " a réduit le prix d’un article.";
                $notification->url = "/admin/publication/" . $post->id . "/view";
                $notification->message = "Prix mis à jour de {$post->old_prix} à {$post->prix}.";
                $notification->id_user = Auth::id();
                $notification->destination = "admin";
                $notification->save();
            }
        }
    }


}
