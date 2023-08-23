<?php
// $host = 'localhost';
// $user = 'root';
// $password = '';
// $dbname = 'Sample';
require 'config.php';

// $conn = new mysqli($host, $user, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// main_table creation statement
$createMainTableSql = "CREATE TABLE IF NOT EXISTS main_table (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_type VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    department VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    is_approve BOOLEAN DEFAULT FALSE,
    otp VARCHAR(6) DEFAULT NULL,
    otp_expiry TIMESTAMP DEFAULT NULL
)";
$conn->query($createMainTableSql);

$checkTableSql = "CREATE TABLE IF NOT EXISTS temp_table (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_type VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    department VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    email_status INT DEFAULT 0,
    otp VARCHAR(6) DEFAULT NULL,
    otp_expiry TIMESTAMP DEFAULT NULL
    
)";
$conn->query($checkTableSql);

echo "Setup completed!";
?>
//Nilest_comment