<?php
include "../adFunctions/AdminSession.php";
require_once "../admin_commons/header.php";
include "../adFunctions/DB_connector.php";
class OrdersPage {
    private $DB_Connector;
    private $orderBy;
    private $readOrderQuery;
    private $pageLimit = 8;
    private $totalRows;
    private $totalPages;
    private $current_page;

    public function __construct() {
        $this->DB_Connector = include "../../commons/DB_connector.php";
    }

    private function setOrderBy() {
        $allowedColumns = ['First_Name', 'orderDate', 'status', 'numOfItems', 'totalFee'];

        if (isset($_GET['order-by'])) {
            $orderBy = $_GET['order-by'];

            if (in_array($orderBy, $allowedColumns)) {
                $this->orderBy = $orderBy;
            } else {
                echo "Invalid order-by option";
                exit;
            }
        } else {
            $this->orderBy = 'orderDate';
        }
    }

    private function fetchOrders() {
        $readOrder = "SELECT * FROM orders
                      JOIN order_details ON orders.ID = order_details.orderID
                      ORDER BY $this->orderBy ASC";

        $readOrderQuery = mysqli_query($this->DB_Connector, $readOrder);
        $this->readOrderQuery = $readOrderQuery;

        $this->totalRows = mysqli_num_rows($readOrderQuery);
        $this->totalPages = ceil($this->totalRows / $this->pageLimit);

        $this->current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->current_page = max(1, $this->current_page);
        $this->current_page = min($this->current_page, $this->totalPages);

        $offset = ($this->current_page - 1) * $this->pageLimit;

        $readOrder .= " LIMIT $offset, $this->pageLimit";
        $readOrderQuery = mysqli_query($this->DB_Connector, $readOrder);
        $this->readOrderQuery = $readOrderQuery;
    }

    private function displayPagination() {
        if ($this->totalPages > 1) {
            echo '<div class="pagination">';

            if ($this->current_page > 1) {
                echo '<a href="?page='.($this->current_page - 1).'&order-by='.$this->orderBy.'">&laquo; Previous</a>';
            }

            for ($i = 1; $i <= $this->totalPages; $i++) {
                if ($i == $this->current_page) {
                    echo '<a class="active" href="?page='.$i.'&order-by='.$this->orderBy.'">'.$i.'</a>';
                } else {
                    echo '<a href="?page='.$i.'&order-by='.$this->orderBy.'">'.$i.'</a>';
                }
            }

            if ($this->current_page < $this->totalPages) {
                echo '<a href="?page='.($this->current_page + 1).'&order-by='.$this->orderBy.'">Next &raquo;</a>';
            }

            echo '</div>';
        }
    }

    public function run() {
        $this->setOrderBy();
        $this->fetchOrders();
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
            <link rel="stylesheet" type="text/css" href="../css/astyle.css" />
            <!--    <link rel="stylesheet" type="text/css" href="./css/style.css" />-->
        </head>
        <body>
        <main>
            <div class="main-content">
                <div class="sidebar">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a class="active" href="orders.php">Orders</a></li>
                        <li><a href="addProduct.php">Products</a></li>
                        <li><a href="addCategory.php">Categories</a></li>
                        <li><a href="addStock.php">Stock</a></li>
                        <li><a href="users.php">Users</a></li>
                        <li><a href="contacts.php">Contacts</a></li>
                        <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
                    </ul>
                </div>
                <div class="content">
                    <h3>Orders</h3>
                    <div class="content-detail">
                        <form method="GET">
                            <label for="order-by">Sort By:</label>
                            <select name="order-by" id="order-by" onchange="this.form.submit()">
                                <option value="orderDate" <?php if ($this->orderBy === 'orderDate') echo 'selected'; ?>>Order Date</option>
                                <option value="First_Name" <?php if ($this->orderBy === 'First_Name') echo 'selected'; ?>>Customer Name</option>
                                <option value="status" <?php if ($this->orderBy === 'status') echo 'selected'; ?>>Status</option>
                                <option value="numOfItems" <?php if ($this->orderBy === 'numOfItems') echo 'selected'; ?>>Number of Items</option>
                                <option value="totalFee" <?php if ($this->orderBy === 'totalFee') echo 'selected'; ?>>Total Fee</option>
                            </select>
                        </form>

                        <table>
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order Ref#</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Num of Items</th>
                                <th>Total Fee</th>
                                <th>Payment Mode</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php while ($orders = mysqli_fetch_assoc($this->readOrderQuery)) { ?>
                                <tr>
                                    <td><?php echo $orders['orderDate']; ?></td>
                                    <td><?php echo $orders['ID']; ?></td>
                                    <td><?php echo $orders['First_Name'] . " " . $orders['Last_Name']; ?></td>
                                    <td><?php echo $orders['products']; ?></td>
                                    <td><?php echo $orders['NumOfItems']; ?></td>
                                    <td><?php echo $orders['totalFee']; ?></td>
                                    <td><?php echo $orders['Payment_method']; ?></td>
                                    <td><?php echo $orders['status']; ?></td>
                                    <td>
                                        <a href="../adFunctions/manageOrders.php?order_Id=<?php echo $orders['ID'] ?>">Manage</a><br>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                        <?php $this->displayPagination(); ?>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div class="container">
                <div class="footer-bar">
                    <div class="copyright-text">
                        <p>Proudly made by Section A Software Engineering Students.<br>
                            Copyright &copy;
                            AASTU 2023 - All Rights Reserved.<p>
                    </div>
                </div> <!-- Footer Bar -->
            </div>
        </footer> <!-- Footer Area -->

        </body>
        </html>
        <?php
    }
}

$ordersPage = new OrdersPage();
$ordersPage->run();
?>

<style> 
.pagination {
    margin-top: 10px;
    text-align: center;
}

.pagination a {
    display: inline-block;
    padding: 8px 12px;
    margin-right: 5px;
    background-color: #f2f2f2;
    border: 1px solid #ddd;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
}

.pagination a:hover {
    background-color: #ddd;
}
</style>
