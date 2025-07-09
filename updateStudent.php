<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

require_once 'includes/db.php';

$studentId    = $_POST['studentId'] ?? null;
$fullName     = $_POST['fullName'] ?? '';
$emailAddress = $_POST['emailAddress'] ?? '';
$phoneNumber  = $_POST['phoneNumber'] ?? '';
$courseName   = $_POST['courseName'] ?? '';

if (!$studentId || !$fullName || !$emailAddress || !$courseName) {
  $_SESSION['message'] = "Please complete all required fields.";
  header("Location: viewStudent.php");
  exit();
}

$query = "UPDATE studentRecords SET fullName = ?, emailAddress = ?, phoneNumber = ?, courseName = ? WHERE studentId = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssssi", $fullName, $emailAddress, $phoneNumber, $courseName, $studentId);

if (mysqli_stmt_execute($stmt)) {
  $_SESSION['message'] = "Student record updated successfully.";
} else {
  $_SESSION['message'] = "Update failed. Try again.";
}

mysqli_stmt_close($stmt);
header("Location: viewStudent.php");
exit();
