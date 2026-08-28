<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) die("Student not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Application | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --bg: #f3f4f6; --surface: #ffffff; --border: #e5e7eb; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); padding: 40px; }
        .wrapper { max-width: 1100px; margin: auto; display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .card { background: var(--surface); padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h3 { color: var(--primary); margin-top: 0; border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 5px; }
        select, textarea, input { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-family: 'Poppins'; font-size: 14px; background: #f9fafb; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary); background: #fff; }
        button { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; margin-top: 20px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #172554; }
    </style>
</head>
<body>
    <a href="admin_dashboard.php" style="display:inline-block; margin-bottom: 20px; text-decoration:none; color:var(--primary); font-weight:600;">&larr; Back to Dashboard</a>
    
    <!-- We wrap BOTH cards in one form so everything saves at once -->
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

        <div class="wrapper">
            <!-- LEFT: Editable Student Data -->
            <div class="card">
                <h3>✏️ Edit Applicant Details</h3>
                
                <div class="grid-2">
                    <div><label>Full Name</label><input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required></div>
                    <div><label>Phone Number</label><input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required></div>
                    <div><label>Date of Birth</label><input type="date" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>"></div>
                    <div><label>Blood Group</label><input type="text" name="blood_group" value="<?php echo htmlspecialchars($student['blood_group']); ?>"></div>
                    <div style="grid-column: 1 / -1;"><label>Interested Course</label><input type="text" name="interested_course" value="<?php echo htmlspecialchars($student['interested_course']); ?>" required></div>
                </div>
                
                <h4 style="margin: 25px 0 15px; color: var(--primary);">Academic Data</h4>
                <div class="grid-2">
                    <div><label>10th Percentage</label><input type="number" step="0.01" name="tenth_percent" value="<?php echo htmlspecialchars($student['tenth_percent']); ?>"></div>
                    <div><label>12th Percentage</label><input type="number" step="0.01" name="twelfth_percent" value="<?php echo htmlspecialchars($student['twelfth_percent']); ?>"></div>
                    <div><label>Stream / Background</label><input type="text" name="background" value="<?php echo htmlspecialchars($student['background']); ?>"></div>
                    <div><label>Passing Year</label><input type="number" name="passing_year" value="<?php echo htmlspecialchars($student['passing_year']); ?>"></div>
                </div>
                
                <h4 style="margin: 25px 0 15px; color: var(--primary);">Family & Background</h4>
                <div class="grid-2">
                    <div><label>Father's Name</label><input type="text" name="father_name" value="<?php echo htmlspecialchars($student['father_name']); ?>"></div>
                    <div><label>Family Income (₹)</label><input type="number" name="family_income" value="<?php echo htmlspecialchars($student['family_income']); ?>"></div>
                    <div style="grid-column: 1 / -1;"><label>Address</label><textarea name="address" rows="2"><?php echo htmlspecialchars($student['address']); ?></textarea></div>
                </div>
            </div>

            <!-- RIGHT: Admin Actions -->
            <div class="card" style="background: #f8fafc; border: 2px solid var(--primary); height: fit-content;">
                <h3>⚙️ Admin Actions</h3>
                
                <label>Application Status</label>
                <select name="status">
                    <option value="Pending" <?php if($student['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Approved" <?php if($student['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                    <option value="Rejected" <?php if($student['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                </select>

                <label style="margin-top: 15px;">Custom Remarks (Visible to student)</label>
                <textarea name="admin_remarks" rows="5" placeholder="E.g., Please submit original marksheets..."><?php echo htmlspecialchars($student['admin_remarks']); ?></textarea>

                <?php if ($student['reset_request'] == 1): ?>
                    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ef4444;">
                    <label style="color: #dc2626; font-size: 15px;">⚠️ Password Reset Requested!</label>
                    <input type="text" name="new_password" placeholder="Enter new password to clear alert" required>
                    <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 5px;">Providing a password here will reset their login and clear this alert.</small>
                <?php endif; ?>

                <button type="submit">Save All Changes</button>
            </div>
        </div>
    </form>
</body>
</html>