@extends('layouts.master')
@section('title', __('translation.create-project'))
@section('css')
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Project
        @endslot
        @slot('title')
            Create Project
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            {{-- ====== Form Start ====== --}}
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-body">

                        {{-- Project Title --}}
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Project Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="project-title-input" name="title" value="{{ old('title') }}"
                                placeholder="Enter project title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thumbnail --}}
                        <div class="mb-3">
                            <label class="form-label" for="project-thumbnail-img">Thumbnail Image</label>
                            <input class="form-control @error('thumbnail') is-invalid @enderror" id="project-thumbnail-img"
                                type="file" name="thumbnail" accept="image/png,image/jpeg,image/gif">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Project Description</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Enter description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority / Status / Deadline --}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3 mb-lg-0">
                                    <label for="choices-priority-input" class="form-label">Priority</label>
                                    <select name="priority" class="form-select @error('priority') is-invalid @enderror"
                                        id="choices-priority-input">
                                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High
                                        </option>
                                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 mb-lg-0">
                                    <label for="choices-status-input" class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        id="choices-status-input">
                                        <option value="Inprogress" {{ old('status') == 'Inprogress' ? 'selected' : '' }}>
                                            Inprogress</option>
                                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="datepicker-deadline-input" class="form-label">Deadline</label>
                                <input type="text" class="form-control @error('deadline') is-invalid @enderror"
                                    name="deadline" id="datepicker-deadline-input" placeholder="Select deadline">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Attached Files --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attached files</h5>
                    </div>
                    <div class="card-body">
                        <input type="file" name="attached_files[]"
                            class="form-control @error('attached_files.*') is-invalid @enderror" multiple>
                        @error('attached_files.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if (!empty($project->attached_files) && is_array($project->attached_files))
                            @foreach ($project->attached_files as $file)
                                <li>{{ basename($file) }}</li>
                            @endforeach
                        @endif

                    </div>
                </div>

                {{-- Submit --}}
                <div class="text-end mb-4">
                    <a href="{{ route('projects.index') }}" class="btn btn-danger w-sm">Cancel</a>
                    <button type="submit" class="btn btn-success w-sm">Create</button>
                </div>
            </form>
            {{-- ====== Form End ====== --}}
        </div>
        <!-- end col -->
        <div class="col-lg-4">

            {{-- ===== Privacy ===== --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Privacy</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label for="choices-privacy-status-input" class="form-label">Status</label>
                        <select name="privacy_status" class="form-select @error('privacy_status') is-invalid @enderror"
                            id="choices-privacy-status-input" data-choices data-choices-search-false>
                            <option value="Private" {{ old('privacy_status') == 'Private' ? 'selected' : '' }}>Private
                            </option>
                            <option value="Team" {{ old('privacy_status') == 'Team' ? 'selected' : '' }}>Team</option>
                            <option value="Public" {{ old('privacy_status', 'public') == 'Public' ? 'selected' : '' }}>
                                Public
                            </option>
                        </select>
                        @error('privacy_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- end Privacy card -->

            {{-- ===== Tags ===== --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tags</h5>
                </div>
                <div class="card-body">

                    {{-- Categories --}}
                    <div class="mb-3">
                        <label for="choices-categories-input" class="form-label">Categories</label>
                        <select name="categories" class="form-select @error('categories') is-invalid @enderror"
                            id="choices-categories-input" data-choices data-choices-search-false>
                            <option value="Real Estate Development"
                                {{ old('categories') == 'Real Estate Development' ? 'selected' : '' }}>Real Estate
                                Development</option>
                            <option value="Real Estate Marketing"
                                {{ old('categories') == 'Real Estate Marketing' ? 'selected' : '' }}>Real Estate Marketing
                            </option>
                            <option value="Contracting" {{ old('categories') == 'Contracting' ? 'selected' : '' }}>
                                Contracting</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Skills --}}
                    <div>
                        <label for="choices-text-input" class="form-label">Skills</label>
                        <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror"
                            id="choices-text-input" data-choices data-choices-limit="Required Limit"
                            placeholder="Enter Skills"
                            value="{{ old('skills', 'UI/UX, Figma, HTML, CSS, Javascript, C#, Nodejs') }}">
                        @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- end Tags card -->
            <!-- Budget for expenses-->
            <div class="mb-3">
                <label for="budget" class="form-label">Budget</label>
                <input type="number" step="0.01" name="budget" id="budget"
                    class="form-control @error('budget') is-invalid @enderror" value="{{ old('budget') }}">

                @error('budget')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


        </div>
        <!-- end card -->
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/project-create.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => console.error(error));
    </script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#datepicker-deadline-input", {
                altInput: true, // عرض أنيق
                altFormat: "F j, Y", // مثل: October 5, 2025
                dateFormat: "Y-m-d", // القيمة المحفوظة في قاعدة البيانات
                minDate: "today", // يمنع اختيار تاريخ قديم
                allowInput: true,
                disableMobile: true, // يخلي التقويم يظهر في الموبايل
                locale: {
                    firstDayOfWeek: 0 // يبدأ الأسبوع من الأحد
                }
            });
        });
    </script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
