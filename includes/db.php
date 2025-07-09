<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'studentdb';

$conn = new mysqli('localhost', 'root', '', 'studentsdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
