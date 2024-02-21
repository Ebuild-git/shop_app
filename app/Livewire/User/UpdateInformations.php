<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateInformations extends Component
{
    public $name, $email, $telephone, $ville, $gouvernorat, $avatar, $adresse;

    public function render()
    {
        return view('livewire.user.update-informations');
    }

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
        'telephone' => ['nullable', 'regex:/^([0-9]{10})$/'],
        'ville' => 'required|string|max:255',
        'gouvernorat' => 'required|string|max:255',
        'adresse' => 'string|nullable|max:255',
        'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
    ];

    public function update()
    {

        $this->validate();
        $user = User::find(Auth::user()->id);

        //verifier si l'email a ete changer si oui si cela est libre
        if ($this->email != Auth::user()->email) {
            $existingEmail = User::where('email', $this->email)->first();
            if ($existingEmail) {
                //retutn erro in email input field
                $this->addError('email', 'Cet email existe déja!');
            } else {
                $user->email = $this->email;
            }
        }
        if ($this->avatar) {
            $newName = $this->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        }
        $user->name = $this->name;
        $user->phone_number = $this->telephone;
        $user->ville = $this->ville;
        $user->gouvernorat = $this->gouvernorat;
        $user->adress = $this->adresse;
        $user->save();

        session()->flash('info', 'Informations mises à jour avec succès !');
    }
}
