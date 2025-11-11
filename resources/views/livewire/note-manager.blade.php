<div class="container mt-4">


<div class="mb-3 d-flex gap-2 align-items-center">
 
    <button wire:click="resetFormAndOpenModal" class="btn btn-success bg-dark">
   <i class="bi bi-plus-circle-dotted fs-2"></i>
    </button>

    <!-- search -->
    <input type="text" 
           wire:model.live.debounce.300ms="search" 
           placeholder="عنوان را جستجو کنید"
           class="form-control form-control-lg rounded-pill shadow-sm flex-grow-1">
</div>

    <!-- select name -->
    <div class="mb-3">
        <label for="userSelect" class="form-label">یک کاربر را انتخاب کنید</label>
        <select wire:model="selectedUser" id="userSelect" class="form-select">
            <option value="">نمایش همه کاربران</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>
    </div>

  
    <select wire:model.number="sortStatus" wire:change="loadNotes" class="form-select">
        <option value="">همه</option>
        <option value="2">انجام‌نشده</option>
        <option value="1">انجام‌شده</option>
    </select>
    <br>

    @if ($notes->isEmpty())
        <div class="alert alert-info text-center">
            هیچ یادداشتی برای نمایش وجود ندارد.
        </div>
    @else
        <div class="row">
            @foreach ($notes as $note)
                <div class="col-md-4 mb-3">
                    <div
                        class="card shadow-sm
                @if ($note->status == '1') bg-success text-white
                @elseif($note->status == '2') bg-warning text-dark @endif">

                        <div class="card-body position-relative">
                            <h5 class="card-title">{{ $note->title }}</h5>

                            <h6 class="text-muted mb-2">
                                نویسنده:
                                {{ $note->user ? $note->user->first_name . ' ' . $note->user->last_name : '---' }}
                            </h6>

                            <p class="card-text">{{ Str::limit($note->content, 100) }}</p>





                            <div class="w-100 mb-2">
                                <hr class="border-dark">
                            </div>

                            <div>
                                @if ($note->files->isNotEmpty())
                                    <div class="mb-2">

                                        <ul class="list-unstyled mb-0">
                                            @foreach ($note->files as $file)
                                                <li>
                                                    {{ $file->file_name }}
                                                    ({{ number_format($file->file_size / 1024 / 1024, 2) }} MB)
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <!-- edit -->
                                <button wire:click="edit({{ $note->id }})"
                                    class="btn btn-sm bg-white text-dark border rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:40px; height:40px; padding:0;">
                                    <i class="bi bi-pen"></i>
                                </button>

                                <!-- delete -->
                                <button wire:click="delete({{ $note->id }})"
                                    class="btn btn-sm bg-white text-dark border rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:40px; height:40px; padding:0;"
                                    onclick="confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟') || event.stopImmediatePropagation()">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

    <!-- modal /edit -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">
                        {{ $selectedNote ? 'ویرایش یادداشت' : 'افزودن یادداشت جدید' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="saveNote" enctype="multipart/form-data">

                        <div class="mb-3">
                            <input type="text" wire:model="title" class="form-control" placeholder="عنوان یادداشت"
                                required>
                        </div>

                        <div class="mb-3">
                            <textarea wire:model="content" class="form-control" placeholder="متن یادداشت" rows="4" required></textarea>
                        </div>

                        <!-- upload -->
                        <div class="mb-3">
                            <input type="file" wire:model="newFiles" multiple>
                            @error('newFiles.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Files available for editing -->

                        @if ($selectedNote)
                            <div class="mb-3">
                                <label class="form-label">فایل‌های موجود</label>
                                <ul class="list-group">
                                    @foreach (\App\Models\Note::find($selectedNote)->files as $file)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                                                {{ $file->file_name }}
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                wire:click="markFileDeleted({{ $file->id }})">
                                                حذف
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="status" class="form-label">وضعیت</label>
                            <select wire:model="status" id="status" class="form-select">
                                <option value="2">انجام‌نشده</option>
                                <option value="1">انجام‌شده</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                {{ $selectedNote ? 'ذخیره تغییرات' : 'ذخیره یادداشت' }}
                            </button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                بستن
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('openModal', () => {
            const modalEl = document.getElementById('noteModal');
            if (modalEl) {
                new bootstrap.Modal(modalEl).show();
            }
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('noteModal');
            if (modalEl) {
                const myModal = bootstrap.Modal.getInstance(modalEl);
                if (myModal) myModal.hide();
            }
        });
    });
</script>
