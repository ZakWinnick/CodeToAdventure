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
            <h1>API Documentation</h1>
            <p>Welcome to the Code to Adventure API! Below are the details of the available endpoints, rate limits, and usage instructions.</p>

            <h2>Endpoints</h2>

            <h3>1. Fetch a Random Referral Code</h3>
            <p><strong>URL:</strong> <code>GET /api.php?path=random</code></p>
            <p><strong>Description:</strong> Returns a random referral code from the database.</p>
            <p><strong>Example Response:</strong></p>
            <pre><code>{
    "name": "John Doe",
    "username": "johndoe123",
    "referral_code": "ABC123",
    "link": "https://rivian.com/configurations/list?reprCode=ABC123"
}</code></pre>

            <h3>2. Fetch All Referral Codes</h3>
            <p><strong>URL:</strong> <code>GET /api.php?path=codes</code></p>
            <p><strong>Description:</strong> Returns all referral codes stored in the database.</p>
            <p><strong>Example Response:</strong></p>
            <pre><code>[
    {
        "name": "John Doe",
        "username": "johndoe123",
        "referral_code": "ABC123",
        "link": "https://rivian.com/configurations/list?reprCode=ABC123"
    },
    {
        "name": "Jane Smith",
        "username": "janesmith456",
        "referral_code": "XYZ456",
        "link": "https://rivian.com/configurations/list?reprCode=XYZ456"
    }
]</code></pre>

            <h3>3. Invalid API Path</h3>
            <p>If an invalid endpoint is requested, the API will return the following response:</p>
            <pre><code>{
    "error": "Invalid API endpoint"
}</code></pre>

            <h2>Rate Limiting</h2>
            <div class="rate-limit">
                <p><strong>Limit:</strong> 100 requests per minute per IP.</p>
                <p><strong>Exceeding the limit:</strong> Returns the following response:</p>
                <pre><code>{
    "error": "Rate limit exceeded. Try again later."
}</code></pre>
            </div>

            <h2>Response Codes</h2>
            <p>The API uses standard HTTP response codes:</p>
            <ul>
                <li><strong>200 OK:</strong> Request successful.</li>
                <li><strong>404 Not Found:</strong> Endpoint does not exist.</li>
                <li><strong>429 Too Many Requests:</strong> Rate limit exceeded.</li>
                <li><strong>500 Internal Server Error:</strong> Server issue.</li>
            </ul>

            <h2>Usage Instructions</h2>
            <p>To use the API, send a <code>GET</code> request to the specified endpoint. You can test it using a browser, tools like Postman, or integrate it into your application.</p>
            <p>Respect the rate limit for consistent access.</p>

            <a href="index.php" class="back-link">Back to Home</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/modal.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>