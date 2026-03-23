@extends('layouts.master')
@section('title')
    @lang('translation.list-tasks')
@endsection

@section('css')
    {{-- SweetAlert / Flatpickr / Choices --}}
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .badge-small {
            padding: .35rem .6rem;
            border-radius: .25rem;
            color: #fff;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tasks
        @endslot
        @slot('title')
            Tasks view
        @endslot
    @endcomponent

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        {{-- Stats cards (kept as you had) --}}
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Total Tasks</p>
                        <h2 class="mt-4 ff-secondary fw-semibold">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info text-info rounded-circle fs-4">
                            <i class="ri-ticket-2-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        {{-- other stat cards... (omitted here to keep snippet short) --}}
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Pending Tasks</p>
                        <h2 class="mt-4 ff-secondary fw-semibold">{{ $stats['pending'] }}</h2>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-4">
                            <i class="mdi mdi-timer-sand"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Completed Tasks</p>
                        <h2 class="mt-4 ff-secondary fw-semibold">{{ $stats['completed'] }}</h2>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success text-success rounded-circle fs-4">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Deleted Tasks</p>
                        <h2 class="mt-4 ff-secondary fw-semibold">{{ $stats['deleted'] }}</h2>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-danger text-danger rounded-circle fs-4">
                            <i class="ri-delete-bin-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page header + create toggle --}}
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Tasks</h5>
            <button class="btn btn-danger" id="toggleCreateCardBtn">
                <i class="ri-add-line align-bottom me-1"></i> Create Task
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
                <div class="col-lg-5">
                    <input type="text" name="q" class="form-control"
                        placeholder="Search by title, client or assigned..." value="{{ request('q') }}">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="date_range" id="filter-date" class="form-control"
                        placeholder="Select date range" value="{{ request('date_range') }}">
                </div>
                <div class="col-lg-2">
                    <select name="status" class="form-control choices-single" data-choices-search-false>
                        <option value="">Status (All)</option>
                        @foreach (['New', 'Inprogress', 'Pending', 'Completed'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                {{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card" id="tasksList">
        <div class="card-body">
            <form id="bulkDeleteForm" action="{{ route('tasks.bulkDelete') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <button id="delete-selected-btn" class="btn btn-danger d-none" type="button"><i
                            class="ri-delete-bin-2-line"></i> Delete Selected</button>
                </div>

                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th style="width:40px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Client Name</th>
                                <th>Assigned To</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr id="task-row-{{ $task->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input task-checkbox" type="checkbox" name="tasks[]"
                                                value="{{ $task->id }}">
                                        </div>
                                    </td>
                                    <td>{{ $task->id }}</td>
                                    <td>{{ $task->project?->title ?? '—' }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->client_name }}</td>
                                    <td>{{ $task->assignedUsers->pluck('name')->join(', ') ?? '—' }}</td>
                                    <td>{{ optional($task->due_date)->format('Y/M/d') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $task->status == 'Completed' ? 'bg-success' : ($task->status == 'Inprogress' ? 'bg-info' : ($task->status == 'Pending' ? 'bg-warning text-dark' : 'bg-primary')) }}">
                                            {{ $task->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $task->priority == 'High' ? 'bg-danger' : ($task->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                                            {{ $task->priority ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tasks.show', $task->id) }}"
                                            class="btn btn-info btn-sm">Show</a>

                                        {{-- Edit button: set data-* attributes and open single modal --}}
                                        <button type="button" class="btn btn-sm btn-primary open-edit-modal"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $task->id }}" data-project_id="{{ $task->project_id }}"
                                            data-title="{{ $task->title }}" data-description="{{ $task->description }}"
                                            data-client_name="{{ $task->client_name }}"
                                            data-due_date="{{ optional($task->due_date)->format('Y-m-d') }}"
                                            data-status="{{ $task->status }}" data-priority="{{ $task->priority }}"
                                            data-assigned='@json($task->assignedUsers->pluck('id'))'>
                                            Edit
                                        </button>

                                        {{-- Delete single (form submits normally but we intercept to show SweetAlert confirm) --}}
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                            class="d-inline delete-form" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No tasks found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- pagination --}}
                <div class="d-flex justify-content-end">
                    {{ $tasks->withQueryString()->links() }}
                </div>
            </form>
        </div>
    </div>
    <!-- Create Task Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-soft-info">
                    <h5 class="modal-title">Create Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row g-3">
                            <!-- Project -->
                            <div class="col-lg-12">
                                <label for="create-project-field" class="form-label">Project</label>
                                <select id="create-project-field" name="project_id" class="form-control choices-single">
                                    <option value="">— NON Project —</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}"
                                            {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                            {{ $proj->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title -->
                            <div class="col-lg-12">
                                <label for="create-title-field" class="form-label">Title</label>
                                <input type="text" name="title" id="create-title-field" class="form-control"
                                    placeholder="Title" value="{{ old('title') }}" required />
                            </div>

                            <!-- Description -->
                            <div class="col-lg-12">
                                <label for="create-description-field" class="form-label">Description</label>
                                <input type="text" name="description" id="create-description-field"
                                    class="form-control" placeholder="Description" value="{{ old('description') }}" />
                            </div>

                            <!-- Client -->
                            <div class="col-lg-12">
                                <label for="create-client-field" class="form-label">Client Name</label>
                                <input type="text" id="create-client-field" name="client_name" class="form-control"
                                    placeholder="Client name" value="{{ old('client_name') }}" required />
                            </div>

                            <!-- Assigned To -->
                            <div class="col-lg-12">
                                <label class="form-label">Assigned To</label>
                                <div data-simplebar style="height: 150px;">
                                    <ul class="list-unstyled vstack gap-2 mb-0">
                                        @foreach ($users as $user)
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox"
                                                        name="assignedTo[]" value="{{ $user->id }}"
                                                        id="create-user-{{ $user->id }}"
                                                        {{ is_array(old('assignedTo')) && in_array($user->id, old('assignedTo')) ? 'checked' : '' }}>
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="create-user-{{ $user->id }}">
                                                        <span class="flex-shrink-0">
                                                            <img src="{{ $user->avatar_url ?? asset('assets/images/users/default.png') }}"
                                                                alt="{{ $user->name }}"
                                                                class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">{{ $user->name }}</span>
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-lg-6">
                                <label for="create-due-field" class="form-label">Due Date</label>
                                <input type="text" id="create-due-field" name="due_date"
                                    class="form-control flatpickr" placeholder="Due date"
                                    value="{{ old('due_date') }}" />
                            </div>

                            <!-- Status -->
                            <div class="col-lg-6">
                                <label for="create-status-field" class="form-label">Status</label>
                                <select class="form-control choices-single" name="status" id="create-status-field">
                                    <option value="">Status</option>
                                    @foreach (['New', 'Inprogress', 'Pending', 'Completed'] as $s)
                                        <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>
                                            {{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority -->
                            <div class="col-lg-12">
                                <label for="create-priority-field" class="form-label">Priority</label>
                                <select class="form-control choices-single" name="priority" id="create-priority-field">
                                    <option value="">Priority</option>
                                    @foreach (['High', 'Medium', 'Low'] as $p)
                                        <option value="{{ $p }}"
                                            {{ old('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="hstack gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Single Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-soft-info">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editTaskForm" method="POST" action="" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-id" value="">
                    <div class="modal-body">
                        <div class="row g-3">
                            {{-- Project --}}
                            <div class="col-lg-12">
                                <label class="form-label">Project</label>
                                <select name="project_id" id="edit-project" class="form-control choices-single">
                                    <option value="">— NON Project —</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Title --}}
                            <div class="col-lg-12">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="edit-title" class="form-control" required>
                            </div>

                            {{-- Description --}}
                            <div class="col-lg-12">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" id="edit-description" class="form-control" />
                            </div>

                            {{-- Client --}}
                            <div class="col-lg-12">
                                <label class="form-label">Client Name</label>
                                <input type="text" name="client_name" id="edit-client" class="form-control" required>
                            </div>

                            {{-- Assigned To --}}
                            <div class="col-lg-12">
                                <label class="form-label">Assigned To</label>
                                <div data-simplebar style="height: 150px;">
                                    <ul class="list-unstyled vstack gap-2 mb-0" id="edit-assigned-list">
                                        @foreach ($users as $user)
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3 edit-assigned-checkbox"
                                                        type="checkbox" name="assignedTo[]" value="{{ $user->id }}"
                                                        id="edit-user-{{ $user->id }}">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="edit-user-{{ $user->id }}">
                                                        <span class="flex-shrink-0">
                                                            <img src="{{ $user->avatar_url ?? asset('assets/images/users/default.png') }}"
                                                                alt="{{ $user->name }}"
                                                                class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">{{ $user->name }}</span>
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- Due Date --}}
                            <div class="col-lg-6">
                                <label class="form-label">Due Date</label>
                                <input type="text" name="due_date" id="edit-due" class="form-control flatpickr" />
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6">
                                <label class="form-label">Status</label>
                                <select name="status" id="edit-status" class="form-control choices-single">
                                    @foreach (['New', 'Inprogress', 'Pending', 'Completed'] as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Priority --}}
                            <div class="col-lg-12">
                                <label class="form-label">Priority</label>
                                <select name="priority" id="edit-priority" class="form-control choices-single">
                                    @foreach (['High', 'Medium', 'Low'] as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Initialize Flatpickr
            flatpickr(".flatpickr", {
                dateFormat: "Y-m-d"
            });

            // Initialize Choices.js and store references
            const choicesInstances = {};
            document.querySelectorAll('.choices-single').forEach(el => {
                try {
                    choicesInstances[el.id] = new Choices(el, {
                        searchEnabled: false,
                        shouldSort: false
                    });
                } catch (e) {}
            });

            // SweetAlert for delete
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This task will be deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then(res => {
                        if (res.isConfirmed) form.submit();
                    });
                });
            });

            // Toggle Create Card
            const createBtn = document.getElementById('toggleCreateCardBtn');
            const createModalEl = document.getElementById('createModal');
            const createModal = new bootstrap.Modal(createModalEl);

            createBtn.addEventListener('click', () => {
                createModal.show();
            });


            // Reopen Create Card if validation fails
            @if ($errors->any() && !old('id'))
                createCard.classList.remove('d-none');
                createCard.scrollIntoView({
                    behavior: 'smooth'
                });
            @endif

            // Open Edit Modal and populate data
            const editModalEl = document.getElementById('editModal');
            const editModal = new bootstrap.Modal(editModalEl);

            document.querySelectorAll('.open-edit-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const form = document.getElementById('editTaskForm');
                    form.action = `/tasks/${btn.dataset.id}`;
                    document.getElementById('edit-id').value = btn.dataset.id;
                    document.getElementById('edit-project').value = btn.dataset.project_id;
                    document.getElementById('edit-title').value = btn.dataset.title;
                    document.getElementById('edit-description').value = btn.dataset.description;
                    document.getElementById('edit-client').value = btn.dataset.client_name;

                    // Set due date
                    const dueInput = document.getElementById('edit-due');
                    if (dueInput._flatpickr) {
                        dueInput._flatpickr.setDate(btn.dataset.due_date || null);
                    }

                    // Set status and priority and update Choices.js
                    const statusSelect = document.getElementById('edit-status');
                    const prioritySelect = document.getElementById('edit-priority');

                    statusSelect.value = btn.dataset.status;
                    prioritySelect.value = btn.dataset.priority;

                    if (choicesInstances['edit-status']) {
                        choicesInstances['edit-status'].setChoiceByValue(btn.dataset.status);
                    }
                    if (choicesInstances['edit-priority']) {
                        choicesInstances['edit-priority'].setChoiceByValue(btn.dataset.priority);
                    }

                    // Assigned users
                    const assigned = JSON.parse(btn.dataset.assigned || '[]');
                    document.querySelectorAll('.edit-assigned-checkbox').forEach(chk => {
                        chk.checked = assigned.includes(parseInt(chk.value));
                    });

                    editModal.show();
                });
            });

        });
    </script>
@endsection
