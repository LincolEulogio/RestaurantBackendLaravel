import Chart from "chart.js/auto";

export default () => ({
    init() {
        console.log("Reports Manager initialized");
        this.initCharts();
    },

    initCharts() {
        // Setup defaults
        Chart.defaults.font.family = "'Figtree', sans-serif";
        Chart.defaults.color = "#64748b";

        this.initIncomeChart();
        this.initProductsChart();
        this.initHourlyChart();
        this.initPaymentChart();
        this.initDailyTrendsChart();
    },

    initIncomeChart() {
        const canvas = document.getElementById("incomeChart");
        if (!canvas) return;

        const monthlyData = window.reportsData?.monthlyRevenue || [];

        const labels = monthlyData.map((item) => {
            const [year, month] = item.month.split("-");
            const monthNames = [
                "Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Sep",
                "Oct",
                "Nov",
                "Dic",
            ];
            return monthNames[parseInt(month) - 1];
        });

        const revenueData = monthlyData.map((item) => parseFloat(item.revenue));

        const ctx = canvas.getContext("2d");
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, "rgba(59, 130, 246, 0.2)");
        gradient.addColorStop(1, "rgba(59, 130, 246, 0)");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Ingresos",
                        data: revenueData,
                        borderColor: "#3b82f6",
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "#ffffff",
                        pointBorderColor: "#3b82f6",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1e293b",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || "";
                                if (label) label += ": ";
                                if (context.parsed.y !== null) {
                                    label +=
                                        "S/ " +
                                        context.parsed.y.toLocaleString(
                                            "es-PE",
                                            {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2,
                                            }
                                        );
                                }
                                return label;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "#f1f5f9", drawBorder: false },
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000) {
                                    return (
                                        "S/ " + (value / 1000).toFixed(0) + "k"
                                    );
                                }
                                return "S/ " + value;
                            },
                        },
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                    },
                },
            },
        });
    },

    initProductsChart() {
        const canvas = document.getElementById("productsChart");
        if (!canvas) return;

        const productsData = window.reportsData?.topProducts || [];

        const labels = productsData.map((item) => item.name);
        const data = productsData.map((item) => parseInt(item.total_quantity));

        const ctx = canvas.getContext("2d");
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: [
                            "#3b82f6",
                            "#10b981",
                            "#eab308",
                            "#ef4444",
                            "#8b5cf6",
                        ],
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "65%",
                plugins: {
                    legend: {
                        position: "right",
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12 },
                        },
                    },
                    tooltip: {
                        backgroundColor: "#1e293b",
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const label = context.label || "";
                                const value = context.parsed || 0;
                                return label + ": " + value + " unidades";
                            },
                        },
                    },
                },
            },
        });
    },

    initHourlyChart() {
        const canvas = document.getElementById("hourlyChart");
        if (!canvas) return;

        const hourlyData = window.reportsData?.hourlyData || [];

        const labels = hourlyData.map((item) => item.hour + ":00");
        const revenueData = hourlyData.map((item) => parseFloat(item.revenue));

        const ctx = canvas.getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Ventas",
                        data: revenueData,
                        backgroundColor: "#10b981",
                        borderRadius: 4,
                        barPercentage: 0.7,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1e293b",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return (
                                    "Ventas: S/ " +
                                    context.parsed.y.toLocaleString("es-PE", {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2,
                                    })
                                );
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "#f1f5f9", drawBorder: false },
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000) {
                                    return (
                                        "S/ " + (value / 1000).toFixed(0) + "k"
                                    );
                                }
                                return "S/ " + value;
                            },
                        },
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                    },
                },
            },
        });
    },

    initPaymentChart() {
        const canvas = document.getElementById("paymentChart");
        if (!canvas) return;

        const paymentData = window.reportsData?.paymentMethods || [];

        const labels = paymentData.map((item) => {
            return item.payment_method === "cash" ? "Efectivo" : "Tarjeta";
        });
        const data = paymentData.map((item) => parseFloat(item.revenue));

        const ctx = canvas.getContext("2d");
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: ["#10b981", "#6366f1"],
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "65%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12 },
                        },
                    },
                    tooltip: {
                        backgroundColor: "#1e293b",
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const label = context.label || "";
                                const value = context.parsed || 0;
                                return (
                                    label +
                                    ": S/ " +
                                    value.toLocaleString("es-PE", {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2,
                                    })
                                );
                            },
                        },
                    },
                },
            },
        });
    },

    initDailyTrendsChart() {
        const canvas = document.getElementById("dailyTrendsChart");
        if (!canvas) return;

        const dailyData = window.reportsData?.dailyTrends || [];

        const labels = dailyData.map((item) => {
            const date = new Date(item.date);
            return date.toLocaleDateString("es-PE", {
                month: "short",
                day: "numeric",
            });
        });
        const revenueData = dailyData.map((item) => parseFloat(item.revenue));

        const ctx = canvas.getContext("2d");
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, "rgba(16, 185, 129, 0.2)");
        gradient.addColorStop(1, "rgba(16, 185, 129, 0)");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Ingresos Diarios",
                        data: revenueData,
                        borderColor: "#10b981",
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "#ffffff",
                        pointBorderColor: "#10b981",
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1e293b",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return (
                                    "Ingresos: S/ " +
                                    context.parsed.y.toLocaleString("es-PE", {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2,
                                    })
                                );
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "#f1f5f9", drawBorder: false },
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000) {
                                    return (
                                        "S/ " + (value / 1000).toFixed(0) + "k"
                                    );
                                }
                                return "S/ " + value;
                            },
                        },
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                    },
                },
            },
        });
    },
});
