<?php

$host = 'localhost';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$departments = ['first_year', 'cse', 'mech', 'civil', 'entc','co_login'];

foreach ($departments as $department) {
    // Create a new connection for each department
    $conn = new mysqli($host, $user, $password);

    if ($conn->connect_error) {
        die("Connection failed for department {$department}: " . $conn->connect_error);
    }

    // Create the department database
    $createDbSql = "CREATE DATABASE IF NOT EXISTS {$department}";
    $conn->query($createDbSql);

    // Select the department database
    $conn->select_db($department);

    // Check if this department is "co_login" and then create tables in it
    if($department == 'co_login') {
        $createMainTableSql = "CREATE TABLE IF NOT EXISTS main_table (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_type VARCHAR(255) NOT NULL,
            department VARCHAR(255),
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            is_admin BOOLEAN DEFAULT FALSE,
            is_approve BOOLEAN DEFAULT FALSE
        )";
        $conn->query($createMainTableSql);

        $checkTableSql = "CREATE TABLE IF NOT EXISTS temp_table (
            user_type VARCHAR(255),
            department VARCHAR(255),
            name VARCHAR(255),
            email VARCHAR(255) UNIQUE,
            password VARCHAR(255),
            otp VARCHAR(10),
            otp_expiry TIMESTAMP,
            email_status INT DEFAULT 0
        )";
        $conn->query($checkTableSql);
    }

    $conn->close();
}

echo "Setup completed for all departments!";

?>
