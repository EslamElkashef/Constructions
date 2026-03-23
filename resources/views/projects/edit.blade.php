@extends('layouts.master')
@section('title', __('translation.edit-project'))

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Project
        @endslot
        @slot('title')
            Edit Project
        @endslot
    @endcomponent

    <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- ===== Left Column (8) ===== --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" name="title" value="{{ old('title', $project->title) }}"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Enter project title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thumbnail --}}
                        <div class="mb-3">
                            <label class="form-label">Thumbnail Image</label>
                            @if ($project->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="thumbnail"
                                        class="img-thumbnail" style="max-height:120px">
                                </div>
                            @endif
                            <input type="file" name="thumbnail"
                                class="form-control @error('thumbnail') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/gif">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                placeholder="Enter description">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority / Status / Deadline --}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                        @foreach (['High', 'Medium', 'Low'] as $p)
                                            <option value="{{ $p }}"
                                                {{ old('priority', $project->priority) == $p ? 'selected' : '' }}>
                                                {{ $p }}</option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        @foreach (['Inprogress', 'Completed'] as $s)
                                            <option value="{{ $s }}"
                                                {{ old('status', $project->status) == $s ? 'selected' : '' }}>
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">Deadline</label>
                                <input type="text" name="deadline" value="{{ old('deadline', $project->deadline) }}"
                                    class="form-control @error('deadline') is-invalid @enderror" data-provider="flatpickr">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Right Column (4) ===== --}}
            <div class="col-lg-4">
                {{-- Attached Files --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attached Files</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($project->attached_files))
                            <ul class="list-group mb-3">
                                @foreach ($project->attached_files as $index => $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-file-3-line me-2 text-muted fs-5"></i>
                                            <a href="{{ asset('storage/' . ($file['path'] ?? $file)) }}" target="_blank"
                                                class="text-decoration-none">
                                                {{ $file['original_name'] ?? basename($file['path'] ?? $file) }}
                                            </a>
                                        </div>

                                        {{-- زرار الحذف مع SweetAlert --}}
                                        <form
                                            action="{{ route('projects.files.destroy', ['project' => $project->id, 'fileIndex' => $index]) }}"
                                            method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-item"
                                                title="Delete File">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No files attached.</p>
                        @endif

                        <label class="form-label">Add more files</label>
                        <input type="file" name="attached_files[]"
                            class="form-control @error('attached_files.*') is-invalid @enderror" multiple>
                        @error('attached_files.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                {{-- Privacy / Category / Skills --}}
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Privacy</label>
                            <select name="privacy" class="form-select @error('privacy') is-invalid @enderror">
                                @foreach (['Private', 'Team', 'Public'] as $pr)
                                    <option value="{{ $pr }}"
                                        {{ old('privacy', $project->privacy) == $pr ? 'selected' : '' }}>
                                        {{ $pr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('privacy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="choices-categories-input" class="form-label">Categories</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror"
                                id="choices-categories-input" data-choices data-choices-search-false>
                                <option value="Designing"
                                    {{ old('category', $project->category) == 'Designing' ? 'selected' : '' }}>
                                    Designing
                                </option>
                                <option value="Development"
                                    {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>
                                    Development
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div>
                            <label class="form-label">Skills</label>
                            <input type="text" name="skills" value="{{ old('skills', $project->skills) }}"
                                class="form-control @error('skills') is-invalid @enderror">
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Budget for expenses-->
                <div class="mb-3">
                    <label for="budget" class="form-label">Budget</label>
                    <input type="number" step="0.01" name="budget" id="budget"
                        class="form-control @error('budget') is-invalid @enderror"
                        value="{{ old('budget', $project->budget) }}">
                    @error('budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 text-end mt-3 mb-4">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>

    </form>
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
