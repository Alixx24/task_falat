<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class NoteManager extends Component
{
    use WithFileUploads;

    public $notes = [];
    public $title = '';
    public $content = '';
    public $status = 'انجام‌نشده';
    public $selectedNote = null;
    public $selectedUser = null;
    public $users = [];
    public $search = '';

    public $newFiles = [];
    public $deletedFiles = [];

    protected $listeners = ['markFileDeleted'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'status' => 'required|string',
        'selectedUser' => 'nullable|exists:users,id',
        'newFiles.*' => 'file|max:10240', // 10MB
    ];

    public function mount()
    {
        $this->users = User::all();
        $this->selectedUser = $this->users->first()?->id;
        $this->loadNotes();
    }

    public function updatedSelectedUser() { $this->loadNotes(); }
    public function updatedSearch() { $this->loadNotes(); }

    public function loadNotes()
    {
        $query = Note::with('user');

        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        if (!empty(trim($this->search))) {
            $query->where('title', 'LIKE', '%' . trim($this->search) . '%');
        }

        $this->notes = $query->orderByDesc('created_at')->get();
    }

    public function delete($id)
    {
        $note = Note::findOrFail($id);

        // حذف فایل‌ها از استوریج
        foreach ($note->files as $file) {
            \Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $note->delete();
        $this->loadNotes();
    }

    public function edit($id)
    {
        $note = Note::with('files')->findOrFail($id);
        $this->selectedNote = $note->id;
        $this->title = $note->title;
        $this->content = $note->content;
        $this->status = $note->status;
        $this->selectedUser = $note->user_id;
        $this->newFiles = [];
        $this->deletedFiles = [];
        $this->dispatch('openModal');
    }

    public function updateStatus($id, $status)
    {
        $note = Note::findOrFail($id);
        $note->update(['status' => $status]);
        $this->loadNotes();
    }

    public function saveNote()
    {
        $this->validate();

        if ($this->selectedNote) {
            $note = Note::findOrFail($this->selectedNote);
            $note->update([
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'user_id' => $this->selectedUser,
            ]);

            // حذف فایل‌های انتخاب شده
            foreach ($this->deletedFiles as $fileId) {
                $file = $note->files()->find($fileId);
                if ($file) {
                    \Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }
            }
        } else {
            $note = Note::create([
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'user_id' => $this->selectedUser,
                'date' => now(),
            ]);
        }

        // ذخیره فایل‌های جدید
        foreach ($this->newFiles as $uploadedFile) {
            $path = $uploadedFile->store('note_files', 'public');
            $note->files()->create([
                'file_path' => $path,
                'file_name' => $uploadedFile->getClientOriginalName(),
            ]);
        }

        $this->resetForm();
        $this->loadNotes();
        $this->dispatch('closeModal');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->content = '';
        $this->status = 'انجام‌نشده';
        $this->selectedNote = null;
        $this->selectedUser = $this->users->first()?->id;
        $this->newFiles = [];
        $this->deletedFiles = [];
    }

    public function resetFormAndOpenModal()
    {
        $this->resetForm();
        $this->dispatch('openModal');
    }

    public function markFileDeleted($fileId)
    {
        $this->deletedFiles[] = $fileId;
    }

    public function render()
    {
        return view('livewire.note-manager', [
            'notes' => $this->notes,
            'users' => $this->users,
        ]);
    }
}
