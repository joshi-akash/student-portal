<?php
session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded Admin Credentials
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login | Doon University</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --bg: #f3f4f6; --surface: #ffffff; --border: #e5e7eb; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { background: var(--surface); padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0 20px; border: 2px solid var(--border); border-radius: 8px; box-sizing: border-box; font-family: 'Poppins'; }
        button { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        button:hover { background: #172554; }
        .error { color: #dc2626; font-size: 14px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="color: var(--primary); margin-top: 0;">Staff Portal</h2>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username (admin)" required>
            <input type="password" name="password" placeholder="Password (admin123)" required>
            <button type="submit">Access Dashboard</button>
        </form>
        <a href="index.php" style="display: block; margin-top: 20px; color: #6b7280; text-decoration: none; font-size: 14px;">&larr; Back to Student Portal</a>
    </div>
</body>
</html>