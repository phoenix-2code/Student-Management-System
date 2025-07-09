<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

if (!session_id()) session_start();
require_once 'includes/db.php';

// Dashboard Data
$totalQuery    = $conn->query("SELECT COUNT(*) AS total FROM studentRecords");
$totalCount    = $totalQuery->fetch_assoc()['total'];

$diplomaQuery  = $conn->query("SELECT COUNT(*) AS count FROM studentRecords WHERE courseName LIKE 'Diploma%'");
$diplomaCount  = $diplomaQuery->fetch_assoc()['count'];

$degreeQuery   = $conn->query("SELECT COUNT(*) AS count FROM studentRecords WHERE courseName LIKE 'BSc%'");
$degreeCount   = $degreeQuery->fetch_assoc()['count'];

$mastersQuery  = $conn->query("SELECT COUNT(*) AS count FROM studentRecords WHERE courseName LIKE 'MSc%' OR courseName LIKE 'MBA%'");
$mastersCount  = $mastersQuery->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Student Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="container mt-5">
    <!-- 🖋️ Welcome and System Message -->
    <div class="mb-4 text-center">
      <h1><i class="bi bi-speedometer2"></i> Admin Control Panel</h1>
      <p class="lead">
        Manage student records with clarity and efficiency. This panel provides real-time insights and system access for administrators.
      </p>
    </div>

    <!-- 📊 Dashboard Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-bg-info text-center">
          <div class="card-body">
            <h5>Total Students</h5>
            <h3><?= $totalCount ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-bg-primary text-center">
          <div class="card-body">
            <h5>Diploma</h5>
            <h3><?= $diplomaCount ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-bg-success text-center">
          <div class="card-body">
            <h5>Degree</h5>
            <h3><?= $degreeCount ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-bg-warning text-center">
          <div class="card-body">
            <h5>Masters</h5>
            <h3><?= $mastersCount ?></h3>
          </div>
        </div>
      </div>
    </div>

    <!-- 🚀 Quick Links -->
    <div class="row g-3">
      <div class="col-md-4">
        <a href="addStudent.php" class="btn btn-outline-success w-100">
          <i class="bi bi-person-plus-fill"></i> Add Student
        </a>
      </div>
      <div class="col-md-4">
        <a href="viewStudent.php" class="btn btn-outline-primary w-100">
          <i class="bi bi-table"></i> View Records
        </a>
      </div>
      <div class="col-md-4">
        <a href="viewStudent.php?export=csv" class="btn btn-outline-dark w-100">
          <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
        </a>
      </div>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



