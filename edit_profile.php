<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $user_type = $_POST["user_type"];
    $department = $_POST["department"];



    if ($user_type == 'principal') {
        $department = NULL;
    }

    $sql = "UPDATE temp_table SET name = ?, user_type = ?, department = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $user_type, $department, $user_id);
    $stmt->execute();

    header("Location: wait_for_approval.php");
    exit();
}

$sql = "SELECT * FROM temp_table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="register.css">
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

    <form id="eadit_profile-in-form" action="edit_profile.php" method="POST">

        <div class="form_wrapper">
            <div class="form_container">
                <div class="title_container">
                    <h1> Edit profile</h1>
                </div>
                <div class="row clearfix">
                    <div class="">
                        <form>
                            <div class="row clearfix">
                                <div class="input_field">
                                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"> 👤</i></span>
                                        <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" placeholder=" Name" />
                                    </div>
                                </div>

                            </div>
                            <div class="input_field select_option">

                                <div id="use-type">
                                    <select id="user_type" name="user_type" required>
                                        <option value="principal" <?php if ($user['user_type'] == 'principal') echo 'selected'; ?>>Principal</option>
                                        <option value="hod" <?php if ($user['user_type'] == 'hod') echo 'selected'; ?>>hod</option>
                                        <option value="teacher" <?php if ($user['user_type'] == 'teacher') echo 'selected'; ?>>Teacher</option>

                                    </select>
                                </div>
                                <div class="select_arrow"></div>
                            </div>
                            <div id="departmentContainer">
                                <div class="input_field select_option">

                                    <select id="department" name="department" value="<?php echo $user['department']; ?>">required
                                        <option value="" <?php if ($user['department'] == 'null') echo 'selected'; ?>></option>
                                        <option value="first-year" <?php if ($user['department'] == 'first-year') echo 'selected'; ?>>First year</option>
                                        <option value="cse" <?php if ($user['department'] == 'cse') echo 'selected'; ?>>Computer Science and Engineering</option>
                                        <option value="entc" <?php if ($user['department'] == 'entc') echo 'selected'; ?>>Electronics and Telecommunication Engineering</option>
                                        <option value="mech" <?php if ($user['department'] == 'mech') echo 'selected'; ?>>Mechanical Engineering</option>
                                        <option value="civil" <?php if ($user['department'] == 'civil') echo 'selected'; ?>>Civil Engineering</option>
                                        <option value="electrical" <?php if ($user['department'] == 'electrical') echo 'selected'; ?>>Electronics and Telecommunication Engineering</option>
                                    </select>
                                    <div class="select_arrow"></div>
                                </div>
                            </div>


                            <div class="frame"><button class="custom-btn btn-9 , button" type="submit" id="register">Save Changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </form>
    <script src="script.js" defer></script>
</body>

</html>