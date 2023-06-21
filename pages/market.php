<?php
require_once "../commons/Header.php";

class MarketPage
{
    private $DB_Connector;
    private $itemsPerPage;
    private $currentPage;
    private $totalItems;
    private $totalPages;

    public function __construct()
    {
        $this->DB_Connector = include "../commons/DB_connector.php";
        $this->itemsPerPage = 6;
        $this->currentPage = $_GET['page'] ?? 1;
    }

    public function  display()
    {
        $this->setPaginationValues();
        $this-> displayHead();
        $this-> displayBody();
        $this-> displayFooter();
    }

    private function setPaginationValues()
    {
        $this->totalItems = mysqli_num_rows(mysqli_query($this->DB_Connector, "SELECT * FROM products"));
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }

        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
    }

    private function  displayHead()
    {
        echo '
        <head>
            <!-- Meta Tags -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Title -->
            <title>Grain Mill</title>
            <!-- Style Sheet -->
            <link rel="stylesheet" type="text/css" href="../CSS/global.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/header.css">
            <link rel="stylesheet" type="text/css" href="../CSS/market.css"/>
            <link rel="stylesheet" type="text/css" href="../CSS/footer.css"/>
        </head>';
    }

    private function  displayBody()
    {
        echo '
        <body>
            <div class="container">
                <main>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="../index.php">Home</a></li>
                            <li> /</li>
                            <li>Market</li>
                        </ul>
                    </div> <!-- End of Breadcrumb-->
        
                    <div class="new-product-section shop">
                        <div class="sidebar">
                            <br>
                            <div class="sidebar-widget">
                                <h3>Categories</h3>
                                <ul>
                                    <li><a href="market.php">All</a></li>';
        $this-> displayCategories();
        echo '
                                </ul>
                            </div>
                        </div>
                        <div class="product-content">';
        $this-> displayProducts();
        echo '
                        </div>
                    </div> <!-- New Product Section -->
                    <div class="pagination" style="float: left">';
        $this-> displayPagination();
        echo '
                    </div>
                </main> <!-- Main Area -->
            </div>
        </body>';
    }

    private function  displayCategories()
    {
        $catSelector = "SELECT * FROM categories";
        $catQuery = mysqli_query($this->DB_Connector, $catSelector);
        while ($categories = mysqli_fetch_assoc($catQuery)) {
            echo '<li><a href="market.php?category=' . $categories['cat_name'] . '">' . $categories['cat_name'] . '</a></li>';
        }
    }

    private function  displayProducts()
    {
        $proSelector = "SELECT * FROM products LIMIT {$this->itemsPerPage} OFFSET {$this->getOffset()}";
        if (isset($_GET['category'])) {
            $category = htmlspecialchars(stripslashes(trim($_GET['category'])));
            $proSelector = "SELECT * FROM products WHERE Category='$category' LIMIT {$this->itemsPerPage} OFFSET {$this->getOffset()}";
            if (isset($_GET['search'])) {
                $search = htmlspecialchars(stripslashes(trim($_GET['search'])));
                $proSelector = "SELECT * FROM products WHERE Category='$category' AND Name LIKE '%$search%' LIMIT {$this->itemsPerPage} OFFSET {$this->getOffset()}";
            }
        }
        if (isset($_GET['search'])) {
            $search = htmlspecialchars(stripslashes(trim($_GET['search'])));
            $proSelector = "SELECT * FROM products WHERE Name LIKE '%$search%' OR Category LIKE '%$search%' LIMIT {$this->itemsPerPage} OFFSET {$this->getOffset()}";
        }
        $Query = mysqli_query($this->DB_Connector, $proSelector);

        $counter = 0;

        while ($proShow = mysqli_fetch_assoc($Query)) {
            $counter++;
            echo '
            <div class="product">
                <a href="../functions/productDetails.php?pro_detail_id=' . $proShow['ID'] . '">
                    <img src="../admin/' . $proShow['Picture'] . '" alt="' . $proShow['Name'] . '"/>
                </a>
                <div class="product-detail">
                    <a href="../functions/productDetails.php?pro_detail_id=' . $proShow['ID'] . '"><h3>' . $proShow['Name'] . '</h3></a>
                    <h2>' . $proShow['Category'] . '</h2>
                    <h6>' . $proShow['Price'] . ' ETB/Kg</h6><br>
                    <a name="add_to_cart" onclick="location.href=\'../functions/addToCart.php?cart_id=' . $proShow['ID'] . '&cart_name=' . $proShow['Name'] . '&cart_price=' . $proShow['Price'] . '&cart_photo=' . $proShow['Picture'] . '\'">Add to Cart</a>
                </div>
            </div>';
        }

        if (isset($_GET['category']) && $counter === 0) {
            echo '<h1 style="color: red">NO products found for this category!</h1>';
        } elseif (isset($_GET['search']) && $counter === 0) {
            echo '<h1 style="color: red">NO results for the keyword ' . htmlspecialchars(stripslashes(trim($_GET['search']))) . '!</h1>';
        } elseif ($counter === 0) {
            echo '<h1 style="color: red">Sorry,  No products found!</h1>';
        }
    }

    private function getOffset()
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    private function  displayPagination()
    {
        $prevPage = $this->currentPage - 1;
        $nextPage = $this->currentPage + 1;

        if ($this->currentPage > 1) {
            echo '<a href="?page=' . $prevPage . '">Previous</a>';
        }

        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->currentPage) {
                echo '<span>' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($this->currentPage < $this->totalPages) {
            echo '<a href="?page=' . $nextPage . '">Next</a>';
        }
    }

    private function  displayFooter()
    {
        require_once "../commons/footer.php";
    }
}

$page = new MarketPage();
$page-> display();
?>
