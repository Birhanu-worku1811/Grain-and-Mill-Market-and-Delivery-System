<?php
include "DB_connector.php";
if (isset($_GET['deletingID'])){
    $deleteID = htmlspecialchars(stripslashes(trim($_GET['deletingID'])));
    $deleter = "DELETE orders, order_details 
        FROM orders 
        INNER JOIN order_details 
        ON orders.ID = order_details.orderID
        WHERE orders.ID = $deleteID";
    $DB_Connector->query($deleter);
    header("Location: ../index.php");
}