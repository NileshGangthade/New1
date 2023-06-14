<?php
session_start();

require 'Mail/phpmailer/Exception.php';
require 'Mail/phpmailer/PHPMailer.php';
require 'Mail/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


function generateOTP($length = 6)
{
    $digits = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $digits[rand(0, strlen($digits) - 1)];
    }
    return $otp;
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];

    require 'config.php';
    // echo "Connected to database successfully.<br>"; // Debug line

    // Check if the email and name exist in the main_table
    $stmt = $conn->prepare("SELECT * FROM main_table WHERE email = ? AND name = ?");
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {


        $row = $result->fetch_assoc();

        // Generate a 6-digit OTP and store it in the main_table along with a timestamp
        $otp = generateOTP();
        $otp_expiry = date("Y-m-d H:i:s", strtotime('+5 minutes')); // OTP valid for 10 minutes

        $stmt = $conn->prepare("UPDATE main_table SET otp = ?, otp_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $otp, $otp_expiry, $email);
        $stmt->execute();

        $subject = "OTP for Password Reset";
        $message = "Your OTP for password reset is: " . $otp . "\n\nIt will expire in 5 minutes.";

        // Send OTP email
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = 'html';


        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'noreply.courseoutcome@gmail.com';               //SMTP username
            $mail->Password   = 'gkuuvyrynwxramew';                  //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('noreply.courseoutcome@gmail.com', 'course outcomes');
            $mail->addAddress($email, $name);                           //Add a recipient

            //Content
            $mail->isHTML(false);                                       //Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
?>
            <script>
                alert("<?php echo " OTP sent to " . $email ?> ,valid for 5 min");
            </script>
        <?php
        } catch (Exception $e) {
        ?>
            <script>
                alert("Error sending email");
            </script>
        <?php
        }


        $_SESSION['reset_email'] = $email;
        $_SESSION['otp_expiry']=$otp_expiry;


        header("Location: verify_otp.php?email=" . urlencode($email) . "&from=forgot_password");
    } else {
        ?>
        <script>
            alert("Email and name combination not found in our records.");
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
    <title>Forgot password</title>
    <link rel="stylesheet" href="register.css">


</head>

<body>
<?php include 'nav_bar.php'; ?>

    </nav>
    <form id="login-in-form" action="forgot_password.php" method="POST">

        <div class="form_wrapper">
            <div class="form_container">
                <div class="title_container">
                    <h1> Forgot password</h1>
                </div>
                <div class="row clearfix">
                    <div class="">

                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope">✉</i></span>
                            <input type="email" id="email" name="email" placeholder="Email" required />
                        </div>
                        <div class="row clearfix">
                            <div class="input_field">
                                <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"> 👤</i></span>
                                    <input type="text" id="name" name="name" placeholder=" Name" />
                                </div>
                            </div>

                        </div>

                        <div class="frame"><button class="custom-btn btn-9 , button" type="submit" id="register" name="submit">submit</button>

                        </div>
                        <a href="login.php"> ← Back to login</a>



                    </div>
                </div>
            </div>

        </div>
    </form>
    <script src="script.js" defer></script>
</body>

</html>