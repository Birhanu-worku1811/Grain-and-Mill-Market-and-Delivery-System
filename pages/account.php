<?php
include "../functions/loginSession.php";
include "../commons/Header.php";

class AccountPage
{
    private $DB_Connector;

    public function __construct()
    {
        $this->DB_Connector = include "../commons/DB_connector.php";
    }

    public function updateProfilePicture()
    {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileDestination = "../img/userProfile/" . $fileName;
        $id = $_SESSION['user_details']['ID'];

        move_uploaded_file($fileTmpName, $fileDestination);
        $fileDestination = substr($fileDestination, 3);
        $imgUpdate = "UPDATE user_profiles SET Profile_picture='$fileDestination' WHERE ID='$id'";
        $this->DB_Connector->query($imgUpdate);
        $_SESSION['user_details']['Profile_picture'] = $fileDestination;
    }

    public function updateAccountDetails()
    {
        if (isset($_POST['updateAccount'])) {
            $new_userName = htmlspecialchars(stripslashes(trim($_POST['username'])));
            $new_Age = htmlspecialchars(stripslashes(trim($_POST['age'])));
            $new_FirstName = htmlspecialchars(stripslashes(trim($_POST['fname'])));
            $new_LastName = htmlspecialchars(stripslashes(trim($_POST['lname'])));
            $new_Email = htmlspecialchars(stripslashes(trim($_POST['email'])));
            $new_city = htmlspecialchars(stripslashes(trim($_POST['city'])));
            $new_address = htmlspecialchars(stripslashes(trim($_POST['address'])));
            $new_phoneNumber = htmlspecialchars(stripslashes(trim($_POST['phone'])));
            $id = $_SESSION['user_details']['ID'];

            $updater = "UPDATE user_profiles SET username='$new_userName', Age='$new_Age', First_Name='$new_FirstName', Last_Name='$new_LastName', Email='$new_Email', city='$new_city', address='$new_address', phone_Number='$new_phoneNumber' WHERE ID='$id'";
            $updateQuery = $this->DB_Connector->query($updater);
        }
    }

    public function displayAccountDetails()
    {
        $username = $_SESSION['user_details']['username'];
        $read_DB = "SELECT * FROM user_profiles WHERE username='$username'";
        $ReadQuery = $this->DB_Connector->query($read_DB);
        $Account = $ReadQuery->fetch_assoc();

        return $Account;
    }
    public function retriveCities(){
        $cityQuery = $this->DB_Connector->query("SELECT * FROM cities");
        return $cityQuery;
    }
    public function displayAccount(){

        $accountDetails = $this->displayAccountDetails();
        ?>

        <!doctype html>
        <html lang="en">

        <style>
            /* Hide the default file input button */
            input[type="file"] {
                display: none;
            }

            /* Style the label to look like a button */
            .upload-button {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                color: #fff;
                background-color: #4CAF50;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            /* Change the color of the label text on hover */
            .upload-button:hover {
                background-color: #3e8e41;
            }

            /* Display the file name next to the button */
            .file-name {
                margin-left: 10px;
                font-size: 14px;
                color: #777;
            }
        </style>

        <head>
            <!-- Meta Tags -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Title -->
            <title>My Account - Grain Mill</title>
            <!-- Style Sheet -->
            <link rel="stylesheet" type="text/css" href="../CSS/global.css" />
            <link rel="stylesheet" type="text/css" href="../CSS/header.css">
            <link rel="stylesheet" type="text/css" href="../CSS/account.css" />
            <link rel="stylesheet" type="text/css" href="../CSS/checkout.css" />
            <link rel="stylesheet" type="text/css" href="../CSS/footer.css" />
        </head>

        <body>

        <div class="container">
            <main>
                <div class="breadcrumb">
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li> /</li>
                        <li>Account</li>
                    </ul>
                </div> <!-- End of Breadcrumb-->

                <div class="account-page">
                    <div class="profile">
                        <div class="profile-img">
                            <div id="profile-picture-container">
                                <?php if (!empty($_SESSION['user_details']['Profile_picture'])) { ?>
                                    <img id="profile-picture-input" src="../<?php echo $accountDetails['Profile_picture']; ?>" alt="Profile Picture">
                                <?php }
                                if (empty($_SESSION['user_details']['Profile_picture'])) { ?>
                                    <img id="profile-picture-input" src="../img/icons/account.png" alt="Profile Picture">
                                <?php } ?>
                            </div>
                            <h2><?php echo $accountDetails['username']; ?></h2>
                            <p><?php echo $accountDetails['Email'] ?></p>
                        </div>
                        <ul>
                            <li><a href="account.php" class="active">Account <span>></span></a></li>
                            <li><a href="orders.php">My Orders <span>></span></a></li>
                            <li><a href="change-password.php">Change Password <span>></span></a></li>
                            <li><a href="../functions/userLogout.php">Logout <span>></span></a></li>
                        </ul>
                    </div>
                    <div class="account-detail">
                        <h2>Account</h2>
                        <div class="billing-detail">
                            <form class="checkout-form" action="account.php" method="post" enctype="multipart/form-data">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label for="username">User Name (Required)</label>
                                        <input type="text" id="username" name="username" value="<?php echo $accountDetails['username'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="age">Age (Required)</label>
                                        <input type="number" id="age" name="age" min="18" value="<?php echo $accountDetails['Age'] ?>" required>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" id="fname" name="fname" value="<?php echo $accountDetails['First_Name'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" id="lname" name="lname" value="<?php echo $accountDetails['Last_Name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo $accountDetails['Email'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Profile Picture</label>
                                    <label for="cname" class="upload-button">Select Image</label>
                                    <span class="file-name"></span>
                                    <input type="file" id="cname" name="file" accept="image/*">
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <select id="city" name="city">
                                            <?php
                                            $cities = $this->retriveCities();
                                            while ($row = mysqli_fetch_assoc($cities)) {
                                                $selected = ($accountDetails['city_name'] == $row['city_name']) ? 'selected' : '';
                                                echo "<option value=" . $row['city_name'] . " " . $selected . ">" . $row['city_name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="tel" id="phone" name="phone" minlength="9" maxlength="12" placeholder="+251-XXXXXXX" value="<?php echo $accountDetails['phone_Number']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea style="resize:none" id="address" name="address" rows="3"><?php echo $accountDetails['address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label></label>
                                    <input type="submit" id="update" name="updateAccount" value="Update">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main> <!-- Main Area -->
        </div>



        </body>

        <?php include "../commons/footer.php"; ?>
        <script>
            // Update the file name display when a file is selected
            document.getElementById('cname').addEventListener('change', function() {
                var fileName = this.value.split('\\').pop();
                document.querySelector('.file-name').textContent = fileName;
            });
        </script>

        </html>
        <?php
   }
}

$accountPage = new AccountPage();
$accountPage->displayAccount();

if (isset($_POST['updateProfilePic'])) {
    $accountPage->updateProfilePicture();
}

if (isset($_POST['updateAccount'])) {
    $accountPage->updateAccountDetails();
}

?>

