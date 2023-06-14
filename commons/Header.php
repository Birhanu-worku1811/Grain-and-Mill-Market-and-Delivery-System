<style>
    .profile-picture {
        width: 50px; /* Set the width of the profile picture container */
        height: 50px; /* Set the height of the profile picture container */
        overflow: hidden; /* Hide any part of the image that exceeds the container size */
        border-radius: 50%; /* Make the profile picture round */
        margin-right: 10px; /* Add some space to the right of the profile picture */
    }

    .profile-picture img {
        max-width: 100%; /* Set the maximum width of the image to the container width */
        max-height: 100%; /* Set the maximum height of the image to the container height */
        object-fit: cover; /* Scale the image to cover the entire container */
    }
    .dropdown-menu button {
        background: none;
        border: none;
        padding: 0;
        /* Add the following styles to remove borders */
        border-style: none;
        outline: none;
        background-color: rgba(30, 30, 30, 0.5);
    }
</style>
<header>
    <div class="container">
        <div class="brand">
            <div class="logo">
                <?php
                if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                    <a href="index.php">
                        <img src="img/icons/gm-logo.png">
                        <div class="logo-text">
                            <p class="big-logo">GrainMill</p>
                            <p class="small-logo">Market&Delivery</p>
                        </div>
                    </a>
                    <?php }
                else if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                    <a href="../index.php">
                        <img src="../img/icons/gm-logo.png">
                        <div class="logo-text">
                            <p class="big-logo">GrainMill</p>
                            <p class="small-logo">Market&Delivery</p>
                        </div>
                    </a>
              <?php  }
                ?>
            </div> <!-- logo -->
            <div class="shop-icon">
                <div class="dropdown">
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        @session_start();
                    }
                    if (isset($_SESSION['logged_in'])&& $_SESSION['logged_in']){
                    ?>
                    <div class="profile-picture">
                        <?php
                        if (!empty($_SESSION['user_details']['Profile_picture'])){
                            if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                                <img src="<?php echo $_SESSION['user_details']['Profile_picture'];?>" alt="Profile Picture">
                            <?php }
                            else if(basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                                <img src="../<?php echo $_SESSION['user_details']['Profile_picture'];?>" alt="Profile Picture">
                           <?php }
                            ?>
                        <?php }
                        if (empty($_SESSION['user_details']['Profile_picture'])){
                            if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                                <img src="img/icons/account.png" alt="Profile Picture">
                           <?php }
                            else if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                                <img src="../img/icons/account.png" alt="Profile Picture">
                           <?php }
                            ?>

                        <?php
                        }
                        ?>
                    </div>
                    <div class="dropdown-menu wishlist-item">
                        <?php

                        echo "<h1 style='color: blue'>".$_SESSION['user_details']['username']."</h1>";
                        echo "<h1 style='color: green'>".$_SESSION['user_details']['Email']."</h1>";
                        ?>
                        <ul>
                            <?php
                            if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                                <li><a href="pages/account.php">My Account</a></li>
                                <li><a href="pages/orders.php">My Orders</a></li>
                                <li><a href="functions/userLogout.php">Log Out</a></li>
                                <?php }
                            else if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                                <li><a href="../pages/account.php">My Account</a></li>
                                <li><a href="../pages/orders.php">My Orders</a></li>
                                <li><a href="../functions/userLogout.php">Log Out</a></li>
                    <?php }
                            }          ?>
                        </ul>
                    </div>
                </div>
                <div class="dropdown">
                    <?php
                    if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                        <img src="img/icons/shopping_cart.png">
                    <div class="dropdown-menu">
                       <button style="color: green"> <a href="pages/cart.php"> Go To Cart</a></button>
                    </div>
                   <?php }
                    else if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                        <img src="../img/icons/shopping_cart.png">
                    <div class="dropdown-menu">
                       <button style="color: green"> <a href="../pages/cart.php"> Go To Cart</a></button>
                    </div>
                  <?php  }
                    ?>

                </div>
            </div> <!-- shop icons -->
        </div> <!-- brand -->

        <div class="menu-bar">
            <div class="menu">
                <ul>
                    <?php
                    if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                       <li><a href="index.php">HOME</a></li>
                    <li><a href="pages/market.php">Market</a></li>
                    <li><a href="pages/register.php">Register</a></li>
                    <?php
                    if (!isset($_SESSION['logged_in'])){
                    ?>
                    <li><a href="pages/login.php">SIGN IN</a></li>
                    <?php } ?>
                   <?php }
                    else if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                        <li><a href="../index.php">HOME</a></li>
                        <li><a href="../pages/market.php">Market</a></li>
                        <li><a href="../pages/register.php">Register</a></li>
                        <?php
                        if (!isset($_SESSION['logged_in'])){
                            ?>
                            <li><a href="../pages/login.php">SIGN IN</a></li>
                        <?php } ?>
                        <?php
                        }
                    ?>

                </ul>
            </div>
            <div class="search-bar">
                <form action="<?php if (basename(dirname($_SERVER['PHP_SELF'])) === "GM-IP-main") { ?>pages/market.php<?php }
                                else { ?> market.php<?php } ?>" method="get">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" id="searchInput" placeholder="Search products">
                        <button type="submit" name="submit" style="background: none; border: none; padding: 0;">
                            <?php if (basename(dirname($_SERVER['PHP_SELF'])) === "GM-IP-main") { ?>
                                <img src="img/icons/search.png" id="searchIcon" alt="Search">
                            <?php } else { ?>
                                <img src="../img/icons/search.png" id="searchIcon" alt="Search">
                            <?php } ?>
                        </button>
                    </div>
                </form>
            </div>
        </div> <!-- menu -->
    </div> <!-- container -->
</header> <!-- header -->

