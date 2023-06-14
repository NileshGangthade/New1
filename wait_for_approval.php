<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM temp_table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
?>
    <script>
        alert("User not found");
    </script>
<?php

    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

</head>

<body>
    <nav>
        <div class="left-nav">
            <ul class="nav-links">

                <a href="wait_for_approval.php">
                    <button class="nav_button">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span> dashbord
                    </button>
                </a>

            </ul>
        </div>
        <div class="right-nav">

            <a href="logout.php">

                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Logout
                </button>
            </a>
        </div>

    </nav>
    <div class="msg">
        <h2> your form is successfully submited, wait for admin approval</h2>
    </div>
    <div class="profile-container">
        <div class="profile-icon">
            <?php echo strtoupper(substr($user["name"], 0, 1)); ?>
        </div>
        <div class="profile-info">
            <h2><?php echo $user["name"]; ?></h2>
            <p>Email: <?php echo $user["email"]; ?></p>
            <p>User Type: <?php echo ucfirst($user["user_type"]); ?></p>
            <?php if (!empty($user["department"])) : ?>
                <p>Department: <?php echo ucfirst($user["department"]); ?></p>
            <?php endif; ?>

            <!-- Add the buttons after the profile-info div -->
            <div class="action-buttons">
                <a href="edit_profile.php">
                    <button>Edit</button>
                </a>
                <form action="delete_profile.php" method="post" onsubmit="return confirm('Are you sure you want to delete your profile? This action cannot be undone.');">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </div>
        </div>

    </div>
</body>

</html>