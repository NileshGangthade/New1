<?php
$conn = mysqli_connect('localhost', 'root', '', 'mysql');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['marks_table_name']) || !isset($_SESSION['num_sub_questions'])) {
  die('Session variables not set.');
}

$marks_table_name = $_SESSION['marks_table_name'];
$num_sub_questions = $_SESSION['num_sub_questions'];
if (isset($_POST['roll_number'])) {
    $roll_number = intval($_POST['roll_number']);
    $sql = "DELETE FROM $marks_table_name WHERE roll_number=$roll_number";
    if (mysqli_query($conn, $sql)) {
      echo "Record deleted successfully.";
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }
  }
mysqli_close($conn);
  
?>