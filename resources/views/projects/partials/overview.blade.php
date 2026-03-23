<div class="row">
    {{-- ========= العمود الرئيسي ========= --}}
    <div class="col-lg-8">
        {{-- ===== Summary ===== --}}
        <div class="card mb-4">
            <div class="card-body text-muted">
                <h6 class="mb-3 fw-semibold text-uppercase">Summary</h6>
                <p>{!! $project->description !!}</p>

                {{-- معلومات إضافية --}}
                <div class="pt-3 border-top border-top-dashed mt-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-2 text-uppercase fw-medium">Create Date :</p>
                            <h6 class="mb-0">{{ $project->created_at->format('d M Y') }}</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-2 text-uppercase fw-medium">Deadline :</p>
                            <h6 class="mb-0">{{ optional($project->deadline)->format('d M Y') }}</h6>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <p class="mb-2 text-uppercase fw-medium">Priority :</p>
                            <span
                                class="badge bg-{{ $project->priority == 'High' ? 'danger' : ($project->priority == 'Medium' ? 'warning' : 'success') }}">
                                {{ $project->priority }}
                            </span>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <p class="mb-2 text-uppercase fw-medium">Status :</p>
                            <span class="badge bg-{{ $project->status == 'Completed' ? 'success' : 'warning' }}">
                                {{ $project->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Activities ===== --}}
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3 fw-semibold text-uppercase d-flex justify-content-between align-items-center">
                    Activities
                    <div class="dropdown">
                        <a href="#" class="text-muted dropdown-toggle" data-bs-toggle="dropdown">
                            Recent
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Recent</a></li>
                            <li><a class="dropdown-item" href="#">Most Important</a></li>
                            <li><a class="dropdown-item" href="#">Older</a></li>
                        </ul>
                    </div>
                </h6>

                @forelse ($project->activities as $activity)
                    <div class="mb-3 border-bottom pb-2">
                        <strong>{{ $activity->user->name ?? 'System' }}</strong>
                        <p class="mb-1">{{ $activity->description }}</p>
                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No activities yet.</p>
                @endforelse
            </div>
        </div>
    </div> {{-- نهاية العمود الرئيسي --}}

    {{-- ========= العمود الجانبي (يمين) ========= --}}
    <div class="col-lg-4">
        {{-- ***** Expenses ***** --}}
        <div class="card mb-3">
            <div class="card-body">
                <h4>Project Expenses</h4>
                <p><strong>Budget:</strong> {{ number_format($project->budget, 2) }}</p>
                <p><strong>Total Expenses:</strong> {{ number_format($project->generalExpenses->sum('amount'), 2) }}
                </p>
                <p><strong>Remaining:</strong>
                    {{ number_format($project->budget - $project->generalExpenses->sum('amount'), 2) }}
                </p>
            </div>
        </div>

        {{-- ***** Team Members ***** --}}
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="mb-3 fw-semibold text-uppercase d-flex justify-content-between align-items-center">
                    Team Members
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#inviteMemberModal">
                        <i class="ri-user-add-line"></i> Invite
                    </button>
                </h6>

                <div class="d-flex flex-wrap gap-3">
                    @forelse($project->teamMembers as $member)
                        <div class="text-center">
                            @if ($member->user->avatar)
                                <img src="{{ asset('storage/' . $member->user->avatar) }}"
                                    class="rounded-circle img-fluid avatar-sm mb-1" alt="{{ $member->user->name }}">
                            @else
                                <div
                                    class="avatar-sm rounded-circle bg-soft-primary text-primary d-flex justify-content-center align-items-center mb-1">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <small>{{ $member->user->name }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No members yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ***** Attached Files ***** --}}
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3 fw-semibold text-uppercase d-flex justify-content-between align-items-center">
                    Files
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadFilesModal">
                        <i class="ri-upload-2-line"></i> Upload
                    </button>
                </h6>

                @foreach ($project->attached_files ?? [] as $index => $file)
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            @php
                                $ext = pathinfo($file['original_name'], PATHINFO_EXTENSION);
                            @endphp

                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ Storage::url($file['path']) }}" alt="{{ $file['original_name'] }}"
                                    class="me-2 rounded" style="width:40px;height:40px;object-fit:cover;">
                            @else
                                <i class="ri-file-line fs-20 me-2 text-primary"></i>
                            @endif

                            <span>{{ $file['original_name'] }}</span>
                            <small class="ms-2 text-muted">
                                ({{ number_format($file['size'] / 1024, 1) }} KB)
                            </small>
                        </div>

                        <div class="d-flex align-items-center">
                            <a href="{{ Storage::url($file['path']) }}" download class="text-success me-2">
                                <i class="ri-download-2-line fs-18"></i>
                            </a>

                            <div class="dropdown">
                                <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                    <i class="ri-more-2-fill"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-edit-2-line me-2 text-warning"></i> Rename
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('projects.files.destroy', [$project, $index]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this file?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-delete-bin-5-fill me-2 text-danger"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- View More Button --}}
                @if (count($files ?? []) > 4)
                    <div class="mt-3 text-center">
                        <a href="#" class="btn btn-primary btn-sm">View more</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> {{-- نهاية row --}}

{{-- ===== Invite Member Modal ===== --}}
<div class="modal fade" id="inviteMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.members.add', $project) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Invite Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <select name="user_id" class="form-select" required>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Upload Files Modal ===== --}}
<div class="modal fade" id="uploadFilesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.files.upload', $project) }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label for="attached_files" class="form-label">Select Files</label>
                <input type="file" id="attached_files" name="attached_files[]" class="form-control" multiple
                    required>

                {{-- عرض الأخطاء لو فيه مشكلة --}}
                @error('attached_files.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </form>
    </div>
</div>
