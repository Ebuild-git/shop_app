<?php

namespace App\Livewire;

use App\Models\posts;
use Livewire\Component;

class DetailsPublicationAction extends Component
{
    public $post,$id,$verified_at;
    public function mount($id)
    {
        $this->id = $id;
    }
    public function render()
    {
        $this->post = posts::find( $this->id);  
        return view('livewire.details-publication-action');
    }

    public function valider()
    {
        //valider le post
        $post = posts::find($this->post->id);
        if ($post) {
            //update verified_at date
            $post->verified_at = now();
            $post->save();
            session()->flash("success", "Le publication a été validée");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }

    public function delete()
    {
        $post = posts::find($this->post->id);
        if ($post) {
            //update verified_at date
            $post->delete();
            return redirect()->back();
        } else {
            session()->flash("error", "Une erreur est survenue lors de la suppression");
        }
    }
}
