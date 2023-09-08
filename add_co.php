<?php
session_start(); ?>

<?php
if (isset($_SESSION["department"])) {
    $department = $_SESSION["department"];
} else {
    echo "Session variable 'department' is not set!";
}

if (isset($_SESSION["table_name"])) {
    $table_name = $_SESSION["table_name"];
} else {
    echo "Table name is not set!";
}

// Explode the table name into its parts
$parts = explode("_", $table_name);

// Define possible values for some fields
$possibleYears = ["SY_Btech", "TY_Btech", "BE"];
$possibleSems = ["SEM_I", "SEM_II"];
$possibleTestTypes = [
    "UT1",
    "UT2",
    "UT3",
    "Prelim",
    "Assign1",
    "Assign2",
    "Assign3",
    "Assign4",
];
$possibleDivisions = ["A", "B", "C", "D", "E", "F", "G", "H"];
$possibleDepartments = [
    "first-year",
    "cse",
    "electrical",
    "mech",
    "civil",
    "entc",
]; // add more if necessary

// Filtering known parts
$year = array_intersect($parts, $possibleYears);
$year = reset($year); // get the first (and only) value

// Given that SEM and I/II are separate, we need to combine them
if (in_array("SEM", $parts)) {
    $sem_key = array_search("SEM", $parts);
    $sem = $parts[$sem_key] . "_" . $parts[$sem_key + 1];
}

$testType = array_intersect($parts, $possibleTestTypes);
$testType = reset($testType);

$division = array_intersect($parts, $possibleDivisions);
$division = reset($division);

$department = array_intersect($parts, $possibleDepartments);
$department = reset($department);

// Since the academic year is split into two parts, we combine them
$academic_year = $parts[count($parts) - 2] . "_" . end($parts);

// The remaining part will be the subject
$knownParts = [$year, $sem, $testType, $division, $department, $academic_year];
$subject = $parts[5];

// If you have subjects that might be split into multiple parts due to underscores, you can add:
for ($i = 6; $i < count($parts) - 3; $i++) {
    $subject .= "_" . $parts[$i];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "config.php";

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", $department);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $new_table_name = "Co_values_" . $subject;
$tableExists = false;

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", $department);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$table_exists_query = "SHOW TABLES LIKE '$new_table_name'";
$result = $conn->query($table_exists_query);

if ($result && $result->num_rows > 0) {
    $tableExists = true;
    // Check if any CO value already exists in the table
    $query = "SELECT COUNT(*) as count FROM $new_table_name";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // If CO values exist in the table, offer update link immediately
    if ($row["count"] > 0) {
        echo "CO values already exist in the table. Do you want to update them first?<br>";
        echo "<a href='update_co.php?table=$new_table_name'>Update CO values</a>";
        exit();  // Exit script to prevent showing the form.
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "config.php";

    // If the table doesn't exist, create it
    if (!$tableExists) {
        // Ensure $CO_table_name contains only alphabets, numbers, or underscores
        if (preg_match('/^[a-zA-Z0-9_]+$/', $new_table_name)) {
            $create_table = "CREATE TABLE $new_table_name (
                            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            CO VARCHAR(255) NOT NULL
                        )";
            if (!$conn->query($create_table)) {
                echo "Error creating table: " . $conn->error;
                exit();
            }
        } else {
            echo "Invalid table name.";
            exit();
        }
    }

    // Insert CO values
    for ($i = 1; $i <= 6; $i++) {
        $co_value = mysqli_real_escape_string($conn, $_POST["co" . $i]);
        $query = "INSERT INTO $new_table_name (CO) VALUES ('$co_value')";
        mysqli_query($conn, $query);
    }

    echo "<script>window.close();</script>";
    exit();
}
}
?>

<html>
<head>
    <title>Add CO values</title>
</head>
<body>
<form action="add_co.php" method="post" onsubmit="return handleFormSubmission(this);">
    <?php for ($i = 1; $i <= 6; $i++) {
        echo "CO$i: <input type='text' name='co$i' required><br>";
    } ?>
    <input type="submit" value="Add CO values">
</form>
</body>
</html>