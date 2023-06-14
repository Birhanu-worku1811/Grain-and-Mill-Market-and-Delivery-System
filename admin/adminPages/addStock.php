<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
include "../adFunctions/DB_connector.php";
?>

<!--Update of the stock Quantity-->
<?php
global $DB_Connector;
if (isset($_POST['addStock'])){
    $stockName = htmlspecialchars(stripslashes(trim($_POST['stockName'])));
    $retrive = "SELECT * FROM products where Name ='$stockName'";
    $retriveQuery = mysqli_query($DB_Connector, $retrive);
    $stockRead = mysqli_fetch_assoc($retriveQuery);
    $currentQuantity = floatval($stockRead['Quantity']) + floatval(htmlspecialchars(stripslashes(trim($_POST['addedQuantity']))));
    $update = "UPDATE products set Quantity='$currentQuantity' WHERE Name='$stockName'";
    $updateQuery = mysqli_query($DB_Connector, $update);
    $currentQuantity = 0;
}
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
            <li><a href="orders.php">Orders</a></li>
            <li><a href="addProduct.php">Products</a></li>
            <li><a href="addCategory.php">Categories</a></li>
            <li><a class="active" href="addStock.php">Stock</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="contacts.php">Contacts</a></li>
            <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
        </ul>
    </div>
    <div class="content">
        <h3>Stock</h3>
        <div class="content-data">
            <div class="content-form">
                <form action="addStock.php" method="post">
                    <h4>Add Stock</h4>
                    <div class="form-inline">
                        <div class="form-group">

                            <label>Product Name</label>
                            <select name="stockName">
                                <?php
                                global $DB_Connector;
                                include("../adFunctions/DB_connector.php");
                                if (isset($_GET['stockID'])){
                                    $id = htmlspecialchars(stripslashes(trim($_GET['stockID'])));
                                    $cat="SELECT Name FROM products WHERE ID='$id'";
                                } else {
                                    $cat = "SELECT Name FROM products";
                                }
                                $result = mysqli_query($DB_Connector, $cat);
                                while ($row = mysqli_fetch_assoc($result)){
                                    echo "<option value=".$row['Name'].">".$row['Name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="addedQuantity" min="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label></label>
                        <input type="submit" name="addStock" value="Add Stock">
                    </div>
                </form>
            </div>
            <div class="content-detail">
                <h4>All Stock Detail</h4>
                <form method="GET">
                    <label for="sort-by">Sort By:</label>
                    <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                        <option value="ID">ID</option>
                        <option value="Name" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Name') echo 'selected'; ?>>Product Name</option>
                        <option value="Category" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Category') echo 'selected'; ?>>Category</option>
                        <option value="Quantity" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Quantity') echo 'selected'; ?>>Stock Amount</option>
                    </select>
                </form>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Available Stock</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $limit = 8;
                    $page = $_GET['page'] ?? 1;
                    $offset = ($page - 1) * $limit;
                    $sortColumn = $_GET['sort-by'] ?? 'ID';
                    $retrieve = "SELECT * FROM products ORDER BY $sortColumn LIMIT $limit OFFSET $offset";
                    $query = mysqli_query($DB_Connector, $retrieve);
                    while ($fetch = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<th>" . $fetch['ID'] . "</th>";
                        echo "<th>" . $fetch['Name'] . "</th>";
                        echo "<th>" . $fetch['Category'] . "</th>";
                        echo "<th>" . $fetch['Quantity'] . "</th>";
                        echo "<th>";
                        if ((int)$fetch['Quantity'] === 0) {
                            echo "አልቋል";
                        } else if ((int)$fetch['Quantity'] <= 200 && (int)$fetch['Quantity'] > 0) {
                            echo "አልቋል በለው";
                        } else if ((int)$fetch['Quantity'] >= 200 && (int)$fetch['Quantity'] <= 500) {
                            echo "እያለቀ ነው Admin";
                        } else if ((int)$fetch['Quantity'] > 500) {
                            echo "Enough";
                        }
                        echo "</th>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>

                <?php
                $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM products";
                $totalRecordsResult = mysqli_query($DB_Connector, $totalRecordsQuery);
                $totalRecords = mysqli_fetch_assoc($totalRecordsResult)['totalRecords'];
                $totalPages = ceil($totalRecords / $limit);

                if ($totalPages > 1) {
                    echo "<div class='pagination-links'>";

                    if ($page > 1) {
                        echo "<a href='?page=" . ($page - 1) . "&sort-by=$sortColumn'>Previous</a>";
                    }

                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo "<a class='active' href='?page=$i&sort-by=$sortColumn'>$i</a></li>";
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