
<?php
include "DB_connector.php";
global $DB_Connector;
$dele_ID =htmlspecialchars(stripslashes(trim($_GET['delete_cat_id']))) ;
$del = "DELETE FROM categories WHERE ID='$dele_ID'";
$deleter = mysqli_query($DB_Connector, $del);

if ($deleter){
    header("Location: ../adminPages/addCategory.php");
} else echo "<script>alert('unable to Delete')</script>";
?>