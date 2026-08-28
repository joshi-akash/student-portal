<?php
require 'db.php';

$full_name         = $_POST['name'] ?? '';
$phone_number      = $_POST['phone'] ?? '';
$interested_course = $_POST['course'] ?? '';
$dob               = !empty($_POST['dob']) ? $_POST['dob'] : NULL; 
$raw_password      = $_POST['password'] ?? '';
$password_hash     = password_hash($raw_password, PASSWORD_DEFAULT);
$tenth             = !empty($_POST['tenth']) ? (float)$_POST['tenth'] : 0;   
$twelfth           = !empty($_POST['twelfth']) ? (float)$_POST['twelfth'] : 0; 
$background        = $_POST['background'] ?? '';
$blood_group       = $_POST['blood_group'] ?? '';
$father_name       = $_POST['father_name'] ?? '';
$father_occupation = $_POST['father_occupation'] ?? '';
$father_qualification = $_POST['father_qualification'] ?? '';
$mother_name       = $_POST['mother_name'] ?? '';
$mother_occupation = $_POST['mother_occupation'] ?? '';
$mother_qualification = $_POST['mother_qualification'] ?? '';
$address           = $_POST['address'] ?? '';
$admission_year    = date("Y"); 
$family_income     = $_POST['income'] ?? '';
$passing_year      = $_POST['year'] ?? ''; 
$has_siblings      = $_POST['has_siblings'] ?? 'No';
$num_siblings      = !empty($_POST['num_siblings']) ? (int)$_POST['num_siblings'] : 0;
$sibling_same_college = $_POST['sibling_same_college'] ?? 'No';
$sibling_roll_no   = $_POST['sibling_roll_no'] ?? '';
$sibling_name      = $_POST['sibling_name'] ?? '';
$sibling_course    = $_POST['sibling_course'] ?? '';

$sql = "INSERT INTO students (
    full_name, phone_number, password, interested_course, dob, tenth_percent, twelfth_percent,
    background, blood_group, father_name, father_occupation, father_qualification, 
    mother_name, mother_occupation, mother_qualification, address, 
    admission_year, family_income, passing_year, has_siblings, 
    num_siblings, sibling_same_college, sibling_roll_no, 
    sibling_name, sibling_course
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssssddssssssssssssisssss", 
        $full_name, $phone_number, $password_hash, $interested_course, $dob, $tenth, $twelfth, 
        $background, $blood_group, $father_name, $father_occupation, $father_qualification,
        $mother_name, $mother_occupation, $mother_qualification, $address,
        $admission_year, $family_income, $passing_year, $has_siblings,
        $num_siblings, $sibling_same_college, $sibling_roll_no,
        $sibling_name, $sibling_course
    );

    if ($stmt->execute()) {
        header("Location: success.php?id=" . $conn->insert_id);
        exit();
    } else {
        echo "Database Execution Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "SQL Prepare Error: " . $conn->error;
}
$conn->close();
?>