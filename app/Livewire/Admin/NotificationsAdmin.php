<?php

namespace App\Livewire\Admin;

use App\Models\notifications;
use Livewire\Component;

class NotificationsAdmin extends Component
{
    public $notifications;

    protected $listeners = ['notificationReceived' => 'notificationReceived'];


    public function render()
    {
        $this->notifications = $this->getList();
        return view('livewire.admin.notifications-admin');
    }

    public function notificationReceived()
    {
        $this->notifications = $this->getList();
    }

    public function getList()
    {
        $data = notifications::where("destination", "admin")
            ->Orderby("id", "Desc")
            ->get();
        return $data;
    }

    public function all_read()
    {
        notifications::where("destination", "admin")->update(
            [
                'statut' => "read"
            ]
        );
        $this->notifications = $this->getList();
    }


    public function delete($id)
    {
        if ($id) {
            $row =  notifications::findOrFail($id);
            $row->delete();
        }
        $this->notifications = $this->getList();
    }
}
