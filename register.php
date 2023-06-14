<?php
session_start();
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">


</head>

<body>

    <?php include 'nav_bar.php'; ?>
    <form id="sign-in-form" action="register_process.php" method="POST">

        <div class="form_wrapper">
            <div class="form_container">
                <div class="title_container">
                    <h1> Registration Form</h1>
                </div>
                <div class="row clearfix">
                    <div class="">
                        <form>
                            <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope">✉</i></span>
                                <input type="email" id="email" name="email" placeholder="Email" required />
                            </div>
                            <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i>🔒</span>
                                <input type="password" id="password" name="password" placeholder="Password" required />
                            </div>
                            <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i>🔒</span>
                                <input type="password" id="confirm-password" name="password" placeholder="Re-enter Password" required />
                            </div>
                            <div class="row clearfix">
                                <div class="input_field">
                                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"> 👤</i></span>
                                        <input type="text" id="name" name="name" placeholder=" Name" />
                                    </div>
                                </div>

                            </div>
                            <div class="input_field select_option">

                                <div id="use-type">
                                    <select id="user-type" name="user-type" required>
                                        <option value="">Select User Type</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="hod">HOD</option>
                                        <option value="principal">principal</option>
                                    </select>
                                </div>
                                <div class="select_arrow"></div>
                            </div>
                            <div id="departmentContainer">
                                <div class="input_field select_option">

                                    <select id="department" name="department">required
                                        <option value="">Select Department</option>
                                        <option value="first-year">First Year</option>
                                        <option value="cse">Computer Science and Engineering</option>
                                        <option value="entc">Electronics and Telecommunication Engineering</option>
                                        <option value="mech">Mechanical Engineering</option>
                                        <option value="civil">Civil Engineering</option>
                                        <option value="electrical">electrical Engineering</option>
                                    </select>
                                    <div class="select_arrow"></div>
                                </div>
                            </div>


                            <div class="frame"><button class="custom-btn btn-9 , button" type="submit" id="register">Register</button>
                            </div>
                        </form>
                        <p>If you have alearedy account continue with → <a href="login.php"> Login.</a></p>

                    </div>
                </div>
            </div>

        </div>
    </form>
    <script src="script.js" defer></script>
</body>

</html>