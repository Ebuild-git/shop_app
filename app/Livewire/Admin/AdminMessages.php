<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AdminMessage;
use Livewire\WithPagination;

class AdminMessages extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMessageId = null;
    public $showTrashed = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewMessage($id)
    {
        $this->selectedMessageId = $id;
    }

    public function closeMessage()
    {
        $this->selectedMessageId = null;
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function deleteMessage($id)
    {
        AdminMessage::find($id)?->delete();
        $this->selectedMessageId = null;
    }

    public function restoreMessage($id)
    {
        AdminMessage::withTrashed()->find($id)?->restore();
        $this->selectedMessageId = null;
    }

    public function deleteAll()
    {
        if ($this->showTrashed) {
            AdminMessage::onlyTrashed()->forceDelete();
        } else {
            AdminMessage::whereNull('deleted_at')->delete();
        }
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function render()
    {
        $query = AdminMessage::with(['sender', 'recipient', 'post'])
            ->when($this->search, function ($q) {
                $q->where('sujet', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%')
                  ->orWhereHas('recipient', fn($q) => $q->where('username', 'like', '%' . $this->search . '%'));
            });

        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        $messages = $query->latest()->paginate(15);

        $selectedMessage = $this->selectedMessageId
            ? AdminMessage::withTrashed()->with(['sender', 'recipient', 'post'])->find($this->selectedMessageId)
            : null;

        $trashedCount = AdminMessage::onlyTrashed()->count();

        return view('livewire.admin.admin-messages', compact('messages', 'selectedMessage', 'trashedCount'));
    }
}
