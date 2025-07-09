<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

require_once 'includes/db.php';

$studentId = $_GET['studentId'] ?? null;
if (!$studentId) {
  header("Location: viewStudent.php");
  exit();
}

$query = "SELECT * FROM studentRecords WHERE studentId = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
  header("Location: viewStudent.php");
  exit();
}

$courseQuery = "SELECT courseName FROM courses ORDER BY courseName ASC";
$courseResult = mysqli_query($conn, $courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <main class="container mt-4">
    <h2 class="text-center mb-4">Edit Student</h2>

    <form method="POST" action="updateStudent.php">
      <input type="hidden" name="studentId" value="<?= $student['studentId'] ?>" />

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($student['fullName']) ?>" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="emailAddress" class="form-control" value="<?= htmlspecialchars($student['emailAddress']) ?>" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phoneNumber" class="form-control" value="<?= htmlspecialchars($student['phoneNumber']) ?>" />
      </div>
      <div class="mb-3">
        <label class="form-label">Course</label>
        <select name="courseName" class="form-select" required>
          <option value="">Select a course</option>
          <?php while ($row = mysqli_fetch_assoc($courseResult)): ?>
            <option value="<?= $row['courseName'] ?>"
              <?= $row['courseName'] === $student['courseName'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['courseName']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    <button type="submit" class="btn btn-primary w-100 mb-2">Save Changes</button>
    <a href="viewStudent.php" class="btn btn-secondary w-100">Cancel</a>
    </form>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>
</html>



