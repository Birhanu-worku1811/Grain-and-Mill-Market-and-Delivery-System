<?php
require_once "../commons/Header.php";
include "../commons/DB_connector.php";
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Meta Tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Title -->
        <title>My Orders - Grain Mill</title>
        <!-- Style Sheet -->
        <link rel="stylesheet" type="text/css" href="../CSS/orderDetails.css"/>
        <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
        <link rel="stylesheet" type="text/css" href="../CSS/header.css">
        <link rel="stylesheet" type="text/css" href="../CSS/account.css"/>
        <link rel="stylesheet" type="text/css" href="../CSS/orders.css"/>
        <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
    </head>
    <body>
    <main>
        <?php
        $id = htmlspecialchars(stripslashes(trim($_GET['order_id'])));
        $orderD = "SELECT * FROM orders
                JOIN order_details ON orders.ID=order_details.orderID
                WHERE orders.ID=$id";
        $orderD_Query = $DB_Connector->query($orderD);
        $orderFetch=mysqli_fetch_assoc($orderD_Query);
        ?>
        <h1 style="color: #0f4bac; font-size: 80px">Order Details</h1>
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
                    <span>Completed</span>
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
            <a href="../pages/market.php">Return to Shopping</a>
            <a href="../pages/orders.php">View Other Orders</a>
        </div>
    </main>
    </body>
    </html>
<?php
require_once "../commons/footer.php";
