@extends('layouts.master')
@section('title', 'Construction Reports')
@section('content')
    <div class="container py-4">
        {{-- KPIs Section --}}
        @include('units.reports.kpis')

        <h3 class="fw-bold mb-4 text-primary">Construction Reports</h3>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        {{-- Filters --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Project</label>
                <select id="projectFilter" class="form-select">
                    <option value="">All Projects</option>
                    @foreach (\App\Models\Project::all() as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="text" id="startDate" class="form-control" placeholder="Select start date">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="text" id="endDate" class="form-control" placeholder="Select end date">
            </div>
        </div>
        <button id="filterBtn" class="btn btn-primary mb-4">
            <i class="fas fa-filter me-2"></i>Apply Filters
        </button>

        {{-- Charts Row 1 --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Progress per Project</h5>
                    <div id="progressGauge" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Budget vs Actual</h5>
                    <div id="budgetVsActual" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row 2 --}}
        <div class="row g-4 mt-3">
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Workers per Project</h5>
                    <div id="workersChart" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Remaining Budget</h5>
                    <div id="remainingBudgetChart" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="row g-4 mt-3">
            <div class="col-12">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Project Timeline</h5>
                    <div id="timelineChart" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contractors Table --}}
        <div class="row g-4 mt-3">
            <div class="col-12">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Contractors Table</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="contractorsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Project</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #4C8BF5;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            z-index: 10;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-completed {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-in-progress {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-pending {
            background: #E0E7FF;
            color: #3730A3;
        }

        .no-data-message {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-data-message i {
            font-size: 48px;
            opacity: 0.3;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/choices/choices.js.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Loading Construction Reports...');

            // Initialize Flatpickr
            flatpickr("#startDate", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    const endPicker = document.getElementById("endDate")._flatpickr;
                    if (endPicker) endPicker.set('minDate', dateStr);
                }
            });

            flatpickr("#endDate", {
                dateFormat: "Y-m-d"
            });

            // Load initial data
            loadConstructionData();

            // Filter button
            document.getElementById('filterBtn').addEventListener('click', loadConstructionData);

            function loadConstructionData() {
                const projectId = document.getElementById('projectFilter').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                const params = new URLSearchParams();
                if (projectId) params.append('project_id', projectId);
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                const url = `/api/reports/construction${params.toString() ? '?' + params.toString() : ''}`;
                console.log('📡 Fetching:', url);

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        console.log('🏗️ Construction Data:', data);
                        initProgressGauge(data.progress || data);
                        initBudgetVsActual(data.budget || data);
                        initWorkersChart(data.workers || data);
                        initRemainingBudget(data.remainingBudget || data);
                        initTimeline(data.timeline || data);
                        loadContractorsTable(data.contractors || []);
                    })
                    .catch(error => {
                        console.error('❌ Error:', error);
                        showError();
                    });
            }

            function initProgressGauge(data) {
                const container = document.getElementById('progressGauge');
                removeSpinner(container);

                if (!data || !data.projects || data.projects.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: data.progress || [],
                    chart: {
                        height: 300,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 0,
                            endAngle: 270,
                            hollow: {
                                margin: 5,
                                size: '30%',
                                background: 'transparent',
                            },
                            dataLabels: {
                                name: {
                                    show: true,
                                    fontSize: '13px'
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    fontWeight: 600
                                }
                            }
                        }
                    },
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#8B5CF6'],
                    labels: data.projects,
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '13px',
                        position: 'left',
                        offsetX: 10,
                        offsetY: 10,
                        labels: {
                            useSeriesColors: true,
                        },
                        formatter: function(seriesName, opts) {
                            return seriesName + ": " + opts.w.globals.series[opts.seriesIndex] + "%"
                        },
                    },
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function initBudgetVsActual(data) {
                const container = document.getElementById('budgetVsActual');
                removeSpinner(container);

                if (!data || !data.projects || data.projects.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                        name: 'Budget',
                        data: data.budget || []
                    }, {
                        name: 'Actual',
                        data: data.actual || []
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: data.projects,
                    },
                    yaxis: {
                        title: {
                            text: 'Amount ($)'
                        },
                        labels: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString();
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#4C8BF5', '#EF4444'],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString()
                            }
                        }
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function initWorkersChart(data) {
                const container = document.getElementById('workersChart');
                removeSpinner(container);

                if (!data || !data.projects || data.projects.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                        name: 'Workers',
                        data: data.counts || []
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: data.projects,
                    },
                    colors: ['#10B981']
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function initRemainingBudget(data) {
                const container = document.getElementById('remainingBudgetChart');
                removeSpinner(container);

                if (!data || !data.projects || data.projects.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: data.remaining || [],
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    labels: data.projects,
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#8B5CF6'],
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString()
                            }
                        }
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function initTimeline(data) {
                const container = document.getElementById('timelineChart');
                removeSpinner(container);

                if (!data || !Array.isArray(data) || data.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                        data: data.map(item => ({
                            x: item.project || 'Unknown',
                            y: [
                                new Date(item.startDate).getTime(),
                                new Date(item.endDate).getTime()
                            ],
                            fillColor: item.status === 'completed' ? '#10B981' : item
                                .status === 'in-progress' ? '#F59E0B' : '#4C8BF5'
                        }))
                    }],
                    chart: {
                        height: 300,
                        type: 'rangeBar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true,
                            dataLabels: {
                                hideOverflowingLabels: false
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            const a = new Date(val[0]);
                            const b = new Date(val[1]);
                            const diff = Math.ceil((b - a) / (1000 * 60 * 60 * 24));
                            return diff + ' days';
                        }
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: {
                        show: true
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function loadContractorsTable(data) {
                const tbody = document.querySelector('#contractorsTable tbody');

                if (!data || data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="text-center text-muted py-4">No contractors data</td></tr>';
                    return;
                }

                tbody.innerHTML = data.map(contractor => `
            <tr>
                <td>${contractor.name || 'N/A'}</td>
                <td>${contractor.project || 'N/A'}</td>
                <td>$${(contractor.cost || 0).toLocaleString()}</td>
                <td><span class="status-badge status-${contractor.status || 'pending'}">${contractor.status || 'Pending'}</span></td>
            </tr>
        `).join('');
            }

            function removeSpinner(container) {
                const spinner = container.querySelector('.spinner');
                if (spinner) spinner.remove();
            }

            function showNoData(container) {
                container.innerHTML = `
            <div class="no-data-message">
                <i class="ri-database-2-line d-block mb-3"></i>
                <div>No data available</div>
            </div>
        `;
            }

            function showError() {
                ['progressGauge', 'budgetVsActual', 'workersChart', 'remainingBudgetChart', 'timelineChart'].forEach
                    (id => {
                        const elem = document.getElementById(id);
                        removeSpinner(elem);
                        showNoData(elem);
                    });

                document.querySelector('#contractorsTable tbody').innerHTML =
                    '<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>';
            }
        });
    </script>
@endpush
