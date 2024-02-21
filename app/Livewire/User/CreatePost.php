<?php

namespace App\Livewire\User;

use App\Models\categories;
use Livewire\WithFileUploads;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreatePost extends Component
{
    use WithFileUploads;

    public $titre, $description, $photos, $ville, $gouvernorat, $categorie, $prix;

    public function render()
    {
        $categories = categories::all([ 'id', 'titre']);
        return view('livewire.user.create-post')->with("categories",$categories);
    }

    //validation with multi upload image
    protected $rules = [
        'titre' => 'required|min:6',
        'description' => 'required|min:10',
        'photos.*' => 'image|max:2048|min:1',
        'ville' => 'required',
        'categorie' => 'required|integer|exists:categories,id',
        'gouvernorat' => 'required',
        'prix' => 'required|numeric|min:1'
    ];




    public function submit()
    {

        $this->validate();

        foreach ($this->photos as $photo) {
            $name = $photo->store('uploads/posts', 'public');
            $data[] = $name;
        }

        $post = new posts();
        $post->titre = $this->titre;
        $post->description = $this->titre;
        $post->ville = $this->ville;
        $post->gouvernorat = $this->gouvernorat;
        $post->id_categorie = $this->categorie;
        $post->prix = $this->prix;
        $post->photos = json_encode($data);
        $post->id_user = Auth::user()->id;
        $post->save();

        session()->flash("success", "Le post a été crée avec succés, Vous allez recevoir une notification une fois la publication vlider par un administrateur");

        //reset form
        $this->reset(['titre','description','ville','gouvernorat','categorie','prix']);
    }
}
