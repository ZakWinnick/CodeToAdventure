<header class="bg-gray-200 dark:bg-gray-900 shadow-md">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/" class="text-primary dark:text-accent text-2xl font-bold transition-colors duration-200">
                Code To Adventure
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">Home</a>
                <button onclick="showModal()" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">Submit Code</button>
                <a href="/api-docs.php" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">API Docs</a>
                <a href="/changelog.php" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">Changelog</a>
                <a href="/admin" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">Admin</a>

                <!-- Dark mode toggle -->
                <button onclick="toggleTheme()" class="p-2 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200">
                    <svg class="hidden dark:inline-block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="inline-block dark:hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden p-3 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200" 
                aria-label="Toggle menu" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <nav id="mobile-menu" class="hidden md:hidden flex flex-col items-center w-full space-y-4 mt-4 pb-4 border-t border-gray-300 dark:border-gray-700">
            <a href="/" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">Home</a>
            <button onclick="showModal()" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">Submit Code</button>
            <a href="/api-docs.php" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">API Docs</a>
            <a href="/changelog.html" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">Changelog</a>
            <a href="/admin" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">Admin</a>
            <button onclick="toggleTheme()" class="block w-full text-center text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-accent transition-colors duration-200 py-2">Toggle Dark Mode</button>
        </nav>
    </div>
</header>
