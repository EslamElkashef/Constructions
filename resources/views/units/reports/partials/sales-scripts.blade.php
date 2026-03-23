<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const charts = ['#unitsSoldByType', '#salespersonsSuccess', '#topCities', '#topSeller'];

        charts.forEach(s => document.querySelector(s).innerHTML = '<div class="spinner"></div>');

        fetch("{{ route('units.reports.sales') }}")
            .then(res => res.json())
            .then(data => {
                const colors = ["#4C8BF5", "#50C4B7", "#F2A742", "#EF6F6C", "#8F86F8"];
                const isEmpty = arr => !arr || arr.length === 0 || arr.every(v => !v);

                function noData(selector) {
                    const el = document.querySelector(selector);
                    el.innerHTML = '<div class="no-data-message">No data available</div>';
                    setTimeout(() => el.querySelector('.no-data-message').classList.add('show'), 20);
                    el.classList.add('loaded');
                }

                function renderChart(selector, options) {
                    const el = document.querySelector(selector);
                    el.innerHTML = '';
                    new ApexCharts(el, options).render();
                    el.classList.add('loaded');
                }

                const months = data.months || [];

                // Units Sold by Type
                if (isEmpty(data.unit_type_series)) noData('#unitsSoldByType');
                else renderChart('#unitsSoldByType', {
                    chart: {
                        type: 'bar',
                        height: 330,
                        stacked: true
                    },
                    series: data.unit_type_series,
                    xaxis: {
                        categories: months
                    },
                    colors: colors,
                    plotOptions: {
                        bar: {
                            horizontal: false
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        theme: 'dark'
                    }
                });

                // Salespersons Success Rate
                if (isEmpty(data.salespersons_success_values)) noData('#salespersonsSuccess');
                else renderChart('#salespersonsSuccess', {
                    chart: {
                        type: 'bar',
                        height: 330
                    },
                    series: [{
                        name: 'Success Rate (%)',
                        data: data.salespersons_success_values
                    }],
                    xaxis: {
                        categories: data.salespersons,
                        labels: {
                            rotate: -20
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            columnWidth: '60%'
                        }
                    },
                    colors: colors,
                    dataLabels: {
                        enabled: true
                    },
                    tooltip: {
                        theme: 'dark'
                    }
                });

                // Top Cities
                if (isEmpty(data.top_cities_values)) noData('#topCities');
                else renderChart('#topCities', {
                    chart: {
                        type: 'pie',
                        height: 330
                    },
                    series: data.top_cities_values,
                    labels: data.top_cities,
                    colors: colors,
                    tooltip: {
                        theme: 'dark'
                    }
                });

                // Top Seller
                if (isEmpty(data.salespersons_success_values)) noData('#topSeller');
                else {
                    const maxVal = Math.max(...data.salespersons_success_values);
                    const seriesColors = data.salespersons_success_values.map(v => v === maxVal ?
                        '#EF6F6C' : '#4C8BF5');
                    renderChart('#topSeller', {
                        chart: {
                            type: 'donut',
                            height: 330
                        },
                        series: data.salespersons_success_values,
                        labels: data.salespersons,
                        colors: seriesColors,
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: val => val + ' %'
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    });
                }

            })
            .catch(err => {
                console.error("Error fetching sales data:", err);
                charts.forEach(s => {
                    const el = document.querySelector(s);
                    el.innerHTML = '<div class="no-data-message">Error loading data</div>';
                    el.classList.add('loaded');
                });
            });
    });
</script>
