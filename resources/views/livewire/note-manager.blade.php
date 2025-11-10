<div class="container mt-4">

    <!-- دکمه افزودن یادداشت جدید -->
    <div class="mb-3">
        <button wire:click="resetFormAndOpenModal" class="btn btn-success">
            افزودن یادداشت جدید
        </button>
    </div>

    <!-- فیلتر بر اساس کاربر -->
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

    <!-- جستجوی زنده بر اساس نام نویسنده -->
    <div class="mb-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="نام نویسنده را جستجو کنید"
            class="form-control form-control-lg rounded-pill shadow-sm">
    </div>

    <h3>یادداشت‌ها:</h3>

    @if ($notes->isEmpty())
        <div class="alert alert-info text-center">
            هیچ یادداشتی برای نمایش وجود ندارد.
        </div>
    @else
        <div class="row">
            @foreach ($notes as $note)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm
                        @if ($note->status == 'انجام‌شده') bg-success text-white
                        @elseif($note->status == 'انجام‌نشده') bg-warning text-dark @endif">

                        <div class="card-body">
                            <h5 class="card-title">{{ $note->title }}</h5>

                            <h6 class="text-muted mb-2">
                                نویسنده:
                                {{ $note->user ? $note->user->first_name . ' ' . $note->user->last_name : '---' }}
                            </h6>

                            <p class="card-text">{{ Str::limit($note->content, 100) }}</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="me-2 fw-bold">وضعیت:</span>
                                    <select wire:change="updateStatus({{ $note->id }}, $event.target.value)"
                                        class="form-select form-select-sm w-auto">
                                        <option value="انجام‌نشده"
                                            {{ $note->status == 'انجام‌نشده' ? 'selected' : '' }}>انجام‌نشده</option>
                                        <option value="انجام‌شده" {{ $note->status == 'انجام‌شده' ? 'selected' : '' }}>
                                            انجام‌شده</option>
                                    </select>
                                </div>

                                <div>
                                    <button wire:click="edit({{ $note->id }})" class="btn btn-primary btn-sm">
                                        ویرایش
                                    </button>
                                    <button wire:click="delete({{ $note->id }})" class="btn btn-danger btn-sm"
                                        onclick="confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟') || event.stopImmediatePropagation()">
                                        حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Modal افزودن/ویرایش یادداشت -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true" wire:ignore.self>
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
                            <input type="text" wire:model="title" class="form-control" placeholder="عنوان یادداشت" required>
                        </div>

                        <div class="mb-3">
                            <textarea wire:model="content" class="form-control" placeholder="متن یادداشت" rows="4" required></textarea>
                        </div>

                        <!-- فایل آپلود -->
                        <div class="mb-3">
                            <input type="file" wire:model="newFiles" multiple>
                            @error('newFiles.*') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- فایل‌های موجود در صورت ویرایش -->
                        @if($selectedNote)
                            <div class="mb-3">
                                <label class="form-label">فایل‌های موجود</label>
                                <ul class="list-group">
                                    @foreach(\App\Models\Note::find($selectedNote)->files as $file)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank">
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
                                <option value="انجام‌نشده">انجام‌نشده</option>
                                <option value="انجام‌شده">انجام‌شده</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                {{ $selectedNote ? 'ذخیره تغییرات' : 'ذخیره یادداشت' }}
                            </button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary" data-bs-dismiss="modal">
                                بستن
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- کنترل مودال‌ها با Livewire -->
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
