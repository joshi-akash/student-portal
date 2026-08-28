<?php
session_start();
require 'db.php';

// Security Check
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Handle Password Reset Request
if (isset($_POST['request_reset'])) {
    $conn->query("UPDATE students SET reset_request = 1 WHERE id = $id");
    $msg = "Password reset request sent successfully to the administration.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Doon University</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bg: #f0f4f8; 
            --text-main: #1f2937; 
            --text-muted: #6b7280; 
            --surface: #ffffff;
            --primary: #4f46e5; 
            --personal: #3b82f6; 
            --academic: #8b5cf6; 
            --family: #f59e0b; 
            --sibling: #10b981; 
        }
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text-main); padding-bottom: 50px; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Top Navigation */
        .navbar { background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%); padding: 20px 50px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar h1 { font-size: 20px; font-weight: 600; }
        .btn-logout { background: rgba(255,255,255,0.1); color: white; text-decoration: none; padding: 8px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: 0.3s; border: 1px solid rgba(255,255,255,0.2); }
        .btn-logout:hover { background: #ef4444; border-color: #ef4444; }

        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }

        /* Status Banner */
        .status-banner { display: flex; justify-content: space-between; align-items: center; background: var(--surface); padding: 25px 30px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 6px solid var(--primary); }
        .status-banner h2 { font-size: 24px; color: var(--text-main); margin-bottom: 5px; }
        .status-banner p { color: var(--text-muted); font-size: 15px; }
        .badge { padding: 10px 24px; border-radius: 30px; font-size: 15px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
        .badge-Pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-Approved { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-Rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Custom Decision Messages */
        .decision-box { padding: 20px 30px; border-radius: 12px; margin-bottom: 30px; display: flex; gap: 15px; align-items: flex-start; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .decision-box .icon { font-size: 32px; line-height: 1; }
        .decision-box h4 { margin-bottom: 5px; font-size: 18px; }
        .decision-box p { font-size: 15px; line-height: 1.5; }
        
        .decision-approved { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .decision-approved h4 { color: #15803d; }
        
        .decision-rejected { background: #f9fafb; border: 1px solid #e5e7eb; color: #4b5563; }
        .decision-rejected h4 { color: #374151; }

        /* Admin Remarks */
        .remarks-box { background: #eff6ff; padding: 20px 30px; border-radius: 12px; border: 1px solid #bfdbfe; margin-bottom: 30px; display: flex; gap: 15px; align-items: flex-start; }
        .remarks-box .icon { font-size: 24px; }
        .remarks-box h4 { color: #1e40af; margin-bottom: 5px; }
        .remarks-box p { color: #1e3a8a; font-size: 14px; line-height: 1.6; }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 40px; }

        .card { background: var(--surface); border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s; position: relative; overflow: hidden; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
        .card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px; }
        .card-personal::before { background: var(--personal); }
        .card-academic::before { background: var(--academic); }
        .card-family::before { background: var(--family); }
        .card-sibling::before { background: var(--sibling); }

        .card h3 { font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--text-main); padding-bottom: 15px; border-bottom: 1px solid #f3f4f6; }
        
        .data-group { margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; font-size: 14px; }
        .data-label { color: var(--text-muted); font-weight: 500; }
        .data-value { color: var(--text-main); font-weight: 600; text-align: right; max-width: 60%; word-wrap: break-word; }
        .data-value.full-width { display: block; max-width: 100%; text-align: left; margin-top: 5px; background: #f9fafb; padding: 10px; border-radius: 8px; border: 1px solid #f3f4f6; }

        .actions-footer { background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .btn-reset { background: var(--surface); color: var(--text-main); border: 2px solid #e5e7eb; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 14px; }
        .btn-reset:hover { border-color: var(--primary); color: var(--primary); background: #eff6ff; }
        .msg-success { color: #10b981; font-size: 14px; font-weight: 500; margin-top: 15px; display: inline-block; background: #dcfce7; padding: 8px 16px; border-radius: 20px; }

        @media (max-width: 768px) {
            .navbar { padding: 15px 20px; }
            .status-banner { flex-direction: column; text-align: center; gap: 15px; }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>🎓 Doon University Portal</h1>
        <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>

    <div class="container animate">
        
        <!-- Welcome & Status Banner -->
        <div class="status-banner">
            <div>
                <h2>Welcome back, <?php echo htmlspecialchars($student['full_name']); ?>!</h2>
                <p>Application ID: <strong>#<?php echo str_pad($student['id'], 5, '0', STR_PAD_LEFT); ?></strong> | Applied for: <strong><?php echo htmlspecialchars($student['interested_course']); ?></strong></p>
            </div>
            <div>
                <span class="badge badge-<?php echo htmlspecialchars($student['status'] ?? 'Pending'); ?>">
                    <?php echo htmlspecialchars($student['status'] ?? 'Pending'); ?>
                </span>
            </div>
        </div>

        <!-- NEW: Custom Decision Messages (Only shows if Approved or Rejected) -->
        <?php if ($student['status'] == 'Approved'): ?>
            <div class="decision-box decision-approved animate" style="animation-delay: 0.1s;">
                <div class="icon">🎉</div>
                <div>
                    <h4>Congratulations!</h4>
                    <p>Your application has been successfully approved! We are thrilled to welcome you to Doon University. Please check your registered email for further enrollment instructions.</p>
                </div>
            </div>
        <?php elseif ($student['status'] == 'Rejected'): ?>
            <div class="decision-box decision-rejected animate" style="animation-delay: 0.1s;">
                <div class="icon">💡</div>
                <div>
                    <h4>Thank you for applying.</h4>
                    <p>Unfortunately, your application was not successful at this time. We sincerely appreciate your interest in Doon University. Better luck next time, and we wish you all the best for your future endeavors!</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Admin Remarks Alert -->
        <?php if (!empty($student['admin_remarks'])): ?>
            <div class="remarks-box animate" style="animation-delay: 0.15s;">
                <div class="icon">🔔</div>
                <div>
                    <h4>Message from Administration</h4>
                    <p><?php echo nl2br(htmlspecialchars($student['admin_remarks'])); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Detailed Data Grid -->
        <div class="grid">
            
            <div class="card card-personal animate" style="animation-delay: 0.2s;">
                <h3>👤 Personal Profile</h3>
                <div class="data-group"><span class="data-label">Full Name</span><span class="data-value"><?php echo htmlspecialchars($student['full_name']); ?></span></div>
                <div class="data-group"><span class="data-label">Mobile No.</span><span class="data-value"><?php echo htmlspecialchars($student['phone_number']); ?></span></div>
                <div class="data-group"><span class="data-label">Date of Birth</span><span class="data-value"><?php echo htmlspecialchars($student['dob']); ?></span></div>
                <div class="data-group"><span class="data-label">Blood Group</span><span class="data-value"><?php echo htmlspecialchars($student['blood_group']); ?></span></div>
                <div class="data-group"><span class="data-label">Course Applied</span><span class="data-value" style="color: var(--primary);"><?php echo htmlspecialchars($student['interested_course']); ?></span></div>
            </div>

            <div class="card card-academic animate" style="animation-delay: 0.3s;">
                <h3>🎓 Academic Record</h3>
                <div class="data-group"><span class="data-label">10th Percentage</span><span class="data-value"><?php echo htmlspecialchars($student['tenth_percent']); ?>%</span></div>
                <div class="data-group"><span class="data-label">12th Percentage</span><span class="data-value"><?php echo htmlspecialchars($student['twelfth_percent']); ?>%</span></div>
                <div class="data-group"><span class="data-label">Stream / Background</span><span class="data-value"><?php echo htmlspecialchars($student['background']); ?></span></div>
                <div class="data-group"><span class="data-label">Passing Year</span><span class="data-value"><?php echo htmlspecialchars($student['passing_year']); ?></span></div>
                <div class="data-group"><span class="data-label">Admission Year</span><span class="data-value"><?php echo htmlspecialchars($student['admission_year']); ?></span></div>
            </div>

            <div class="card card-family animate" style="animation-delay: 0.4s;">
                <h3>👨‍👩‍👧 Family Background</h3>
                <div class="data-group"><span class="data-label">Father's Name</span><span class="data-value"><?php echo htmlspecialchars($student['father_name']); ?></span></div>
                <div class="data-group"><span class="data-label">Mother's Name</span><span class="data-value"><?php echo htmlspecialchars($student['mother_name']); ?></span></div>
                <div class="data-group"><span class="data-label">Family Income</span><span class="data-value">₹<?php echo number_format((float)$student['family_income'], 2); ?></span></div>
                <div style="margin-top: 15px;">
                    <span class="data-label">Permanent Address</span>
                    <span class="data-value full-width"><?php echo nl2br(htmlspecialchars($student['address'])); ?></span>
                </div>
            </div>

            <div class="card card-sibling animate" style="animation-delay: 0.5s;">
                <h3>🏫 Sibling Information</h3>
                <?php if ($student['has_siblings'] == 'Yes'): ?>
                    <div class="data-group"><span class="data-label">Total Siblings</span><span class="data-value"><?php echo htmlspecialchars($student['num_siblings']); ?></span></div>
                    <div class="data-group"><span class="data-label">In Doon Univ?</span><span class="data-value"><?php echo htmlspecialchars($student['sibling_same_college']); ?></span></div>
                    
                    <?php if ($student['sibling_same_college'] == 'Yes'): ?>
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #e5e7eb;">
                            <div class="data-group"><span class="data-label">Sibling Name</span><span class="data-value"><?php echo htmlspecialchars($student['sibling_name']); ?></span></div>
                            <div class="data-group"><span class="data-label">Roll Number</span><span class="data-value"><?php echo htmlspecialchars($student['sibling_roll_no']); ?></span></div>
                            <div class="data-group"><span class="data-label">Course</span><span class="data-value"><?php echo htmlspecialchars($student['sibling_course']); ?></span></div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 30px 0; color: var(--text-muted);">
                        <p style="font-size: 30px; margin-bottom: 10px;">👤</p>
                        <p>No siblings registered.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Password Reset Action -->
        <div class="actions-footer animate" style="animation-delay: 0.6s;">
            <h3 style="margin-bottom: 10px; color: var(--text-main);">Need to update your security?</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">If you feel your password has been compromised, you can request a manual reset from the administration staff.</p>
            
            <form method="POST">
                <button type="submit" name="request_reset" class="btn-reset">🔒 Request Password Reset</button>
            </form>
            
            <?php if(isset($msg)) echo "<div class='msg-success'>✓ $msg</div>"; ?>
        </div>

    </div>

</body>
</html>