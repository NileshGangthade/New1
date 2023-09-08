<?php
session_start();

if(!isset($_GET['table'])) {
    die("Table name not provided.");
}

$table_name = $_GET['table'];

// Ensure the table name is safe to use
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
    die("Invalid table name.");
}

require 'config.php';

$conn = mysqli_connect('localhost', 'root', '', $_SESSION['department']);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// If the form is submitted, update the CO values
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i = 1; $i <= 6; $i++) {
        $co_value = mysqli_real_escape_string($conn, $_POST["co" . $i]);
        $query = "UPDATE $table_name SET CO = '$co_value' WHERE id = $i";
        mysqli_query($conn, $query);
    }
    echo "CO values updated successfully. <a href='view_results.php'>Go back</a>";
    exit;
}

// Fetch existing CO values
$query = "SELECT * FROM $table_name ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$co_values = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<html>
<head>
    <title>Update CO values</title>
</head>
<body>
<form action="update_co.php?table=<?php echo $table_name; ?>" method="post">
    <?php
    foreach ($co_values as $index => $co) {
        $i = $index + 1;
        echo "CO$i: <input type='text' name='co$i' value='{$co['CO']}' required><br>";
    }
    ?>
    <input type="submit" value="Update CO values">
</form>
</body>
</html>
