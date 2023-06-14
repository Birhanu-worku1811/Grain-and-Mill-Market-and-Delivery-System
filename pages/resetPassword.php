<?php
include "../commons/DB_connector.php";
require_once "../commons/Header.php";
?>


<?php
if (isset($_POST['updatePass'])){
    $newPassword = htmlspecialchars(stripslashes(trim($_POST['newPassword'])));
    $repeatPassword = htmlspecialchars(stripslashes(trim($_POST['repeatPassword'])));
    if ($newPassword !==$repeatPassword){
        $diffPass = "<h1 style='color: red'>Passwords do not Much</h1>";
    }else {
        $vId = $_SESSION['f_ID'];
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $passUpdate = "UPDATE user_profiles set Password='$newPassword' WHERE ID='$vId'";
        $DB_Connector->query($passUpdate);
        header("Location: login.php");
    }
}
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
        <h2 class="title">Update Password</h2>
        <div class="account-detail">
            <div class="billing-detail">
                <?php
                if (isset($diffPass)){
                    echo $diffPass;
                }
                ?>
                <form class="checkout-form" action="resetPassword.php" method="post">
                    <div class="form-group">
                        <label for="password" align="left">New Password</label>
                        <input type="password" id="password" name="newPassword">
                        <label for="password" align="left">Repeat Password</label>
                        <input type="password" id="password" name="repeatPassword" required>
                    </div>
                    <div class="form-group">
                        <label></label>
                        <input type="submit" id="update" name="updatePass" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </main> <!-- Main Area -->
</div>


</body>
<?php require_once "../commons/footer.php";?>
</html>