<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
include "../adFunctions/DB_connector.php";
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
                <li><a href="orders.php">Order</a></li>
                <li><a href="addProduct.php">Product</a></li>
                <li><a href="addCategory.php">Category</a></li>
                <li><a href="addStock.php">Stock</a></li>
                <li><a class="active" href="users">Users</a></li>
                <li><a href="contacts.php">Contacts</a></li>
                <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Users</h3>
            <div class="content-detail">
                <form method="GET">
                    <label for="sort-by">Sort By:</label>
                    <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                        <option value="ID">ID</option>
                        <option value="username" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'username') echo 'selected'; ?>>Username</option>
                        <option value="First_Name" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'First_Name') echo 'selected'; ?>>First Name</option>
                        <option value="Age" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Age') echo 'selected'; ?>>Age</option>
                    </select>
                </form>
                <table>
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Password(Hashed)</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $limit = 8;
                    $page = $_GET['page'] ?? 1;
                    $offset = ($page - 1) * $limit;
                    $sortColumn = $_GET['sort-by'] ?? 'ID';
                    $retrieve = "SELECT * FROM user_profiles ORDER BY $sortColumn LIMIT $limit OFFSET $offset";
                    $query = mysqli_query($DB_Connector, $retrieve);
                    while ($fetch = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<th>" . $fetch['username'] . "</th>";
                        echo "<th>" . $fetch['First_Name'] . "</th>";
                        echo "<th>" . $fetch['Last_Name'] . "</th>";
                        echo "<th>" . $fetch['Age'] . "</th>";
                        echo "<th>" . $fetch['Email'] . "</th>";
                        echo "<th>" . substr($fetch['Password'], 0, 10) . "...</th>";
                        echo "<th>" . $fetch['phone_Number'] . "</th>";
                        echo "<th>" . $fetch['city'] . "</th>";
                        echo "<th>" . $fetch['address'] . "</th>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <!-- Pagination Links -->
                <?php
                $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM user_profiles";
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