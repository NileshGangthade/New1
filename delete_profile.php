
<?php
session_start();
require'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];

    $sql = "DELETE FROM temp_table WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        ?>
        <script>
          alert("Error deleting profile. ");
        </script>
    <?php
      
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: wait_for_approval.php");
    exit();
}
