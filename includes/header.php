<?php
// $currentPage should be set before including this file
// e.g., $currentPage = 'home';
if (!isset($currentPage)) {
    $currentPage = '';
}
if (!isset($isAdmin)) {
    $isAdmin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}
// $basePath should be set for admin pages (e.g., $basePath = '../')
if (!isset($basePath)) {
    $basePath = '';
}
// $isAdminPage indicates if we're on an admin page (show Logout instead of Submit Code)
if (!isset($isAdminPage)) {
    $isAdminPage = false;
}
?>
<header class="header">
    <div class="header-content">
        <a href="<?php echo $basePath; ?>index.php" class="logo">
            <div class="logo-icon">
                <img src="<?php echo $basePath; ?>logo-dark.png" alt="Code to Adventure" id="logo-img">
            </div>
            <div class="logo-text">Code to Adventure</div>
        </a>

        <nav class="nav" id="nav">
            <a href="<?php echo $basePath; ?>index.php" class="nav-link<?php echo $currentPage === 'home' ? ' active' : ''; ?>">Home</a>
            <?php if (!$isAdminPage): ?>
            <a href="<?php echo $basePath; ?>submit.php" class="nav-link<?php echo $currentPage === 'submit' ? ' active' : ''; ?>">Submit Code</a>
            <?php endif; ?>
            <a href="<?php echo $basePath; ?>api-docs.php" class="nav-link<?php echo $currentPage === 'api-docs' ? ' active' : ''; ?>">API Docs</a>
            <a href="<?php echo $basePath; ?>changelog.php" class="nav-link<?php echo $currentPage === 'changelog' ? ' active' : ''; ?>">Changelog</a>
            <?php if ($isAdmin && !$isAdminPage): ?>
            <a href="<?php echo $basePath; ?>admin/admin.php" class="nav-link admin-link">Admin</a>
            <?php endif; ?>
            <?php if ($isAdminPage): ?>
            <a href="logout.php" class="nav-link">Logout</a>
            <?php endif; ?>
        </nav>

        <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>
</header>
