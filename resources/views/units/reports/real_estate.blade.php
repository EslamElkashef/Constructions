@extends('layouts.master')
@section('title', 'Real Estate Reports')

@section('content')
    {{-- KPIs Section --}}
    @include('units.reports.kpis')

    <div class="card p-4 mt-4 shadow-sm rounded-4">
        <h4 class="fw-bold mb-3">Real Estate Monthly Reports</h4>
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <h5 class="fw-semibold">Monthly Created Units</h5>
                <div id="monthlySoldUnits" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-semibold">Units Types Distribution</h5>
                <div id="unitTypes" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="fw-semibold">Units by Employee</h5>
                <div id="companyShare" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="fw-semibold">Sales Over Time</h5>
                <div id="unitsPerCity" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .chart-box {
            background: #fff;
            border-radius: 16px;
            padding: 15px;
            min-height: 330px;
            position: relative;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .chart-box.loaded {
            opacity: 1;
        }

        .chart-box .spinner {
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
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .no-data-message {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #666;
        }

        .no-data-message.show {
            opacity: 1;
        }
    </style>
@endsection

@section('scripts')
    {{-- استخدام ApexCharts من CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // جلب البيانات من API
            fetch('/units/reports/real-estate/data')
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Data received from API:', data);

                    // Chart 1: Monthly Created Units
                    renderMonthlyUnits(data);

                    // Chart 2: Unit Types
                    renderUnitTypes(data);

                    // Chart 3: Units by Employee
                    renderEmployeeStats(data);

                    // Chart 4: Sales Over Time
                    renderSalesOverTime(data);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    showAllNoData();
                });

            // ============= Chart 1: Monthly Created Units =============
            function renderMonthlyUnits(data) {
                const container = document.getElementById('monthlySoldUnits');
                removeSpinner(container);

                if (!data.months || data.months.length === 0) {
                    return showNoData(container);
                }

                const options = {
                    series: [{
                        name: 'Units Created',
                        data: data.monthly_sold_units || []
                    }, {
                        name: 'Available Units',
                        data: data.monthly_available_units || []
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded',
                            borderRadius: 4
                        }
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
                        categories: data.months || [],
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Units',
                            style: {
                                fontSize: '13px',
                                fontWeight: 500
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#4C8BF5', '#10B981'],
                    tooltip: {
                        y: {
                            formatter: val => val + ' units'
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            // ============= Chart 2: Unit Types Distribution (Donut) =============
            function renderUnitTypes(data) {
                const container = document.getElementById('unitTypes');
                removeSpinner(container);

                if (!data.unit_type_labels || data.unit_type_labels.length === 0) {
                    return showNoData(container);
                }

                const options = {
                    series: data.unit_type_counts || [],
                    chart: {
                        type: 'donut',
                        height: 300,
                        fontFamily: 'inherit'
                    },
                    labels: data.unit_type_labels || [],
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                    legend: {
                        position: 'bottom',
                        fontSize: '13px'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Units',
                                        fontSize: '16px',
                                        fontWeight: 600
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toFixed(1) + '%';
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 280
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            // ============= Chart 3: Units by Employee =============
            function renderEmployeeStats(data) {
                const container = document.getElementById('companyShare');
                removeSpinner(container);

                if (!data.months || data.months.length === 0) {
                    return showNoData(container);
                }

                const options = {
                    series: [{
                        name: 'Company Share',
                        data: data.monthly_company_share || []
                    }, {
                        name: 'Success Rate (%)',
                        data: data.monthly_success_rate || []
                    }],
                    chart: {
                        type: 'line',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit'
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    xaxis: {
                        categories: data.months || [],
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(0);
                            }
                        }
                    },
                    colors: ['#4C8BF5', '#10B981'],
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    },
                    markers: {
                        size: 5
                    },
                    grid: {
                        borderColor: '#f1f1f1'
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            // ============= Chart 4: Sales Over Time =============
            function renderSalesOverTime(data) {
                const container = document.getElementById('unitsPerCity');
                removeSpinner(container);

                if (!data.units_per_city || data.units_per_city.length === 0) {
                    return showNoData(container);
                }

                const options = {
                    series: data.units_per_city || [],
                    chart: {
                        type: 'line',
                        height: 300,
                        toolbar: {
                            show: true
                        },
                        fontFamily: 'inherit'
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: data.months || [],
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Units'
                        }
                    },
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    },
                    markers: {
                        size: 4
                    },
                    grid: {
                        borderColor: '#f1f1f1'
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            // ============= Helper Functions =============
            function removeSpinner(container) {
                const spinner = container.querySelector('.spinner');
                if (spinner) spinner.remove();
            }

            function showNoData(container) {
                const msg = document.createElement('div');
                msg.className = 'no-data-message show';
                msg.innerHTML = '<i class="fas fa-chart-bar me-2"></i>No data available';
                container.appendChild(msg);
                container.classList.add('loaded');
            }

            function showAllNoData() {
                document.querySelectorAll('.chart-box').forEach(box => {
                    removeSpinner(box);
                    showNoData(box);
                });
            }

        });
    </script>
@endsection
