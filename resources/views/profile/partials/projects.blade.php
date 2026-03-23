@foreach ($projects as $project)
    <div class="col-xxl-3 col-sm-6 mb-3">
        <div class="card shadow-none profile-project-card profile-project-{{ strtolower($project->status) }}">
            <div class="card-body p-4">
                <div class="d-flex">
                    <div class="flex-grow-1 text-muted overflow-hidden">
                        <h5 class="fs-14 text-truncate"><a href="#" class="text-dark">{{ $project->title }}</a></h5>
                        <p class="text-muted text-truncate mb-0">
                            Deadline: <span
                                class="fw-semibold text-dark">{{ $project->deadline ? $project->deadline->format('d M Y') : 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <div
                            class="badge badge-soft-{{ $project->status == 'Completed' ? 'success' : 'warning' }} fs-10">
                            {{ $project->status }}
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-truncate mb-0">{{ $project->description }}</p>
                </div>
                <div class="mt-3 d-flex align-items-center gap-2">
                    <div class="avatar-group">
                        @foreach ($project->teamMembers as $member)
                            <div class="avatar-group-item">
                                <div class="avatar-xs">
                                    <img src="{{ $member->avatar }}" alt="" class="rounded-circle img-fluid" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
