
<?php require_once "../commons/Header.php";
include "../commons/DB_connector.php" ?>

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="author" content="Kamran Mubarik">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Contact - Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/contact.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/checkout.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
</head>
<body>
<div class="container">
    <main>
        <div class="contact">
            <h2 class="heading">Contact Form</h2>

            <?php
            if (!empty($_POST['contact'])){
                $fname = htmlspecialchars(stripslashes(trim($_POST['fname'])));
                $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
                $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
                global $DB_Connector;
                $query = "INSERT INTO contact(First_Name, email, message) VALUES('$fname', '$email', '$message')";
                $send = mysqli_query($DB_Connector, $query);
                if (!$send){echo "oops something went wrong";}
                echo "<h1 style='color: #0A64C8'>Message Sent, Thank you!";
            }
            ?>
            <form class="checkout-form" method="post" action="contact.php">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" id="fname" name="fname" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Your Message</label>
                   <textarea name="message" id="message" rows="10" required></textarea>
                </div>
                <div class="form-group">
                    <label></label>
                    <input type="submit" id="contact" name="contact" value="Send">
                </div>
            </form>
        </div>
    </main> <!-- Main Area -->
</div>
<?php require_once "../commons/footer.php"; ?>
</body>

