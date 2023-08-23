<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'Sample';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// main_table creation statement
$createMainTableSql = "CREATE TABLE IF NOT EXISTS main_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type VARCHAR(255) NOT NULL,
    department VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
)";
$conn->query($createMainTableSql);

echo "Setup completed!";
?>
