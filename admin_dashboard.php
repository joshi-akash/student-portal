<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Catch the search query if one exists
$search = trim($_GET['search'] ?? '');
$search_count = 0; // To track how many results we found

if (!empty($search)) {
    // If searching: Look for partial matches in full_name OR exact/partial matches in phone_number
    $sql = "SELECT id, full_name, phone_number, interested_course, status, reset_request 
            FROM students 
            WHERE full_name LIKE ? OR phone_number LIKE ? 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $search_count = $result->num_rows; // Count the specific matches
} else {
    // If no search: Load everyone
    $sql = "SELECT id, full_name, phone_number, interested_course, status, reset_request 
            FROM students 
            ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard | Doon University</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --bg: #f3f4f6; --surface: #ffffff; --border: #e5e7eb; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; padding: 40px; }
        .container { max-width: 1200px; margin: auto; background: var(--surface); padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        /* Search Bar & Feedback Styling */
        .search-container { display: flex; flex-direction: column; align-items: flex-end; margin-bottom: 20px; }
        .search-form { display: flex; gap: 10px; width: 100%; max-width: 450px; }
        .search-form input { flex: 1; padding: 10px 15px; border: 1px solid var(--border); border-radius: 8px; font-family: 'Poppins'; outline: none; transition: border-color 0.3s; }
        .search-form input:focus { border-color: var(--primary); }
        .search-form button { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 500; transition: 0.3s; }
        .search-form button:hover { background: #172554; }
        .btn-clear { background: #f3f4f6; color: #4b5563; border: 1px solid var(--border); padding: 10px 15px; border-radius: 8px; text-decoration: none; font-size: 14px; display: flex; align-items: center; }
        .btn-clear:hover { background: #e5e7eb; }

        /* NEW: Search Feedback Banner */
        .search-feedback { width: 100%; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-top: 15px; text-align: left; animation: fadeIn 0.3s ease-in; }
        .feedback-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .feedback-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f8fafc; color: var(--primary); font-weight: 600; }
        .btn-edit { background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; margin-right: 5px; }
        .btn-delete { background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; }
        .btn-delete:hover { background: #dc2626; }
        .alert { background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .empty-state { text-align: center; padding: 40px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0; color: var(--primary);">Application Manager</h2>
            <a href="logout.php" style="color: #dc2626; text-decoration: none; font-weight: 600;">Logout Admin</a>
        </div>
        
        <?php 
            if(isset($_GET['msg']) && $_GET['msg'] == 'updated') echo "<p style='color: #10b981; font-weight: 500;'>✅ Record successfully updated.</p>"; 
            if(isset($_GET['msg']) && $_GET['msg'] == 'deleted') echo "<p style='color: #ef4444; font-weight: 500;'>🗑️ Application permanently deleted.</p>"; 
        ?>

        <!-- Search Bar UI -->
        <div class="search-container">
            <div style="width: 100%; display: flex; justify-content: flex-end;">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search by Student Name or Phone..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                    <?php if(!empty($search)): ?>
                        <a href="admin_dashboard.php" class="btn-clear">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- NEW: Dynamic Search Feedback Box -->
            <?php if(!empty($search)): ?>
                <?php if($search_count > 0): ?>
                    <div class="search-feedback feedback-success">
                        ✅ <strong>Result Found:</strong> Showing <?php echo $search_count; ?> application(s) matching "<?php echo htmlspecialchars($search); ?>".
                    </div>
                <?php else: ?>
                    <div class="search-feedback feedback-error">
                        ⚠️ <strong>No Results Found:</strong> No student matched the details for "<?php echo htmlspecialchars($search); ?>".
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Status</th>
                <th>Alerts</th>
                <th>Action</th>
            </tr>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['interested_course']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['status']); ?></strong></td>
                    <td>
                        <?php if($row['reset_request'] == 1) echo "<span class='alert'>Password Reset Req!</span>"; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Review</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to permanently delete this application?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty state if search finds nothing -->
                <tr>
                    <td colspan="7" class="empty-state">
                        Ensure you typed the name or 10-digit phone number correctly.
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>