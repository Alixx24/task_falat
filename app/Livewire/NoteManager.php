<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\User;
use Livewire\Component;

class NoteManager extends Component
{
    public $notes;
    public $title;
    public $content;
    public $status = 'انجام‌نشده';
    public $selectedNote = null;
    public $selectedUser = null;
    public $users;
    public $isModalOpen = false;

    public function mount()
    {
        $this->users = User::all();
        $this->notes = Note::all();
    }

    public function updatedSelectedUser($value)
    {
        if ($value) {
            $this->notes = Note::where('user_id', $value)->get();
        } else {
            $this->notes = Note::all();
        }
    }

    public function delete($id)
    {
        Note::find($id)->delete();
        $this->notes = Note::where('user_id', $this->selectedUser)->get();
        $this->dispatch('modalClosed');
    }

    public function edit($id)
    {
        $note = Note::find($id);
        $this->selectedNote = $note->id;
        $this->title = $note->title;
        $this->content = $note->content;
        $this->status = $note->status;
        $this->dispatch('openModal');
    }

    public function updateStatus($id, $status)
    {
        $note = Note::find($id);
        $note->update(['status' => $status]);
        $this->notes = Note::where('user_id', $this->selectedUser)->get();
    }

    public function saveNote()
    {
        if ($this->selectedNote) {
            $note = Note::find($this->selectedNote);
            $note->update([
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'user_id' => $this->selectedUser,
            ]);
        } else {
            Note::create([
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'user_id' => $this->selectedUser,
            ]);
        }
        $this->notes = Note::where('user_id', $this->selectedUser)->get();
        $this->dispatch('closeModal');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->content = '';
        $this->status = 'انجام‌نشده';
        $this->selectedNote = null;
    }

    // متد جدید برای باز کردن مودال و ریست کردن فرم
    public function resetFormAndOpenModal()
    {
        $this->resetForm();
        $this->dispatch('openModal');  // ارسال رویداد برای باز کردن مودال
    }

    public function render()
    {
        return view('livewire.note-manager');
    }
}
