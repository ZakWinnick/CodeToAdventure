class RealTimeAnalytics {
    constructor() {
        this.charts = {};
        this.socket = new WebSocket('wss://codetoadventure.com/ws');
        this.initializeCharts();
        this.setupWebSocket();
    }

    setupWebSocket() {
        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.updateCharts(data);
        };

        // Subscribe to analytics channel
        this.socket.onopen = () => {
            this.socket.send(JSON.stringify({
                action: 'subscribe',
                channel: 'analytics'
            }));
        };
    }

    updateCharts(data) {
        switch (data.type) {
            case 'new_visit':
                this.updateVisitorsChart(data);
                break;
            case 'new_code':
                this.updateCodesChart(data);
                break;
            case 'code_usage':
                this.updateUsageChart(data);
                break;
        }
    }

    // Chart update methods...
} 