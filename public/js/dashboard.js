let stockChartInstance = null;
let transactionReportChartInstance = null;

function initializeCharts() {
    const isDarkMode = document.documentElement.classList.contains('dark');
    const chartTextColor = isDarkMode ? '#E5E7EB' : '#374151';
    const chartBorderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

    Chart.defaults.color = chartTextColor;
    Chart.defaults.borderColor = chartBorderColor;
    Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";

    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: true, // Ubah ke true untuk hover spesifik
            mode: 'point', // Ubah ke 'point' untuk target satu elemen
            axis: 'xy' // Tambahkan untuk presisi hover
        },
        onHover: (event, activeElements, chart) => {
            // Mengubah cursor saat hover
            event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: chartTextColor,
                    usePointStyle: true,
                    padding: 20,
                    font: { size: 12, weight: '500' }
                },
                onClick: (e, legendItem, legend) => {
                    const index = legendItem.datasetIndex;
                    const ci = legend.chart;
                    if (ci.isDatasetVisible(index)) {
                        ci.hide(index);
                        legendItem.hidden = true;
                    } else {
                        ci.show(index);
                        legendItem.hidden = false;
                    }
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: isDarkMode ? 'rgba(17, 24, 39, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                titleColor: isDarkMode ? '#F3F4F6' : '#1F2937',
                bodyColor: isDarkMode ? '#E5E7EB' : '#4B5563',
                borderColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                borderWidth: 1,
                cornerRadius: 12,
                padding: 12,
                displayColors: true,
                usePointStyle: true,
                // Konfigurasi tambahan untuk tooltip yang lebih presisi
                filter: function(tooltipItem) {
                    return tooltipItem.parsed.y !== null;
                },
                itemSort: function(a, b) {
                    return b.parsed.y - a.parsed.y;
                }
            }
        },
        scales: {
            x: {
                grid: { drawBorder: false },
                ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
            },
            y: {
                grid: { drawBorder: false },
                ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
            }
        },
        elements: {
            point: { 
                radius: 3, 
                hoverRadius: 8, // Perbesar hover radius untuk area hover yang lebih jelas
                borderWidth: 2,
                hoverBorderWidth: 3 // Tambahkan border width saat hover
            },
            line: { 
                tension: 0.4,
                hoverBorderWidth: 4 // Border lebih tebal saat hover untuk line chart
            },
            bar: {
                hoverBorderWidth: 3, // Border lebih tebal saat hover untuk bar chart
                hoverBackgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return;
                    
                    // Buat gradient yang lebih terang untuk hover
                    const gradient = ctx.createLinearGradient(0, 0, 600, 0);
                    gradient.addColorStop(0, isDarkMode ? 'rgba(34, 197, 94, 0.4)' : 'rgba(22, 163, 74, 0.4)');
                    gradient.addColorStop(1, isDarkMode ? 'rgba(34, 197, 94, 1)' : 'rgba(22, 163, 74, 1)');
                    return gradient;
                }
            }
        },
        animation: { duration: 1500, easing: 'easeInOutQuart' }
    };

    if (stockChartInstance) stockChartInstance.destroy();
    if (transactionReportChartInstance) transactionReportChartInstance.destroy();

    // --- 1. Stok Chart (Horizontal Bar Chart) ---
    const stockCtx = document.getElementById('stockChart')?.getContext('2d');
    if (stockCtx && typeof stockLabels !== 'undefined' && stockData.length > 0) {
        stockChartInstance = new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: stockLabels,
                datasets: [{
                    label: 'Stok Barang',
                    data: stockData,
                    backgroundColor: (ctx) => {
                        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 600, 0);
                        gradient.addColorStop(0, isDarkMode ? 'rgba(34, 197, 94, 0.2)' : 'rgba(22, 163, 74, 0.2)');
                        gradient.addColorStop(1, isDarkMode ? 'rgba(34, 197, 94, 0.9)' : 'rgba(22, 163, 74, 0.9)');
                        return gradient;
                    },
                    borderColor: isDarkMode ? '#22C55E' : '#16A34A',
                    borderWidth: 2,
                    borderRadius: { topRight: 6, bottomRight: 6, topLeft: 0, bottomLeft: 0 },
                    borderSkipped: false,
                    // Konfigurasi hover khusus untuk bar chart
                    hoverBackgroundColor: (ctx) => {
                        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 600, 0);
                        gradient.addColorStop(0, isDarkMode ? 'rgba(34, 197, 94, 0.4)' : 'rgba(22, 163, 74, 0.4)');
                        gradient.addColorStop(1, isDarkMode ? 'rgba(34, 197, 94, 1)' : 'rgba(22, 163, 74, 1)');
                        return gradient;
                    },
                    hoverBorderColor: isDarkMode ? '#10B981' : '#059669',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                ...commonChartOptions,
                indexAxis: 'y',
                interaction: {
                    intersect: true, // Hover hanya pada bar yang tepat diklik
                    mode: 'nearest', // Mode nearest untuk bar chart horizontal
                    axis: 'y' // Fokus pada axis Y untuk horizontal bar
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { drawBorder: false },
                        ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                    },
                    y: {
                        grid: { drawBorder: false },
                        ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                    }
                },
                plugins: {
                    ...commonChartOptions.plugins,
                    legend: {
                        display: false
                    },
                    tooltip: {
                        ...commonChartOptions.plugins.tooltip,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.x} unit`;
                            }
                        }
                    }
                }
            }
        });
    }

    // --- 2. Transaction Report Chart (Line Chart) ---
    const transactionCtx = document.getElementById('transactionReportChart')?.getContext('2d');
    if (transactionCtx && typeof transactionLabels !== 'undefined') {
        transactionReportChartInstance = new Chart(transactionCtx, {
            type: 'line',
            data: {
                labels: transactionLabels,
                datasets: [
                    {
                        label: 'Transaksi Masuk',
                        data: incomingTransactionData,
                        borderColor: isDarkMode ? '#3B82F6' : '#2563EB',
                        backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.2)' : 'rgba(37, 99, 235, 0.2)',
                        fill: true,
                        pointBackgroundColor: isDarkMode ? '#3B82F6' : '#2563EB',
                        pointBorderColor: isDarkMode ? '#1E40AF' : '#1D4ED8',
                        pointHoverBackgroundColor: isDarkMode ? '#60A5FA' : '#3B82F6',
                        pointHoverBorderColor: isDarkMode ? '#2563EB' : '#1E40AF',
                        pointHoverRadius: 8,
                        hoverBorderWidth: 4
                    }, {
                        label: 'Transaksi Keluar',
                        data: outgoingTransactionData,
                        borderColor: isDarkMode ? '#EF4444' : '#DC2626',
                        backgroundColor: isDarkMode ? 'rgba(239, 68, 68, 0.2)' : 'rgba(220, 38, 38, 0.2)',
                        fill: true,
                        pointBackgroundColor: isDarkMode ? '#EF4444' : '#DC2626',
                        pointBorderColor: isDarkMode ? '#DC2626' : '#B91C1C',
                        pointHoverBackgroundColor: isDarkMode ? '#F87171' : '#EF4444',
                        pointHoverBorderColor: isDarkMode ? '#DC2626' : '#B91C1C',
                        pointHoverRadius: 8,
                        hoverBorderWidth: 4
                    }, {
                        label: 'Total Transaksi',
                        data: combinedTransactionData,
                        borderColor: isDarkMode ? '#A855F7' : '#9333EA',
                        borderWidth: 4,
                        borderDash: [5, 5],
                        fill: false,
                        pointBackgroundColor: isDarkMode ? '#A855F7' : '#9333EA',
                        pointBorderColor: isDarkMode ? '#9333EA' : '#7C3AED',
                        pointHoverBackgroundColor: isDarkMode ? '#C084FC' : '#A855F7',
                        pointHoverBorderColor: isDarkMode ? '#9333EA' : '#7C3AED',
                        pointHoverRadius: 8,
                        hoverBorderWidth: 5
                    }
                ]
            },
            options: {
                ...commonChartOptions,
                interaction: {
                    intersect: false, // Untuk line chart, bisa false agar lebih mudah hover
                    mode: 'point', // Point mode untuk hover spesifik pada titik
                    axis: 'x' // Fokus pada axis X
                },
                plugins: {
                    ...commonChartOptions.plugins,
                    tooltip: {
                        ...commonChartOptions.plugins.tooltip,
                        callbacks: {
                            title: function(context) {
                                return `Periode: ${context[0].label}`;
                            },
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} transaksi`;
                            }
                        }
                    }
                }
            }
        });
    }
}

