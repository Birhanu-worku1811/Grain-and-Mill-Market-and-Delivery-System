<?php
require_once "../admin_commons/header.php";
include "../adFunctions/AdminSession.php";
include "../adFunctions/DB_connector.php";
?>
<!DOCTYPE html>
<html lang="en">
 <?php
if (isset($_POST['add_product'])) {
    $id = htmlspecialchars(stripslashes(trim($_POST['id'])));
    $pname = htmlspecialchars(stripslashes(trim($_POST['product_name'])));
    $price = htmlspecialchars(stripslashes(trim($_POST['product_price'])));
    $mprice = htmlspecialchars(stripslashes(trim($_POST['milling_price'])));
    $quantity = htmlspecialchars(stripslashes(trim($_POST['quantity'])));
    $category = htmlspecialchars(stripslashes(trim($_POST['product_category'])));
    $description = htmlspecialchars(stripslashes(trim($_POST['product_description'])));
    $long_description = htmlspecialchars(stripslashes(trim($_POST['plong_description'])));


    $catID = "SELECT ID FROM categories WHERE cat_name='$category'";
    $catIDQuery = $DB_Connector->execute_query($catID);
    $row = mysqli_fetch_assoc($catIDQuery);
    $cat_Id = $row['ID'];

    $target = "../uploads/product_images/";
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_store = $target.$file_name;

    move_uploaded_file($file_tmp, $file_store);

    $target = "uploads/product_images/";
    $file_store = $target.$file_name;


    $query = "INSERT INTO products (Name, Category, Quantity, Price, milling_price, Picture, Description, long_description, Category_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $DB_Connector->prepare($query);
    $statement->bind_param("ssiddsssi", $pname, $category, $quantity, $price, $mprice, $file_store, $description, $long_description, $cat_Id);
    $statement->execute();

}

