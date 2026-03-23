    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <h5 class="card-title flex-grow-1">Documents</h5>
            </div>

            {{-- Upload Form --}}
            <form action="{{ route('projects.files.upload', $project) }}" method="POST" enctype="multipart/form-data"
                class="mb-4">
                @csrf
                <div class="input-group">
                    <input type="file" name="attached_files[]" class="form-control" multiple required>
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive table-card">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Upload Date</th>
                            <th style="width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($project->attached_files ?? []) as $file)
                            @php
                                $filePath = $file['path'] ?? '';
                                $ext = strtoupper(pathinfo($filePath, PATHINFO_EXTENSION));
                                $name = $file['original_name'] ?? basename($filePath);
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light text-danger rounded fs-24">
                                                <i class="ri-file-line"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h5 class="fs-14 mb-0">
                                                <a href="{{ Storage::url($filePath) }}" class="text-dark"
                                                    target="_blank">
                                                    {{ $name }}
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ext }} File</td>
                                <td>{{ $project->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-soft-secondary btn-sm btn-icon"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-fill"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ Storage::url($filePath) }}"
                                                    target="_blank">
                                                    <i class="ri-eye-fill me-2 text-muted"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ Storage::url($filePath) }}" download>
                                                    <i class="ri-download-2-fill me-2 text-muted"></i>Download
                                                </a>
                                            </li>
                                            <li class="dropdown-divider"></li>
                                            <li>
                                                <x-delete-button :action="route('projects.files.destroy', [$project, $loop->index])">
                                                    <i class="ri-delete-bin-5-fill me-2 text-muted"></i> Delete
                                                </x-delete-button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No documents uploaded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
