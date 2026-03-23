<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    flatpickr("#startDate", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#endDate", {
        dateFormat: "Y-m-d"
    });

    function renderCharts(data) {
        // Monthly Revenue Chart
        new ApexCharts(document.querySelector('#monthlyRevenue'), {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                },
                redrawOnParentResize: true
            },
            series: [{
                name: 'Revenue',
                data: data.monthly_revenue_values
            }],
            xaxis: {
                categories: data.months
            },
            colors: ['#7b8cf7'],
            tooltip: {
                theme: 'dark'
            }
        }).render();

        // Cashflow Forecast Chart
        new ApexCharts(document.querySelector('#cashflowForecast'), {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                },
                redrawOnParentResize: true
            },
            series: [{
                name: 'Cashflow',
                data: data.cashflow_values
            }],
            xaxis: {
                categories: data.months
            },
            colors: ['#6ed3cf'],
            tooltip: {
                theme: 'dark'
            }
        }).render();

        // Overdue Payments Bar Chart
        new ApexCharts(document.querySelector('#overduePaymentsBar'), {
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                },
                redrawOnParentResize: true
            },
            series: [{
                name: 'Overdue',
                data: data.overdue_values
            }],
            xaxis: {
                categories: data.overdue_customers
            },
            colors: ['#f8a07c'],
            tooltip: {
                theme: 'dark'
            }
        }).render();

        // Populate Table
        const tbody = document.querySelector('#overduePaymentsTable tbody');
        tbody.innerHTML = '';
        data.overdue_details.forEach(d => {
            const tr = document.createElement('tr');
            tr.innerHTML =
                `<td>${d.customer}</td><td>${d.unit}</td><td>${d.due_date}</td><td>${d.days_late}</td>`;
            tbody.appendChild(tr);
        });
    }

    // Load Finance Data
    function loadFinanceData(filters = {}) {
        const params = new URLSearchParams(filters).toString();
        fetch("{{ route('units.reports.finance') }}?" + params)
            .then(res => res.json())
            .then(data => renderCharts(data));
    }

    // Initial load
    loadFinanceData();

    // Apply filters
    document.getElementById('filterBtn').addEventListener('click', () => {
        const filters = {
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value
        };
        loadFinanceData(filters);
    });
</script>
