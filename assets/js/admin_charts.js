/**
 * MobileHub — Grand Admin Dashboard Charts
 */

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    // Premium Gradient for the chart
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 350);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.1)'); // Blue
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
            datasets: [{
                label: 'Monthly Revenue',
                data: [45000, 52000, 48000, 61000, 55000, 72000, 95000, 88000, 102000, 123781],
                borderColor: '#6366f1',
                borderWidth: 4,
                fill: true,
                backgroundColor: gradient,
                tension: 0.45,
                pointRadius: 0, // Clean look without points
                pointHitRadius: 10,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#6366f1',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' },
                    bodyFont: { family: 'Inter', size: 13 },
                    padding: 15,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '  Revenue: ₹ ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { family: 'Inter', size: 12, weight: '500' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.4)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { family: 'Inter', size: 12, weight: '500' },
                        callback: function(value) {
                            return '₹' + (value >= 1000 ? (value / 1000) + 'k' : value);
                        }
                    }
                }
            }
        }
    });
});
