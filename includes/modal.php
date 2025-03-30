<div id="submitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md w-full mx-4 relative">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Submit Your Referral Code</h2>
        
        <form onsubmit="handleFormSubmit(event)" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500">
            </div>

            <div>
                <label for="referralCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</label>
                <input type="text" id="referralCode" name="referralCode" placeholder="Ex: ZAK1452284" required
                    pattern="(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+"
                    title="Code must contain at least 2 letters and 7 numbers"
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500">
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-md">
                    Submit Code
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Toast notification for feedback -->
<div id="toast" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg hidden z-50 transition-opacity duration-300 opacity-100 border"></div>