class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }

    init() {
        this.observePageLoad();
        this.observeNetworkRequests();
        this.observeUserInteractions();
    }

    observePageLoad() {
        window.addEventListener('load', () => {
            const navigation = performance.getEntriesByType('navigation')[0];
            this.metrics.pageLoad = {
                dnsLookup: navigation.domainLookupEnd - navigation.domainLookupStart,
                tcpConnection: navigation.connectEnd - navigation.connectStart,
                firstByte: navigation.responseStart - navigation.requestStart,
                domInteractive: navigation.domInteractive,
                domComplete: navigation.domComplete
            };
            this.sendMetrics('pageLoad');
        });
    }

    // Additional monitoring methods...
} 