<div class="container mt-4">
    <div class="mb-3">
        <!-- دکمه برای باز کردن مودال و ریست فرم -->
        <button wire:click="resetFormAndOpenModal" class="btn btn-success">افزودن یادداشت جدید</button>
    </div>

    <div class="mb-3">
        <label for="userSelect" class="form-label">یک کاربر را انتخاب کنید</label>
        <select wire:model="selectedUser" id="userSelect" class="form-select">
            <option value="">یک کاربر را انتخاب کنید</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>
    </div>

    <h3>یادداشت‌ها:</h3>
    @if($notes->isEmpty())
        <div class="alert alert-info">هیچ یادداشتی برای این کاربر وجود ندارد.</div>
    @else
        <div class="row">
            @foreach ($notes as $note)
                <div class="col-md-4 mb-3">
                    <div class="card @if($note->status == 'انجام‌شده') bg-success text-white @elseif($note->status == 'انجام‌نشده') bg-warning text-dark @endif">
                        <div class="card-body">
                            <h5 class="card-title">{{ $note->title }}</h5>
                            <p class="card-text">{{ Str::limit($note->content, 100) }}</p>
                            <span class="badge bg-info">{{ $note->status }}</span>
                            <div class="d-flex justify-content-between mt-3">
                                <select wire:change="updateStatus({{ $note->id }}, $event.target.value)" wire:model="status" class="form-select form-select-sm w-auto">
                                    <option value="انجام‌نشده" {{ $note->status == 'انجام‌نشده' ? 'selected' : '' }}>انجام‌نشده</option>
                                    <option value="انجام‌شده" {{ $note->status == 'انجام‌شده' ? 'selected' : '' }}>انجام‌شده</option>
                                </select>
                                <div>
                                    <button wire:click="edit({{ $note->id }})" class="btn btn-primary btn-sm">ویرایش</button>
                                    <button wire:click="delete({{ $note->id }})" class="btn btn-danger btn-sm">حذف</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">{{ $selectedNote ? 'ویرایش یادداشت' : 'افزودن یادداشت جدید' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveNote">
                        <div class="mb-3">
                            <input type="text" wire:model="title" class="form-control" placeholder="عنوان یادداشت" required>
                        </div>
                        <div class="mb-3">
                            <textarea wire:model="content" class="form-control" placeholder="متن یادداشت" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <select wire:model="status" class="form-select">
                                <option value="انجام‌شده">انجام‌شده</option>
                                <option value="انجام‌نشده">انجام‌نشده</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ $selectedNote ? 'ویرایش یادداشت' : 'ذخیره یادداشت' }}</button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('openModal', () => {
            var myModal = new bootstrap.Modal(document.getElementById('noteModal'));
            myModal.show();
        });

        Livewire.on('closeModal', () => {
            var myModal = new bootstrap.Modal(document.getElementById('noteModal'));
            myModal.hide();
        });
    });
</script>
