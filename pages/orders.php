<?php require_once "../commons/Header.php";  ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$_SESSION['from']="orders";
include "../functions/loginSession.php";
      include "../commons/DB_connector.php";

global $DB_Connector;
$username = $_SESSION['user_details']['username'];
$read_DB = "SELECT * from user_profiles where username='$username'";
$ReadQuery = mysqli_query($DB_Connector, $read_DB);
$Account  = mysqli_fetch_assoc($ReadQuery);
?>



<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>My Orders - Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/account.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/orders.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
</head>
<body>

<div class="container">
    <main>
        <div class="breadcrumb">
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li> /</li>
                <li><a href="account.php">Account</a></li>
                <li> /</li>
                <li>Orders</li>
            </ul>
        </div> <!-- End of Breadcrumb-->


        <div class="account-page">
            <div class="profile">
                <div class="profile-img">
                    <div id="profile-picture-container">
                        <?php
                        if (!empty($_SESSION['user_details']['Profile_picture'])){?>
                            <img id="profile-picture-input" src="../<?php echo $Account['Profile_picture']; ?>" alt="Profile Picture">
                        <?php }
                        if (empty($_SESSION['user_details']['Profile_picture'])){ ?>
                            <img id="profile-picture-input" src="../img/icons/account.png" alt="Profile Picture">
                        <?php } ?>
                    </div>
                    <h2><?php echo $Account['username'];?></h2>
                    <p><?php echo $Account['Email']?></p>
                </div>
                <ul>
                    <li><a href="account.php">Account <span>></span></a></li>
                    <li><a href="orders.php" class="active">My Orders <span>></span></a></li>
                    <li><a href="change-password.php">Change Password <span>></span></a></li>
                    <li><a href="../functions/userLogout.php">Logout <span>></span></a></li>
                </ul>
            </div>
            <div class="account-detail">
                <h2>My Orders</h2>
                <div class="order-detail">
                    <form method="GET">
                        <label for="sort-by">Sort By:</label>
                        <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                            <option value="ID">ID</option>
                            <option value="orderDate" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'orderDate') echo 'selected'; ?>>Date</option>
                            <option value="totalFee" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'totalFee') echo 'selected'; ?>>Total Fee</option>
                            <option value="Payment_method" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Payment_method') echo 'selected'; ?>>Payment Method</option>
                            <option value="status" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'status') echo 'selected'; ?>>Status</option>
                        </select>
                    </form>
                    <table>
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order Ref#</th>
                            <th>Products</th>
                            <th>Total Fee</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $limit = 8;
                        $page = $_GET['page'] ?? 1;
                        $offset = ($page - 1) * $limit;
                        $sortColumn = $_GET['sort-by'] ?? 'ID';

                        $emailForOrder = $_SESSION['user_details']['Email'];

                        $orderViewer = "SELECT orders.orderDate, orders.ID, orders.totalFee, orders.Payment_method, order_details.products, orders.status
                                        FROM orders 
                                        JOIN order_details ON orders.ID = order_details.orderID
                                        WHERE orders.Email = '$emailForOrder'
                                        ORDER BY $sortColumn LIMIT $limit OFFSET $offset";

                        $orderViewerQuery=mysqli_query($DB_Connector, $orderViewer);
                        while ($orders=mysqli_fetch_assoc($orderViewerQuery)){ ?>

                        <tr>
                            <td><?php echo $orders['orderDate']?></td>
                            <td><?php echo $orders['ID']?></td>
                            <td><?php echo $orders['products']?></td>
                            <td><?php echo $orders['totalFee']?></td>
                            <td><?php echo $orders['Payment_method']?></td>
                            <td><?php echo $orders['status']?></td>
                            <td><a href="../functions/orderDetails.php?order_id=<?php echo $orders['ID']?>">View</a></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <?php
                    $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM orders WHERE Email='$emailForOrder'";
                    $totalRecordsResult = mysqli_query($DB_Connector, $totalRecordsQuery);
                    $totalRecords = mysqli_fetch_assoc($totalRecordsResult)['totalRecords'];
                    $totalPages = ceil($totalRecords / $limit);

                    if ($totalPages > 1) {
                        echo "<div class='pagination-links'>";

                        if ($page > 1) {
                            echo "<a href='?page=" . ($page - 1) . "&sort-by=$sortColumn'>Previous</a>";
                        }

                        for ($i = 1; $i <= $totalPages; $i++) {
                            if ($i === $page) {
                                echo "<a class='active' href='?page=$i&sort-by=$sortColumn'>$i</a>";
                            } else {
                                echo "<a href='?page=$i&sort-by=$sortColumn'>$i</a>";
                            }
                        }

                        if ($page < $totalPages) {
                            echo "<a href='?page=" . ($page + 1) . "&sort-by=$sortColumn'>Next</a>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main> <!-- Main Area -->
</div>

<?php require_once "../commons/footer.php"; ?>

</body>
