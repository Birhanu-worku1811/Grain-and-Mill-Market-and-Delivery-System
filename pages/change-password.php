<?php

require_once "../functions/loginSession.php";
require_once "../commons/Header.php";

class ChangePasswordPage
{
    private $DB_Connector;
    private $username;
    private $Account;
    private $message;

    public function __construct()
    {
        $this->DB_Connector = include "../commons/DB_connector.php";
        $this->username = $_SESSION['user_details']['username'];
        $this->fetchUserAccount();
    }

    private function fetchUserAccount()
    {
        $read_DB = "SELECT * FROM user_profiles WHERE username='$this->username'";
        $ReadQuery = mysqli_query($this->DB_Connector, $read_DB);
        $this->Account = mysqli_fetch_assoc($ReadQuery);
    }

    private function validatePassword($password)
    {
        // Password must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long
        $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
        $missing_requirements = [];

        if (!preg_match('/[a-z]/', $password)) {
            $missing_requirements[] = '<h3 style="color: red"><ul><li>a lowercase letter</li></ul></h3> ';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $missing_requirements[] = '<h3 style="color: red"><ul><li>an uppercase letter</li></ul></h3>';
        }
        if (!preg_match('/\d/', $password)) {
            $missing_requirements[] = '<h3 style="color: red"><ul><li>a digit</li></ul></h3>';
        }
        if (strlen($password) < 8) {
            $missing_requirements[] = '<h3 style="color: red"><ul><li>at least 8 characters </li></ul></h3>';
        }

        return $missing_requirements;
    }

    public function updatePassword($oldPass, $newPass, $confirmPass)
    {
        if (password_verify($oldPass, $this->Account['Password'])) {
            $missing_requirements = $this->validatePassword($newPass);

            if (empty($missing_requirements)) {
                $user = $this->username;
                $newPass = password_hash($newPass, PASSWORD_DEFAULT);
                $passUpdate = "UPDATE user_profiles SET Password='$newPass' WHERE username='$user'";
                $this->DB_Connector->query($passUpdate);
                $this->message = "<h1 style='color: green'>Password Updated</h1>";
            } else {
                $this->message = "<h2 style='color: red ' > Your password is missing</h2>";

                if (count($missing_requirements) == 1) {
                    $this->message .= "<h2 style='color: red'>the following requirement: </h2>";
                } else {
                    $this->message .= "<h2 style='color: red'>the following requirements: </h2>";
                }

                $this->message .= implode(" ", $missing_requirements);
            }
        } else {
            $this->message = "<h1 style='color: red'> Wrong Password</h1>";
        }
    }

    public function display()
    {
        ?>
        <head>
            <!-- Meta Tags -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Title -->
            <title>Change Password - Grain Mill</title>
            <!-- Style Sheet -->
            <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/header.css">
            <link rel="stylesheet" type="text/css" href="../CSS/account.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/checkout.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
        </head>
        <body>
        <div class="container">
            <main>
                <div class="breadcrumb">
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li> /</li>
                        <li><a href="account.php">Account</a></li>
                        <li> /</li>
                        <li>Change Password</li>
                    </ul>
                </div> <!-- End of Breadcrumb-->

                <div class="account-page">
                    <div class="profile">
                        <div class="profile-img">
                            <?php if (!empty($_SESSION['user_details']['Profile_picture'])) { ?>
                                <img id="profile-picture-input" src="../<?php echo $accountDetails['Profile_picture']; ?>" alt="Profile Picture">
                            <?php }
                            if (empty($_SESSION['user_details']['Profile_picture'])) { ?>
                                <img id="profile-picture-input" src="../img/icons/account.png" alt="Profile Picture">
                            <?php } ?>
                            <h2><?php echo $this->Account['username'];?></h2>
                            <p><?php echo $this->Account['Email']?></p>
                        </div>
                        <ul>
                            <li><a href="account.php">Account <span>></span></a></li>
                            <li><a href="orders.php">My Orders <span>></span></a></li>
                            <li><a href="change-password.php" class="active">Change Password <span>></span></a></li>
                            <li><a href="../functions/userLogout.php">Logout <span>></span></a></li>
                        </ul>
                    </div>
                    <div class="account-detail">
                        <h2>Change Password</h2>
                        <div class="billing-detail">
                            <form class="checkout-form" action="change-password.php" method="post">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" id="old_password" name="old_password" required>
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                                <?php
                                if (isset($this->message)) {
                                    echo $this->message;
                                } ?>
                                <div class="form-group">
                                    <label></label>
                                    <input type="submit" id="update_pass" name="update_pass" value="Update">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main> <!-- Main Area -->
        </div>
        </body>
        <?php
        require_once "../commons/footer.php";
    }
}
$page = new ChangePasswordPage();

if (isset($_POST['update_pass'])) {
    $oldPass = trim(htmlspecialchars(stripslashes($_POST['old_password'])));
    $newPass = trim(htmlspecialchars(stripslashes($_POST['new_password'])));
    $confirmPass = trim(htmlspecialchars(stripslashes($_POST['confirm_password'])));
    $page->updatePassword($oldPass, $newPass, $confirmPass);
}

$page->display();
?>
