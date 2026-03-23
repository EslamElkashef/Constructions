<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const charts = ['#monthlySoldUnits', '#unitTypes', '#companyShare', '#unitsPerCity'];

        // إضافة spinner لكل chart
        charts.forEach(selector => {
            const el = document.querySelector(selector);
            el.innerHTML = `<div class="spinner"></div>`;
        });

        fetch("{{ route('units.reports.realestate.data') }}")
            .then(res => res.json())
            .then(data => {

                const colors = ["#4C8BF5", "#50C4B7", "#F2A742", "#EF6F6C", "#8F86F8"];
                const isEmpty = arr => !arr || arr.length === 0 || arr.every(v => !v);

                function noData(selector) {
                    const el = document.querySelector(selector);
                    el.innerHTML = `<div class="no-data-message">No data available</div>`;
                    setTimeout(() => el.querySelector('.no-data-message').classList.add('show'), 20);
                    el.classList.add('loaded');
                }

                const months = data.months || [];

                function cleanSeries(arr, length) {
                    if (!arr) arr = [];
                    arr = arr.map(v => typeof v === 'number' ? v : 0);
                    while (arr.length < length) arr.push(0);
                    return arr;
                }

                function renderChart(selector, chartOptions) {
                    const el = document.querySelector(selector);
                    el.innerHTML = ''; // إزالة spinner
                    new ApexCharts(el, chartOptions).render();
                    el.classList.add('loaded'); // fade-in
                }

                // ------------------- Sold vs Available Units -------------------
                const sold = cleanSeries(data.monthly_sold_units, months.length);
                const available = cleanSeries(data.monthly_available_units, months.length);
                if (isEmpty(sold) && isEmpty(available)) {
                    noData('#monthlySoldUnits');
                } else {
                    renderChart('#monthlySoldUnits', {
                        chart: {
                            type: 'bar',
                            height: 330
                        },
                        series: [{
                                name: "Sold Units",
                                type: 'bar',
                                data: sold
                            },
                            {
                                name: "Available Units",
                                type: 'line',
                                data: available
                            }
                        ],
                        xaxis: {
                            categories: months
                        },
                        stroke: {
                            width: [0, 4]
                        },
                        markers: {
                            size: 6
                        },
                        colors: [colors[0], colors[3]]
                    });
                }

                // ------------------- Unit Types Pie -------------------
                const labels = data.unit_type_labels || [];
                const counts = cleanSeries(data.unit_type_counts, labels.length);
                if (isEmpty(counts)) noData('#unitTypes');
                else renderChart('#unitTypes', {
                    chart: {
                        type: 'pie',
                        height: 330
                    },
                    labels: labels,
                    series: counts,
                    colors: colors
                });

                // ------------------- Company Share + Success Rate -------------------
                const company = cleanSeries(data.monthly_company_share, months.length);
                const success = cleanSeries(data.monthly_success_rate, months.length);
                if (isEmpty(company) && isEmpty(success)) noData('#companyShare');
                else renderChart('#companyShare', {
                    chart: {
                        type: 'area',
                        height: 330
                    },
                    series: [{
                            name: "Company Share",
                            data: company
                        },
                        {
                            name: "Success Rate (%)",
                            data: success
                        }
                    ],
                    xaxis: {
                        categories: months
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    colors: [colors[1], colors[2]]
                });

                // ------------------- Units per City -------------------
                const cities = data.units_per_city || [];
                if (cities.length === 0) noData('#unitsPerCity');
                else {
                    const series = cities.map(c => ({
                        name: c.name || 'Unknown',
                        data: cleanSeries(c.data, months.length)
                    }));
                    renderChart('#unitsPerCity', {
                        chart: {
                            type: 'bar',
                            height: 330
                        },
                        series: series,
                        xaxis: {
                            categories: months
                        },
                        dataLabels: {
                            enabled: true
                        },
                        colors: colors
                    });
                }

            })
            .catch(err => {
                console.error("Error fetching report data:", err);
                charts.forEach(selector => {
                    const el = document.querySelector(selector);
                    el.innerHTML = `<div class="no-data-message">Error loading data</div>`;
                    el.classList.add('loaded');
                });
            });
    });
</script>
