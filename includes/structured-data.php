<?php
function generateStructuredData() {
    $data = [
        "@context" => "https://schema.org",
        "@type" => "WebApplication",
        "name" => "Code To Adventure",
        "description" => "Find and share Rivian referral codes for rewards",
        "applicationCategory" => "ReferralProgram",
        "offers" => [
            "@type" => "Offer",
            "description" => "500 points and 6 months free charging",
            "price" => "0",
            "priceCurrency" => "USD"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.8",
            "ratingCount" => "150"
        ]
    ];
    
    return '<script type="application/ld+json">' . 
           json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) .
           '</script>';
}
?> 