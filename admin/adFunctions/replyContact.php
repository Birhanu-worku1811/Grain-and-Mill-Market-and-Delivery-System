<?php
include "DB_connector.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpEmail/PHPMailer/src/Exception.php';
require '../../phpEmail/PHPMailer/src/PHPMailer.php';
require '../../phpEmail/PHPMailer/src/SMTP.php';

if (isset($_POST['reply'])) {
    $recipient = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $fname = htmlspecialchars(stripslashes(trim($_POST['First_Name'])));
    $cId = htmlspecialchars(stripslashes(trim($_POST['contactId'])));
    $message = htmlspecialchars(trim($_POST['replyMessage']));
    $mailer = new PHPMailer(true);

    try {
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'python3.birhanu@gmail.com';
        $mailer->Password = 'aaamexgdgzygzgje';
        $mailer->SMTPSecure = 'ssl';
        $mailer->Port = '465';

        $mailer->setFrom('python3.birhanu@gmail.com');
        $mailer->addAddress($recipient);
        $mailer->isHTML(true);
        $mailer->Subject = 'GMMD';
        $mailer->Body = $message;
        $mailer->send();

        // Writing the email to the DB
        $emailStore = "INSERT INTO replies(ID, First_Name, email, body) VALUES ('$cId', '$fname', '$recipient', '$message')";
        $DB_Connector->query($emailStore);

        header("Location: ../adminPages/contacts.php");
        exit; // Add this line to stop executing the rest of the code

    } catch (\Exception $ex) {
        echo "<script>alert('Something went wrong');</script>";
        echo "<script>window.location.href='../adminPages/contacts.php';</script>";
        exit;
    }
}
?>
