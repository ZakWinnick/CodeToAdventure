<?php
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<?php include 'includes/modal.php'; ?>

<body class="!bg-white dark:!bg-gray-900 transition-colors duration-200">
    <?php include 'includes/header.php'; ?>

    <main class="main-content max-w-7xl mx-auto px-4 py-8">
        <!-- Changelog Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-primary dark:text-accent mb-4 transition-colors duration-200">
                Changelog
            </h1>
            <h2 class="text-2xl text-gray-700 dark:text-gray-300 transition-colors duration-200">
                Latest updates and improvements.
            </h2>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">

        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">
                    Version 2025.7.2 - February 19, 2025
                </h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Introduced the new site logo, and implemented it in the header (both light and dark mode).</li>                
                </ul>
            </div>

        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">
                    Version 2025.7.1 - February 6, 2025
                </h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Fixed modal appearance on API Docs and Changelog pages</li>
                    <li>Fixed a bug where you were not able to click/tap out of the Submit Code modal</li>
                    <li>Centered toast notifications for better visibility</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">
                    Version 2025.7 - February 5, 2025
                </h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated design language of the entire site to make it look more clean</li>
                    <li>Added light/dark mode with toggle and auto-detect state</li>
                    <li>Improved mobile formatting for navigation, header, and footer</li>
                    <li>Completely overhauled `api-docs.php` and `changelog.php` for clarity, styling, and accuracy</li>
                    <li>Optimized JavaScript and CSS for better load times</li>
                </ul>
            </div>

            <!-- Previous Versions -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.6 - February 4, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated API to v2 (see API Documentation for more info)</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.5.1 - February 1, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Fixed code to schedule X posts for every 4 hours to work around X API limits</li>
                    <li>Added a new favicon to the header</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.5 - January 31, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Each newly submitted code is now posted to @CodeToAdventure on X</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.4.3 - January 30, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Fixed an issue where codes with only 2 letters before the numbers were being rejected</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.4.2 - January 29, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated code validation to look for 7 or more numbers to match new referral codes being issued by Rivian</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.4.1 - January 27, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Fixed an issue where some referral codes were being rejected due to regex issues (thanks to Zach for the report!)</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.4 - January 24, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated styling for all pages, including bringing the site up to modern design language standards</li>
                    <li>Restored code tracking capabilities for admins</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.3.1 - January 23, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Restored functionality for submission e-mails to admins</li>
                    <li>Minor site updates for performance and stability</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.3 - January 22, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Added referral code tracking for statistical purposes</li>
                    <li>Minor performance and security improvements</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.2 - January 21, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Added a "Get Another Code" button so you don't have to refresh the page to get a new code</li>
                    <li>Changed the site infrastructure to pull out CSS and JS code and place them in their own sections</li>
                    <li>Enhanced some visual elements, including adding icons to the rewards section</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.1.1 - January 18, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated formatting of the new 'Copy Code' button to match the rest of the site</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2025.1 - January 17, 2025</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Added a "Copy Code" modal that also displays referree's code</li>
                    <li>Changed version numbering to new methodology (Year.Version.Fix)</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.12.28 - December 28, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated color scheme</li>
                    <li>Added a working modal dialog for code submission</li>
                    <li>Improved the navigation menu styling</li>
                    <li>Made button interactions more intuitive</li>
                    <li>Created consistent styling across all components</li>
                    <li>Added proper mobile responsiveness</li>
                    <li>Improved user experience with cancel/submit options</li>
                    <li>Updated referral points to reflect program changes by Rivian</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.12.24 - December 24, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated Admin Portal with sortable tables and pagination</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.12.18 - December 18, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Redesigned mobile navigation with improved layout and touch interaction</li>
                    <li>Enhanced site performance through optimized resource loading</li>
                    <li>Implemented comprehensive responsive design improvements</li>
                    <li>Updated API documentation to reflect all API calls</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.12.11 - December 11, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Complete refresh of the design language for the site</li>
                    <li>Simplified backend code for efficiency</li>
                    <li>Revamped admin console design, and added persistent login via browser cookies</li>
                    <li>Removed X Username field from Submit Code page</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.12.10 - December 10, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Added admin referral code banner</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.11.13 - November 13, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Created new admin dashboard for editing and deleting entries</li>
                    <li>Added email notifications for new submissions</li>
                </ul>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2024.11.7 - November 7, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Moved development to GitHub for version tracking</li>
                    <li>Made Twitter handle submission optional</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200 text-left">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Version 2.0 - September 29, 2024</h3>
                <ul class="list-disc ml-4 text-gray-900 dark:text-gray-300">
                    <li>Updated page titles for SEO</li>
                    <li>Improved layout and responsiveness</li>
                    <li>Added random header images</li>
                    <li>Enhanced footer links and meta tags</li>
                </ul>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>