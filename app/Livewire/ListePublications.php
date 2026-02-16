<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\categories;
use App\Models\notifications;
use App\Models\posts;
use App\Models\regions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListePublications extends Component
{
    use WithPagination;

    protected $listeners = ['annonce-delete' => '$refresh'];

    public $type, $categories, $mot_key, $categorie_key, $region_key, $deleted, $date, $signalement;

    public $postIdToDelete;
    public $motif_suppression = '';
    public $custom_motif_suppression;

    public function mount($deleted)
    {
        $this->deleted = $deleted;
    }

    public $status_filter; // This will hold the selected status for filtering

    public function render()
    {

        $this->categories = categories::all();

        $postsQuery = posts::query();
        if ($this->deleted == 'oui') {
            $postsQuery->onlyTrashed()->orderBy('deleted_at', 'desc');
        } else {
            $postsQuery->orderBy('id', 'desc');
        }

        if (strlen($this->status_filter) > 0) {
            if ($this->status_filter === 'vente') {
                $postsQuery->whereNotNull('verified_at')
                    ->whereNull('sell_at');
            } elseif ($this->status_filter === 'vendu') {
                $postsQuery->whereNotNull('sell_at');
            } elseif ($this->status_filter === 'en voyage') {
                $postsQuery->whereNotNull('verified_at')
                    ->whereNull('sell_at')
                    ->whereHas('user_info', function ($query) {
                        $query->where('voyage_mode', 1);
                    });
            } else {
                $postsQuery->where('statut', $this->status_filter);
            }
        }


        //filtre rn fonction de l'ordre des post les plus signaler
        if (strlen($this->signalement) > 0) {
            $order = $this->signalement == "Asc" ? 'asc' : 'desc';

            $postsQuery->withCount('signalements')
                ->orderBy('signalements_count', $order);
        }

        if (strlen($this->date) > 0) {
            $date = $this->date;
            $year = Carbon::createFromFormat('Y-m', $date)->year;
            $month = Carbon::createFromFormat('Y-m', $date)->month;

            $postsQuery->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }
        // Filtrage par mot-clé
        if (strlen($this->mot_key) > 0) {
            $mot_key = $this->mot_key;

            $postsQuery->where(function ($query) use ($mot_key) {


                $query->whereHas('user_info', function ($q) use ($mot_key) {
                    $q->where('username', 'like', '%' . $mot_key . '%')
                        ->orWhere('firstname', 'like', '%' . $mot_key . '%')
                        ->orWhere('lastname', 'like', '%' . $mot_key . '%');
                });
                if (strtoupper(substr($mot_key, 0, 1)) === 'P' && is_numeric(substr($mot_key, 1))) {
                    $numericId = substr($mot_key, 1);
                    $query->orWhere('id', $numericId);
                }
                if (is_numeric($mot_key)) {
                    $query->orWhere('id', $mot_key);
                }
                $query->orWhere('titre', 'like', '%' . $mot_key . '%')
                    ->orWhere('description', 'like', '%' . $mot_key . '%');
            });
        }


        // Filtrage par catégories
        if (strlen($this->categorie_key) > 0) {
            $categorie = categories::find($this->categorie_key);
            if ($categorie) {
                $postsQuery->whereHas('sous_categorie_info', function ($query) use ($categorie) {
                    $query->where('id_categorie', $categorie->id);
                });
            }
        }


        // Filtrage par region
        if (strlen($this->region_key) > 0) {
            $postsQuery->where('id_region', $this->region_key);
        }


        $posts = $postsQuery->paginate(50);
        $regions = regions::all(['id', 'nom']);

        //count total trashed post
        $trashCount = posts::onlyTrashed()->count();
        return view('livewire.liste-publications', compact('posts', 'regions', 'trashCount'));
    }

    public function valider($id)
    {
        //valider le post
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->verified_at = now();
            $post->statut = 'vente';
            $post->save();

            //make notification
            $notification = new notifications();
            $notification->titre = "Une vente a été retouner ";
            $notification->id_user_destination = $post->id_user;
            $notification->type = "alerte";
            $notification->url = "/post/" . $post->id;
            $notification->message = "Nous vous informons que votre publication  " . $post->titre . " a été retourné a la vente !";
            $notification->save();
            event(new UserEvent($post->id_user));

            // Send FCM notification
            $fcmService = app(\App\Services\FcmService::class);
            $sent = $fcmService->sendToUser(
                $post->id_user,
                "Une vente a été retouner",
                "Nous vous informons que votre publication " . $post->titre . " a été retourné a la vente !",
                [
                    'type' => 'alerte',
                    'notification_id' => $notification->id,
                    'destination' => 'user',
                    'action' => 'post_validated',
                    'post_id' => $post->id,
                ]
            );

            if ($sent) {
                \Log::info("FCM notification sent successfully", [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'type' => 'post_validated'
                ]);
            } else {
                \Log::warning("FCM notification failed to send", [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'reason' => 'User has no FCM token or token invalid'
                ]);
            }
            $this->dispatch('alert', ['message' => "Le publication a été validée", 'type' => 'success']);
        } else {
            $this->dispatch('alert', ['message' => "Une erreur est survenue", 'type' => 'error']);
        }
    }


    public function filtre()
    {
        $this->resetPage();
    }



    public function confirmDelete($id)
    {
        $post = posts::find($id);

        if ($post) {
            if (!empty($this->motif_suppression)) {
                $post->motif_suppression = $this->motif_suppression;
                $post->save();

                $greeting = $post->user_info->gender === 'female' ? "Chère" : "Cher";
                $notification = new notifications();
                $notification->titre = "{$greeting} " . $post->user_info->username;
                $notification->id_user_destination = $post->id_user;
                $notification->type = "alerte";
                $notification->url = "#";
                $notification->message = "
                    Votre annonce pour <strong>" . htmlspecialchars($post->titre) . "</strong> a été retirée par l'équipe de <span style='color: black; font-weight: 500;'>SHOP</span><span style='color: #008080; font-weight: 500;'>IN</span>.
                    La raison de la suppression est la suivante: <b style='color: #e74c3c;'>" . htmlspecialchars($this->motif_suppression) . "</b> <br/>
                    Merci pour votre compréhension.
                ";
                $notification->save();
                event(new UserEvent($post->id_user));

                // Send FCM notification
                $fcmService = app(\App\Services\FcmService::class);
                $sent = $fcmService->sendToUser(
                    $post->id_user,
                    "{$greeting} " . $post->user_info->username,
                    "Votre annonce pour " . $post->titre . " a été retirée. Raison: " . $this->motif_suppression,
                    [
                        'type' => 'alerte',
                        'notification_id' => $notification->id,
                        'destination' => 'user',
                        'action' => 'post_deleted',
                        'post_id' => $post->id,
                    ]
                );

                if ($sent) {
                    \Log::info("FCM notification sent successfully", [
                        'user_id' => $post->id_user,
                        'notification_id' => $notification->id,
                        'type' => 'post_deleted'
                    ]);
                } else {
                    \Log::warning("FCM notification failed to send", [
                        'user_id' => $post->id_user,
                        'notification_id' => $notification->id,
                        'reason' => 'User has no FCM token or token invalid'
                    ]);
                }

                $post->delete();
                $this->dispatch('closeModal', ['id' => "deleteModal-$id"]);
                $this->dispatch('alert', ['message' => "La publication a été supprimée avec le motif: {$this->motif_suppression} !", 'type' => 'success']);
                $this->dispatch('reloadPage');

                $this->motif_suppression = '';
            } else {
                $this->dispatch('closeModal', ['id' => "deleteModal-$id"]);
                $this->dispatch('alert', ['message' => "Veuillez sélectionner un motif de suppression.", 'type' => 'error']);

            }
        } else {
            $this->dispatch('closeModal', ['id' => "deleteModal-$id"]);
            $this->dispatch('alert', ['message' => "Une erreur est survenue lors de la suppression !", 'type' => 'error']);
        }
    }




    public function restore($id)
    {
        $post = posts::withTrashed()->where('id', $id)->first();
        if ($post) {
            $post->update(['motif_suppression' => null]);
            $post->restore();

            $greeting = $post->user_info->gender === 'female' ? "Chère" : "Cher";

            // Create a notification with styled content
            $notification = new notifications();
            $notification->titre = "{$greeting} " . $post->user_info->username;
            $notification->id_user_destination = $post->id_user;
            $notification->type = "alerte";
            $notification->url = "#";
            $notification->message = "
                Votre annonce pour <strong>
                    <a href='" . route('details_post2', ['id' => $post->id, 'titre' => $post->titre]) . "'
                    class='underlined-link'>
                    " . htmlspecialchars($post->titre) . "
                    </a>
                </strong> a été restaurée par l'équipe de <span style='color: black; font-weight: 500;'>SHOP</span><span style='color: #008080; font-weight: 500;'>IN</span>.
                Merci pour votre patience.
            ";

            $notification->save();

            $fcmService = app(\App\Services\FcmService::class);

            try {
                $fcmService->sendToUser(
                    $post->id_user,
                    'Item Restored',
                    "Dear {$post->user_info->username}, your item \"{$post->titre}\" has been restored. Thank you for your patience.",
                    [
                        'type' => 'alert',
                        'notification_id' => $notification->id,
                        'destination' => 'user',
                        'action' => 'post_restored',
                        'post_id' => $post->id,
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('FCM restore notification failed', [
                    'post_id' => $post->id,
                    'user_id' => $post->id_user,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->dispatch('alert', ['message' => "La publication à été restaurer !", 'type' => 'success']);
        } else {
            $this->dispatch('alert', ['message' => "Cette publication n'existe pas.", 'type' => 'error']);
        }
    }

    public function delete_definitivement($id)
    {
        $post = posts::withTrashed()->findOrFail($id);
        foreach ($post->photos as $img) {
            Storage::disk('public')->delete($img);
        }
        $post->forceDelete();
        $this->dispatch('alert', ['message' => "La publication a été définitivement supprimée !", 'type' => 'success']);
    }


    public function vendu($id)
    {
        //valider le post
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->sell_at = now();
            $post->save();
            session()->flash("success", "Le publication a été vendu");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }
}
