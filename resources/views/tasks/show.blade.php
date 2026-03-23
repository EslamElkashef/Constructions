@extends('layouts.master')
@section('title')
    @lang('translation.task-details')
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-card">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium">Tasks No</td>
                                    <td>#{{ $task->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tasks Title</td>
                                    <td>{{ $task->title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Project Name</td>
                                    <td>{{ $task->project->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Priority</td>
                                    <td>
                                        <span
                                            class="badge 
                                            {{ $task->priority == 'High' ? 'badge-soft-danger' : ($task->priority == 'Medium' ? 'badge-soft-warning' : 'badge-soft-success') }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Status</td>
                                    <td>
                                        <span
                                            class="badge 
                                            {{ $task->status == 'Completed' ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Due Date</td>
                                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end card-->

            <!-- Assigned To -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <h6 class="card-title mb-0 flex-grow-1">Assigned To</h6>
                        <!-- Share Icon -->
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#inviteMembersModal"
                            title="Share">
                            <i class="ri-share-forward-line"></i>
                        </button>
                    </div>
                    <ul class="list-unstyled vstack gap-3 mb-0">
                        @foreach ($task->assignedUsers as $user)
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $user->avatar_url ?? asset('assets/images/users/default.jpg') }}"
                                            alt="" class="avatar-xs rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1"><a>{{ $user->name }}</a></h6>
                                        <p class="text-muted mb-0">{{ $user->role ?? 'Member' }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div><!--end card-->

            <!-- Attachments (Mini List) -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Attachments</h5>
                    <div class="vstack gap-2">
                        @foreach ($task->attachments as $file)
                            <div class="border rounded border-dashed p-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fs-13 mb-1 text-truncate">
                                            {{ $file->file_name }}
                                        </h5>
                                        <div>{{ number_format($file->file_size / 1024, 2) }} KB</div>
                                    </div>
                                    <div class="flex-shrink-0 d-flex gap-1">
                                        <!-- Download -->
                                        <a href="{{ route('attachments.download', $file->id) }}"
                                            class="btn btn-icon btn-sm btn-light" title="Download">
                                            <i class="ri-download-2-line"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('attachments.destroy', $file->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-light"
                                                onclick="return confirm('Are you sure?')" title="Delete">
                                                <i class="ri-delete-bin-5-line text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->

        <div class="col-xxl-9">
            <!-- Summary -->
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">
                        <h6 class="mb-3 fw-semibold text-uppercase">Summary</h6>
                        <!-- عرض الوصف مع التحقق إذا كان موجود -->
                        <p>{{ $task->description ?? 'No description available.' }}</p>
                    </div>
                </div>
            </div>

            <!--end card-->

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#comments-tab" role="tab">
                                <i class="ri-chat-1-line me-1"></i> Comments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#attachments-tab" role="tab">
                                <i class="ri-attachment-2 me-1"></i> Attachments File
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                        <!-- Comments Tab -->
                        <div class="tab-pane active" id="comments-tab" role="tabpanel">
                            <h5 class="card-title mb-4">Comments</h5>
                            <div data-simplebar style="height: 400px;" class="px-3 mx-n3 mb-2">
                                @foreach ($task->comments as $comment)
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $comment->user->avatar_url ?? asset('assets/images/users/default.jpg') }}"
                                                alt="" class="avatar-xs rounded-circle" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="fs-13">
                                                <a href="javascript:void(0)">{{ $comment->user->name }}</a>
                                                <small
                                                    class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </h5>
                                            <p class="text-muted">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add Comment -->
                            <form class="mt-4" method="POST" action="{{ route('tasks.comments.store', $task->id) }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <textarea class="form-control bg-light border-light" name="body" rows="3" placeholder="Enter your comment"></textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div><!--end comments-->

                        <!-- Attachments Tab -->
                        <div class="tab-pane" id="attachments-tab" role="tabpanel">
                            <h5 class="card-title mb-4">Attachments</h5>
                            <div class="table-responsive table-card">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>File Name</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Upload Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($task->attachments as $file)
                                            <tr>
                                                <td>{{ $file->file_name }}</td>
                                                <td>{{ strtoupper(pathinfo($file->file_name, PATHINFO_EXTENSION)) }}</td>
                                                <td>{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                                <td>{{ $file->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <!-- View -->
                                                        <a href="{{ route('attachments.view', $file->id) }}"
                                                            class="btn btn-light btn-sm" target="_blank" title="View">
                                                            <i class="ri-eye-fill"></i>
                                                        </a>
                                                        <!-- Download -->
                                                        <a href="{{ route('attachments.download', $file->id) }}"
                                                            class="btn btn-light btn-sm" title="Download">
                                                            <i class="ri-download-2-line"></i>
                                                        </a>
                                                        <!-- Delete -->
                                                        <form action="{{ route('attachments.destroy', $file->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-light btn-sm"
                                                                onclick="return confirm('Are you sure?')" title="Delete">
                                                                <i class="ri-delete-bin-5-line text-danger"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Upload New File -->
                            <form action="{{ route('tasks.attachments.store', $task->id) }}" method="POST"
                                enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-9">
                                        <input type="file" class="form-control" name="file" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-primary w-100">Upload</button>
                                    </div>
                                </div>
                            </form>
                        </div><!--end attachments-->
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    {{-- @include('tasks.partials.invite-members') <!-- مودال الشير --> --}}
@endsection
