<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

if (!session_id()) session_start();
require_once 'includes/db.php';

$studentId = $_GET['studentId'] ?? null;

if (!$studentId) {
  $_SESSION['message'] = "⚠️ No student ID provided for deletion.";
  header("Location: viewStudent.php");
  exit();
}

// Prepare and execute delete
$stmt = $conn->prepare("DELETE FROM studentRecords WHERE studentId = ?");
$stmt->bind_param("i", $studentId);

if ($stmt->execute()) {
  $_SESSION['message'] = "🗑️ Student record deleted successfully.";
} else {
  $_SESSION['message'] = "❌ Failed to delete student.";
}

$stmt->close();
header("Location: viewStudent.php");
exit();
?>
