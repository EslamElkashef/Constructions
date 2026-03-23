<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Team Members</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inviteMembersModal">
            <i class="ri-user-add-line me-1"></i> Add Team Member
        </button>
    </div>

    <div class="card-body">
        @if ($project->teamMembers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Role</th>
                            <th>Projects</th>
                            <th>Tasks</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->teamMembers as $index => $member)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="d-flex align-items-center">
                                    @if ($member->user->avatar)
                                        <img src="{{ Storage::url($member->user->avatar) }}" class="rounded-circle me-2"
                                            width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-primary text-white text-center me-2"
                                            style="width:40px;height:40px;line-height:40px;">
                                            {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $member->user->name }}</strong><br>
                                        <small class="text-muted">{{ $member->user->email }}</small>
                                    </div>
                                </td>
                                <td>{{ $member->role }}</td>
                                <td>{{ $member->projects_count }}</td>
                                <td>{{ $member->tasks_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('users.show', $member->user->id) }}"
                                        class="btn btn-light btn-sm me-2">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    <form method="POST" action="{{ route('team-members.destroy', $member->id) }}"
                                        class="d-inline delete-team-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-team-btn">
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
            <p class="text-center text-muted mb-0">No team members yet.</p>
        @endif
    </div>
</div>

{{-- Invite Member Modal --}}
<form action="{{ route('team-members.store') }}" method="POST">
    @csrf
    <input type="hidden" name="project_id" value="{{ $project->id }}">
    <div class="modal fade" id="inviteMembersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header p-3 ps-4 bg-soft-success">
                    <h5 class="modal-title">Invite Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Choose user...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" placeholder="e.g. Developer" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Member</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- SweetAlert for delete confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-team-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This team member will be removed from the project!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, remove',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

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
