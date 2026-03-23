@extends('layouts.master')

@section('title', __('translation.project-list'))

@section('content')

    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('Projects') }}
        @endslot
        @slot('title')
            {{ __('Project List') }}
        @endslot
    @endcomponent

    {{-- Top actions --}}
    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <a href="{{ route('projects.create') }}" class="btn btn-success">
                <i class="ri-add-line align-bottom me-1"></i> {{ __('Add New') }}
            </a>
        </div>

        <div class="col-sm d-flex justify-content-end">
            <form method="GET" action="{{ route('projects.index') }}" class="d-inline-flex gap-2 align-items-center">
                {{-- Search --}}
                <div class="search-box">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ __('Search...') }}">
                    <i class="ri-search-line search-icon"></i>
                </div>

                {{-- Filter --}}
                <select name="filter" class="form-select w-md" onchange="this.form.submit()">
                    <option value="" {{ request('filter') == '' ? 'selected' : '' }}>{{ __('All') }}</option>
                    <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>{{ __('Today') }}
                    </option>
                    <option value="yesterday" {{ request('filter') == 'yesterday' ? 'selected' : '' }}>
                        {{ __('Yesterday') }}</option>
                    <option value="last7" {{ request('filter') == 'last7' ? 'selected' : '' }}>{{ __('Last 7 Days') }}
                    </option>
                </select>
            </form>
        </div>
    </div>

    {{-- Projects Grid --}}
    <div class="row">
        @forelse($projects as $project)
            <div class="col-xxl-3 col-sm-6 mb-4">
                <div class="card card-height-100 h-100 shadow-sm border">
                    <div class="card-body d-flex flex-column">
                        {{-- Header --}}
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0">
                                    {{ __('Updated') }} {{ $project->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 d-flex gap-1">
                                {{-- Favourite toggle --}}
                                <form action="{{ route('projects.toggleFavourite', $project) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn avatar-xs p-0 favourite-btn">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill {{ $project->favourite ? 'text-warning' : '' }}"></i>
                                        </span>
                                    </button>
                                </form>

                                {{-- Dropdown Actions --}}
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-1 text-decoration-none fs-15" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.show', $project->id) }}">
                                                <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}">
                                                <i class="ri-pencil-fill align-bottom me-2 text-warning"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteProjectModal-{{ $project->id }}">
                                                <i class="ri-delete-bin-6-fill me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-lg">
                                    <span
                                        class="avatar-title bg-light rounded overflow-hidden d-flex align-items-center justify-content-center">
                                        <img src="{{ $project->thumbnail ? asset('storage/' . $project->thumbnail) : asset('assets/images/brands/default.png') }}"
                                            alt="{{ $project->title ?? 'Project' }}" class="w-100 h-100 object-fit-cover">
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fs-15">
                                    <a href="{{ route('projects.show', $project) }}" class="text-dark">
                                        {{ $project->name }}
                                    </a>
                                </h5>
                                <div class="text-muted text-truncate-two-lines mb-3">
                                    {{ Str::limit(strip_tags($project->description), 100) }}
                                </div>
                            </div>
                        </div>

                        {{-- Tasks progress --}}
                        <div class="mt-auto">
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1">{{ __('Tasks') }}</div>
                                <div class="flex-shrink-0">
                                    <i class="ri-list-check align-bottom me-1 text-muted"></i>
                                    {{ $project->tasks_completed }}/{{ $project->tasks_count }}
                                </div>
                            </div>
                            <div class="progress progress-sm animated-progress">
                                <div class="progress-bar bg-success" style="width: {{ $project->progress_percentage }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-transparent border-top-dashed py-2 text-end text-muted">
                        <i class="ri-calendar-event-fill me-1 align-bottom"></i>
                        {{ $project->created_at->format('d M, Y') }}
                    </div>
                </div>

                {{-- Delete Modal --}}
                <div class="modal fade" id="deleteProjectModal-{{ $project->id }}" tabindex="-1"
                    aria-labelledby="deleteProjectLabel-{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <strong>{{ $project->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <p class="text-center text-muted">{{ __('No projects found.') }}</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="row g-0 text-center text-sm-start align-items-center mb-4">
        <div class="col-sm-6">
            <p class="mb-sm-0 text-muted">
                {{ __('Showing :from to :to of :total entries', [
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem(),
                    'total' => $projects->total(),
                ]) }}
            </p>
        </div>
        <div class="col-sm-6">
            {{ $projects->links() }}
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/pages/project-list.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
@endsection
