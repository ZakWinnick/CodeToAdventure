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
            <h1>Changelog</h1>

            <div class="entry">
                <h3>Version 2025.4.1 - January 27, 2025</h3>
                <p>- Fixed an issue where some referral codes were being rejected due to regex issues</p>
            </div>

            <div class="entry">
                <h3>Version 2025.4 - January 24, 2025</h3>
                <p>- Updated styling for all pages, including bringing the site up to modern design language standards</p>
                <p>- Restored code tracking capabilities for admins</p>
            </div>

            <div class="entry">
                <h3>Version 2025.3.1 - January 23, 2025</h3>
                <p>- Restored functionality for submission e-mails to admins</p>
                <p>- Minor site updates for performance and stability</p>
            </div>

            <div class="entry">
                <h3>Version 2025.3 - January 22, 2025</h3>
                <p>- Added referral code tracking for statistical purposes</p>
                <p>- Minor performance and security improvements</p>
            </div>

            <div class="entry">
                <h3>Version 2025.2 - January 21, 2025</h3>
                <p>- Added a "Get Another Code" button so you don't have to refresh the page to get a new code</p>
                <p>- Changed the site infrastructure to pull out CSS and JS code and place them in their own sections</p>
                <p>- Enhanced some visual elements, including adding icons to the rewards section</p>
            </div>

            <div class="entry">
                <h3>Version 2025.1.1 - January 18, 2025</h3>
                <p>- Updated formatting of the new 'Copy Code' button to match the rest of the site</p>
            </div>

            <div class="entry">
                <h3>Version 2025.1 - January 17, 2025</h3>
                <p>- Added a "Copy Code" modal that also display's referree's code</p>
                <p>- Changed version numbering to new methodology (Year.Version.Fix)</p>
            </div>

            <div class="entry">
                <h3>Version 2024.12.28 - December 28, 2024</h3>
                <p>- Updated color scheme</p>
                <p>- Added a working modal dialog for code submission</p>
                <p>- Improved the navigation menu styling</p>
                <p>- Made button interactions more intuitive</p>
                <p>- Created consistent styling across all components</p>
                <p>- Added proper mobile responsiveness</p>
                <p>- Improved user experience with cancel/submit options</p>
                <p>- Updated referral points to reflect program changes by Rivian</p>
            </div>
            
            <div class="entry">
                <h3>Version 2024.12.24 - December 24, 2024</h3>
                <p>- Updated Admin Portal with sortable tables and pagination</p>
            </div>

            <div class="entry">
                <h3>Version 2024.12.18 - December 18, 2024</h3>
                <p>- Redesigned mobile navigation with improved layout and touch interaction</p>
                <p>- Enhanced site performance through optimized resource loading</p>
                <p>- Implemented comprehensive responsive design improvements</p>
                <p>- Updated API documentation to reflect all API calls</p>
            </div>

            <div class="entry">
                <h3>Version 2024.12.11 - December 11, 2024</h3>
                <p>- Complete refresh of the design language for the site</p>
                <p>- Simplified backend code for efficiency</p>
                <p>- Revamped admin console design, and added persistent login via browser cookies</p>
                <p>- Removed X Username field from Submit Code page</p>
            </div>

            <div class="entry">
                <h3>Version 2024.12.10 - December 10, 2024</h3>
                <p>- Added admin referral code banner</p>
            </div>

            <div class="entry">
                <h3>Version 2024.11.13 - November 13, 2024</h3>
                <p>- Created new admin dashboard for editing and deleting entries</p>
                <p>- Added email notifications for new submissions</p>
            </div>

            <div class="entry">
                <h3>Version 2024.11.7 - November 7, 2024</h3>
                <p>- Moved development to GitHub for version tracking</p>
                <p>- Made Twitter handle submission optional</p>
            </div>

            <div class="entry">
                <h3>Version 2.0 - September 29, 2024</h3>
                <p>- Updated page titles for SEO</p>
                <p>- Improved layout and responsiveness</p>
                <p>- Added random header images</p>
                <p>- Enhanced footer links and meta tags</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/modal.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>