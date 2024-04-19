<?php

namespace App\Livewire\Admin;

use App\Models\proprietes as ModelsProprietes;
use Livewire\Component;

class Proprietes extends Component
{

    //listenign refresh
    protected $listeners = ['created-propriete' => '$refresh'];
 

    public function render()
    {
        $prorietes = ModelsProprietes::Orderby("order")->get();
        return view('livewire.admin.proprietes', compact("prorietes"));
    }





}
