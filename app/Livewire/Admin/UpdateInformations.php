<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateInformations extends Component
{
    use WithFileUploads;

    public $avatar, $avatar2, $firstname, $email;



    public function render()
    {
        $user = User::find(Auth::id());
        if ($user) {
            $this->firstname = $user->firstname;
            $this->email = $user->email;
            $this->avatar = $user->avatar;
        }
        return view('livewire.admin.update-informations');
    }

    protected $rules = [
        'firstname' => 'required',
        'email' => ['required', 'email'],
        'avatar2' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048'
    ];

    public function update_informations()
    {

        //validations 
        $this->validate();


        $user = User::find(Auth::id());
        if ($user) {
            $user->firstname = $this->firstname;
            if ($user->email != $this->email) {
                $count = User::where('email', $this->email)->count();
                if ($count > 0) {
                    $this->addError("email", "Désoler mais cette adresse est déja  utilisée");
                    return;
                } else {
                    $user->email = $this->email;
                }
            }
            if ($this->avatar2) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $newName = \App\Services\ImageService::uploadAndConvert($this->avatar2, 'admin');
                $user->avatar = $newName;
            }
            $user->save();
            $this->render();
            session()->flash('success', "Informations modifiées avec succes");
        }
    }
}
