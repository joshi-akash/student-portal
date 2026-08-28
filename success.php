<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$row = $result->fetch_assoc();

if (!$row) { die("Application not found."); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Doon University</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 40px; }
        .receipt { background: white; padding: 30px; border-radius: 10px; max-width: 500px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .label { color: #666; }
        .val { font-weight: bold; }
    </style>
</head>
<body>
    <div class="receipt">
        <h2 style="text-align:center; color:#1e3c72;">Application Receipt</h2>
        <div style="text-align:center; font-size: 20px; margin-bottom: 20px;">ID: #<?php echo $id; ?></div>
        
        <div class="row"><span class="label">Name:</span> <span class="val"><?php echo $row['full_name']; ?></span></div>
        <div class="row"><span class="label">Phone:</span> <span class="val"><?php echo $row['phone_number']; ?></span></div>
        <div class="row"><span class="label">DOB:</span> <span class="val"><?php echo $row['dob']; ?></span></div>
        <div class="row"><span class="label">Course:</span> <span class="val"><?php echo $row['interested_course']; ?></span></div>
        <div class="row"><span class="label">Father's Name:</span> <span class="val"><?php echo $row['father_name']; ?></span></div>

        <button onclick="window.print()" style="width:100%; margin-top:20px; padding:10px; background:#1e3c72; color:white; border:none; cursor:pointer;">Print Receipt</button>
    <a href="index.php" style="display:block; text-align:center; width:100%; margin-top:12px; padding:12px; background:#f0f4f8; color:#1e3c72; text-decoration:none; border-radius:5px; border:1px solid #1e3c72; font-weight:bold; box-sizing: border-box;">
            Go Back to Home
        </a>
    </div>
    
</body>
</html>