
    <?php
    include "DB_connector.php";
    global $DB_Connector;
    $del_ID = htmlspecialchars(stripslashes(trim($_GET['del_id'])));
    $del = "DELETE FROM products WHERE ID='$del_ID'";
    $deleter = mysqli_query($DB_Connector, $del);

    if ($deleter){
        header("Location: ../adminPages/addProduct.php");
    } else echo "<script>alert('unable to Delete')</script>";
    ?>