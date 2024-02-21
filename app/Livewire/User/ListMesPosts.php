<?php

namespace App\Livewire\User;

use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListMesPosts extends Component
{
    use WithPagination;

    public  $date, $etat;
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
}
