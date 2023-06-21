<?php
require_once "../commons/Header.php";
//require_once "../commons/Footer.php";
require_once '../phpEmail/PHPMailer/src/Exception.php';
require_once '../phpEmail/PHPMailer/src/PHPMailer.php';
require_once '../phpEmail/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPassword
{
    private $dbConnector;
    private $mailer;
    public $notRegistered;

    public function __construct()
    {
        $this->dbConnector = include "../commons/DB_connector.php";
        $this->mailer = new PHPMailer(true);
        $this->notRegistered = null;
    }

    public function sendConde()
    {
        if (isset($_POST['resetPassword'])) {
            $recipient = htmlspecialchars(stripslashes(trim($_POST['resetEmail'])));
            $code = rand(100000, 999999);

            $readUser = "SELECT Email, ID FROM user_profiles";
            $readUserQuery = $this->dbConnector->query($readUser);
            $this->notRegistered = "<h1 style='color: red' class='register-label'> You are not Registered</h1>";
            while ($findEmail = mysqli_fetch_assoc($readUserQuery)) {
                if ($recipient == $findEmail['Email']) {
                    $this->notRegistered = null;
                    if (session_status() === PHP_SESSION_NONE) {
                        @session_start();
                    }
                    $_SESSION['f_ID'] = $findEmail['ID'];
                }
            }

            if ($this->notRegistered == null) {
                $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $writeCode = "UPDATE user_profiles SET Verification_code='$code', Verification_code_expiration='$expirationTime' WHERE Email='$recipient'";
                $this->dbConnector->query($writeCode);

                $read = "SELECT * FROM user_profiles WHERE Email='$recipient'";
                $readQuery = $this->dbConnector->query($read);
                $row = mysqli_fetch_assoc($readQuery);
                $FirstName = $row['First_Name'];
                $LastName = $row['Last_Name'];

                try {
                    $this->mailer->isSMTP();
                    $this->mailer->Host = 'smtp.gmail.com';
                    $this->mailer->SMTPAuth = true;
                    $this->mailer->Username = 'python3.birhanu@gmail.com';
                    $this->mailer->Password = 'aaamexgdgzygzgje';
                    $this->mailer->SMTPSecure = 'ssl';
                    $this->mailer->Port = '465';

                    $this->mailer->setFrom('python3.birhanu@gmail.com');
                    $this->mailer->addAddress($recipient);
                    $this->mailer->isHTML(true);
                    $this->mailer->Subject = 'RESET PASSWORD';
                    $this->mailer->Body = "<p> Hello " . $FirstName . " " . $LastName . "<br> You request a password reset.<br> Your verification code is<br><br><h1 style='color: red;'> $code </h1><br> If you didn't request a password reset IGNORE this message</p>";

                    $this->mailer->send();
                    header("Location: verifyCode.php");
                } catch (Exception $ex) {
                    echo "<script> alert('Something Went wrong, check your connection')</script>";
                }
            }
        }
    }
}

$sendConde = new ForgotPassword();
$sendConde->sendConde();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/checkout.css">
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
</head>
<body>
<div class="container" align="center">
    <main style="width: 50%; text-align: center">
        <h2 class="title">Forgot Password</h2>
        <div class="account-detail">
            <div class="billing-detail">
                <?php
                if (isset($sendConde->notRegistered)) {
                    echo $sendConde->notRegistered . PHP_EOL;
                    echo "<a class='register-button' href='register.php'>Register</a>";
                }
                ?>
                <form class="checkout-form" action="forgotPassword.php" method="post">
                    <div class="form-group">
                        <label for="email" align="left">Enter your email</label>
                        <input type="email" id="email" name="resetEmail" required>
                    </div>
                    <div class="form-group">
                        <label></label>
                        <input type="submit" id="login" name="resetPassword" value="RESET">
                    </div>
                </form>
            </div>
        </div>
    </main> <!-- Main Area -->
</div>
</body>
<?php require_once "../commons/Footer.php"; ?>
</html>
