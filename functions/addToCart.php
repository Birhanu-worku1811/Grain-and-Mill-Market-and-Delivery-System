<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
if (isset($_SESSION['cart'])){
    $cartChecker = array_column($_SESSION['cart'],'item_name');
    //Checking if the item already exists in cart
    if (in_array($_GET['cart_name'], $cartChecker)){
        echo "
        <script> alert('product already exists in cart');
                window.location.href='../pages/market.php';
        </script>
        ";
    } else {
        $count = count($_SESSION['cart']);
        $_SESSION['cart'][$count] = array('item_photo'=>$_GET['cart_photo'], 'item_id' => $_GET['cart_id'], 'item_name' => $_GET['cart_name'], 'item_price' => $_GET['cart_price'], 'quantity'=>20, 'sub_total'=>0);
        echo "<script>
            window.location.href='../pages/market.php';
            </script>";
    }
} else {
    $_SESSION['cart'][0]=array('item_photo'=>$_GET['cart_photo'], 'item_id'=>$_GET['cart_id'], 'item_name'=>$_GET['cart_name'], 'item_price'=>$_GET['cart_price'], 'quantity'=>20, 'sub_total'=>0);
    echo "
    <script> 
    window.location.href='../pages/market.php';
    </script>
    ";
}
?>