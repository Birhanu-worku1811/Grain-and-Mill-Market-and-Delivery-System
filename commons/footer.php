

<footer>
    <div class="container">
        <div class="footer-widget">
            <div class="widget">
                <div class="widget-heading">
                    <h3>Important Link</h3>
                </div>
                <div class="widget-content">
                    <ul>
                        <?php
                        if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                            <li><a href="pages/about.php">About</a></li>
                            <li><a href="pages/contact.php">Contact</a></li>
                            <li><a href="pages/refund.php">Refund Policy</a></li>
                            <li><a href="pages/terms.php">Terms & Conditions</a></li>
                       <?php }
                        else { ?>
                            <li><a href="../pages/about.php">About</a></li>
                            <li><a href="../pages/contact.php">Contact</a></li>
                            <li><a href="../pages/refund.php">Refund Policy</a></li>
                            <li><a href="../pages/terms.php">Terms & Conditions</a></li>
                       <?php }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="widget">
                <div class="widget-heading">
                    <h3>Information</h3>
                </div>
                <div class="widget-content">
                    <ul>
                        <?php
                        if (basename(dirname($_SERVER['PHP_SELF']))!=="GM-IP-main"){ ?>
                        <li><a href="../pages/account.php">My Account</a></li>
                        <li><a href="../pages/orders.php">My Orders</a></li>
                        <li><a href="../pages/cart.php">Cart</a></li>
                        <li><a href="../pages/checkout.php">Checkout</a></li>
                        <?php }
                        else { ?>
                            <li><a href="pages/account.php">My Account</a></li>
                        <li><a href="pages/orders.php">My Orders</a></li>
                        <li><a href="pages/cart.php">Cart</a></li>
                        <li><a href="pages/checkout.php">Checkout</a></li>
                     <?php   }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="widget">
                <div class="widget-heading">
                    <h3>Follow us</h3>
                </div>
                <div class="widget-content">
                    <div class="follow">
                        <ul>
                            <?php
                        if (basename(dirname($_SERVER['PHP_SELF']))==="GM-IP-main"){ ?>
                            <li><a href="#"><img src="img/icons/facebook.png"></a></li>
                            <li><a href="#"><img src="img/icons/twitter.png"></a></li>
                            <li><a href="#"><img src="img/icons/instagram.png"></a></li>
                       <?php }else{ ?>
                            <li><a href="#"><img src="../img/icons/facebook.png"></a></li>
                            <li><a href="#"><img src="../img/icons/twitter.png"></a></li>
                            <li><a href="#"><img src="../img/icons/instagram.png"></a></li>
                       <?php } ?>

                        </ul>
                    </div>
                </div>
                <div class="widget-heading">
                    <h3>Subscribe for Newsletter</h3>
                </div>
                <div class="widget-content">
                    <div class="subscribe">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subscribe" placeholder="Email">
                                <img src="../img/icons/paper_plane.png">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- Footer Widget -->
        <div class="footer-bar">
            <div class="copyright-text">
                <p>Proudly made by Section A Software Engineering Students.<br>
                    Copyright &copy; AASTU 2023 All Rights Reserved.</p>
            </div>
        </div> <!-- Footer Bar -->
    </div>
</footer> <!-- Footer Area -->
