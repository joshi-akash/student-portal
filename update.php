<?php
session_start();
require 'db.php';

// Security check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture all the variables
    $id = $_POST['id'];
    
    // Student Info Updates
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $interested_course = $_POST['interested_course'];
    $tenth_percent = !empty($_POST['tenth_percent']) ? (float)$_POST['tenth_percent'] : 0;
    $twelfth_percent = !empty($_POST['twelfth_percent']) ? (float)$_POST['twelfth_percent'] : 0;
    $background = $_POST['background'];
    $passing_year = $_POST['passing_year'];
    $father_name = $_POST['father_name'];
    $family_income = $_POST['family_income'];
    $address = $_POST['address'];

    // Admin Action Updates
    $status = $_POST['status'];
    $admin_remarks = $_POST['admin_remarks'];
    $new_password = $_POST['new_password'] ?? '';

    // 2. Build the SQL Query based on whether a new password was provided
    if (!empty($new_password)) {
        // Update everything INCLUDING the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE students SET 
                full_name=?, phone_number=?, dob=?, blood_group=?, interested_course=?, 
                tenth_percent=?, twelfth_percent=?, background=?, passing_year=?, 
                father_name=?, family_income=?, address=?, 
                status=?, admin_remarks=?, password=?, reset_request=0 
                WHERE id=?";
                
        $stmt = $conn->prepare($sql);
        // 16 parameters: sssssddssssssssi
        $stmt->bind_param("sssssddssssssssi", 
            $full_name, $phone_number, $dob, $blood_group, $interested_course, 
            $tenth_percent, $twelfth_percent, $background, $passing_year, 
            $father_name, $family_income, $address, 
            $status, $admin_remarks, $hashed_password, $id);
            
    } else {
        // Update everything EXCEPT the password
        $sql = "UPDATE students SET 
                full_name=?, phone_number=?, dob=?, blood_group=?, interested_course=?, 
                tenth_percent=?, twelfth_percent=?, background=?, passing_year=?, 
                father_name=?, family_income=?, address=?, 
                status=?, admin_remarks=? 
                WHERE id=?";
                
        $stmt = $conn->prepare($sql);
        // 15 parameters: sssssddsssssssi
        $stmt->bind_param("sssssddsssssssi", 
            $full_name, $phone_number, $dob, $blood_group, $interested_course, 
            $tenth_percent, $twelfth_percent, $background, $passing_year, 
            $father_name, $family_income, $address, 
            $status, $admin_remarks, $id);
    }

    // 3. Execute and redirect
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>