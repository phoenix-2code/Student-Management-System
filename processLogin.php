<?php
if (!session_id()) session_start();

$adminUsername = 'admin';
$adminPassword = 'admin123';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === $adminUsername && $password === $adminPassword) {
  $_SESSION['admin_logged_in'] = true;
  header("Location: index.php");
  exit();
} else {
  $_SESSION['login_error'] = "Invalid credentials.";
  header("Location: login.php");
  exit();
}
