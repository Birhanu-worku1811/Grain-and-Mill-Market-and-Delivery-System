<?php require_once "../commons/Header.php";
      include "../commons/DB_connector.php";
?>

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Product - Grain Mill</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/product.css"/>
    <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
</head>
<body>

<div class="container">
    <main>
        <div class="breadcrumb">
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li> /</li>
                <li><a href="../pages/market.php">Shop</a></li>
                <li> /</li>
                <li>Product</li>
            </ul>
        </div> <!-- End of Breadcrumb-->

        <?php
        global $DB_Connector;
        $proID = htmlspecialchars(stripslashes(trim($_GET['pro_detail_id'])));
        $proReader = "SELECT * FROM products WHERE ID='$proID'";
        $detailQuery = mysqli_query($DB_Connector, $proReader);
        $detailFetch = mysqli_fetch_assoc($detailQuery);
        ?>
        <div class="single-product">
            <div class="images-section">
                <div class="larg-img">
                    <img src="../admin/<?php echo $detailFetch['Picture']?>">
                </div>
            </div> <!-- End of Images Section-->

            <div class="product-detail">
                <div class="product-name">
                    <h2><?php echo $detailFetch['Name']?></h2>
                </div>
                <div class="product-price">
                    <h3><?php echo $detailFetch['Price']?> ETB/Kg</h3>
                </div>
                <br><br>
                <div class="product-cart">
                    <form id="cart-form">
                        <div class="form-group">
                            <a href="addToCart.php?cart_id=<?php echo $detailFetch['ID'];?>&cart_name=<?php echo $detailFetch['Name'];?>&cart_price=<?php echo $detailFetch['Price'];?>&cart_photo=<?php echo $detailFetch['Picture']?>" name="add_to_cart" style="background-color: yellow"> Add To Cart</a>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="product-meta">
                    <p><b>Category: </b> <?php echo $detailFetch['Category']?></p>
                    <p><b><?php echo $detailFetch['Description'] ?> </b></p>
                </div>
            </div> <!-- End of Product Detail-->
        </div>
        <hr>
        <div class="product-long-description">
            <h3>Product Description</h3>
            <p>
                <?php echo $detailFetch['long_description'] ?>
            </p>
        </div>
    </main> <!-- Main Area -->
</div>

<?php require_once "../commons/footer.php"; ?>
</body>

