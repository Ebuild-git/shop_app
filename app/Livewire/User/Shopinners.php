<?php

namespace App\Livewire\User;

use App\Models\pings;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Shopinners extends Component
{
    use WithPagination;
    public function render()
    {
        if (Auth::check()) {
            $userId = auth()->user()->id;

            $shopiners = User::select(
                'users.id',
                'users.name',
                'users.avatar',
                'users.username',
                'users.certifier',
                DB::raw('AVG(ratings.etoiles) as average_rating'),
                DB::raw('COUNT(posts.id) as total_posts')
            )
                ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_rated')
                ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
                ->leftJoin('pings', function ($join) use ($userId) {
                    $join->on('users.id', '=', 'pings.pined')
                        ->where('pings.id_user', $userId);
                })
                ->where('users.role', '!=', 'admin')
                ->where('users.id', '!=', Auth::id())
                ->groupBy('users.id', 'users.name', 'users.avatar', 'users.username', 'users.certifier', 'pings.id_user')
                ->orderByRaw('CASE WHEN pings.id_user IS NOT NULL THEN 0 ELSE 1 END') // Met les "pings" en premier
                ->orderByDesc('average_rating') // Ensuite, trie par note moyenne
                ->orderByDesc('total_posts')
                ->paginate(50);
        } else {
            $shopiners = User::select('users.id', 'users.name', 'users.avatar', 'users.username', 'users.certifier', DB::raw('AVG(etoiles) as average_rating'), DB::raw('COUNT(posts.id) as total_posts'))
                ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_rated')
                ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
                ->groupBy('users.id', 'users.name', 'users.avatar', 'users.username', 'users.certifier')
                ->where('users.role', '!=', 'admin')
                ->orderByDesc('average_rating')
                ->orderByDesc('total_posts')
                ->paginate(50);
        }



        return view('livewire.user.shopinners', compact("shopiners"));
    }


    public function ping($id_user)
    {
        if (Auth::check()) {
            $user = pings::where('id_user', Auth::id())->where('pined', $id_user)->first();
            if ($user) {
                $user->delete();
                $this->dispatch('alert', ['message' => "Votre ping a été retiré !", 'type' => 'warning']);
            } else {
                pings::firstOrCreate(
                    [
                        'id_user' => Auth::id(),
                        'pined' => $id_user
                    ]
                );
                $this->dispatch('alert', ['message' => "Votre ping a été ajouté !", 'type' => 'success']);
            }
        }
    }
}