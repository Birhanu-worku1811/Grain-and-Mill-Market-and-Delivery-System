<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
if (empty($_SESSION['admin_email'] and $_SESSION['admin_password'])){
    if (basename(dirname($_SERVER['PHP_SELF']))!=="admin"){
    header('Location: ../adminPages/adminlogin.php');
    } else{
        header('Location: adminPages/adminlogin.php');
    }
}