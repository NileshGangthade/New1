<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) ) {
  header("Location: login.php");
  exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  $id = $_POST['id'];

  if ($action == 'approve') {
    // Fetch data from temp_table
    $sql_temp = "SELECT * FROM temp_table WHERE id = ?";
    $stmt_temp = $conn->prepare($sql_temp);
    $stmt_temp->bind_param("i", $id);
    $stmt_temp->execute();
    $result_temp = $stmt_temp->get_result();

    if ($result_temp->num_rows > 0) {
      $row_temp = $result_temp->fetch_assoc();
      $email = $row_temp['email'];

      // Check if email already exists in main_table
      $sql_check_email = "SELECT * FROM main_table WHERE email = ?";
      $stmt_check_email = $conn->prepare($sql_check_email);
      $stmt_check_email->bind_param("s", $email);
      $stmt_check_email->execute();
      $result_check_email = $stmt_check_email->get_result();

      if ($result_check_email->num_rows > 0) {

       
         echo ("Email already exists in the main table.");
        
      
      } else {
        // Move data from temp_table to main_table
        $sql_insert = "INSERT INTO main_table (user_type, department, name, email, password, is_admin, is_approve) VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssi", $row_temp['user_type'], $row_temp['department'], $row_temp['name'], $row_temp['email'], $row_temp['password'], $row_temp['is_admin']);
        $stmt_insert->execute();

        // Delete the record from temp_table
        $sql_delete = "DELETE FROM temp_table WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();

     
          echo("User approved successfully.");
      
   

      }

      $stmt_check_email->close();
    }

    $stmt_temp->close();
  } elseif ($action == 'reject') {
    // Delete the record from temp_table
    $sql_delete = "DELETE FROM temp_table WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();

   
    echo("User rejected successfully.");
    

  } elseif ($action == 'delete') {
    $sql_delete = "DELETE FROM main_table WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete) {
      $stmt_delete->bind_param("i", $id);
      $stmt_delete->execute();

      if ($stmt_delete->affected_rows > 0) {
    
        echo("User deleted successfully.");
      

      } else {
     
          echo("Error deleting user.");
        

      }

      $stmt_delete->close();
    } else {
      ?>
      <script>
        alert("Error :".<?php $conn ?>);
      </script>
<?php


    }
  }

  $conn->close();
}
