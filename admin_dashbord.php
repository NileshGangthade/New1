<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: login.php");
  exit();
}



// Fetch waiting for approval list
$sql_waiting = "SELECT id, user_type, department, name, email FROM temp_table WHERE email_status = 1 AND user_type = 'hod';";
$result_waiting = $conn->query($sql_waiting);

// Fetch approved list
$sql_approved = "SELECT id, user_type, department, name, email FROM main_table WHERE is_approve = 1  AND user_type = 'hod';";
$result_approved = $conn->query($sql_approved);

?>

<!DOCTYPE html>
<html>

<head>
  <title>Admin dashbord</title>


  <link rel="stylesheet" href=" //cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="admin_dashbord.css">
</head>

<body>
<?php include 'nav_bar.php'; ?>
  <h1>Welcome to the Admin dashbord</h1>

  <h2>Waiting for Approval</h2>
  <div class="contener">

    <div class="td"></div>
    <table class="display" id="waitingTable">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">User Type</th>
          <th scope="col">Department</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_waiting->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_type']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
              <button onclick="handleAction('approve', <?php echo $row['id']; ?>)">Approve</button>
              <button onclick="handleAction('reject', <?php echo $row['id']; ?>)">Reject</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  </div>

  <h2>Approved List</h2>
  <div class="contener">
    <table class="display" id="approvalTable">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">User Type</th>
          <th scope="col">Department</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_approved->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_type']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
              <button onclick="handleAction('delete', <?php echo $row['id']; ?>)">Delete</button> <!-- Add this line -->
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    function handleAction(action, id) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'handle_approval.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          alert(xhr.responseText);
          location.reload();
        }
      };
      xhr.send('action=' + action + '&id=' + id);
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('table').DataTable();
    });
  </script>

</body>

</html>