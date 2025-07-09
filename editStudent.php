<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

if (!session_id()) session_start();
require_once 'includes/db.php';

$studentId = $_GET['studentId'] ?? null;
$formErrors = [];

if (!$studentId) {
  $_SESSION['message'] = "⚠️ No student ID provided.";
  header("Location: viewStudent.php");
  exit();
}

// Retrieve current record
$stmt = $conn->prepare("SELECT * FROM studentRecords WHERE studentId = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
  $_SESSION['message'] = "❌ Student record not found.";
  header("Location: viewStudent.php");
  exit();
}

// Handle update on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = trim($_POST['fullName']);
  $emailAddress = trim($_POST['emailAddress']);
  $phoneNumber = trim($_POST['phoneNumber']);
  $courseName = trim($_POST['courseName']);

  // Validate
  if ($fullName === '') $formErrors[] = "Full Name is required.";
  if ($emailAddress === '' || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    $formErrors[] = "Valid Email Address is required.";
  }
  if ($courseName === '') $formErrors[] = "Course Name is required.";

  // Update record if valid
  if (empty($formErrors)) {
    $stmt = $conn->prepare("
      UPDATE studentRecords 
      SET fullName = ?, emailAddress = ?, phoneNumber = ?, courseName = ? 
      WHERE studentId = ?
    ");
    $stmt->bind_param("ssssi", $fullName, $emailAddress, $phoneNumber, $courseName, $studentId);

    if ($stmt->execute()) {
      $_SESSION['message'] = "✅ Student updated successfully.";
      header("Location: viewStudent.php");
      exit();
    } else {
      $formErrors[] = "❌ Failed to update student.";
    }
    $stmt->close();
  }

  // Update form display
  $student = compact('fullName', 'emailAddress', 'phoneNumber', 'courseName');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Student</h2>

    <?php if ($formErrors): ?>
      <div class="alert alert-danger">
        <?php foreach ($formErrors as $error): ?>
          <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="editStudent.php?studentId=<?= $studentId ?>" class="row g-3">
      <div class="col-md-6">
        <label for="fullName" class="form-label">Full Name</label>
        <input type="text" name="fullName" id="fullName" class="form-control" value="<?= htmlspecialchars($student['fullName']) ?>" required />
      </div>
      <div class="col-md-6">
        <label for="emailAddress" class="form-label">Email Address</label>
        <input type="email" name="emailAddress" id="emailAddress" class="form-control" value="<?= htmlspecialchars($student['emailAddress']) ?>" required />
      </div>
      <div class="col-md-6">
        <label for="phoneNumber" class="form-label">Phone Number</label>
        <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?= htmlspecialchars($student['phoneNumber']) ?>" />
      </div>
      <div class="col-md-6">
        <label for="courseName" class="form-label">Course Name</label>
        <input type="text" name="courseName" id="courseName" class="form-control" value="<?= htmlspecialchars($student['courseName']) ?>" required />
      </div>
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-save-fill"></i> Update Student</button>
        <a href="viewStudents.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
    </form>
  </main>

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
