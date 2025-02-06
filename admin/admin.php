<?php
require_once '../config.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch referral codes from the database
$sql = "SELECT id, name, username, referral_code FROM codes ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body class="!bg-white dark:!bg-gray-900 transition-colors duration-200">
    <?php include 'includes/header.php'; ?>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Dashboard Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-primary dark:text-accent mb-4 transition-colors duration-200">
                Admin Dashboard
            </h1>
            <h2 class="text-2xl text-gray-700 dark:text-gray-300 transition-colors duration-200">
                Manage referral codes and user submissions.
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-primary dark:text-accent mb-4">Manage Referral Codes</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-left">Username</th>
                            <th class="p-3 text-left">Referral Code</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="p-3 text-gray-900 dark:text-gray-100"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="p-3 text-gray-900 dark:text-gray-100"><?= htmlspecialchars($row['username']) ?></td>
                            <td class="p-3 text-gray-900 dark:text-gray-100"><?= htmlspecialchars($row['referral_code']) ?></td>
                            <td class="p-3">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline dark:text-red-400 ml-4">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-8">
            <h3 class="text-xl font-bold text-primary dark:text-accent mb-4">Add New Referral Code</h3>
            <form action="store_code.php" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                    <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="referral_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</label>
                    <input type="text" id="referral_code" name="referral_code" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-md">
                    Add Referral Code
                </button>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
