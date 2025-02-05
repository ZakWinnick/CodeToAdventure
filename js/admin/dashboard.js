// Chart configurations and data fetching
class DashboardCharts {
  constructor() {
    this.charts = {};
    this.initializeCharts();
  }

  async initializeCharts() {
    await this.createUsageChart();
    await this.createTrafficChart();
    await this.createGeographyChart();
    this.setupResponsiveCharts();
  }

  async createUsageChart() {
    const ctx = document.getElementById('usageChart').getContext('2d');
    const data = await this.fetchUsageData();
    
    this.charts.usage = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Code Usage',
          data: data.values,
          borderColor: 'rgb(144, 169, 85)',
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Daily Code Usage'
          }
        }
      }
    });
  }

  // Add more chart initialization methods...
} 