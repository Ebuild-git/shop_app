<?php

namespace App\Livewire\User;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;



class Inscription extends Component
{
    use WithFileUploads;
    public $nom, $email, $telephone, $password, $photo, $matricule, $username;

    public function render()
    {
        return view('livewire.user.inscription');
    }



    public function updatedUsername($value)
    {
        $cleanedUsername = preg_replace('/[^A-Za-z0-9]/', '', $value);
        $count = User::where("username", $cleanedUsername)->count();
        if ($count > 0) {
            $this->addError('username', "Ce nom d'utilisateur est déjà utilisé !");
        }
        $this->username = $cleanedUsername;
    }


    //validation strict
    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => ['required', 'string'],
        'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
        'nom' => ['required', 'string'],
        'telephone' => ['required', 'numeric'],
        'username' => "string|unique:users,username",
    ];


    public function inscription()
    {
        $this->validate();





        //generer un token pour la verification de mail
        $token = md5(time());

        $user = new User();
        $user->name = $this->nom;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->phone_number = $this->telephone;
        $user->role = "user";
        $user->type = "user";
        $user->username = $this->username;
        if ($this->photo) {
            $newName = $this->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        }
        $user->ip_adress = request()->ip();
        $user->remember_token = $token;

        if ($this->matricule) {
            $matricule = $this->matricule->store('uploads/documents', 'public');
            $user->type = "shop";
            $user->matricule = $matricule;
        } else {
            $user->validate_at = now();
        }


        $user->save();


        //donner le role user
        $user->assignRole('user');

        //envoi du mail avec le lien de validation
        Mail::to($user->email)->send(new VerifyMail($user, $token));

        return redirect("/connexion")->with("success", "Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.");
        //reset form

    }
}
