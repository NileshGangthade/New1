<?php
ob_start();
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$department =  $_SESSION['department'];

function getDepartmentName($department)
{
  switch ($department) {
    case 'first-year':
      return 'First Year';
    case 'cse':
      return 'Computer Science and Engineering';
    case 'electrical':
      return 'Electrical Engineering';
    case 'mech':
      return 'Mechanical Engineering';
    case 'civil':
      return 'Civil Engineering';
    case 'entc':
      return 'Electronics and Telecommunication Engineering';
    default:
      return 'Unknown Department';
  }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Question Paper Generator</title>
    <link rel="stylesheet" href="view_result.css">


</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <form id="marks-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form_wrapper">
            <div class="form_container">
                <div class="title_container">
                    <h1>Question Paper Generator</h1>
                </div>
                <div class="row clearfix">
                    <div class="">

                        <div class="input_field_dept">
                            <label for="department">Department : </label>
                            <span><i aria-hidden="true" class="fa fa-university"></i></span>
                            <span id="department-text"><?php echo getDepartmentName($department); ?></span>
                            <input type="hidden" id="department" name="department" value="<?php echo $department; ?>">
                        </div>

                        <input type="hidden" name="department_hidden" value="<?php echo $department; ?>">

                        <div class="input_field select_option">
                            <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
                            <select id="year" name="year" required>
                                <option value="">Select Year</option>
                                <option value="SY_Btech">Second Year</option>
                                <option value="TY_Btech">Third Year</option>
                                <option value="BE">Fourth Year</option>
                            </select>
                            <div class="select_arrow"></div>
                        </div>



                        <div class="input_field select_option">
                            <span><i aria-hidden="true" class="fa fa-sort-alpha-down"></i></span>
                            <select id="division" name="division" required>
                                <option value="">Select Division</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                            </select>
                            <div class="select_arrow"></div>
                        </div>

                        <div class="input_field select_option">
                            <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
                            <select id="sem" name="sem" required>
                                <option value="SEM_I">First Semester</option>
                                <option value="SEM_II">Second Semester</option>
                            </select>
                            <div class="select_arrow"></div>
                        </div>

                        <div class="input_field">
                            <span><i aria-hidden="true" class="fa fa-book"></i></span>
                            <input type="text" id="subject" name="subject" placeholder="Subject" required>
                        </div>

                        <div class="input_field">
                            <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
                            <input type="text" id="academic-year" name="academic-year" pattern="\d{4}_\d{2}"
                                placeholder="Academic Year (e.g., 2023_24)" required>
                        </div>
                        <div class="input_field select_option">
                            <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
                            <select id="test-type" name="test-type" placeholder="Test type" required>
                                <option value="">Test type</option>
                                <option value="UT1">UT1</option>
                                <option value="UT2">UT2</option>
                                <option value="UT3">UT3</option>
                                <option value="Prelim">Prelims</option>
                                <option value="Assign1">Assignment_1</option>
                                <option value="Assign2">Assignment_2</option>
                                <option value="Assign3">Assignment_3</option>
                                <option value="Assign4">Assignment_4</option>
                            </select>
                            <div class="select_arrow"></div>
                        </div>

                        <div class="frame">
                            <button class="custom-btn btn-9" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>




</body>

</html>
<?php

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $subject = $_POST['subject'];
  $testType = $_POST['test-type'];
  $department = $_POST['department'];
  $year = $_POST['year'];
  $sem = $_POST['sem'];
  $academic_year = $_POST['academic-year'];
  $division = $_POST['division'];


  // Set session variables
  $_SESSION['department'] = $department;
  $_SESSION['year'] = $year;
  $_SESSION['sem'] = $sem;
  $_SESSION['subject'] = $subject;
  $_SESSION['division'] = $division;
  $_SESSION['academic_year'] = $academic_year;
  $_SESSION['test-type'] = $testType;



  // Create a database connection
  $conn = mysqli_connect('localhost', 'root', '', 'mysql');
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  // Create database if it doesn't exist
  $sql = "CREATE DATABASE IF NOT EXISTS $department";
  if (mysqli_query($conn, $sql)) {
    // Check if database already exists
    if (mysqli_select_db($conn, $department)) {
      echo "<br>";
    } else {
      echo "Database created successfully";
    }
  } else {
    echo "Error creating database: " . mysqli_error($conn);
  }

  // Select the database
  mysqli_select_db($conn, $department);
  function display_table($conn, $table_name)
  {
    $sql = "SELECT * FROM $table_name";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo "<table border='1'>";
      echo "<tr>";
      echo "<th>Main Question</th>";
      echo "<th>Sub Question Number</th>";
      echo "<th>Sub Question</th>";
      echo "<th>Marks</th>";
      echo "<th>CO</th>";
      echo "<th>BL</th>";
      echo "</tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["main_question"] . "</td>";
        echo "<td>" . $row["sub_question_number"] . "</td>";
        echo "<td>" . $row["sub_question"] . "</td>";
        echo "<td>" . $row["marks"] . "</td>";
        echo "<td>" . $row["co"] . "</td>";
        echo "<td>" . $row["bl"] . "</td>";
        echo "</tr>";
      }

      echo "</table>";
    } else {
      echo "No data found in the table.";
    }
  }

  // Check if table exists
  $table_name = $year . '_' . $department . '_' . $division . '_' . $sem . '_' . $subject . '_' . $testType . '_' . $academic_year;
  $sql = "SHOW TABLES LIKE '$table_name'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    echo "Paper for $table_name already exists. <br> Do you want to continue with that the question paper?<br>";
    echo "<button onclick=\"window.location.href='dashbord.php'\">No</button>";
    echo "<button onclick=\"location.href='http://localhost/new1/excel_interface.php?table_name=" . urlencode($table_name) . "&department=" . urlencode($department) . "'\">Yes</button>";
    // Display the existing table data
    echo "<br><br><h3>Existing Question Paper Data:</h3>";
    display_table($conn, $table_name);

    exit();
  }

  // Create table if it doesn't exist
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        main_question INT NOT NULL,
        sub_question_number INT NOT NULL,
        sub_question VARCHAR(50) NOT NULL,
        marks INT NOT NULL,
        co INT NOT NULL,
        bl INT NOT NULL
      )";
  if (mysqli_query($conn, $sql)) {
    echo "Table created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }

  // Set session variables

  $_SESSION['table_name'] = $table_name;
  $_SESSION['department'] = $department;
  $_SESSION['sem'] = $sem;

  // Redirect to the next page
  // ob_end_flush();
  header("Location: test_input.php");
  exit();
}
// Check available tables and delete selected table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Delete selected table
  if (isset($_POST['delete_table'])) {
    $table_name = $_POST['table_name'];
    $sql = "DROP TABLE IF EXISTS $table_name";
    if (mysqli_query($conn, $sql)) {
      echo "Table $table_name deleted successfully";
    } else {
      echo "Error deleting table: " . mysqli_error($conn);
    }
  }
}

?>