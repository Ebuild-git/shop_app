<?php

namespace App\Livewire\User;

use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListMesPosts extends Component
{
    use WithPagination;

    protected $listeners = ['update-price' => 'refresh'];


    public  $date, $etat,$filter,$titre,$statut;

   
    
    public function mount($titre,$filter,$statut){
            $this->titre = $titre;
            $this->filter=$filter;
            $this->statut=$statut;
            if(isset($statut)  && !empty($statut)){
                $this->etat=$statut;
            }
    }



    public function render()
    {
        $Query = posts::where("id_user", Auth::user()->id)->Orderby("id", "Desc");


        if (!empty($this->date)) {
            $date = $this->date.'-01'; 
            $Query->whereYear('Created_at', date('Y', strtotime($date)))
                  ->whereMonth('Created_at', date('m', strtotime($date)));
        }
        
        
        if (!empty($this->statut)) {
            switch ($this->statut) {
                case 'validation':
                    $postsQuery = $Query->where('statut', "validation");
                    break;
                    case 'vente':
                        $postsQuery = $Query->where('statut', "vente");
                        break;
                        case 'vendu':
                            $postsQuery = $Query->where('statut', "vendu");
                            break;
                            case 'livraison':
                                $postsQuery = $Query->where('statut', "livraison");
                                break;
                                case 'livré':
                                    $postsQuery = $Query->where('statut', "livré");
                                    break;
            }
        }
        $posts =  $Query
            ->paginate("30");
        return view('livewire.user.list-mes-posts', compact('posts'));
    }


    public function filtrer()
    {
        $this->resetPage();
    }


    
}
