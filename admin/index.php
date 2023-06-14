<?php
include "adFunctions/AdminSession.php";
class AdminDashboardPage
{
    private $DB_Connector;

    public function __construct()
    {
        $this->DB_Connector = include "../commons/DB_connector.php";
    }

    public function lowStockReport(): mysqli_result{
        $retrive = "SELECT * FROM products WHERE Quantity<500";
        $query = mysqli_query($this->DB_Connector, $retrive);
        return $query;
    }

    public function lastThreeDaysOrders(): mysqli_result{

        $readOrder = "SELECT * FROM orders WHERE orderDate >=DATE_SUB(NOW(), INTERVAL 3 DAY)";
        $readOrderQuery = mysqli_query($this->DB_Connector, $readOrder);
        return $readOrderQuery;
    }
    public function completedOrder():mysqli_result{
        $completed = "SELECT * FROM orders WHERE status='completed'";
        $completedQuery = mysqli_query($this->DB_Connector, $completed);
        return $completedQuery;
    }
    public function forgotenOrders(): mysqli_result{
        $readOrder = "SELECT * FROM orders WHERE orderDate <DATE_SUB(NOW(), INTERVAL  7 DAY) AND status!='completed'";
        $readOrderQuery = mysqli_query($this->DB_Connector, $readOrder);
        return $readOrderQuery;
    }

    public function display()
    {
        require_once "admin_commons/header.php";
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <!-- Meta Tags -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Title -->
            <title>GM - Admin</title>
            <!-- Style Sheet -->
            <link rel="stylesheet" type="text/css" href="./css/astyle.css" />
            <!--	<link rel="stylesheet" type="text/css" href="./css/style.css" />-->
        </head>

        <body>

        <main>
            <div class="main-content">
                <div class="sidebar">
                    <h3>Menu</h3>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li><a href="adminPages/orders.php">Orders</a></li>
                        <li><a href="adminPages/addProduct.php">Products</a></li>
                        <li><a href="adminPages/addCategory.php">Categories</a></li>
                        <li><a href="adminPages/addStock.php">Stock</a></li>
                        <li><a href="adminPages/users.php">Users</a></li>
                        <li><a href="adminPages/contacts.php">Contacts</a></li>
                        <li><a href="adFunctions/adminLogout.php">Log out</a></li>
                    </ul>
                </div>
                <div class="content dashboard">
                    <h3>Dashboard</h3>
                    <div class="content-data">
                        <div class="content-detail">
                            <h4>Low Stock Report</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $low = $this->lowStockReport();
                                while ($fetch = mysqli_fetch_assoc($low)) { ?>
                                    <tr>
                                        <?php echo "<th>" . $fetch['Name'] . "</th>";
                                        echo "<th>" . $fetch['Price'] . "</th>";
                                        echo "<th>" . $fetch['Category'] . "</th>";
                                        echo "<th>" . $fetch['Quantity'] . "</th>"; ?>
                                        <th><a href="adminPages/addStock.php?stockID=<?php echo $fetch['ID'] ?>">Add Stock</a>
                                        </th>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <br><br>
                            <h4>Last Three Days Orders</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order Ref#</th>
                                    <th>Customer</th>
                                    <th>totalFee</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $ordersT = $this->lastThreeDaysOrders();
                                while ($orders3 = mysqli_fetch_assoc($ordersT)) { ?>
                                    <tr>
                                        <td><?php echo $orders3['orderDate'] ?></td>
                                        <td><?php echo $orders3['ID'] ?></td>
                                        <td><?php echo $orders3['First_Name'] . " " . $orders3['Last_Name'] ?></td>
                                        <td><?php echo $orders3['totalFee'] ?><sub>Birr</sub></td>
                                        <td><?php echo $orders3['status'] ?></td>
                                        <td><a href="adFunctions/manageOrders.php?order_Id=<?php echo $orders3['ID'] ?>">Manage</a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="content-detail">
                            <h4>Completed Order</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order Ref#</th>
                                    <th>Customer</th>
                                    <th>Total Fee</th>
                                    <th>View</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $completeds = $this->completedOrder();
                                while ($completed = mysqli_fetch_assoc($completeds)) { ?>
                                    <tr>
                                        <td><?php echo $completed['orderDate'] ?></td>
                                        <td><?php echo $completed['ID'] ?></td>
                                        <td><?php echo $completed['First_Name'] . " " . $completed['Last_Name'] ?></td>
                                        <td><?php echo $completed['totalFee'] ?></td>
                                        <td><a href="adFunctions/manageOrders.php?order_Id=<?php echo $completed['ID'] ?>">View</a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="content-detail">
                            <h4 style="color: red">Forgotten Orders/7 days or more passed but not complete!!!!</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order Ref#</th>
                                    <th>Customer</th>
                                    <th>totalFee</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $today = date('Y-m-d');
                                $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
                                $forgottenOrders = $this->forgotenOrders();
                                while ($forgottenOrder = mysqli_fetch_assoc($forgottenOrders)) { ?>
                                    <tr>
                                        <td><?php echo $forgottenOrder['orderDate'] ?></td>
                                        <td><?php echo $forgottenOrder['ID'] ?></td>
                                        <td><?php echo $forgottenOrder['First_Name'] . " " . $forgottenOrder['Last_Name'] ?></td>
                                        <td><?php echo $forgottenOrder['totalFee'] ?><sub>Birr</sub></td>
                                        <td><?php echo $forgottenOrder['status'] ?></td>
                                        <td><a href="adFunctions/manageOrders.php?order_Id=<?php echo $forgottenOrder['ID'] ?>">Manage</a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
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

        <?php
    }
}

$dashboardPage = new AdminDashboardPage();
$dashboardPage->display();
?>

