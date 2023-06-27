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
            <h3>Category</h3>
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
                                if ($cat['cat_name'] === $_POST['cat_name']) {
                                    return true;
                                }
                            }
                            return false;
                        }
                        if(isset($_POST['add_category'])){
                            if (categoryExists()){
                                echo "<h4 style='color: red;'>Category Already Exists!</h4>";
                            } else {
                                $insert = "INSERT INTO categories (cat_name) VALUES ('".$_POST['cat_name']."')";
                                mysqli_query($DB_Connector, $insert);
                                echo "<h2 style='color: green;'>Category Added Successfully!</h2>";
                            }
                        }
                        ?>
                        <input type="text" name="cat_name" placeholder="Category Name" required/><br><br>
                        <input type="submit" name="add_category" value="Add Category" />
                    </form>
                </div>
                <div class="content-table">
                    <h4>Categories</h4>
                    <table>
                        <thead>
                        <tr>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = mysqli_query($DB_Connector, "SELECT * FROM categories");
                        while ($fetch = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<th>" . $fetch['ID'] . "</th>";
                            echo "<th>" . $fetch['cat_name'] . "</th>";
                            ?>
                            <th>
                                <button id="delete-btn-<?php echo $fetch['ID']; ?>" class="delete-btn" onclick="showConfirmation(<?php echo $fetch['ID']; ?>)">Delete</button>
                                <?php
                                $toBeDeletedCatId = $fetch['ID'];
                                $cate = $fetch['cat_name'];
                                ?>
                                <div id="confirmation-popup-<?php echo $fetch['ID']; ?>" class="confirmation-popup">
                                    <p>Are you sure you want to delete the category '<?php echo $cate; ?>'?</p>
                                    <button class="confirm-btn" onclick="deleteItem(<?php echo $toBeDeletedCatId; ?>)">Yes</button>
                                    <button class="cancel-btn" onclick="hideConfirmation(<?php echo $toBeDeletedCatId; ?>)">No</button>
                                </div>
                            </th>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!--Script for the confirmation pop up-->
<script>
    function showConfirmation(categoryId) {
        // Show the confirmation pop-up
        var confirmationPopup = document.getElementById("confirmation-popup-" + categoryId);
        confirmationPopup.style.display = "block";

        // Position the confirmation popup relative to the button
        var button = document.getElementById("delete-btn-" + categoryId);
        var buttonRect = button.getBoundingClientRect();
        confirmationPopup.style.top = (buttonRect.top + buttonRect.height + 5) + "px";
        confirmationPopup.style.left = (buttonRect.left - 10) + "px";
    }

    function hideConfirmation(categoryId) {
        // Hide the confirmation pop-up
        var confirmationPopup = document.getElementById("confirmation-popup-" + categoryId);
        confirmationPopup.style.display = "none";
    }

    function deleteItem(categoryId) {
        // Perform the delete action
        window.location.href = "../adFunctions/deleteCategory.php?delete_cat_id=" + categoryId;
    }
</script>

</body>
</html>

