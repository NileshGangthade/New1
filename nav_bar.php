<?php
require 'config.php';

if (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];
} else {
    $user_type = '';
}

$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0;
?>

<?php
if ($is_admin == 1 && $user_type != 'hod') {
?>
    <nav>

        <div class="left-nav">
            <ul class="nav-links">
                <a href="admin_dashbord.php">
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
            <a href="profile.php">
                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Profile
                </button>
            </a>
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
<?php
} elseif ($user_type == 'hod' || $user_type == 'teacher') {
?>
    <nav>
        <!-- Your hod/teacher navigation bar here -->

        <div class="left-nav">
            <ul class="nav-links">
                <?php
                if ($user_type == 'teacher') {
                ?>
                    <a href="dashbord.php">
                        <button class="nav_button">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span> dashbord
                        </button>
                    </a>
                <?php
                }
                ?>

                <a href="view_results.php">
                    <button class="nav_button">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span> View Results
                    </button>
                </a>

                <?php
                if ($user_type == 'hod') {
                ?>
                    <a href="department_approval_list.php">
                        <button class="nav_button">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span> Pending Approval
                        </button>
                    </a>
                    <a href="progress.php">
                        <button class="nav_button">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span> Progress
                        </button>
                    </a>
                <?php
                }
                ?>
            </ul>
        </div>

        <div class="right-nav">
            <a href="profile.php">
                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Profile
                </button>
            </a>
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
<?php
} else {
?>
    <nav>
        <!-- Your regular navigation bar here -->
        <div class="left-nav">
            <ul class="nav-links">
                <a href="index.php">
                    <button class="nav_button">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span> Home
                    </button>
                </a>
            </ul>
        </div>
        <div class="right-nav">
            <a href="register.php">
                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Register
                </button>
            </a>
            <a href="login.php">
                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Login
                </button>
            </a>
        </div>
    </nav>
<?php
}
?>