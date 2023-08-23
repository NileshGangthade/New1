<?php
// Create a database connection
$conn = mysqli_connect('localhost', 'root', '', 'mysql');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

// Check if table_name is set in either $_GET or $_SESSION
if (isset($_GET['table_name'])) {
  $table_name = $_GET['table_name'];
} elseif (isset($_SESSION['table_name'])) {
  $table_name = $_SESSION['table_name'];
} else {
  die('Table name not provided.');
}

// Explode the table name into its parts
$parts = explode('_', $table_name);

// Define possible values for some fields
$possibleYears = ['SY_Btech', 'TY_Btech', 'BE'];
$possibleSems = ['SEM_I', 'SEM_II'];
$possibleTestTypes = ['UT1', 'UT2', 'UT3', 'Prelim', 'Assign1', 'Assign2', 'Assign3', 'Assign4'];
$possibleDivisions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
$possibleDepartments = ['first-year', 'cse', 'electrical', 'mech', 'civil', 'entc']; // add more if necessary

// Filtering known parts
$year = array_intersect($parts, $possibleYears);
$year = reset($year);  // get the first (and only) value

// Given that SEM and I/II are separate, we need to combine them
if (in_array('SEM', $parts)) {
  $sem_key = array_search('SEM', $parts);
  $sem = $parts[$sem_key] . '_' . $parts[$sem_key + 1];
}


$testType = array_intersect($parts, $possibleTestTypes);
$testType = reset($testType);

$division = array_intersect($parts, $possibleDivisions);
$division = reset($division);

$department = array_intersect($parts, $possibleDepartments);
$department = reset($department);

// Since the academic year is split into two parts, we combine them
$academic_year = $parts[count($parts) - 2] . '_' . end($parts);


// The remaining part will be the subject
$knownParts = [$year, $sem, $testType, $division, $department, $academic_year];
$subject = $parts[5];

// If you have subjects that might be split into multiple parts due to underscores, you can add:
for ($i = 6; $i < count($parts) - 3; $i++) {
    $subject .= '_' . $parts[$i];
}


echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Year: $year  |  Semester: $sem  |  Department: $department</h2>";
echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Subject: $subject  |  Test type: $testType  |  Division: $division </h2>";
echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Academic Year: $academic_year</h2>";

?>