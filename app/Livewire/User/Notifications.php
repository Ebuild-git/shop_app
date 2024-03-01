<?php

namespace App\Livewire\User;

use App\Models\notifications as ModelsNotifications;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        $notifications = ModelsNotifications::where("id_user_destination", Auth::id())->Orderby("id","Desc")->get();
        return view('livewire.user.notifications')
            ->with("notifications", $notifications);
    }

    public function delete($id)
    {
        $notification = ModelsNotifications::find($id);
        if ($notification) {
            //verifier que il a l'autorisation de supprimer
            if ($notification->id_user_destination == Auth::user()->id) {
                $notification->delete();

                //show success message
                session()->flash('success', 'Notification Supprimée');
            } else {
                //show error message
                session()->flash('error', 'Vous n\'avez pas la permission de supprimer cette notification');
            }
        } else {
            //show error message
            session()->flash('error', 'Cette Notification n\'existe pas');
        }
    }
}
