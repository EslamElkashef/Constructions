@extends('layouts.master')
@section('title', 'Sales Reports')
@section('content')
    {{-- KPIs Section --}}
    @include('units.reports.kpis')

    <div class="card p-4 mt-4 shadow-sm rounded-4">
        <h4 class="fw-bold mb-3">Sales Reports</h4>
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <h5 class="fw-semibold">Units Sold by Type (Monthly)</h5>
                <div id="unitsSoldByType" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-semibold">Salespersons Success Rate</h5>
                <div id="salespersonsSuccess" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-semibold">Top Cities (Most Sales)</h5>
                <div id="topCities" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-semibold">Top Seller</h5>
                <div id="topSeller" class="chart-box">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .chart-box {
            background: #fff;
            border-radius: 16px;
            padding: 15px;
            min-height: 300px;
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

        .no-data-message {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-data-message i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 15px;
        }

        .top-seller-card {
            text-align: center;
            padding: 40px 20px;
        }

        .seller-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .seller-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1a202c;
        }

        .seller-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/choices/choices.js.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Loading Sales Reports...');

            fetch('/api/reports/sales')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Sales Data Received:', data);
                    initUnitsSoldByType(data);
                    initSalespersonsSuccess(data);
                    initTopCities(data);
                    initTopSeller(data);
                })
                .catch(error => {
                    console.error('❌ Error:', error);
                    showAllErrors();
                });

            function initUnitsSoldByType(data) {
                const container = document.getElementById('unitsSoldByType');
                removeSpinner(container);

                if (!data.months || !data.unit_type_series || data.unit_type_series.length === 0) {
                    showNoData(container, 'No sales data available');
                    return;
                }

                // Check if there's any actual data
                const hasData = data.unit_type_series.some(series =>
                    series.data.some(val => val > 0)
                );

                if (!hasData) {
                    showNoData(container, 'No units sold yet');
                    return;
                }

                const options = {
                    series: data.unit_type_series,
                    chart: {
                        type: 'area',
                        height: 280,
                        toolbar: {
                            show: false
                        },
                        stacked: false,
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: data.months,
                        labels: {
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Units Sold',
                            style: {
                                fontSize: '12px',
                                fontWeight: 600
                            }
                        },
                        labels: {
                            formatter: function(val) {
                                return Math.floor(val);
                            }
                        }
                    },
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.6,
                            opacityTo: 0.1,
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        fontSize: '12px'
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(val) {
                                return val + ' units';
                            }
                        }
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            function initSalespersonsSuccess(data) {
                const container = document.getElementById('salespersonsSuccess');
                removeSpinner(container);

                if (!data.salespersons || data.salespersons.length === 0) {
                    showNoData(container, 'No salespersons data');
                    return;
                }

                const options = {
                    series: [{
                        name: 'Success Rate',
                        data: data.salespersons_success_values || []
                    }],
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            horizontal: true,
                            distributed: true,
                            barHeight: '70%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    colors: ['#4C8BF5', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444'],
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val + "%";
                        },
                        offsetX: 30,
                        style: {
                            fontSize: '12px',
                            colors: ['#304758']
                        }
                    },
                    xaxis: {
                        categories: data.salespersons,
                        max: 100,
                        labels: {
                            formatter: function(val) {
                                return val + "%";
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + "% success rate";
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            function initTopCities(data) {
                const container = document.getElementById('topCities');
                removeSpinner(container);

                const cities = (data.top_cities || []).map(city => city || 'Unknown City');
                const values = data.top_cities_values || [];

                if (cities.length === 0 || values.every(v => v === 0)) {
                    showNoData(container, 'No city data available');
                    return;
                }

                const options = {
                    series: [{
                        name: 'Sales',
                        data: values
                    }],
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: '60%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        }
                    },
                    xaxis: {
                        categories: cities,
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Sales',
                            style: {
                                fontSize: '12px',
                                fontWeight: 600
                            }
                        }
                    },
                    colors: ['#4C8BF5'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#10B981'],
                            inverseColors: false,
                            opacityFrom: 1,
                            opacityTo: 0.8,
                        }
                    }
                };

                const chart = new ApexCharts(container, options);
                chart.render();
                container.classList.add('loaded');
            }

            function initTopSeller(data) {
                const container = document.getElementById('topSeller');
                removeSpinner(container);

                if (!data.top_seller) {
                    showNoData(container, 'No top seller data');
                    return;
                }

                const sellerName = data.top_seller;
                const initials = sellerName.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                const totalSales = data.top_cities_values ? data.top_cities_values.reduce((a, b) => a + b, 0) : 0;

                const html = `
            <div class="top-seller-card">
                <div class="seller-avatar">${initials}</div>
                <div class="seller-name">${sellerName}</div>
                <div style="color: #718096; font-size: 14px; margin-bottom: 10px;">
                    🏆 Top Performer
                </div>
                <div class="seller-badge">
                    ${totalSales} Sales Closed
                </div>
                <div style="margin-top: 25px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; color: white;">
                    <div style="font-weight: 600; font-size: 16px;">Outstanding Achievement</div>
                    <div style="font-size: 13px; margin-top: 8px; opacity: 0.9;">
                        Leading the sales team with exceptional performance
                    </div>
                </div>
            </div>
        `;

                container.innerHTML = html;
                container.classList.add('loaded');
            }

            function removeSpinner(container) {
                const spinner = container.querySelector('.spinner');
                if (spinner) spinner.remove();
            }

            function showNoData(container, message = 'No data available') {
                container.innerHTML = `
            <div class="no-data-message">
                <i class="ri-database-2-line"></i>
                <div style="font-size: 16px; font-weight: 600;">${message}</div>
            </div>
        `;
                container.classList.add('loaded');
            }

            function showAllErrors() {
                document.querySelectorAll('.chart-box').forEach(box => {
                    removeSpinner(box);
                    showNoData(box, 'Error loading data');
                });
            }
        });
    </script>
@endpush
