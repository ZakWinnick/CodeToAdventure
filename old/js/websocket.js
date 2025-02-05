class RealTimeUpdates {
  constructor() {
    this.socket = null;
    this.reconnectAttempts = 0;
    this.maxReconnectAttempts = 5;
    this.reconnectDelay = 1000;
    this.init();
  }

  init() {
    const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${wsProtocol}//${window.location.host}/ws`;
    
    this.socket = new WebSocket(wsUrl);
    this.attachEventListeners();
  }

  attachEventListeners() {
    this.socket.addEventListener('open', () => {
      console.log('WebSocket connected');
      this.reconnectAttempts = 0;
    });

    this.socket.addEventListener('message', (event) => {
      const data = JSON.parse(event.data);
      this.handleMessage(data);
    });

    this.socket.addEventListener('close', () => {
      this.handleDisconnect();
    });
  }

  handleMessage(data) {
    switch (data.type) {
      case 'new_code':
        this.updateCodeList(data.code);
        break;
      case 'stats_update':
        this.updateDashboardStats(data.stats);
        break;
      // ... handle other message types
    }
  }
} 