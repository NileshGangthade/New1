<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>progress</title>
    <link rel="stylesheet" href="register.css">

</head>

<body>
<?php include 'nav_bar.php'; ?>
    <h1>progress</h1>
</body>

</html>