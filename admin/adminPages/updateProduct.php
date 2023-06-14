<?php
include "../adFunctions/DB_connector.php";
?>

<style>
    /*Style of the addproduct form*/
    .content-formP {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    h4 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-inlineP {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .form-groupP {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        font-size: 16px;
        margin-top: 5px;
    }

    input[type="file"] {
        margin-top: 10px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #3e8e41;
    }

    /* Responsive styles */
    @media only screen and (max-width: 768px) {
        .form-inline {
            flex-direction: column;
        }

        .form-group {
            width: 100%;
        }
    }

</style>

<?php
global $DB_Connector;
    $updateId = htmlspecialchars(stripslashes(trim($_GET['product_ID'])));
    $update = "SELECT * FROM products where ID='$updateId'";
    $updaterQuery = mysqli_query($DB_Connector, $update);
    $updater = mysqli_fetch_assoc($updaterQuery);

?>

    <!-- Login Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="content-formP">
                <form action="../adFunctions/proUpdateHandler.php" method="post" enctype="multipart/form-data">
                    <h4 class="">Update Product</h4>
                    <div class="form-inlineP">

                        <div class="form-groupP" style="width: 20%;">

                            <label for="id">Id</label>
                            <input type="number" id="id" readonly="readonly" class="form-controlP borderP border-warningP" name="New_id"  value="<?php echo $updater['ID']?>">
                        </div>

                        <div class="form-groupP" style="width: 75%;">
                            <label>Product Name</label>
                            <input type="text" name="New_product_name" value="<?php echo $updater['Name']?>" required>
                        </div>
                    </div>
                    <div class="form-inline" >
                        <div class="form-groupP" style="width: 30%;">
                            <label>Price</label>
                            <input type="number" name="New_product_price" value="<?php echo $updater['Price']?>" required>
                        </div>
                        <div class="form-groupP" style="width: 30%;">
                            <label>Milling Cost</label>
                            <input type="number" name="New_milling_price" value="<?php echo $updater['milling_price']?>" required>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-groupP">
                            <label>Category</label>
                            <select name="New_product_category" >
                                <option><?php echo $updater['Category']?></option>
                                <?php
                                include("../adFunctions/DB_connector.php");
                                global $DB_Connector;
                                $cat = "SELECT * FROM categories";
                                $result = mysqli_query($DB_Connector, $cat);
                                while ($row = mysqli_fetch_assoc($result)){
                                    echo "<option value=".$row['cat_name'].">".$row['cat_name']."</option>";
                                }
                                ?>

                            </select>
                        </div>
                        <div class="form-groupP">
                            <label>Images</label>
                            <input type="file" name="New_file" accept="image/png, image/jpeg, image/jpg"  >
                        </div>
                    </div>
                    <div class="form-groupP">
                        <label>Short Description</label>
                        <input type="text" name="New_product_description" value="<?php echo $updater['Description']?>" required>
                    </div>
                    <div class="form-groupP">
                        <label>Long Description (Optional)</label>
                        <textarea style="resize:none" name="New_plong_description" rows="3"><?php echo $updater['long_description']?></textarea>
                    </div>
                    <input type="hidden" value="<?php echo $updater['ID'];?>" name="update_id">
                    <div class="form-groupP">
                        <label></label>
                        <input type="submit" name="update_product" value="UPDATE">
                    </div>
                </form>
            </div>
        </div>
    </div>
