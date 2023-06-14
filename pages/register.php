<!doctype html>
<html lang="en">
<?php

class Registration
{
     public $userName;
     public $firstName;
     public $lastName;
     public $age;
     public $email;
     public $password;
     public $confirmPassword;
     public $emailPattern;
     public $passwordPattern;
     public $dbConnector;
    public $ErrorMessage;
    public $userNameExists;
    public $emailExists;

    public function __construct()
    {
        $this->emailPattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $this->passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
        $this->dbConnector = include "../commons/DB_connector.php";
        $this->emailExists = "";
        $this->userNameExists = "";
    }

    public function writeToDB(): void
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user_profiles(username, First_Name, Last_Name, Age, Email, Password)";
        $query .= " VALUES ('$this->userName', '$this->firstName', '$this->lastName', '$this->age', '$this->email', '$hashedPassword')";
        $result = $this->dbConnector->query($query);
        if (!$result) {
            die('Query Failed' . $this->dbConnector->error);
        }
        header("Location: login.php");
    }

    public function findUserName(): string
    {
        $read = "SELECT username FROM user_profiles";
        $userCheck = $this->dbConnector->query($read);
        while ($users = $userCheck->fetch_assoc()) {
            if ($users['username'] === $this->userName) {
                $this->userNameExists = "<h4 style='color: red'> Username is taken by others, man";
                break;
            }
        }
        return $this->userNameExists;
    }
    public function findEmail(): string
    {
        $read = "SELECT Email FROM user_profiles";
        $userCheck = $this->dbConnector->query($read);
        while ($users = $userCheck->fetch_assoc()) {
            if ($users['Email'] === $this->email) {
                $this->emailExists = "<h4 style='color: red;'> There is an account with this email already";
                break;
            }
        }
        return $this->emailExists;
    }

    public function validateForm()
    {
        if (empty($this->userName)) {
            $this->ErrorMessage = "<h3 style='color: red' align='center'>User name cannot be empty</h3>";
        } elseif (strlen($this->userName) > 15) {
            $this->ErrorMessage = "<h3 style='color: red'>User name cannot be longer than 15 characters</h3>";
        } elseif (empty($this->age)) {
            $this->ErrorMessage = "<h3 style='color: red'>Age is required</h3>";
        } elseif (empty($this->firstName) || empty($this->lastName)) {
            $this->ErrorMessage = "<h3 style='color: red'>First Name and Last name are required";
        } elseif (!preg_match($this->emailPattern, $this->email)) {
            $this->ErrorMessage = "<h3 style='color: red'>Invalid email, Please check your email again</h3>";
        } elseif (empty($this->password)) {
            $this->ErrorMessage = "<h3 style='color: red'>Password cannot be empty</h3>";
        } elseif (!preg_match($this->passwordPattern, $this->password)) {
            $missingRequirements = [];

            // Password must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long
            if (!preg_match('/[a-z]/', $this->password)) {
                $missingRequirements[] = '<h3 style="color: red"><ul><li>a lowercase letter</li></ul></h3> ';
            }
            if (!preg_match('/[A-Z]/', $this->password)) {
                $missingRequirements[] = '<h3 style="color: red"><ul><li>an uppercase letter</li></ul></h3>';
            }
            if (!preg_match('/\d/', $this->password)) {
                $missingRequirements[] = '<h3 style="color: red"><ul><li>a digit</li></ul></h3>';
            }
            if (strlen($this->password) < 8) {
                $missingRequirements[] = '<h3 style="color: red"><ul><li>at least 8 characters</li></ul></h3>';
            }

            if ($this->password !== $this->confirmPassword) {
                $missingRequirements[] = '<h3 style="color: red"><ul><li>Passwords do not match</li></ul></h3>';
            }

            if (empty($missingRequirements)) {
                $this->findUserName();
                $this->findEmail();
                if (empty($this->userNameExists) && empty($this->emailExists)){
                    $this->writeToDB();
                }
            } else {
                $this->ErrorMessage = "<h2 style='color: red'> Your password is missing the following requirement(S)</h2>";
                $this->ErrorMessage .= implode(" ", $missingRequirements);
            }
        }
    }

    public function processForm(): void
    {
        if (isset($_POST['Register'])) {
            $this->userName = htmlspecialchars(stripslashes(trim($_POST['username'])));
            $this->age = htmlspecialchars(stripslashes(trim($_POST['age'])));
            $this->firstName = htmlspecialchars(stripslashes(trim($_POST['f_name'])));
            $this->lastName = htmlspecialchars(stripslashes(trim($_POST['l_name'])));
            $this->email = htmlspecialchars(stripslashes(trim($_POST['email'])));
            $this->password = $_POST["password"];
            $this->confirmPassword = htmlspecialchars(stripslashes(trim($_POST['confirm_password'])));

            $this->validateForm();
        }
    }
}

$registrationForm = new Registration();
$registrationForm->processForm();
?>

<?php require_once "../commons/Header.php"; ?>

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Register - Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/checkout.css">
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
</head>

<body>
<div class="container">
    <main style="width: 80%;">
        <div class="account-detail">
            <div class="billing-detail">
                <form class="checkout-form" action="register.php" method="post" name="form1">
                    <h2 class="title" align="center">Create New Account</h2>
                    <?php
                    if (isset($registrationForm->ErrorMessage)){
                        echo $registrationForm->ErrorMessage;
                    } elseif (!empty($registrationForm->userNameExists)){
                        echo $registrationForm->userNameExists;
                    } elseif (!empty($registrationForm->emailExists)){
                        echo $registrationForm->emailExists;
                    }
                    ?>
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="username">User Name (Required)</label>
                            <input type="text" id="username" name="username" value="<?php echo $registrationForm->userName ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="age">Age (Required)</label>
                            <input type="number" id="age" name="age" min="18" value="<?php echo $registrationForm->age ?? ''; ?>"></div>
                    </div>
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="f_name" placeholder="E.g. Bisrat" value="<?php echo $registrationForm->firstName ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="l_name" placeholder="E.g. Kebere" value="<?php echo $registrationForm->lastName ?? ''; ?>">
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="E.g dev@bisrat.tech" value="<?php echo $registrationForm->email ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-inline">
                        <div class="form-group">
                            <label>Password ()</label>
                            <input type="password" id="password" name="password"
                                   placeholder="+8 characters lower, UPPER, Speci@l and Number ">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password (Required)</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                   placeholder="Repeat your Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="register"></label>
                        <input type="submit" id="register" name="Register" value="REGISTER">
                    </div>
                </form>
                <div style="padding-top: 20px;">
                    Already Have an Account? <a href="login.php"> Login</a>
                </div>
            </div>
        </div>
    </main> <!-- Main Area -->
</div>

</body>
<?php require_once "../commons/footer.php"; ?>


</html>
