<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    flatpickr("#startDate", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#endDate", {
        dateFormat: "Y-m-d"
    });

    let progressChart, budgetChart, workersChart, remainingBudgetChart, timelineChart;

    function initCharts() {
        const commonOptions = {
            chart: {
                height: 300,
                toolbar: {
                    show: false
                },
                redrawOnParentResize: true
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 250
                    }
                }
            }]
        };

        progressChart = new ApexCharts(document.querySelector('#progressGauge'), {
            ...commonOptions,
            chart: {
                type: 'radialBar',
                ...commonOptions.chart
            },
            series: [],
            labels: [],
            colors: ['#7b8cf7', '#6ed3cf', '#f8a07c', '#fbd786', '#81ecec'],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        value: {
                            formatter: val => val + '%'
                        }
                    }
                }
            }
        });
        progressChart.render();

        budgetChart = new ApexCharts(document.querySelector('#budgetVsActual'), {
            ...commonOptions,
            chart: {
                type: 'bar',
                ...commonOptions.chart
            },
            series: [],
            xaxis: {
                categories: []
            },
            colors: ['#7b8cf7', '#6ed3cf']
        });
        budgetChart.render();

        workersChart = new ApexCharts(document.querySelector('#workersChart'), {
            ...commonOptions,
            chart: {
                type: 'bar',
                ...commonOptions.chart
            },
            series: [{
                name: 'Workers',
                data: []
            }],
            xaxis: {
                categories: []
            },
            colors: ['#f8a07c']
        });
        workersChart.render();

        remainingBudgetChart = new ApexCharts(document.querySelector('#remainingBudgetChart'), {
            ...commonOptions,
            chart: {
                type: 'bar',
                ...commonOptions.chart
            },
            series: [{
                name: 'Remaining Budget',
                data: []
            }],
            xaxis: {
                categories: []
            },
            colors: ['#6ed3cf']
        });
        remainingBudgetChart.render();

        timelineChart = new ApexCharts(document.querySelector('#timelineChart'), {
            ...commonOptions,
            chart: {
                type: 'rangeBar',
                ...commonOptions.chart
            },
            plotOptions: {
                bar: {
                    horizontal: true
                }
            },
            series: [],
            xaxis: {
                type: 'datetime'
            },
            colors: ['#7b8cf7']
        });
        timelineChart.render();
    }

    // Update charts with data
    function updateCharts(data) {
        progressChart.updateOptions({
            labels: data.progress_labels
        });
        progressChart.updateSeries(data.progress_values);

        budgetChart.updateOptions({
            xaxis: {
                categories: data.budget_vs_actual_categories
            }
        });
        budgetChart.updateSeries(data.budget_vs_actual_series);

        workersChart.updateOptions({
            xaxis: {
                categories: data.progress_labels
            }
        });
        workersChart.updateSeries([{
            name: 'Workers',
            data: data.workers_series
        }]);

        remainingBudgetChart.updateOptions({
            xaxis: {
                categories: data.progress_labels
            }
        });
        remainingBudgetChart.updateSeries([{
            name: 'Remaining Budget',
            data: data.remaining_budget_series
        }]);

        timelineChart.updateSeries([{
            data: data.timeline_data
        }]);

        const tbody = document.querySelector('#contractorsTable tbody');
        tbody.innerHTML = '';
        data.contractors.forEach(c => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${c.name}</td><td>${c.project}</td><td>${c.cost}</td><td>${c.status}</td>`;
            tbody.appendChild(tr);
        });
    }

    // Load data from server
    function loadConstructionData(filters = {}) {
        const params = new URLSearchParams(filters).toString();
        fetch("{{ route('units.reports.construction') }}?" + params)
            .then(res => res.json())
            .then(data => updateCharts(data));
    }

    // Initialize charts
    initCharts();

    // Initial load
    loadConstructionData();

    // Apply Filters
    document.getElementById('filterBtn').addEventListener('click', () => {
        const filters = {
            project_id: document.getElementById('projectFilter').value,
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value
        };
        loadConstructionData(filters);
    });
</script>
