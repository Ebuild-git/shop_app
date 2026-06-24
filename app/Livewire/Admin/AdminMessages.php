<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AdminMessage;
use App\Models\Contact;
use App\Models\User;
use Livewire\WithPagination;

class AdminMessages extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMessageId = null;
    public $selectedMessageType = null;
    public $showTrashed = false;
    public $selectedUserId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewMessage($id, $type)
    {
        $this->selectedMessageId = $id;
        $this->selectedMessageType = $type;
    }

    public function closeMessage()
    {
        $this->selectedMessageId = null;
        $this->selectedMessageType = null;
    }

    public function viewUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function closeUser()
    {
        $this->selectedUserId = null;
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function deleteMessage($id, $type)
    {
        if ($type === 'admin') {
            AdminMessage::find($id)?->delete();
        } else {
            Contact::find($id)?->delete();
        }
        $this->selectedMessageId = null;
    }

    public function restoreMessage($id, $type)
    {
        if ($type === 'admin') {
            AdminMessage::withTrashed()->find($id)?->restore();
        } else {
            Contact::withTrashed()->find($id)?->restore();
        }
        $this->selectedMessageId = null;
    }

    public function forceDeleteMessage($id, $type)
    {
        if ($type === 'admin') {
            AdminMessage::withTrashed()->find($id)?->forceDelete();
        } else {
            Contact::withTrashed()->find($id)?->forceDelete();
        }
        $this->selectedMessageId = null;
    }

    public function deleteAll()
    {
        if ($this->showTrashed) {
            AdminMessage::onlyTrashed()->forceDelete();
            Contact::onlyTrashed()->forceDelete();
        } else {
            AdminMessage::whereNull('deleted_at')->delete();
            Contact::whereNull('deleted_at')->delete();
        }
        $this->selectedMessageId = null;
        $this->resetPage();
    }

    public function render()
    {
        // ── Drill-down: one user's messages ──────────────────────────────
        if ($this->selectedUserId) {
            $user = User::withTrashed()->find($this->selectedUserId);

            $adminMessages = AdminMessage::with(['sender', 'recipient', 'post'])
                ->where('from_user_id', $this->selectedUserId)
                ->when($this->showTrashed, fn($q) => $q->onlyTrashed(), fn($q) => $q->whereNull('deleted_at'))
                ->latest()->get()->map(fn($m) => [...$m->toArray(), '_type' => 'admin', '_model' => $m]);

            $contacts = Contact::where('user_id', $this->selectedUserId)
                ->when($this->showTrashed, fn($q) => $q->onlyTrashed(), fn($q) => $q->whereNull('deleted_at'))
                ->latest()->get()->map(fn($c) => [...$c->toArray(), '_type' => 'contact', '_model' => $c]);

            $userMessages = $adminMessages->concat($contacts)->sortByDesc('created_at')->values();

            $selectedMessage = null;
            if ($this->selectedMessageId && $this->selectedMessageType) {
                $selectedMessage = $this->selectedMessageType === 'admin'
                    ? AdminMessage::withTrashed()->with(['sender', 'recipient', 'post'])->find($this->selectedMessageId)
                    : Contact::withTrashed()->find($this->selectedMessageId);
                $selectedMessage?->offsetSet('_type', $this->selectedMessageType);
            }

            $trashedCount = AdminMessage::onlyTrashed()->count() + Contact::onlyTrashed()->count();

            return view('livewire.admin.admin-messages', compact(
                'userMessages', 'selectedMessage', 'trashedCount', 'user'
            ));
        }

        $users = User::withTrashed()
            ->when($this->search, fn($q) => $q
                ->where('username', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
            )
            ->withCount([
                'adminMessagesSent as admin_messages_count',
                'contacts as contact_messages_count',
            ])
            ->with([
                'adminMessagesSent' => fn($q) => $q->with('recipient')->latest()->limit(1),
            ])
            ->having('admin_messages_count', '>', 0)
            ->orHaving('contact_messages_count', '>', 0)
            ->latest()
            ->paginate(15);

        $guestContacts = Contact::whereNull('user_id')
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
            )
            ->when($this->showTrashed, fn($q) => $q->onlyTrashed(), fn($q) => $q->whereNull('deleted_at'))
            ->latest()->paginate(15);

        $trashedCount = AdminMessage::onlyTrashed()->count() + Contact::onlyTrashed()->count();

        $selectedMessage = null;

        return view('livewire.admin.admin-messages', compact(
            'users', 'guestContacts', 'selectedMessage', 'trashedCount'
        ));
    }
    public function deleteGuestContact($id)
    {
        Contact::find($id)?->delete();
    }

    public function deleteAllGuestContacts()
    {
        if ($this->showTrashed) {
            Contact::onlyTrashed()->whereNull('user_id')->forceDelete();
        } else {
            Contact::whereNull('user_id')->whereNull('deleted_at')->delete();
        }
        $this->resetPage();
    }

    public function restoreGuestContact($id)
    {
        Contact::withTrashed()->whereNull('user_id')->find($id)?->restore();
    }
}
