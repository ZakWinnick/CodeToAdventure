<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <div class="content">
        <div class="api-docs">
            <h1>API Documentation v2.0</h1>
            
            <section id="overview">
                <h2>Overview</h2>
                <p>The Code to Adventure API provides programmatic access to referral codes. All requests must use HTTPS.</p>
                
                <h3>Base URL</h3>
                <code>https://codetoadventure.com/api.php</code>
            </section>

            <section id="authentication">
                <h2>Authentication</h2>
                <p>Currently, the API uses IP-based rate limiting without authentication.</p>
            </section>

            <section id="endpoints">
                <h2>Endpoints</h2>

                <article class="endpoint">
                    <h3>1. Fetch Random Referral Code</h3>
                    <p><strong>Request:</strong> <code>GET /api.php?path=random</code></p>
                    <p><strong>Response:</strong></p>
                    <pre><code>{
    "name": "John Doe",
    "username": "johndoe123",
    "referral_code": "ABC123",
    "link": "https://rivian.com/configurations/list?reprCode=ABC123"
}</code></pre>
                </article>

                <article class="endpoint">
                    <h3>2. Fetch All Referral Codes</h3>
                    <p><strong>Request:</strong> <code>GET /api.php?path=codes</code></p>
                    <p><strong>Response:</strong></p>
                    <pre><code>[
    {
        "name": "John Doe",
        "username": "johndoe123",
        "referral_code": "ABC123",
        "link": "https://rivian.com/configurations/list?reprCode=ABC123"
    }
]</code></pre>
                </article>
            </section>

            <section id="rate-limiting">
                <h2>Rate Limiting</h2>
                <p>Limits: 100 requests per minute per IP address.</p>
                <p>Response Headers:</p>
                <pre><code>X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1635789600</code></pre>
            </section>

            <section id="errors">
                <h2>Error Responses</h2>
                <pre><code>{
    "error": {
        "code": "ERROR_CODE",
        "message": "Human readable message"
    }
}</code></pre>
                <h3>HTTP Status Codes</h3>
                <ul>
                    <li><strong>200</strong> - Success</li>
                    <li><strong>404</strong> - Not Found</li>
                    <li><strong>429</strong> - Rate Limit Exceeded</li>
                    <li><strong>500</strong> - Server Error</li>
                </ul>
            </section>

            <section id="caching">
                <h2>Caching</h2>
                <p>Responses are cached for 5 minutes. Use the ETag header for conditional requests.</p>
            </section>

            <a href="index.php" class="back-link">Back to Home</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/modal.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>