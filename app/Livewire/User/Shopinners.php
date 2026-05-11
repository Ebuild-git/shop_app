<?php

namespace App\Livewire\User;

use App\Models\pings;
use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Shopinners extends Component
{
    use WithPagination;
    public $key;
    public $rating;

    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }

    public function updatedRating($value)
    {
        $this->rating = $value;
        $this->resetPage();
    }

    public function render()
    {
        $blockedIds = [];

        if (Auth::check()) {

            $userId = auth()->user()->id;
            $blockedIds = UserBlock::where('blocker_id', $userId)->pluck('blocked_id')->toArray();

            $Query = User::select(
                'users.id',
                'users.lastname',
                'users.username',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at',
                DB::raw('AVG(ratings.etoiles) as average_rating'),
                DB::raw('COUNT(posts.id) as total_posts'),
                DB::raw('COUNT(ratings.id) as total_reviews'),
                'pings.id as is_pinned'
            )
                ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_sell')
                ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
                ->leftJoin('pings', function ($join) use ($userId) {
                    $join->on('users.id', '=', 'pings.pined')
                        ->where('pings.id_user', $userId);
                })
                ->where('users.role', '!=', 'admin')
                ->where('users.locked', false)
                ->whereNotNull('users.email_verified_at');

            // Exclude blocked users
            if (!empty($blockedIds)) {
                $Query->whereNotIn('users.id', $blockedIds);
            }

            if (!empty($this->key)) {
                $Query = $Query->where('users.username', 'LIKE', $this->key . '%');
            }
            if (!empty($this->rating)) {
                $Query = $Query->having('average_rating', '>=', $this->rating);
            }

            $shopiners = $Query
                ->groupBy('users.id', 'users.lastname', 'users.username', 'users.voyage_mode', 'users.avatar', 'users.photo_verified_at', 'pings.id_user', 'pings.id')
                ->orderByRaw("CASE WHEN users.id = {$userId} THEN 0 ELSE 1 END") // Auth user first
                ->orderByRaw('CASE WHEN pings.id_user IS NOT NULL THEN 0 ELSE 1 END') // Met les "pings" en premier
                ->orderBy('total_reviews', 'desc')
                ->orderBy('users.username')
                ->orderBy('average_rating', 'desc')
                ->orderBy('total_posts')
                ->paginate(50);

        } else {
            $Query = User::select(
                'users.id',
                'users.name',
                'users.username',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at',
                DB::raw('AVG(etoiles) as average_rating'),
                DB::raw('COUNT(posts.id) as total_posts'),
                DB::raw('COUNT(ratings.id) as total_reviews')
            )
                ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_rated')
                ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
                ->where('users.role', '!=', 'admin')
                ->where('users.locked', false)
                ->whereNotNull('users.email_verified_at')
                ->groupBy('users.id', 'users.lastname', 'users.username', 'users.avatar', 'users.photo_verified_at');

            if (!empty($this->key)) {
                $Query = $Query->where('users.username', 'LIKE', $this->key . '%');
            }
            if (!empty($this->rating)) {
                $Query = $Query->having('average_rating', '>=', $this->rating);
            }

            $shopiners = $Query->orderBy('total_reviews', 'desc')
                ->orderBy('users.username')
                ->orderBy('average_rating', 'desc')
                ->orderBy('total_posts')
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
                $this->dispatch(
                    'alert',
                    [
                        'message' => __('alert_removed'),
                        'type' => 'warning'
                    ]
                );
            } else {
                pings::firstOrCreate(
                    [
                        'id_user' => Auth::id(),
                        'pined' => $id_user
                    ]
                );
                $this->dispatch(
                    'alert',
                    [
                        'message' => __('alert_pinned'),
                        'type' => 'success'
                    ]
                );
            }
        }
    }

    public function blockUser($id_user)
    {
        if (Auth::check() && Auth::id() != $id_user) {
            UserBlock::firstOrCreate([
                'blocker_id' => Auth::id(),
                'blocked_id' => $id_user,
            ]);

            // Remove any ping between these users
            pings::where('id_user', Auth::id())->where('pined', $id_user)->delete();
            pings::where('id_user', $id_user)->where('pined', Auth::id())->delete();

            $this->dispatch('alert', [
                'message' => __('Utilisateur bloqué'),
                'type' => 'warning'
            ]);
        }
    }

    public function unblockUser($id_user)
    {
        if (Auth::check()) {
            UserBlock::where('blocker_id', Auth::id())
                ->where('blocked_id', $id_user)
                ->delete();

            $this->dispatch('alert', [
                'message' => __('Utilisateur débloqué'),
                'type' => 'success'
            ]);
        }
    }
}
