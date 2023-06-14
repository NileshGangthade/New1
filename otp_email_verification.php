<?php
session_start();
require 'config.php';


if (!isset($_SESSION['otp_expiry'])) {
    header("Location: register.php");
    exit();
}

$current_time = date("Y-m-d H:i:s");
$otp_expiry = $_SESSION['otp_expiry'];

if ($current_time > $otp_expiry) {
    unset($_SESSION['otp_expiry']);
    unset($_SESSION['verify_email']);
    echo "<script>alert('OTP has expired, please request a new one.'); window.location.href='register.php';</script>";
    exit();
}

// Your existing otp_email_verification.php code

if (isset($_GET['email']) && isset($_POST['submit'])) {
    $email = $_GET['email'];
    $entered_otp = $_POST['otp'];
    // echo "Email: $email<br>";
    // echo "Entered OTP: $entered_otp<br>";


    // Check if the entered OTP is valid
    $stmt = $conn->prepare("SELECT * FROM temp_table WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Update the status and clear the otp and otp_expiry fields
        $stmt = $conn->prepare("UPDATE temp_table SET email_status=1, otp = NULL, otp_expiry = NULL WHERE email = ?");
        $stmt->bind_param("s", $email);
        $is_updated = $stmt->execute();


        if ($is_updated) {
?>
            <script>
                alert("email verification sussfully ,wait for admin approval");
            </script>
        <?php

            header("Location: login.php?from=otp_email_verification");
        }
    } else {
        ?>
        <script>
            alert("Invalid or expired OTP. Please try again.");
        </script>
<?php
    }

    $stmt->close();
    $conn->close();
}


?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify otp</title>
    <link rel="stylesheet" href="register.css">

    <style>
        .alert {
            padding: 15px;
            background-color: chartreuse;
            font-size: 17px;
            color: black;
            opacity: 5;
            visibility: hidden;
            transition: opacity 0.5s, visibility 100.5s;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert.show-alert {
            opacity: 1;
            visibility: visible;
        }
    </style>


</head>

<body>

    <div id="success-alert" class="alert">
        OTP for email verification has been sent on your register email address! Please check your email.
    </div>

    <form id="verify_email-in-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?email=' . urlencode($_GET['email']); ?>" method="POST">

        <div class="form_wrapper">
            <div class="form_container">
                <div class="title_container">
                    <h1>Verify otp</h1>
                </div>

                <div class="otp-timer" id="otp-timer" style="text-align: center; font-weight: bold; margin-bottom: 15px;"></div>
                <div class="row clearfix">
                    <div class="">
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i>🔒</span>
                            <input type="text" id="otp" name="otp" placeholder="Enter otp" required />
                        </div>
                        <div class="frame"><button class="custom-btn btn-9 , button" type="submit" id="register" name="submit" value="submit">Submit</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    $otp_expiry_js = strtotime($otp_expiry) * 1000; // Convert OTP expiry time to JavaScript timestamp (milliseconds)
    ?>
    <script>
        // Set the date we're counting down to
        var countDownDate = new Date(<?php echo $otp_expiry_js; ?>);

        // Update the count down every 1 second
        var countdownFunction = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="otp-timer"
            document.getElementById("otp-timer").innerHTML = "Time remaining: " + minutes + "m " + seconds + "s ";

            // If the count down is finished, display expired message
            if (distance < 0) {
                clearInterval(countdownFunction);
                document.getElementById("otp-timer").innerHTML = "OTP expired!";
            }
        }, 1000);
    </script>


    <script>
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        function showAlert() {
            var alertElement = document.getElementById('success-alert');
            alertElement.classList.add('show-alert');
            setTimeout(function() {
                alertElement.classList.remove('show-alert');
            }, 5000);
        }

        window.addEventListener('DOMContentLoaded', function() {
            var fromRegisterProcess = getParameterByName('from') === 'register_process';
            if (fromRegisterProcess) {
                showAlert();
            }
        });
    </script>


</body>

</html>