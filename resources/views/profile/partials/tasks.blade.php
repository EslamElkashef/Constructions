@foreach ($tasks as $task)
    <div class="col-xxl-3 col-sm-6 mb-3">
        <div class="card shadow-none profile-project-card profile-project-{{ strtolower($task->status) }}">
            <div class="card-body p-4">
                <div class="d-flex">
                    <div class="flex-grow-1 text-muted overflow-hidden">
                        <h5 class="fs-14 text-truncate"><a href="#" class="text-dark">{{ $task->title }}</a></h5>
                        <p class="text-muted text-truncate mb-0">
                            Due: <span class="fw-semibold text-dark">{{ $task->due_date->format('d M Y') }}</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <div class="badge badge-soft-{{ $task->status == 'Completed' ? 'success' : 'warning' }} fs-10">
                            {{ $task->status }}
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-truncate mb-0">Client: {{ $task->client_name }}</p>
                </div>
                <div class="mt-3 d-flex align-items-center gap-2">
                    <div class="avatar-group">
                        @if ($task->assignedTo)
                            <div class="avatar-group-item">
                                <div class="avatar-xs">
                                    <img src="{{ $task->assignedTo->avatar }}" alt=""
                                        class="rounded-circle img-fluid" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
