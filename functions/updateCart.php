<?php

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (isset($_POST['updateCart'])){
    $quantities = $_POST['Quantity'];
    $subtotals = $_POST['subTotal'];
    $totalFee = $_POST['totalFee'];
    $_SESSION['totalFee']=$totalFee;

    for ($i=0; $i<count($_SESSION['cart']);$i++) {
        $_SESSION['cart'][$i]['quantity']=$quantities[$i];
        $_SESSION['cart'][$i]['sub_total'] = $subtotals[$i];
    }
    header("Location: ../pages/checkout.php?totalFee=$totalFee");
}