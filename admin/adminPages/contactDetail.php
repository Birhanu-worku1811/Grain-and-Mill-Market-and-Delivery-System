<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
include "../adFunctions/DB_connector.php";
?>

<style xmlns="http://www.w3.org/1999/html">
    .contact-page {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f2f2f2;
    }

    .contact-info {
        max-width: 800px;
        margin: 0 auto;
    }

    h2 {
        text-align: center;
    }

    .contact-list {
        display: grid;
        gap: 20px;
    }

    .contact-card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .info {
        display: flex;
        margin-bottom: 10px;
    }

    .label {
        flex-basis: 120px;
        font-weight: bold;
    }

    .value {
        flex-grow: 1;
    }

    /* Styling for the buttons */
    .buttons {
        display: flex;
        justify-content: flex-end;
    }

    button {
        background-color: #4caf50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }

    .important-button {
        background-color: #f8b500;
    }

    .delete-button {
        background-color: darkgoldenrod;
    }
    .delete-button:hover {
        background-color:red;
    }

    /* Styling for the icons */
    button i {
        margin-right: 5px;
    }



</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>GM - Admin</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../css/astyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-jzOR+9eN9F4GcD2RxK3XUxZtGg5h+3rR4vcsKrI8koO67Yj8YclRcY4U1f5BYoBNi7TKfdozV3jsQy2A10lZ6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--    <link rel="stylesheet" type="text/css" href="./css/style.css" />-->
</head>
<body>

<?php
if (isset($_GET['contactID'])){
    $contactID = htmlspecialchars(stripslashes(trim($_GET['contactID'])));
    $contactReader = "SELECT * FROM contact WHERE ID='$contactID'";
    $contactReaderQuery = mysqli_query($DB_Connector,$contactReader);
    $contact = mysqli_fetch_assoc($contactReaderQuery);

    $markAsReadQuery = "UPDATE contact SET read_status = 1 WHERE ID = $contactID";
    mysqli_query($DB_Connector, $markAsReadQuery);
}

?>


<main>


    <div class="main-content">
        <div class="sidebar">
            <h3>Menu</h3>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="addProduct.php">Products</a></li>
                <li><a href="addCategory.php">Categories</a></li>
                <li><a href="addStock.php">Stock</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a class="active" href="contacts.php">Contacts</a></li>
                <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
            </ul>
        </div>
        <div class="content">
            <h3 style="text-align: center"><?php echo $contact['First_Name'] ?></h3>
            <div class="content-detail">
                <div class="contact-page"
                    <div class="contact-info">
                        <div class="contact-list">
                            <div class="contact-card">
                                <div class="info">
                                    <div class="label">Date:</div>
                                    <div class="value"><?php echo $contact['creation_time']?></div>
                                </div>
                                <div class="info">
                                    <div class="label">Email:</div>
                                    <div class="value"><?php echo $contact['email']?></div>
                                </div>
                                <div class="info">
                                    <div class="label">Message:</div>
                                    <div class="value"><?php echo $contact['message']?></div>
                                </div>
                                <div class="info">
                                    <div class="buttons">
                                        <button onclick="location.href='../adFunctions/deleteContact.php?deletingContactID=<?php echo $contactID?>'" class="delete-button"><i class="fas fa-trash" ></i> Delete Contact</button>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $cId = trim($contact['ID']);
                            $replies = "SELECT * FROM replies WHERE ID = '$cId'";
                            $repliesQuery = mysqli_query($DB_Connector, $replies);
                            while ($reply = mysqli_fetch_assoc($repliesQuery)){ ?>
                            <div class="contact-card">
                                <h2 style="color: green"><b>Your Replies</b></h2>
                                <div class="info">
                                    <div class="label">Date:</div>
                                    <div class="value"><?php echo $reply['creation_time']?></div>
                                </div>
                                <div class="info">
                                    <div class="label">Message:</div>
                                    <div class="value"><?php echo $reply['body']?></div>
                                </div>
                                <hr><hr>
                                <?php }
                                ?>
                            </div>
                            <div class="contact-card">
                                <form action="../adFunctions/replyContact.php" method="POST">
                                    <input type="hidden" name="contactID" value="<?php echo $contactID; ?>">
                                    <div class="info">
                                        <div class="label">Reply:</div>
                                        <div class="value">
                                            <textarea name="replyMessage" rows="3" required></textarea>
                                            <input type="hidden" name="email" value="<?php echo $contact['email']?>">
                                            <input type="hidden" name="contactId" value="<?php echo $contact['ID']?>">
                                            <input type="hidden" name="First_Name" value="<?php echo $contact['First_Name']?>">
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="value">
                                            <button name="reply" type="submit">Send Reply</button>
                                        </div>
                                    </div>
                                </form>

                            </div

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main> <!-- Main Area -->

<footer>
    <div class="container">
        <div class="footer-bar">
            <div class="copyright-text">
                <p>Proudly made by Section A Software Engineering Students.<br>
                    Copyright &copy; AASTU 2023 - All Rights Reserved.<p>
            </div>
        </div> <!-- Footer Bar -->
    </div>
</footer> <!-- Footer Area -->

</body>

</html>