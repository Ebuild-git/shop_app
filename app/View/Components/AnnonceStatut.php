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
            $this->statut = __('voyage_mode');
        } elseif($verifiedAt !== null && $sellAt === null) {
            $this->statut = __('on_sale1');
        } elseif($sellAt !== null) {
            $this->statut = __('sold');
        } else {
            switch($statut) {
                case 'validation':
                    $this->statut = __('waiting_validation');
                    break;
                case 'livraison':
                    $this->statut = __('in_delivery');
                    break;
                case 'livré':
                    $this->statut = __('delivered');
                    break;
                case 'refusé':
                    $this->statut = __('refused');
                    break;
                case 'préparation':
                    $this->statut = __('preparation');
                    break;
                case 'en cours de livraison':
                    $this->statut = __('in_progress_delivery');
                    break;
                case 'ramassée':
                    $this->statut = __('picked_up');
                    break;
                case 'retourné':
                    $this->statut = __('returned');
                    break;
                default:
                    $this->statut = __('unknown_status');
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
