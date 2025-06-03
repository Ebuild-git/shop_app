<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\AramexService;
use App\Models\Commande;
use App\Models\regions;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

class ShipmentTracker extends Component
{
    use WithPagination;

    public $region_id;
    public $date;
    public $filtersApplied = false;

    public function updatingRegionId()
    {
        $this->filtersApplied = false;
    }

    public function updatingDate()
    {
        $this->filtersApplied = false;
    }

    public function applyFilters()
    {
        $this->filtersApplied = true;
        $this->resetPage(); // Reset to first page after applying filters
    }

    public function resetFilters()
    {
        $this->reset(['region_id', 'date', 'filtersApplied']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Commande::orderBy('created_at', 'desc');

        if ($this->filtersApplied && ($this->region_id || $this->date)) {
            if ($this->region_id) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('vendor', function ($q) {
                        $q->where('region', $this->region_id);
                    })->orWhereHas('buyer', function ($q) {
                        $q->where('region', $this->region_id);
                    });
                });
            }

            if ($this->date) {
                $query->whereDate('created_at', Carbon::parse($this->date));
            }
        }

        $commandes = $query->paginate(10);
        $regions = regions::all();

        return view('livewire.admin.shipment-tracker', compact('commandes', 'regions'));
    }
}
