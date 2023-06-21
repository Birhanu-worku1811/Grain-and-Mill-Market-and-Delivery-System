    <?php
    include "DB_connector.php";
    global $DB_Connector;
    //Function to close the window and return
    function closeWindowAndReturn() {
        echo "<script>";
        echo "window.close();";
        echo "window.opener.location.reload();"; // reload the previous window
        echo "window.opener.focus();";
        echo "</script>";
    }

    if (isset($_POST['update_product'])) {
        $New_id = htmlspecialchars(stripslashes(trim($_POST['update_id'])));
        $New_pname = htmlspecialchars(stripslashes(trim($_POST['New_product_name'])));
        $New_price = htmlspecialchars(stripslashes(trim($_POST['New_product_price'])));
        $New_mprice = htmlspecialchars(stripslashes(trim($_POST['New_milling_price'])));
//      $New_quantity = htmlspecialchars(stripslashes(trim($_POST['New_quantity'])));
        $New_category = htmlspecialchars(stripslashes(trim($_POST['New_product_category'])));
        $New_description = htmlspecialchars(stripslashes(trim($_POST['New_product_description'])));
        $New_long_description = htmlspecialchars(stripslashes(trim($_POST['New_plong_description'])));

        $target = "../uploads/product_images/";
        $file_name = $_FILES['New_file']['name'];
        $file_tmp = $_FILES['New_file']['tmp_name'];
        $file_store = $target.$file_name;

        move_uploaded_file($file_tmp, $file_store);

        $update = "UPDATE products set Name='$New_pname', Category='$New_category', Price='$New_price', milling_price='$New_mprice', Picture='$file_store', Description='$New_description', long_description='$New_long_description' WHERE ID='$New_id'";
        $updateQuery = mysqli_query($DB_Connector, $update);
        if ($updateQuery){
//            header("Location: addProduct.php");
            closeWindowAndReturn();
        } else{
            echo "<script> alert('Unable to Update.');</script>".mysqli_error();
        }
    }
    ?>
