<?php
session_start();
require 'db.php';

$error = '';
$show_reset = false;
$failed_phone = '';
$reset_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Handle Standard Login
    if (isset($_POST['login'])) {
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $conn->prepare("SELECT id, full_name, password FROM students WHERE phone_number = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_id'] = $row['id'];
                $_SESSION['student_name'] = $row['full_name'];
                header("Location: student_dashboard.php");
                exit();
            } else {
                // FAILED PASSWORD LOGIC
                $error = "Incorrect password.";
                $show_reset = true;
                $failed_phone = $phone; // Remember phone number for the reset button
            }
        } else {
            $error = "No application found with this mobile number.";
        }
    }
    
    // 2. Handle Password Reset Request
    elseif (isset($_POST['request_reset'])) {
        $phone_to_reset = $_POST['failed_phone'];
        $stmt = $conn->prepare("UPDATE students SET reset_request = 1 WHERE phone_number = ?");
        $stmt->bind_param("s", $phone_to_reset);
        $stmt->execute();
        $reset_success = "Reset request sent to administration successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doon University | Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --primary-light: #3b82f6; --accent: #f59e0b; --bg: #f3f4f6; --surface: #ffffff; --text-main: #1f2937; --text-muted: #6b7280; --border: #e5e7eb; }
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { background-color: var(--bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
        .container { display: flex; width: 100%; max-width: 1050px; background: var(--surface); border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); overflow: hidden; }
        .hero-section { width: 50%; padding: 60px; background: linear-gradient(135deg, var(--primary) 0%, #172554 100%); color: white; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden; }
        .hero-section::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%); pointer-events: none; }
        .hero-section h1 { font-size: 38px; font-weight: 700; line-height: 1.2; margin-bottom: 20px; }
        .hero-section p { font-size: 16px; opacity: 0.85; line-height: 1.6; margin-bottom: 40px; font-weight: 300; }
        .btn-outline { display: inline-block; padding: 14px 28px; border: 2px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.05); color: white; font-weight: 500; border-radius: 12px; text-decoration: none; transition: all 0.3s ease; backdrop-filter: blur(10px); }
        .btn-outline:hover { border-color: var(--accent); background: var(--accent); color: #000; }
        .login-section { width: 50%; padding: 70px 60px; display: flex; flex-direction: column; justify-content: center; background: var(--surface); }
        .login-section h2 { font-size: 28px; margin-bottom: 30px; color: var(--primary); font-weight: 600; }
        .form-group { margin-bottom: 24px; }
        label { display: block; font-size: 14px; font-weight: 500; color: var(--text-muted); margin-bottom: 8px; }
        input { width: 100%; padding: 14px 18px; background: #f9fafb; border: 2px solid var(--border); border-radius: 12px; font-size: 15px; transition: all 0.3s ease; color: var(--text-main); }
        input:focus { outline: none; border-color: var(--primary-light); background: var(--surface); }
        .btn-primary { width: 100%; padding: 16px; background: var(--primary); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-top: 10px; }
        .btn-primary:hover { background: #172554; }
        .btn-link { background: none; border: none; color: var(--primary-light); font-weight: 500; text-decoration: underline; cursor: pointer; font-size: 14px; padding: 10px 0; }
        .error { font-size: 14px; color: #dc2626; background: #fef2f2; padding: 14px; border-radius: 10px; margin-bottom: 24px; border: 1px solid #fca5a5; }
        .success { font-size: 14px; color: #10b981; background: #d1fae5; padding: 14px; border-radius: 10px; margin-bottom: 24px; border: 1px solid #6ee7b7; }
        @media (max-width: 768px) { .container { flex-direction: column; } .hero-section, .login-section { width: 100%; padding: 40px 24px; } }
    </style>
</head>
<body>

<div class="container animate">
    <div class="hero-section">
        <h1 class="animate delay-1">Doon University<br>Admissions 2026</h1>
        <p class="animate delay-2">Begin your academic journey. Submit your application seamlessly through our secure, modern portal.</p>
        <div class="animate delay-3"><a href="admission_form.php" class="btn-outline">📝 Start New Application</a></div>
    </div>

    <div class="login-section">
        <h2 class="animate delay-1">Student Portal</h2>
        
        <?php if($error) echo "<div class='error animate delay-2'>⚠️ $error</div>"; ?>
        <?php if($reset_success) echo "<div class='success animate delay-2'>✅ $reset_success</div>"; ?>
        
        <form method="POST" action="" class="animate delay-2">
            <div class="form-group">
                <label>Registered Mobile Number</label>
                <input type="text" name="phone" placeholder="Enter 10-digit number" value="<?php echo htmlspecialchars($failed_phone); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn-primary">Sign In to Dashboard</button>
        </form>

        <!-- NEW: Only show the reset button if password failed -->
        <?php if ($show_reset): ?>
            <form method="POST" action="" class="animate delay-3" style="text-align: center; margin-top: 15px;">
                <input type="hidden" name="failed_phone" value="<?php echo htmlspecialchars($failed_phone); ?>">
                <button type="submit" name="request_reset" class="btn-link">Forgot Password? Request Reset from Admin</button>
            </form>
        <?php endif; ?>

        <div class="animate delay-3" style="text-align: center; margin-top: 30px;">
            <a href="admin_login.php" style="color: var(--text-muted); font-size: 13px; font-weight: 500; text-decoration: none;">
                University Staff? <span style="color: var(--primary);">Admin Login &rarr;</span>
            </a>
        </div>
    </div>
</div>

</body>
</html>