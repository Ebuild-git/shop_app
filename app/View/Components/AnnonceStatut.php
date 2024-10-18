<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnnonceStatut extends Component
{
    /**
     * Create a new component instance.
     */
    public $statut;

    public function __construct($statut, $sellAt, $verifiedAt, $voyageMode)
    {
        if($verifiedAt !== null && $sellAt === null && $voyageMode === 1) {
            $this->statut = "En mode voyage";
        } elseif($verifiedAt !== null && $sellAt === null) {
            $this->statut = "En vente";
        } elseif($sellAt !== null) {
            $this->statut = "Vendu";
        } else {
            switch($statut) {
                case 'validation':
                    $this->statut = "En attente de validation";
                    break;
                case 'livraison':
                    $this->statut = "En livraison";
                    break;
                case 'livré':
                    $this->statut = "Livré";
                    break;
                case 'refusé':
                    $this->statut = "Refusé";
                    break;
                case 'préparation':
                    $this->statut = "Préparation";
                    break;
                case 'en cours de livraison':
                    $this->statut = "En cours de livraison";
                    break;
                case 'ramassée':
                    $this->statut = "Ramassée";
                    break;
                case 'retourné':
                    $this->statut = "Retourné";
                    break;
                default:
                    $this->statut = "Statut inconnu";
            }
        }
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.annonce-statut');
    }
}