// Pastikan DOM sudah siap sebelum menjalankan script
document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi hanya jika elemen canvas ada di halaman
    if (document.getElementById('stockChart') || document.getElementById('transactionReportChart')) {
        initializeCharts();
    }
    
    // Event listener untuk theme-toggle, jika ada
    document.getElementById('theme-toggle')?.addEventListener('click', () => {
        setTimeout(() => {
             if (document.getElementById('stockChart') || document.getElementById('transactionReportChart')) {
                initializeCharts();
            }
        }, 100);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.style.animationPlayState = 'running'; });
    }, { threshold: 0.1, rootMargin: '0px 0px -10% 0px' });

    document.querySelectorAll('.animate-fade-in-up').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });

    function createParticle() {
        const particle = document.createElement('div');
        const isDarkMode = document.documentElement.classList.contains('dark');
        const particleColor = isDarkMode ? 'bg-blue-400/20' : 'bg-blue-600/20';
        particle.className = `fixed w-1 h-1 ${particleColor} rounded-full pointer-events-none animate-ping`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.top = `${Math.random() * 100}vh`;
        particle.style.animationDelay = `${Math.random() * 2}s`;
        document.body.appendChild(particle);
        setTimeout(() => particle.remove(), 4000);
    }
    setInterval(createParticle, 3000);
});