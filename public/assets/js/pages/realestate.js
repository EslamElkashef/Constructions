document.addEventListener("DOMContentLoaded", function () {
    fetch("/api/real-estate-data")
        .then((res) => res.json())
        .then((data) => {
            console.log("API DATA:", data);

            /* ============================================
                1) MONTHLY SOLD UNITS (COLUMN CHART)
            ============================================ */
            var options1 = {
                series: [
                    {
                        name: "Sold Units",
                        data: data.monthly_sold_units,
                    },
                ],
                chart: {
                    type: "bar",
                    height: 350,
                },
                xaxis: {
                    categories: data.months,
                },
            };

            new ApexCharts(
                document.querySelector("#monthly_sold_units_chart"),
                options1
            ).render();

            /* ============================================
                2) COMPANY SHARE (STACKED BAR)
            ============================================ */
            var options2 = {
                series: [
                    {
                        name: "Company Share",
                        data: data.monthly_company_share_company,
                    },
                    {
                        name: "Others",
                        data: data.monthly_company_share_others,
                    },
                ],
                chart: {
                    type: "bar",
                    stacked: true,
                    height: 350,
                },
                xaxis: {
                    categories: data.months,
                },
            };

            new ApexCharts(
                document.querySelector("#company_share_chart"),
                options2
            ).render();

            /* ============================================
                3) UNITS PER CITY (MULTI LINE)
            ============================================ */
            var options3 = {
                series: data.units_per_city,
                chart: {
                    height: 350,
                    type: "line",
                },
                xaxis: {
                    categories: data.months,
                },
            };

            new ApexCharts(
                document.querySelector("#units_per_city_chart"),
                options3
            ).render();

            /* ============================================
                4) SALESPERSON PERFORMANCE
            ============================================ */
            var options4 = {
                series: data.salesperson_series,
                chart: {
                    height: 350,
                    type: "line",
                },
                xaxis: {
                    categories: data.months,
                },
            };

            new ApexCharts(
                document.querySelector("#salesperson_performance_chart"),
                options4
            ).render();
        })
        .catch((err) => console.error("ERROR LOADING REAL ESTATE DATA:", err));
});
