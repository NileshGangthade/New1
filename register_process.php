<?php
session_start();
require 'config.php';
require 'Mail/phpmailer/Exception.php';
require 'Mail/phpmailer/PHPMailer.php';
require 'Mail/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateOTP($length = 6)
{
    $digits = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $digits[rand(0, strlen($digits) - 1)];
    }
    return $otp;
}

if (isset($_POST["user-type"]) && isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    $user_type = validate_input($_POST["user-type"]);
    $department = isset($_POST["department"]) ? validate_input($_POST["department"]) : "";
    $name = validate_input($_POST["name"]);
    $email = validate_input($_POST["email"]);
    $password = validate_input($_POST["password"]);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the temp_table
    $stmt = $conn->prepare("SELECT * FROM temp_table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email ID already exists'); window.location.href='register.php';</script>";
    } else {
        $sql = "INSERT INTO temp_table (user_type, department, name, email, password) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($user_type == 'principal') {
            $department = NULL;
        }
        $stmt->bind_param("sssss", $user_type, $department, $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // Generate a 6-digit OTP and store it in the main_table along with a timestamp
            $otp = generateOTP();
            $otp_expiry = date("Y-m-d H:i:s", strtotime('+5 minutes')); // OTP valid for 10 minutes

            $stmt = $conn->prepare("UPDATE temp_table SET otp = ?, otp_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $otp, $otp_expiry, $email);
            $stmt->execute();

            $subject = "OTP for Account verification";
            $message = "Your OTP for account verification is: " . $otp . "\n\nIt will expire in 5 minutes.";

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
                // Set the OTP expiration time in session
                $_SESSION['otp_expiry'] = $otp_expiry;
                $_SESSION['verify_email'] = $row['email'];
?>

                <?php
                // Your PHP code here

                // Display the alert message using JavaScript with echo
                echo '<script>alert("OTP sent to ' . $email . ', valid for 10 min");</script>';

                // Redirect the user to the otp_email_verification.php page with the email parameter
                $redirect_url = 'otp_email_verification.php?email=' . urlencode($email) . '&from=register_process';
                header('Location: ' . $redirect_url);


                // More PHP code, if needed
                ?>


            <?php
            } catch (Exception $e) {
            ?>
                <script>
                    alert("Error sending email");
                </script>
            <?php
            }

            // $_SESSION['verify_email'] = $row['email'];
            // header("Location: otp_email_verification.php?email=" . urlencode($email));
            // exit();
        } else {

            ?>
            <script>
                alert("Something else wrong");
                window.location.href = ('register.php');
            </script>

<?php
            echo "<script>alert('Something else wrong'); 
            window.location.href='register.php';
            </script>";
        }
    }
    $stmt->close();
    $conn->close();
}
?>