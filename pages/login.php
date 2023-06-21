<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
//include '../commons/DB_connector.php';

class LoginForm
{
    private $dbConnector;
    private $inPassword;
    private $noUsername;

    public function __construct()
    {
        $this->dbConnector = include "../commons/DB_connector.php";
        $this->inPassword = null;
        $this->noUsername = null;
    }

    public function loginUser(): void
    {
        if (isset($_POST['login'])) {
            $username = htmlspecialchars(stripslashes(trim($_POST['username'])));
            $password = htmlspecialchars(stripslashes(trim($_POST['password'])));

            // Prepare the query using prepared statements
            $stmt = $this->dbConnector->prepare("SELECT * FROM user_profiles WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hash = $row['Password'];
                function redirect($url) {
                    header("Location: " . $url);
                    exit;
                }
                if (password_verify($password, $hash)) {
                    $_SESSION['user_details'] = $row;
                    $_SESSION['logged_in'] = true;

                    if (isset($_SESSION['from'])) {
                        if ($_SESSION['from'] === "checkout") {
                            $totalFee = htmlspecialchars(stripslashes(trim($_GET['totalFee'])));
                            redirect("../pages/checkout.php");
                        } else if ($_SESSION['from'] === 'orders') {
                            redirect("../pages/orders.php");
                        }
                    } else {
                        redirect("../index.php");
                    }
                } else {
                    $this->inPassword = "<p style='color: red'> Incorrect password";
                }
            } else {
                $this->noUsername = "<p style='color: red'>Username not found</p>";
            }
        }
    }

    public function displayForm(): void
    {
        require_once "../commons/Header.php";
        ?>

        <!doctype html>
        <html lang="en">

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
                <h2 class="title">Log into your Account</h2>
                <div class="account-detail">
                    <div class="billing-detail">
                        <form class="checkout-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                              method="post">
                            <div class="form-group">
                                <label for="username" align="left">User Name (case-sensitive)</label>
                                <input type="text" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password" align="left">Password</label>
                                <input type="password" id="password" name="password" required>

                            </div>
                            <a href="forgotPassword.php" align="left" class="form-group">Forgot Password</a>
                            <div class="form-group">
                                <label></label>
                                <input type="submit" id="login" name="login" value="LOGIN">
                                <?php
                                if (isset($this->inPassword)) {
                                    echo $this->inPassword;
                                } else if (isset($this->noUsername)) {
                                    echo $this->noUsername;
                                }
                                ?>
                            </div>
                            <div style="padding-top: 20px;">
                                Are you a new user? <a href="register.php"> Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main> <!-- Main Area -->
        </div>

        </body>

        <?php require_once "../commons/footer.php" ?>
        </html>

        <?php
    }
}

$loginForm = new LoginForm();
$loginForm->loginUser();
$loginForm->displayForm();
?>
