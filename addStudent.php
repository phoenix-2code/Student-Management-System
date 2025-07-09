<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

if (!session_id()) session_start();
require_once 'includes/db.php';

$fullName = $emailAddress = $phoneNumber = $courseName = '';
$formErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = trim($_POST['fullName']);
  $emailAddress = trim($_POST['emailAddress']);
  $phoneNumber = trim($_POST['phoneNumber']);
  $courseName = trim($_POST['courseName']);

  // Validation
  if ($fullName === '') $formErrors[] = "Full Name is required.";
  if ($emailAddress === '' || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    $formErrors[] = "Valid Email Address is required.";
  }
  if ($courseName === '') $formErrors[] = "Please select a course.";

  if (empty($formErrors)) {
    $stmt = $conn->prepare("INSERT INTO studentRecords (fullName, emailAddress, phoneNumber, courseName) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $emailAddress, $phoneNumber, $courseName);

    try {
      if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Student added successfully!";
        header("Location: viewStudent.php");
        exit();
      }
    } catch (mysqli_sql_exception $e) {
      if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $formErrors[] = "❌ A student with that email already exists.";
      } else {
        $formErrors[] = "❌ Something went wrong. Please try again.";
      }
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-person-plus-fill"></i> Add New Student</h2>

    <?php if ($formErrors): ?>
      <div class="alert alert-danger">
        <?php foreach ($formErrors as $error): ?>
          <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="addStudent.php" class="row g-3">
      <div class="col-md-6">
        <label for="fullName" class="form-label">Full Name</label>
        <input type="text" name="fullName" id="fullName" class="form-control" value="<?= htmlspecialchars($fullName) ?>" required />
      </div>

      <div class="col-md-6">
        <label for="emailAddress" class="form-label">Email Address</label>
        <input type="email" name="emailAddress" id="emailAddress" class="form-control" value="<?= htmlspecialchars($emailAddress) ?>" required />
      </div>

      <div class="col-md-6">
        <label for="phoneNumber" class="form-label">Phone Number</label>
        <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?= htmlspecialchars($phoneNumber) ?>" />
      </div>

      <div class="col-md-6">
        <label for="courseName" class="form-label">Select Course</label>
        <select name="courseName" id="courseName" class="form-select" required>
          <option value="">-- Choose a Course --</option>

          <optgroup label="Diploma Programs">
            <option value="Diploma in Computer Science">Diploma in Computer Science</option>
            <option value="Diploma in Business and IT">Diploma in Business and IT</option>
            <option value="Diploma in Information Technology">Diploma in Information Technology</option>
            <option value="Diploma in Data Analytics">Diploma in Data Analytics</option>
          </optgroup>

          <optgroup label="Degree Programs">
            <option value="BSc in Computer Science">BSc in Computer Science</option>
            <option value="BSc in Business and IT">BSc in Business and IT</option>
            <option value="BSc in Information Technology">BSc in Information Technology</option>
            <option value="BSc in Software Engineering">BSc in Software Engineering</option>
          </optgroup>

          <optgroup label="Masters Programs">
            <option value="MSc in Computer Science">MSc in Computer Science</option>
            <option value="MBA in IT Management">MBA in IT Management</option>
            <option value="MSc in Cybersecurity">MSc in Cybersecurity</option>
            <option value="MSc in Information Systems">MSc in Information Systems</option>
          </optgroup>
        </select>
      </div>

      <div class="col-12 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i> Save Student</button>
        <a href="viewStudent.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
    </form>
  </main>

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
