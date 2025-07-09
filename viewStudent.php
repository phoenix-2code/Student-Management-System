<?php
if (!session_id()) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

require_once 'includes/db.php';

// Pagination settings
$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search logic
$search       = trim($_GET['search'] ?? '');
$searchQuery  = "%$search%";

// Count total matches
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM studentRecords WHERE fullName LIKE ?");
$countStmt->bind_param("s", $searchQuery);
$countStmt->execute();
$totalResult = $countStmt->get_result()->fetch_assoc();
$totalPages  = ceil($totalResult['total'] / $limit);
$countStmt->close();

// Fetch student data
$stmt = $conn->prepare("
  SELECT * FROM studentRecords 
  WHERE fullName LIKE ? 
  ORDER BY studentId DESC 
  LIMIT ? OFFSET ?
");
$stmt->bind_param("sii", $searchQuery, $limit, $offset);
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="container mt-4">

    <!-- Feedback Alert -->
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
        <?php endif; ?>


    <!-- Search -->
    <form method="GET" action="viewStudent.php" class="row g-3 mb-4">
      <div class="col-md-8">
        <input type="text" name="search" class="form-control" placeholder="Search by Full Name..." value="<?= htmlspecialchars($search) ?>" />
      </div>
      <div class="col-md-4 text-end">
        <button type="submit" class="btn btn-outline-primary">
          <i class="bi bi-search"></i> Search
        </button>
        <a href="viewStudent.php" class="btn btn-secondary ms-2">Reset</a>
      </div>
    </form>

            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($_SESSION['message']); ?>
        <?php endif; ?>


    <!-- Table -->
    <?php if ($students): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#ID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Course</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $student): ?>
              <tr>
                <td><?= $student['studentId'] ?></td>
                <td><?= htmlspecialchars($student['fullName']) ?></td>
                <td><?= htmlspecialchars($student['emailAddress']) ?></td>
                <td><?= htmlspecialchars($student['phoneNumber']) ?></td>
                <td><?= htmlspecialchars($student['courseName']) ?></td>
                <td class="text-center">
                  <a href="editStudent.php?studentId=<?= $student['studentId'] ?>" class="btn btn-sm btn-warning me-1">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="deleteStudent.php?studentId=<?= $student['studentId'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?');">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
              <a class="page-link" href="viewStudent.php?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>

    <?php else: ?>
      <div class="alert alert-info">
        No records found <?= $search ? "matching <strong>" . htmlspecialchars($search) . "</strong>" : "" ?>.
      </div>
    <?php endif; ?>

  </main>
  

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



