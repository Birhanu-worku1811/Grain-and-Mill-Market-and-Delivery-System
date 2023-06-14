<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
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


<body>

<main>

    <div class="main-content">
        <div class="sidebar">
            <h3>Menu</h3>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="addProduct.php">Products</a></li>
                <li><a class="active" href="addCategory.php">Categories</a></li>
                <li><a href="addStock.php">Stock</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="contacts.php">Contacts</a></li>
                <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Catogory</h3>
            <div class="content-data">
                <div class="content-form">

                    <form action="addCategory.php" method="post">
                        <h4>Add Category</h4>

                        <?php
                        include "../adFunctions/DB_connector.php";
                        global $DB_Connector;
                        function categoryExists(): bool
                        {
                            global $DB_Connector;
                            $read = "SELECT * FROM categories";
                            $catCheck = mysqli_query($DB_Connector, $read);
                            while($cat = mysqli_fetch_assoc($catCheck)){
                                if ($cat['cat_name'] === $_POST['cat_name']){
                                    return (int) $cat['ID'];
                                }
                            }
                            return false;
                        }
                        if (isset($_POST["addCategory"])){
                            $category = htmlspecialchars(stripslashes(trim($_POST["cat_name"])));
                            $categoryId = categoryExists();
                            if ($categoryId !== false){
                                echo "<h2 style='color:red;'>Category already Exists with ID $categoryId</h2>";
                            } else {
                                $add = "INSERT INTO categories (cat_name) VALUES ('$category')";
                                $adder = mysqli_query($DB_Connector, $add);
                                echo "<h3 style='color:green;'>Category ".$category." added successfully!</h3>";
                            }
                        }

//                        mysqli_close($DB_Connector);
                        ?>

                        <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="cat_name" required>
                            </div>
                        <div class="form-group">
                            <label></label>
                            <input type="submit" name="addCategory" value="Add Category">
                        </div>
                    </form>

                </div>
                <div class="content-detail">
                    <h4>All Categories</h4>
                    <form method="GET">
                        <label for="sort-by">Sort By:</label>
                        <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                            <option value="ID" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'ID') echo 'selected'; ?>>ID</option>
                            <option value="cat_name" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'cat_name') echo 'selected'; ?>>Category Name</option>
                        </select>
                    </form>
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $limit = 8;
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;
                        $sortColumn = isset($_GET['sort-by']) ? $_GET['sort-by'] : 'ID';
                        $retrieve = "SELECT * FROM categories ORDER BY $sortColumn LIMIT $limit OFFSET $offset";
                        $query = mysqli_query($DB_Connector, $retrieve);
                        while ($fetch = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<th>" . $fetch['ID'] . "</th>";
                            echo "<th>" . $fetch['cat_name'] . "</th>";
                            ?>
                            <th>
                                <button class="delete-btn" onclick="showConfirmation(this)">Delete</button>
                                <?php
                                $toBeDeletedCatId = $fetch['ID'];
                                $cate = $fetch['cat_name'];
                                $proCounter = "SELECT COUNT(*) as numOfProducts FROM products WHERE Category='$cate'";
                                $proCounterQuery = $DB_Connector->query($proCounter);
                                $numOfItems = mysqli_fetch_assoc($proCounterQuery);
                                ?>
                                <div class="confirmation-popup">
                                    <?php
                                    if (intval($numOfItems['numOfProducts']) === 0) {
                                        ?>
                                        <p>Are you sure you want to delete the category <?php echo $fetch['cat_name'] ?>? There are no items of this category</p>
                                        <?php
                                    } else {
                                        ?>
                                        <p>Are you sure you want to delete the category <?php echo $fetch['cat_name'] ?>? There are <?php echo $numOfItems['numOfProducts'] ?> items of it</p>
                                        <?php
                                    }
                                    ?>
                                    <button class="confirm-btn" onclick="deleteItem()">Yes</button>
                                    <button class="cancel-btn" onclick="hideConfirmation(this)">No</button>
                                </div>
                            </th>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM categories";
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
    </div>

</main> <!-- Main Area -->

<!--Script for the confirmation pop up-->
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
        window.location.href = "../adFunctions/deleteCategory.php?delete_cat_id=<?php echo $toBeDeletedCatId;?>";
    }
</script>

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