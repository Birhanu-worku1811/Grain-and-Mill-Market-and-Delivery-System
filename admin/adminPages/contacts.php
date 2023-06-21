<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
include "../adFunctions/DB_connector.php";
?>

<style>
    .button-link {
        display: inline-block;
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .button-link:hover {
        background-color: darkblue;
    }

</style>

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
                <li><a href="orders.php">Order</a></li>
                <li><a href="addProduct.php">Product</a></li>
                <li><a href="addCategory.php">Category</a></li>
                <li><a href="addStock.php">Stock</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a class="active" href="users.php">Contacts</a></li>
                <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Contacts</h3>
            <div class="content-detail">
                <form method="GET">
                    <label for="sort-by">Sort By:</label>
                    <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                        <option value="ID">ID</option>
                        <option value="creation_time" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'creation_time') echo 'selected'; ?>>Date</option>
                        <option value="First_Name" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'First_Name') echo 'selected'; ?>>First Name</option>
                        <option value="read_status" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'read_status') echo 'selected'; ?>>read/Unread</option>
                    </select>
                </form>
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>View</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $limit = 8;
                    $page = $_GET['page'] ?? 1;
                    $offset = ($page - 1) * $limit;
                    $sortColumn = $_GET['sort-by'] ?? 'ID';
                    $retrieve = "SELECT * FROM contact ORDER BY $sortColumn LIMIT $limit OFFSET $offset";
                    $query = mysqli_query($DB_Connector, $retrieve);
                    while ($fetch = mysqli_fetch_assoc($query)) {
                        echo "<tr>";

                        // Check the read status and apply different font colors
                        if ($fetch['read_status'] == 0) {
                            // Unread email style
                            echo "<th style='color: #000000;'>".$fetch['creation_time']."</th>";
                            echo "<th style='color: #000000;'>".$fetch['First_Name']."</th>";
                            echo "<th style='color: #000000;'>".$fetch['email']."</th>";
                            echo "<th style='color: #000000;'>".substr($fetch['message'], 0, 20)."..."."</th>";
                        } else {
                            // Read email style
                            echo "<th style='color: #808080;'>".$fetch['creation_time']."</th>";
                            echo "<th style='color: #808080;'>".$fetch['First_Name']."</th>";
                            echo "<th style='color: #808080;'>".$fetch['email']."</th>";
                            echo "<th style='color: #808080;'>".substr($fetch['message'], 0, 20)."..."."</th>";
                        }

                        echo "<th><a href='contactDetail.php?contactID=".$fetch['ID']."' class='button-link'>View</a></th>";
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
                <?php
                $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM contact";
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