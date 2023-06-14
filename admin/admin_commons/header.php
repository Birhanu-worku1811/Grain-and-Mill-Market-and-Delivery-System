<!doctype html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>GM - Admin</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../css/astyle.css" />
</head>
<body>

<header>
    <div class="container">
        <div class="brand">
            <div class="logo">
                <a href="index">
                    <img src="../img/icons/online_shopping.png">
                    <div class="logo-text">
                        <p class="big-logo">Grain Mill</p>
                        <p class="small-logo">Market&Delivery</p>
                    </div>
                </a>
            </div> <!-- logo -->
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
            if (!empty($_SESSION['admin_email']&&$_SESSION['admin_password'])){?>
                <div class="shop-icon">
                    <div class="dropdown">
                        <?php
                        if (basename(dirname($_SERVER['PHP_SELF']))==="admin"){ ?>
                            <img src="img/icons/account.png">
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="#">My Account</a></li>
                                <li><a href="adFunctions/adminLogout.php">Logout</a></li>
                            </ul>
                        </div>
                      <?php } else if (basename(dirname($_SERVER['PHP_SELF']))!=="admin"){ ?>
                            <img src="../img/icons/account.png">
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="#">My Account</a></li>
                                <li><a href="../adFunctions/adminLogout.php">Logout</a></li>
                            </ul>
                        </div>
                       <?php }  ?>

                    </div>
                </div> <!-- shop icons -->
         <?php   }
            ?>

        </div> <!-- brand -->
    </div> <!-- container -->
</header> <!-- header -->
</body>

</html>
