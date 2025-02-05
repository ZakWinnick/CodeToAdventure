async function initCharts() {
    // Daily Submissions Chart
    const submissionsCtx = document.getElementById('submissionsChart').getContext('2d');
    new Chart(submissionsCtx, {
        type: 'line',
        data: await fetchSubmissionsData(),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Similar setup for usage and traffic charts
}

async function fetchSubmissionsData() {
    const response = await fetch('api/dashboard/submissions');
    const data = await response.json();
    return {
        labels: data.dates,
        datasets: [{
            label: 'Daily Submissions',
            data: data.counts,
            borderColor: 'rgb(144, 169, 85)',
            tension: 0.1
        }]
    };
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', initCharts); 