<?php
if (!session_id()) session_start();
if (isset($_SESSION['admin_logged_in'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <main class="container mt-5">
    <h2 class="text-center mb-4">Admin Login</h2>

    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="process_login.php" class="mx-auto" style="max-width: 400px;">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </main>
</body>
</html>
