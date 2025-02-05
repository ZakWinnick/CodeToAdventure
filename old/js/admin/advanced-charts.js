class AdvancedDashboardCharts extends DashboardCharts {
  async createGeographyChart() {
    const data = await this.fetchGeographyData();
    const ctx = document.getElementById('geographyChart').getContext('2d');
    
    this.charts.geography = new Chart(ctx, {
      type: 'bubble',
      data: {
        datasets: [{
          label: 'Code Usage by Region',
          data: data.map(item => ({
            x: item.longitude,
            y: item.latitude,
            r: item.usage * 5
          })),
          backgroundColor: 'rgba(144, 169, 85, 0.6)'
        }]
      },
      options: {
        scales: {
          x: {
            type: 'linear',
            position: 'bottom'
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: (context) => {
                return `${data[context.dataIndex].region}: ${data[context.dataIndex].usage} uses`;
              }
            }
          }
        }
      }
    });
  }

  async createHourlyUsageChart() {
    const data = await this.fetchHourlyData();
    const ctx = document.getElementById('hourlyChart').getContext('2d');
    
    this.charts.hourly = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Array.from({length: 24}, (_, i) => `${i}:00`),
        datasets: [{
          label: 'Hourly Usage',
          data: data.counts,
          backgroundColor: 'rgba(79, 119, 45, 0.8)'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
} 