<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activities</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createActivityModal">
            <i class="ri-add-line"></i> Add Activity
        </button>
    </div>

    <div class="card-body">
        @forelse($project->activities as $activity)
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">

                    {{-- Header --}}
                    <div class="d-flex align-items-center mb-2">
                        @if ($activity->user && $activity->user->avatar)
                            <img src="{{ asset('storage/' . $activity->user->avatar) }}" class="rounded-circle me-2"
                                width="40" height="40">
                        @else
                            <div class="rounded-circle bg-primary text-white text-center me-2"
                                style="width:40px;height:40px;line-height:40px;">
                                {{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <strong>{{ $activity->user->name ?? 'System' }}</strong><br>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>

                        {{-- Status Badge --}}
                        <span
                            class="badge ms-auto {{ $activity->status == 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $activity->status }}
                        </span>

                        {{-- 3-dots menu --}}
                        <div class="ms-2 dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                        data-bs-target="#editActivityModal{{ $activity->id }}">
                                        <i class="ri-edit-2-line me-2 text-primary"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <form
                                        action="{{ route('projects.activities.destroy', [$project->id, $activity->id]) }}"
                                        method="POST" class="delete-activity-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="dropdown-item d-flex align-items-center text-danger delete-activity-btn">
                                            <i class="ri-delete-bin-6-line me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Description --}}
                    <p class="mb-3">{{ $activity->description }}</p>

                    {{-- Replies --}}
                    @if ($activity->comments->count() > 0)
                        <div class="ms-5">
                            @foreach ($activity->comments as $comment)
                                <div class="d-flex mb-2">
                                    <div class="rounded-circle bg-secondary text-white text-center me-2"
                                        style="width:32px;height:32px;line-height:32px;">
                                        {{ strtoupper(substr($comment->user->name ?? 'S', 0, 1)) }}
                                    </div>

                                    <div class="bg-light p-2 rounded flex-grow-1 position-relative">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $comment->user->name ?? 'System' }}</strong><br>
                                                <small>{{ $comment->body }}</small><br>
                                                <small
                                                    class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>

                                            {{-- 3-dots menu --}}
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item d-flex align-items-center"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editCommentModal{{ $comment->id }}">
                                                            <i class="ri-edit-2-line me-2 text-primary"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('comments.destroy', $comment->id) }}"
                                                            method="POST" class="delete-comment-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="dropdown-item d-flex align-items-center text-danger delete-comment-btn">
                                                                <i class="ri-delete-bin-6-line me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Comment Modal -->
                                <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Reply</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <textarea name="body" class="form-control" required>{{ $comment->body }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Reply Form --}}
                    <form action="{{ route('activities.comments.store', $activity->id) }}" method="POST"
                        class="ms-5 mt-2">
                        @csrf
                        <div class="d-flex align-items-center">
                            <input type="text" name="body" class="form-control form-control-sm me-2 rounded-pill"
                                placeholder="Write a comment..." required>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Post</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Activity Modal -->
            <div class="modal fade" id="editActivityModal{{ $activity->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('projects.activities.update', [$project->id, $activity->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Activity</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" required>{{ $activity->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="In Progress"
                                            {{ $activity->status == 'In Progress' ? 'selected' : '' }}>In Progress
                                        </option>
                                        <option value="Completed"
                                            {{ $activity->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No activities yet.</p>
        @endforelse
    </div>
</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="createActivityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('projects.activities.store', $project->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // حذف النشاط
        document.querySelectorAll('.delete-activity-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This activity will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // حذف الرد
        document.querySelectorAll('.delete-comment-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This reply will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // رسالة نجاح بعد الحذف
        @if (session('deleted'))
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: '{{ session('deleted') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    });
</script>
