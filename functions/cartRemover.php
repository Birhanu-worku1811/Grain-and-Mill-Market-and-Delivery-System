<?php
include "../commons/DB_connector.php";
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
if (isset($_GET['itemName'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['item_name'] == $_GET['itemName']) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            echo "<script>
                        window.location.href='../pages/cart.php';
                        </script>";
        }
    }
}
