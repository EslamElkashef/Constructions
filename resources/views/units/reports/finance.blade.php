@extends('layouts.master')
@section('title', 'Finance Reports')
@section('content')
    <div class="container py-4">
        {{-- KPIs Section --}}
        @include('units.reports.kpis')

        <h3 class="fw-bold mb-4 text-primary">Finance Reports</h3>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        {{-- Filters --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="text" id="startDate" class="form-control" placeholder="Select start date">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="text" id="endDate" class="form-control" placeholder="Select end date">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="filterBtn" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Apply Filters
                </button>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Monthly Revenue</h5>
                    <div id="monthlyRevenue" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Cashflow Forecast</h5>
                    <div id="cashflowForecast" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overdue Payments --}}
        <div class="row g-4 mt-3">
            <div class="col-12">
                <div class="card p-3 shadow-sm rounded-4">
                    <h5 class="fw-semibold mb-3">Overdue Payments</h5>
                    <div id="overduePaymentsBar" style="height:300px; position: relative;">
                        <div class="spinner"></div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-hover" id="overduePaymentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer</th>
                                    <th>Unit</th>
                                    <th>Due Date</th>
                                    <th>Days Late</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">Loading...</td>
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

        .days-late-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .days-late-high {
            background: #FEE2E2;
            color: #991B1B;
        }

        .days-late-medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .days-late-low {
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
            console.log('🚀 Loading Finance Reports...');

            // Initialize Flatpickr
            flatpickr("#startDate", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    const endDatePicker = document.getElementById("endDate")._flatpickr;
                    if (endDatePicker) {
                        endDatePicker.set('minDate', dateStr);
                    }
                }
            });

            flatpickr("#endDate", {
                dateFormat: "Y-m-d"
            });

            // Load initial data
            loadFinanceData();

            // Filter button
            document.getElementById('filterBtn').addEventListener('click', function() {
                loadFinanceData();
            });

            function loadFinanceData() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                const params = new URLSearchParams();
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                const url = `/api/reports/finance${params.toString() ? '?' + params.toString() : ''}`;
                console.log('📡 Fetching:', url);

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        console.log('💰 Finance Data:', data);
                        initMonthlyRevenue(data.monthlyRevenue || data);
                        initCashflowForecast(data.cashflowForecast || data);
                        initOverduePayments(data.overduePayments || data);
                        loadOverduePaymentsTable(data.overduePaymentsDetails || []);
                    })
                    .catch(error => {
                        console.error('❌ Error:', error);
                        showError();
                    });
            }

            function initMonthlyRevenue(data) {
                const container = document.getElementById('monthlyRevenue');
                removeSpinner(container);

                if (!data || !data.months || data.months.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                        name: 'Revenue',
                        data: data.revenue || []
                    }],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    xaxis: {
                        categories: data.months
                    },
                    yaxis: {
                        title: {
                            text: 'Revenue ($)'
                        },
                        labels: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString();
                            }
                        }
                    },
                    colors: ['#4C8BF5'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.2,
                            stops: [0, 90, 100]
                        }
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

            function initCashflowForecast(data) {
                const container = document.getElementById('cashflowForecast');
                removeSpinner(container);

                if (!data || !data.months || data.months.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                            name: 'Income',
                            data: data.income || []
                        },
                        {
                            name: 'Expenses',
                            data: data.expenses || []
                        },
                        {
                            name: 'Net Cashflow',
                            data: data.netCashflow || []
                        }
                    ],
                    chart: {
                        type: 'line',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        width: [3, 3, 3],
                        curve: 'smooth',
                        dashArray: [0, 0, 5]
                    },
                    xaxis: {
                        categories: data.months
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
                    colors: ['#10B981', '#EF4444', '#4C8BF5'],
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right'
                    },
                    markers: {
                        size: 5,
                        hover: {
                            size: 7
                        }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
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

            function initOverduePayments(data) {
                const container = document.getElementById('overduePaymentsBar');
                removeSpinner(container);

                if (!data || !data.customers || data.customers.length === 0) {
                    showNoData(container);
                    return;
                }

                const options = {
                    series: [{
                        name: 'Overdue Amount',
                        data: data.amounts || []
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
                            distributed: true,
                            barHeight: '70%'
                        }
                    },
                    colors: ['#EF4444', '#F59E0B', '#F97316', '#DC2626', '#B91C1C'],
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return "$" + (val || 0).toLocaleString();
                        },
                        offsetX: 30,
                        style: {
                            fontSize: '12px',
                            colors: ['#304758']
                        }
                    },
                    xaxis: {
                        categories: data.customers || [],
                        labels: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString();
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: true
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "$" + (val || 0).toLocaleString()
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
            }

            function loadOverduePaymentsTable(data) {
                const tbody = document.querySelector('#overduePaymentsTable tbody');

                if (!data || data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="5" class="text-center text-muted py-4">No overdue payments</td></tr>';
                    return;
                }

                tbody.innerHTML = data.map(payment => {
                    let badgeClass = 'days-late-low';
                    if (payment.daysLate > 30) badgeClass = 'days-late-high';
                    else if (payment.daysLate > 15) badgeClass = 'days-late-medium';

                    return `
                <tr>
                    <td>${payment.customer || 'N/A'}</td>
                    <td>${payment.unit || 'N/A'}</td>
                    <td>${payment.dueDate || 'N/A'}</td>
                    <td><span class="days-late-badge ${badgeClass}">${payment.daysLate || 0} days</span></td>
                    <td class="fw-bold text-danger">$${(payment.amount || 0).toLocaleString()}</td>
                </tr>
            `;
                }).join('');
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
                ['monthlyRevenue', 'cashflowForecast', 'overduePaymentsBar'].forEach(id => {
                    const elem = document.getElementById(id);
                    removeSpinner(elem);
                    showNoData(elem);
                });

                document.querySelector('#overduePaymentsTable tbody').innerHTML =
                    '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
            }
        });
    </script>
@endpush
