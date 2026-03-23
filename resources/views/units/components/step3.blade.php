<div>
    <div class="card mb-4 border-0 shadow-lg rounded-3 overflow-hidden">
        <div class="card-header bg-gradient bg-success text-white py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-images me-2"></i>
                Step 3: Media Uploads
            </h5>
        </div>

        <div class="card-body bg-light">

            {{-- رفع الصور --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-card-image me-1 text-success"></i> صور الوحدة
                </label>
                <input type="file" class="form-control border-success shadow-sm" wire:model="images" multiple
                    accept="image/*">
                @error('images.*')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- عرض الصور --}}
            @if ($images)
                <div class="d-flex flex-wrap gap-3 mb-4">
                    @foreach ($images as $i => $img)
                        <div class="position-relative border rounded shadow-sm overflow-hidden" style="width: 120px;">
                            <img src="{{ $img->temporaryUrl() }}" class="img-fluid" alt="Preview">
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                wire:click.prevent="removeImage({{ $i }})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- رفع الفيديوهات --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-camera-reels me-1 text-success"></i> فيديوهات الوحدة (MP4 أو QuickTime)
                </label>
                <input type="file" class="form-control border-success shadow-sm" wire:model="videos" multiple
                    accept="video/*">
                @error('videos.*')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- عرض الفيديوهات --}}
            @if ($videos)
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($videos as $i => $v)
                        <div class="position-relative rounded shadow-sm border overflow-hidden" style="width: 220px;">
                            <video width="220" controls class="rounded">
                                <source src="{{ $v->temporaryUrl() }}" type="{{ $v->getMimeType() }}">
                            </video>
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                wire:click.prevent="removeVideo({{ $i }})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
