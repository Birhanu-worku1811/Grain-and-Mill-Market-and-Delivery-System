<?php
require_once "../admin_commons/header.php";
include "DB_connector.php";
?>

<?php

if (isset($_GET['completingID'])){
    $completeID = htmlspecialchars(stripslashes(trim($_GET['completingID'])));
    $completer = "UPDATE orders SET status='completed' WHERE ID = '$completeID'";
    $DB_Connector->query($completer);
    header("Location: ../index.php");
}
?>


<!--Style for the confirmation pop up-->
<style>
    .confirmation-popup {
        display: none;
        position: absolute;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        padding: 10px;
        box-shadow: 0px 0px 5px #cccccc;
        z-index: 1;
    }

    .confirm-btn, .cancel-btn, .delete-btn {
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .confirm-btn:hover {
        background-color: #a94442;
        color: #ffffff;
    }

    .cancel-btn:hover {
        background-color: #6abf69;
        color: #ffffff;
        border: 1px solid #cccccc;
    }

    .delete-btn:hover {
        background-color: #a94442;
        color: #ffffff;
    }

    .delete-btn {
        background-color: #dc3545;
        color: #ffffff;
    }

    .confirm-btn {
        background-color: #dc3545;
        color: #ffffff;
        margin-right: 10px;
    }

    .cancel-btn {
        background-color: #ffffff;
        color: #333333;
        border: 1px solid #cccccc;
    }
</style>



    <!DOCTYPE html>
    <html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../CSS/orderDetails.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>GM - Admin</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../css/astyle.css" />
    <!--    <link rel="stylesheet" type="text/css" href="./css/style.css" />-->
</head>

    <body>
    <main>
        <?php
        $id = $_GET['order_Id'];
        $id = $_GET['order_Id'];
        $orderD = "SELECT * FROM orders
                    JOIN order_details ON orders.ID=order_details.orderID
                    WHERE orders.ID=$id";
        $orderD_Query = $DB_Connector->query($orderD);
        $orderFetch=mysqli_fetch_assoc($orderD_Query);
        ?>
        <h1 style="color: #0f4bac; font-size: 80px"><?php echo $orderFetch['First_Name']." ".$orderFetch['Last_Name']?></h1>
        <table>
            <tr>
                <div class="order-info">
                    <td> <div class="order-number">
                            <label style="color: #932ab6; font-size: 20px">Order #:</label>
                            <span><?php echo $orderFetch['ID']?></span>
                        </div> </td>
                    <td> <div class="order-date">
                            <label style="color: #932ab6; font-size: 20px">Order Date:</label>
                            <span><?php echo $orderFetch['orderDate']?></span>
                        </div> </td>
                </div>
            </tr>
            <tr>
                <td><div class="shipping-address">
                        <h2 style="color: #932ab6; font-size: 20px">Shipping Address</h2>
                        <address>
                            <?php
                            echo $orderFetch['address'];
                            ?>
                        </address>
                    </div> </td>
                <td><div class="status">
                        <label style="color: #932ab6; font-size: 20px">Status:</label>
                        <span><?php echo $orderFetch['status']?></span>
                    </div>  </td>
            </tr>
            <tr>
                <td><div class="payment-method">
                        <h2 style="color: #932ab6; font-size: 20px">Payment Method</h2>
                        <p><?php echo $orderFetch['Payment_method']?></p>
                    </div> </td>
            </tr>
        </table>

        <table class="order-summary">
            <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>sub Total</th>
            </tr>
            </thead>
            <?php
            $Items = explode(',', $orderFetch['products']);
            $subTotals = explode(',', $orderFetch['subTotals']);
            $quantities = explode(',', $orderFetch['Quantities']);
            ?>
            <tbody>
            <?php
            for ($i=0; $i<count($Items);$i++){ ?>
                <tr>
                    <td><?php echo $Items[$i]?></td>
                    <td><?php echo $quantities[$i]?></td>
                    <td>ETB-<?php echo $subTotals[$i]?></td>
                </tr>
            <?php  }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">Shipping:</td>
                <td>ETB 1000</td>
            </tr>
            <tr>
                <td colspan="2">Total Fee:</td>
                <td>ETB<?php echo $orderFetch['totalFee']?></td>
            </tr>
            </tfoot>
        </table>
        <div class="shipping-method">
            <h2>Shipping Method</h2>
            <p>Standard Shipping - Estimated delivery date: May 12, 2023</p>
        </div>
        <div class="actions">
            <a href="../adminPages/orders.php">View Other Orders</a>
            <?php
            if ($orderFetch['status']!=='completed'){ ?>
            <a href="manageOrders.php?completingID=<?php echo $orderFetch['ID']?>">Complete</a>
            <?php } else{ ?>
                <button class="delete-btn" onclick="showConfirmation(this)">Delete</button>
<!--                <a href="deleteOrder.php?deletingID=--><?php //echo $orderFetch['ID']; ?><!--">Delete</a>-->

                <div class="confirmation-popup">
                    <p style="color: blue">Are you sure you want to delete<br> the order of <?php echo $orderFetch['First_Name']?> ?<br></p>
                    <button class="confirm-btn" onclick="deleteItem()">Yes</button>
                    <button class="cancel-btn" onclick="hideConfirmation(this)">No</button>
                </div>
           <?php } ?>
        </div>
    </main>
    </body>
    </html>

<script>
    function showConfirmation(button) {
        // Show the confirmation pop-up
        var confirmationPopup = button.nextElementSibling;
        confirmationPopup.style.display = "block";

        // Position the confirmation popup relative to the button
        var buttonRect = button.getBoundingClientRect();
        confirmationPopup.style.top = (buttonRect.top + buttonRect.height + 5) + "px";
        confirmationPopup.style.left = (buttonRect.left - 10) + "px";
    }

    function hideConfirmation(button) {
        // Hide the confirmation pop-up
        var confirmationPopup = button.parentNode;
        confirmationPopup.style.display = "none";
    }

    function deleteItem() {
        // Perform the delete action
        window.location.href = "deleteOrder.php?deletingID=<?php echo $orderFetch['ID']; ?>";
    }
</script>