mysqli_close($DB_Connector);
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
                <li><a class="active" href="addProduct.php">Products</a></li>
                <li><a href="addCategory.php">Categories</a></li>
                <li><a href="addStock.php">Stock</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="contacts.php">Contacts</a></li>
                <li><a href="../adFunctions/adminLogout.php">Log out</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Product</h3>
            <div class="content-data">

                <div class="content-form">
                    <form action="addProduct.php" method="post" enctype="multipart/form-data">
                        <h4>Add a new Product</h4>
                        <div class="form-inline">

                            <div class="form-group" style="width: 20%;">

                                <label for="id">Id</label>
                                <input type="number" id="id" readonly="readonly" class="form-control border border-warning" name="id"  value="<%=rs.getInt(1) + 1%>">
                            </div>

                            <div class="form-group" style="width: 75%;">
                                <label>Product Name</label>
                                <input type="text" name="product_name" required>
                            </div>
                        </div>
                        <div class="form-inline" >
                            <div class="form-group" style="width: 30%;">
                                <label>Price</label>
                                <input type="number" name="product_price" required>
                            </div>
                            <div class="form-group" style="width: 30%;">
                                <label>Milling Cost</label>
                                <input type="number" name="milling_price" required>
                            </div>
                            <div class="form-group" style="width: 30%;">
                                <label>Quantity (Kg)</label>
                                <input type="number" name="quantity" min="10" required>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <label>Category</label>
                                <select name="product_category">
                                    <?php
                                    include("../adFunctions/DB_connector.php");
                                    $cat = "SELECT * FROM categories";
                                    $result = mysqli_query($DB_Connector, $cat);
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo "<option value=".$row['cat_name'].">".$row['cat_name']."</option>";
                                    }
                                    ?>
                                    <!-- <option>Other</option> -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Images</label>
                                <input type="file" name="file" accept="image/png, image/jpeg, image/jpg" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Short Description</label>
                            <input type="text" name="product_description" required>
                        </div>
                        <div class="form-group">
                            <label>Long Description (Optional)</label>
                            <textarea style="resize:none" name="plong_description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <input type="submit" name="add_product" value="Add Product">
                        </div>

                    </form>
                </div>
                <!-- Showing the products -->
                <div class="content-detail">
                    <h4>All Products</h4>
                    <form method="GET">
                        <label for="sort-by">Sort By:</label>
                        <select name="sort-by" id="sort-by" onchange="this.form.submit()">
                            <option value="ID">ID</option>
                            <option value="Name" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Name') echo 'selected'; ?>>Product Name</option>
                            <option value="Category" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Category') echo 'selected'; ?>>Category</option>
                            <option value="Quantity" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Quantity') echo 'selected'; ?>>Quantity</option>
                            <option value="Price" <?php if (isset($_GET['sort-by']) && $_GET['sort-by'] === 'Price') echo 'selected'; ?>>Price</option>
                        </select>
                    </form>

                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Mill Cost</th>
                            <th>Description</th>
                            <th>Long Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $limit = 6;
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;
                        $sortColumn = isset($_GET['sort-by']) ? $_GET['sort-by'] : 'Name';
                        $retrive = "SELECT * FROM products ORDER BY $sortColumn LIMIT $limit OFFSET $offset";
                        $query = mysqli_query($DB_Connector, $retrive);
                        while ($fetch = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <?php echo "<th>" . $fetch['ID'] . "</th>"; ?>
                                <th>
                                    <a href='#'
                                       onclick="window.open('updateProduct.php?product_ID=<?php echo $fetch['ID'] ?>', '_blank', 'width=500,height=700,top=0,left=150,resizable=yes,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,statusbar=no'); return false;"><?php echo $fetch['Name']; ?></a><br><br>
                                    <button class="delete-btn" onclick="showConfirmation(this)">Delete</button>
                                    <?php $toBeDeletedId = $fetch['ID']; ?>
                                    <div class="confirmation-popup">
                                        <p style="color: blue">Are you sure you want to delete the product <?php echo $fetch['Name'] ?> ? There are <?php echo $fetch['Quantity'] ?>Kgs of it </p>
                                        <button class="confirm-btn" onclick="deleteItem()">Yes</button>
                                        <button class="cancel-btn" onclick="hideConfirmation(this)">No</button>
                                    </div>
                                    <?php
                                    if (intval($fetch['Quantity']) == 0) {
                                        echo "አልቋል";
                                    } else if (intval($fetch['Quantity']) <= 200 && intval($fetch['Quantity']) > 0) {
                                        echo "እያለቀ ነው";
                                    }
                                    ?>
                                </th>
                                <?php
                                echo "<th>" . $fetch['Category'] . "</th>";
                                echo "<th>" . $fetch['Quantity'] . "</th>";
                                echo "<th>" . $fetch['Price'] . "</th>";
                                echo "<th>" . $fetch['milling_price'] . "</th>";
                                echo "<th>" . $fetch['Description'] . "</th>";
                                echo "<th>" . substr($fetch['long_description'], 0, 20) . "...</th>";
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
</main> <!-- Main Area -->
</body>

 <!--Script for the confirmation pop up-->
 <script>
     // window.removeEventListener('click', hideOnClickOutside)
     function showConfirmation(button) {
         document.querySelectorAll('.confirmation-popup').forEach(popup => {
             popup.style.display = 'none';
         })
         var confirmationPopup = button.nextElementSibling;
         confirmationPopup.style.display = "block";


         var buttonRect = button.getBoundingClientRect();
         confirmationPopup.style.top = (buttonRect.top + buttonRect.height + 100) + "px";
         confirmationPopup.style.left = (buttonRect.left - 10) + "px";
         window.addEventListener('click', hideOnClickOutside)
     }

    const confirmationPopup = document.querySelectorAll('.confirmation-popup');


     function hideOnClickOutside(e){

         if(!e.target.closest('.confirmation-popup') && !e.target.closest('.delete-btn')){
             confirmationPopup.forEach(popup => {
                 popup.style.display = 'none';
             })
         }
     }
     function hideConfirmation(button) {

         var confirmationPopup = button.parentNode;
         confirmationPopup.style.display = "none";
     }

     function deleteItem() {
         window.location.href = "../adFunctions/deleteProduct.php?del_id=<?php echo $toBeDeletedId;?>";
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

</html>