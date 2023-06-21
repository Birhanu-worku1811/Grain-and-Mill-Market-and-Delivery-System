<?php

require_once "../commons/Header.php";

class VerifyCodePage {
    public $wrongCode;
    private $DB_Connector;

    public function __construct() {
        $this->DB_Connector = include "../commons/DB_connector.php";
    }

    public function handleVerify() {
        if (isset($_POST['verify'])) {
            $vID = $_SESSION['f_ID'];
            $read = "SELECT Verification_code FROM user_profiles WHERE ID='$vID'";
            $verifier = $this->DB_Connector->query($read);
            $row = mysqli_fetch_assoc($verifier);
            if (intval($row['Verification_code']) == $_POST['verifyCode']) {
                header("Location: resetPassword.php");
            } else {
                $this->wrongCode = "<h1 style='color: red'>Wrong Code!!!</h1>";
            }
        }
    }

    public function display() {
        ?>
        <head>
            <!-- Meta Tags -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Title -->
            <title>Login - Grain Mill</title>
            <!-- Style Sheet -->
            <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/header.css">
            <link rel="stylesheet" type="text/css" href="../CSS/checkout.css">
            <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
        </head>
        <body>
        <div class="container" align="center">
            <main style="width: 50%; text-align: center">
                <h2 class="title">Verify Code</h2>
                <div class="account-detail">
                    <div class="billing-detail">
                        <?php
                        if (isset($this->wrongCode)){
                            echo $this->wrongCode;
                        }
                        ?>
                        <form class="checkout-form" action="verifyCode.php" method="post">
                            <div class="form-group">
                                <label for="email" align="left">Enter your Verification Code</label>
                                <input type="number" id="verify" name="verifyCode" required>
                            </div>
                            <div class="form-group">
                                <label></label>
                                <input type="submit" id="login" name="verify" value="Verify">
                            </div>
                        </form>
                    </div>
                </div>
            </main> <!-- Main Area -->
        </div>
        </body>
        <?php
        require_once "../commons/footer.php";
    }
}

$page = new VerifyCodePage();
if (isset($_POST['verify'])){
    $page->handleVerify();
}
$page->display();
?>
