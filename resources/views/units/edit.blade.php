<div class="container py-4">
    <h3 class="fw-bold mb-4 text-primary">
        {{ $mode === 'edit' ? 'تعديل الوحدة' : 'إضافة وحدة جديدة' }}
    </h3>

    {{-- Alert Message --}}
    @if ($message)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" wire:click="$set('message', null)"></button>
        </div>
    @endif

    {{-- Steps Header --}}
    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
        @foreach ([1 => 'البيانات الأساسية', 2 => 'تفاصيل الوحدة', 3 => 'الوسائط', 4 => 'تأكيد'] as $step => $label)
            <div class="badge {{ $currentStep >= $step ? 'bg-primary' : 'bg-light text-dark' }}">{{ $step }}
            </div>
            <span
                class="{{ $currentStep >= $step ? 'text-primary fw-semibold' : 'text-muted' }}">{{ $label }}</span>
        @endforeach
    </div>

    {{-- Step Content --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            {{-- Step 1 --}}
            @if ($currentStep == 1)
                <div class="mb-3">
                    <label class="form-label">اسم الوحدة</label>
                    <input type="text" class="form-control" wire:model.defer="name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">العنوان</label>
                    <input type="text" class="form-control" wire:model.defer="address">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الهاتف</label>
                    <input type="text" class="form-control" wire:model.defer="phone">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">نوع الوحدة</label>
                    <select class="form-select" wire:model="unit_type_id">
                        <option value="">اختر النوع</option>
                        @foreach ($unitTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name_ar ?? $type->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_type_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" wire:model.defer="status">
                        <option value="available">متاحة</option>
                        <option value="reserved">محجوزة</option>
                        <option value="sold">مباعة</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الموظف المسؤول</label>
                    <input type="text" class="form-control" value="{{ optional(auth()->user())->name }}" disabled>
                </div>
            @endif

            {{-- Step 2 --}}
            @if ($currentStep == 2)
                @if (!empty($typeFields))
                    <div class="row g-3">
                        @foreach ($typeFields as $field)
                            <div class="col-md-6">
                                <label class="form-label">{{ $field['label'] ?? $field['name'] }}</label>

                                @if ($field['type'] === 'text')
                                    <input type="text" class="form-control"
                                        wire:model.defer="details.{{ $field['name'] }}">
                                @elseif($field['type'] === 'number')
                                    <input type="number" class="form-control"
                                        wire:model.defer="details.{{ $field['name'] }}">
                                @elseif($field['type'] === 'checkbox')
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                            wire:model.defer="details.{{ $field['name'] }}">
                                        <label class="form-check-label">{{ $field['label'] ?? $field['name'] }}</label>
                                    </div>
                                @endif

                                @error('details.' . $field['name'])
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted">لا توجد تفاصيل لهذا النوع بعد.</div>
                @endif
            @endif

            {{-- Step 3 --}}
            @if ($currentStep == 3)
                <div class="mb-3">
                    <label class="form-label">صور الوحدة</label>
                    <input type="file" wire:model="images" multiple class="form-control">
                    @error('images.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    {{-- الصور الحالية --}}
                    @if ($mode === 'edit' && $unitId)
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach (\App\Models\Unit::find($unitId)->media()->where('type', 'image')->get() as $img)
                                <img src="{{ asset('storage/' . $img->path) }}" class="rounded shadow-sm"
                                    style="width:100px;height:100px;object-fit:cover;">
                            @endforeach
                        </div>
                    @endif

                    {{-- معاينة الصور الجديدة --}}
                    @if ($images)
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach ($images as $img)
                                <img src="{{ $img->temporaryUrl() }}" class="rounded shadow-sm"
                                    style="width:100px;height:100px;object-fit:cover;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">فيديوهات الوحدة</label>
                    <input type="file" wire:model="videos" multiple class="form-control">
                    @error('videos.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    {{-- الفيديوهات الحالية --}}
                    @if ($mode === 'edit' && $unitId)
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach (\App\Models\Unit::find($unitId)->media()->where('type', 'video')->get() as $video)
                                <video width="150" controls class="rounded shadow-sm">
                                    <source src="{{ asset('storage/' . $video->path) }}">
                                </video>
                            @endforeach
                        </div>
                    @endif

                    {{-- معاينة الفيديوهات الجديدة --}}
                    @if ($videos)
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach ($videos as $video)
                                <video width="150" controls class="rounded shadow-sm">
                                    <source src="{{ $video->temporaryUrl() }}">
                                </video>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Step 4 --}}
            @if ($currentStep == 4)
                <div class="alert alert-info">
                    اضغط على زر "{{ $mode === 'edit' ? 'تحديث الوحدة' : 'إنشاء الوحدة' }}" لإتمام العملية.
                </div>
            @endif
        </div>
    </div>

    {{-- Navigation Buttons --}}
    <div class="d-flex justify-content-between mt-3">
        @if ($currentStep > 1)
            <button class="btn btn-outline-secondary" wire:click="previousStep">
                <i class="bi bi-arrow-left"></i> رجوع
            </button>
        @else
            <div></div>
        @endif

        @if ($currentStep < 4)
            <button class="btn btn-primary ms-auto" wire:click="nextStep">
                التالي <i class="bi bi-arrow-right"></i>
            </button>
        @else
            <button class="btn btn-success ms-auto" wire:click="submit">
                {{ $mode === 'edit' ? 'تحديث الوحدة' : 'إنشاء الوحدة' }}
            </button>
        @endif
    </div>
</div>

{{-- SweetAlert --}}
<script>
    window.addEventListener('unit-saved', event => {
        Swal.fire({
            icon: 'success',
            title: event.detail.message,
            showConfirmButton: false,
            timer: 1500
        });
    });
</script>

<style>
    .badge {
        min-width: 28px;
        text-align: center;
    }

    .card {
        border-radius: 12px;
    }

    input[type=file] {
        cursor: pointer;
    }
</style>
