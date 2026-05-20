<?php
// ============================================
// Database Connection
// Used by all other PHP files
// ============================================

$host     = "localhost";
$username = "root";
$password = "";
$database = "alula_db";

// Create connection using MySQLi
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set charset to support Arabic characters
$conn->set_charset("utf8mb4");
?>
