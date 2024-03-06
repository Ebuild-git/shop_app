<?php

namespace App\Livewire\User;

use App\Models\notifications;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsUser extends Component
{
    public $notifications;

    protected $listeners = ['notificationReceived' => 'notificationReceived'];


    public function render()
    {
        $this->notifications = $this->getList();
        return view('livewire.user.notifications-user');
    }


    public function notificationReceived()
    {
        $this->notifications = $this->getList();
    }

    public function getList()
    {
        $data =  notifications::where("id_user_destination", Auth::id())->Orderby("id","Desc")->get();
        return $data;
    }
}
