<?php

require_once "../commons/Header.php";
?>

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>My Cart - Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/cart.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>

</head>

<?php

class ShoppingCart {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    public function displayCart() {
        ?>
        <div class="container">
            <main>
                <div class="breadcrumb">
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li> /</li>
                        <li><a href="market.php">Market</a></li>
                        <li> /</li>
                        <li>Cart</li>
                    </ul>
                </div> <!-- End of Breadcrumb-->

                <h2>Shopping Cart</h2>
                <?php
                if (empty($_SESSION['cart'])) {
                    echo "<h1 style='color: red'>No items in cart</h1>";
                }
                ?>
                <form action="../functions/updateCart.php" method="post">
                    <div class="cart-page">
                        <div class="cart-items">
                            <table>
                                <tbody>
                                <?php
                                if (isset($_SESSION['cart'])) {
                                    echo "<thead>
                                            <tr>
                                                <th colspan='3'>Cart Items</th>
                                            </tr>
                                          </thead>";
                                    $productsInCart = 0;
                                    $totalFee = 0;
                                    foreach ($_SESSION['cart'] as $value) {
                                        $productsInCart++;
                                        ?>
                                        <tr>
                                            <td style="width: 20%;"><img src="../admin/<?php echo $value['item_photo'] ?>"></td>
                                            <td style="width: 60%;">
                                                <h2><?php echo $value['item_name']; ?></h2>
                                                <br>
                                                <h3>Price: <?php echo $value['item_price'] ?></h3>
                                                <br>
                                                <!-- Form for Cart Remover -->
                                                <input type="hidden" value="<?php echo $value['item_name'] ?>" name="itemName">
                                                <a href="../functions/cartRemover.php?itemName=<?php echo $value['item_name'] ?>"
                                                   style="color: yellow" name="remove">Remove</a>
                                            </td>
                                            <td class="qty" style="width: 15%;">
                                                <input type="number" name="Quantity[]" onchange=""
                                                       class="cartNumber quantity" value="<?php echo $value['quantity'] ?>"
                                                       min="20" max="500" step="10">Kgs
                                                <br><br>
                                                <label>Total for <span
                                                            style="color: green"><?php echo $value['item_name']; ?></span><br><span
                                                            style="color: #0d6aad"> ETB</span></label>
                                                <input type="number" name="subTotal[]" class="qty subtotal"
                                                       data-price="<?php echo $value['item_price'] ?>"
                                                       value="<?php echo $value['quantity'] * $value['item_price'] ?>"
                                                       readonly="readonly">
                                            </td>
                                        </tr>
                                        <?php
                                        $totalFee += ($value['quantity'] * $value['item_price']);
                                    }
                                    $_SESSION['productsInCart'] = $productsInCart;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        if (!empty($productsInCart)){
                        ?>
                        <div class="cart-summary">
                            <div class="checkout-total">
                                <h3>Cart Summary</h3>
                                <ul>
                                    <li>Number of Products= x<?php
                                        echo $_SESSION['productsInCart']; ?></li>
                                    <hr>
                                    <li>Total Fee(ETB) <input name="totalFee" style="float: right;" id="totalFee"
                                                         value="<?php global $totalFee;
                                                         echo $totalFee; ?>" readonly> </li>
                                    <li><input type="submit" name="updateCart" value="Go to Checkout"
                                               style="background-color: #4CAF50; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer;">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </form>
            </main> <!-- Main Area -->
        </div>
        <?php
    }
}

$shoppingCart = new ShoppingCart();
$shoppingCart->displayCart();

require_once "../commons/footer.php";
?>

<script>
    const cartNumbers = document.querySelectorAll('.quantity');
    const itemTotal = document.querySelectorAll('.subtotal');
    const totalFee = document.querySelector('#totalFee');

    function calcTotal() {
        let total = 0;
        itemTotal.forEach((item) => {
            total += +item.value;
        });
        totalFee.value = total;
    }

    // Call calcTotal to initialize the totalFee value
    calcTotal();

    cartNumbers.forEach(cart => {
        cart.addEventListener('change', (e) => {
            const value = e.target.value;
            const nextElement = e.target.parentElement.querySelector('.subtotal');
            nextElement.value = nextElement.dataset.price * value;

            calcTotal();
        });
    });
</script>

