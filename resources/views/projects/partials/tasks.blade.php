<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tasks</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="ri-add-line me-1"></i> Add Task
        </button>
    </div>

    <div class="card-body">
        @if ($project->tasks->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Assigned To</th>
                            <th>Client</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->tasks as $index => $task)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $task->title }}</td>

                                <td>
                                    @if ($task->assignedUsers->count())
                                        {{ $task->assignedUsers->pluck('name')->join(', ') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>{{ $task->client_name ?? '—' }}</td>

                                <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '—' }}
                                </td>

                                <td>
                                    @switch($task->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @break

                                        @case('in_progress')
                                            <span class="badge bg-info text-dark">In Progress</span>
                                        @break

                                        @case('completed')
                                            <span class="badge bg-success">Completed</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">—</span>
                                    @endswitch
                                </td>

                                <td>
                                    @switch($task->priority)
                                        @case('high')
                                            <span class="badge bg-danger">High</span>
                                        @break

                                        @case('medium')
                                            <span class="badge bg-warning text-dark">Medium</span>
                                        @break

                                        @case('low')
                                            <span class="badge bg-success">Low</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">—</span>
                                    @endswitch
                                </td>

                                <td class="text-end">
                                    <button class="btn btn-light btn-sm me-2 editTaskBtn" data-id="{{ $task->id }}"
                                        data-title="{{ $task->title }}" data-desc="{{ $task->description }}"
                                        data-client="{{ $task->client_name }}" data-due="{{ $task->due_date }}"
                                        data-status="{{ $task->status }}" data-priority="{{ $task->priority }}"
                                        data-assigned='@json($task->assignedUsers->pluck('id'))' data-bs-toggle="modal"
                                        data-bs-target="#editTaskModal">
                                        <i class="ri-edit-line"></i>
                                    </button>

                                    <form method="POST"
                                        action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}"
                                        class="d-inline delete-task-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-task-btn">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted mb-0">No tasks found.</p>
        @endif
    </div>
</div>

{{-- ========================= Create Task Modal ========================= --}}
<form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
    @csrf
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header p-3 ps-4 bg-soft-success">
                    <h5 class="modal-title">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_users[]" class="form-select" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <input type="text" name="client_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- ========================= Edit Task Modal ========================= --}}
<form id="editTaskForm" method="POST">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">

                <div class="modal-header p-3 ps-4 bg-soft-primary">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input id="editTitle" type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select id="editAssigned" name="assigned_users[]" class="form-select" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <input id="editClient" type="text" name="client_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input id="editDue" type="date" name="due_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select id="editPriority" name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="editStatus" name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="editDesc" name="description" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>

            </div>
        </div>
    </div>
</form>

{{-- ========================= JS ========================= --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ===================== Delete Task =====================
            document.querySelectorAll(".delete-task-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This task will be deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Yes, delete"
                    }).then(res => {
                        if (res.isConfirmed) form.submit();
                    });
                });
            });

            // ===================== Edit Task =====================
            document.querySelectorAll(".editTaskBtn").forEach(btn => {
                btn.addEventListener("click", function() {

                    let id = this.dataset.id;

                    document.getElementById("editTitle").value = this.dataset.title;
                    document.getElementById("editDesc").value = this.dataset.desc;
                    document.getElementById("editClient").value = this.dataset.client;
                    document.getElementById("editDue").value = this.dataset.due;
                    document.getElementById("editPriority").value = this.dataset.priority;
                    document.getElementById("editStatus").value = this.dataset.status;

                    let assigned = JSON.parse(this.dataset.assigned);
                    let editSelect = document.getElementById("editAssigned");

                    [...editSelect.options].forEach(opt => {
                        opt.selected = assigned.includes(Number(opt.value));
                    });

                    let action = `/projects/{{ $project->id }}/tasks/${id}`;
                    document.getElementById("editTaskForm").action = action;

                });
            });

        });
    </script>
@endpush
