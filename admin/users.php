<?php
session_start(); // Start the session to check if the user is logged in

// If no user is logged in or not an admin, redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page
    exit();  // Ensure no further code is executed
}

include '../config.php';  // Adjust the path as necessary

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Query to get the total number of users
$totalSqlUsers = "SELECT COUNT(*) as total FROM users";
$totalResultUsers = $conn->query($totalSqlUsers);
$totalRowUsers = $totalResultUsers->fetch_assoc();
$totalUsers = $totalRowUsers['total']; // Store the total user count
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <header>Admin Dashboard - Manage Users</header>
    
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="main-content">
        <div class="total-submissions">
            <strong>Total Users: <?php echo $totalUsers; ?></strong>
        </div>

        <h3>Add New User</h3>
        <form action="add_user.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <br>
            <button type="submit">Add User</button>
        </form>

        <h3>Existing Users</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
    </footer>
</body>
</html>
