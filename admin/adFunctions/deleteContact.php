<?php
include "DB_connector.php";
if (isset($_GET['deletingContactID'])){
    $deleteID = htmlspecialchars(stripslashes(trim($_GET['deletingContactID'])));
    $deleter = "DELETE FROM contact WHERE ID = $deleteID";
    $DB_Connector->query($deleter);
    header("Location: ../adminPages/contacts.php");
}