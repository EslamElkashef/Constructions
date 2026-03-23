@extends('layouts.master')
@section('title', 'Edit Task')
@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container">
        <h3 class="mb-4">Edit Task: {{ $task->title }}</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-lg-12">
                    <label for="edit-project" class="form-label">Project</label>
                    <select id="edit-project" name="project_id" class="form-control">
                        <option value="">— NON Project —</option>
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}"
                                {{ old('project_id', $task->project_id) == $proj->id ? 'selected' : '' }}>
                                {{ $proj->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-12">
                    <label for="tasksTitle-field" class="form-label">Title</label>
                    <input type="text" name="title" id="tasksTitle-field" class="form-control" placeholder="Title"
                        required value="{{ old('title', $task->title) }}" />
                </div>

                <div class="col-lg-12">
                    <label for="clientName-field" class="form-label">Client Name</label>
                    <input type="text" name="client_name" id="clientName-field" class="form-control"
                        placeholder="Client name" required value="{{ old('client_name', $task->client_name) }}" />
                </div>

                <div class="col-lg-12">
                    <label class="form-label">Assigned To</label>
                    <div data-simplebar style="height: 150px;">
                        <ul class="list-unstyled vstack gap-2 mb-0">
                            @foreach ($users as $user)
                                <li>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" name="assignedTo[]"
                                            value="{{ $user->id }}" id="user-{{ $user->id }}"
                                            {{ $task->assignedUsers->contains($user->id) || in_array($user->id, old('assignedTo', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center"
                                            for="user-{{ $user->id }}">
                                            <span class="flex-shrink-0">
                                                <img src="{{ $user->avatar_url ?? asset('assets/images/users/default.png') }}"
                                                    alt="{{ $user->name }}" class="avatar-xxs rounded-circle">
                                            </span>
                                            <span class="flex-grow-1 ms-2">{{ $user->name }}</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <label for="duedate-field" class="form-label">Due Date</label>
                    <input type="text" name="due_date" id="duedate-field" class="form-control" data-provider="flatpickr"
                        placeholder="Due date" required value="{{ old('due_date', $task->due_date) }}" />
                </div>

                <div class="col-lg-6">
                    <label for="ticket-status" class="form-label">Status</label>
                    <select class="form-control" name="status" data-choices data-choices-search-false id="ticket-status"
                        required>
                        <option value="">Select Status</option>
                        <option value="New" {{ old('status', $task->status) == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Inprogress" {{ old('status', $task->status) == 'Inprogress' ? 'selected' : '' }}>
                            Inprogress</option>
                        <option value="Pending" {{ old('status', $task->status) == 'Pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="Completed" {{ old('status', $task->status) == 'Completed' ? 'selected' : '' }}>
                            Completed</option>
                    </select>
                </div>

                <div class="col-lg-12">
                    <label for="priority-field" class="form-label">Priority</label>
                    <select class="form-control" name="priority" data-choices data-choices-search-false id="priority-field"
                        required>
                        <option value="">Select Priority</option>
                        <option value="High" {{ old('priority', $task->priority) == 'High' ? 'selected' : '' }}>High
                        </option>
                        <option value="Medium" {{ old('priority', $task->priority) == 'Medium' ? 'selected' : '' }}>Medium
                        </option>
                        <option value="Low" {{ old('priority', $task->priority) == 'Low' ? 'selected' : '' }}>Low
                        </option>
                    </select>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-light">Back</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
