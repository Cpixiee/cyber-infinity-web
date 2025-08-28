/**
 * ====================================================
 * DASHBOARD CHARTS JAVASCRIPT
 * Handles Chart.js initialization for dashboard
 * ====================================================
 */

/**
 * Initialize dashboard charts when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        initializeDashboardCharts();
    } else {
        console.error('Chart.js library not loaded');
    }
});

/**
 * Initialize all dashboard charts
 */
function initializeDashboardCharts() {
    initializeRegistrationChart();
    initializePerformanceChart();
}

/**
 * Initialize registration statistics chart
 */
function initializeRegistrationChart() {
    const registrationCtx = document.getElementById('registrationChart');
    if (!registrationCtx) {
        console.log('Registration chart canvas not found');
        return;
    }
    
    // Get data from global variables (set by blade template)
    const registrationData = window.chartData?.registration || {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        values: [0, 0, 0, 0, 0, 0, 0]
    };
    
    const maxValue = Math.max(...registrationData.values);
    console.log('Rendering registration chart with max value:', maxValue);
    
    const registrationChart = new Chart(registrationCtx.getContext('2d'), {
        type: 'line',
        data: {
            labels: registrationData.labels,
            datasets: [{
                label: 'Registrasi Harian',
                data: registrationData.values,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return `${context.parsed.y} user daftar`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#F3F4F6'
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6B7280',
                        stepSize: 1
                    },
                    max: Math.max(maxValue + 2, 5)
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6B7280'
                    }
                }
            }
        }
    });
}

/**
 * Initialize performance speedometer chart
 */
function initializePerformanceChart() {
    const performanceCtx = document.getElementById('performanceChart');
    if (!performanceCtx) {
        console.log('Performance chart canvas not found');
        return;
    }
    
    // Get performance data from global variables (set by blade template)
    let performancePercentage = window.chartData?.performance || 50;
    if (isNaN(performancePercentage) || performancePercentage < 0) {
        console.log('Using sample performance data');
        performancePercentage = 50; // Default sample
    }
    const remainingPerformance = 100 - performancePercentage;
    console.log('Rendering performance chart with:', performancePercentage + '%');
    
    // Color based on performance level (speedometer style)
    let performanceColor = '#EF4444'; // Red for low performance
    if (performancePercentage >= 80) {
        performanceColor = '#22C55E'; // Green for excellent
    } else if (performancePercentage >= 60) {
        performanceColor = '#3B82F6'; // Blue for good  
    } else if (performancePercentage >= 40) {
        performanceColor = '#F59E0B'; // Yellow for average
    }
    
    const performanceChart = new Chart(performanceCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [performancePercentage, remainingPerformance],
                backgroundColor: [performanceColor, '#E5E7EB'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.dataIndex === 0) {
                                return `Performance: ${performancePercentage.toFixed(1)}%`;
                            } else {
                                return `Potential: ${remainingPerformance.toFixed(1)}%`;
                            }
                        }
                    }
                }
            }
        }
    });
    
    // Add percentage text in center
    const centerText = {
        id: 'centerText',
        beforeDatasetsDraw: function(chart) {
            const ctx = chart.ctx;
            ctx.save();
            
            const centerX = chart.getDatasetMeta(0).data[0].x;
            const centerY = chart.getDatasetMeta(0).data[0].y;
            
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = 'bold 24px Inter, sans-serif';
            ctx.fillStyle = performanceColor;
            ctx.fillText(performancePercentage.toFixed(0) + '%', centerX, centerY - 5);
            
            ctx.font = '12px Inter, sans-serif';
            ctx.fillStyle = '#6B7280';
            ctx.fillText('Performance', centerX, centerY + 15);
            
            ctx.restore();
        }
    };
    
    Chart.register(centerText);
}

/**
 * Set chart data from blade template
 */
function setChartData(data) {
    window.chartData = data;
}

/**
 * Update charts with new data
 */
function updateCharts(newData) {
    window.chartData = newData;
    // Re-initialize charts with new data
    initializeDashboardCharts();
}
