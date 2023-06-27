<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Home - Grain Mill Market and Delivery</title>
    <!-- Style Sheet -->
    <link rel="stylesheet" type="text/css" href="CSS/global.css"/>
    <link rel="stylesheet" type="text/css" href="CSS/header.css">
    <link rel="stylesheet" type="text/css" href="CSS/index.css"/>
    <link rel="stylesheet" type="text/css" href="CSS/footer.css"/>
    <link rel="stylesheet" type="text/css" href="CSS/market.css"/>
</head>

<?php
class Index
{
    public $queryExecution;
    public $terminator;
    public $DB_Connector;

    public function __construct()
    {
        $this->DB_Connector = include "commons/DB_connector.php";
    }

    public function includeHeader()
    {
        require_once "commons/Header.php";
    }

    public function includeFooter()
    {
        require_once "commons/footer.php";
    }

    public function display()
    {
        $this->includeHeader();
        $this->displayBody();
        $this->includeFooter();
    }
    public function getProducts(){
         $proSelector = "SELECT * FROM products WHERE Quantity>500 ORDER BY RAND()";
         $this->queryExecution = mysqli_query($this->DB_Connector, $proSelector);
         return $this->queryExecution;
    }

    protected function displayBody()
    {
        ?>
        <div class="container">
            <main>
                <div class="hero">
                    <div class="hero-contents">
                        <video controls align="left" autoplay muted>
                            <source src="images/video.mp4" type="video/mp4"/>
                            <source src="movie.ogg" type="video/ogg"/>
                            Your browser does not support the video tag.
                        </video>
                        <div class="hero-text">
                            <h1>Grain-Mill Market and Delivery</h1>
                            <h2>Get it Milled.</h2>
                            <a href="pages/market.php">Shop Now</a>
                            <a href="pages/register.php">Register</a>
                        </div>
                    </div>
                </div> <!-- hero -->

                <!-- Products -->
                <div id="products">
                    <h2 class="main_title" align="center">Our products</h2>
                    <div class="container">
                        <div class="products-grid">
                            <?php
                            $this->terminator = 1;
                            $profetch = $this->getProducts();
                            while ($proShow = mysqli_fetch_assoc($profetch)){ ?>
                                <div class="products-section">
                                    <a href="functions/productDetails.php?pro_detail_id=<?php echo $proShow['ID'] ?>">
                                        <h3 class="product-name"><?php echo $proShow['Name']?></h3>
                                        <img src="admin/<?php echo $proShow['Picture']?>" alt="<?php echo $proShow['Name']?>"/>
                                        <h4 class="product-name">Price: <?php echo $proShow['Price']?> ETB/Kg</h4>
                                        <h4 class="product-name">Milling Price: <?php echo $proShow['milling_price']?> ETB/Kg</h4>
                                    </a>
                                </div>
                                <?php
                                if ($this->terminator === 6){
                                    break;
                                }
                                $this->terminator++; } ?>
                        </div>
                        <div class="load-more">
                            <br>
                            <a href="pages/market.php">View All</a><br><hr>
                        </div>
                        <style>
                            .products-grid {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 20px;
                            }

                            .products-section {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                            }

                            .product-name {
                                margin-top: 10px;
                                font-size: 14px;
                            }
                        </style>

                    </div>
                </div>

                <!-- features -->
                <div id="features">
                    <h3 class="main_title" align="center">Services</h3>
                    <div class="feature__content">
                        <div class="feature__data">
                            <div>
                                <!--                        <img src="images/beans.jpg" height="220" class="feature__img"/>-->
                                <span class="feature__subtitle">
                                    <h4 class="feature-title">Variety of Grains and Mill Products</h4>
                                    <p class="feature-description">
                                    We offer a wide selection of grains and mill products, from wheat
                                    to barley, oats to rye, and more. Our selection is sure to meet
                                    all your needs for baking, cooking, or brewing.
                                    </p>
                              </span>
                            </div>
                            <div>
                                <span class="feature__rounder"></span>
                                <span class="feature__line"></span>
                            </div>
                        </div>

                        <div class="feature__data">
                            <div></div>
                            <div>
                                <span class="feature__rounder"></span>
                                <span class="feature__line"></span>
                            </div>

                            <div>
                                <!--                        <img-->
                                <!--                                src="images/beans.jpg"-->
                                <!--                                height="220"-->
                                <!--                        />-->
                                <span class="feature__subtitle">
                            <h4 class="feature-title">Automated Delivery System</h4>
                            <p class="feature-description">
                              Our automated delivery system allows customers to order their
                              grains and mill products with ease and convenience. We offer fast
                              and reliable delivery services to ensure that your orders arrive
                              on time.
                            </p>
                          </span>
                            </div>
                        </div>

                        <div class="feature__data">
                            <div>
                                <!--                        <img-->
                                <!--                                src="images/beans.jpg"-->
                                <!--                                height="220"-->
                                <!--                        />-->
                                <span class="feature__subtitle">
                    <h4 class="feature-title">Quality Assurance</h4>
                    <p class="feature-description">
                      All of our grains and mill products are sourced from trusted
                      suppliers who guarantee the highest quality standards in the
                      industry. We take pride in providing our customers with the best
                      products available.
                    </p>
                  </span>
                            </div>

                            <div>
                                <span class="feature__rounder"></span>
                                <span class="feature__line"></span>
                            </div>
                        </div>

                        <div class="feature__data">
                            <div></div>
                            <div>
                                <span class="feature__rounder"></span>
                                <!-- <span class="qualification__line"></span> -->
                            </div>

                            <div>
                                <!--                        <img-->
                                <!--                                src="images/beans.jpg"-->
                                <!--                                height="220"-->
                                <!--                        />-->
                                <span class="feature__subtitle">
                    <h4 class="feature-title">Secure Payment System</h4>
                    <p class="feature-description">
                      We use a secure payment system that ensures your information is
                      kept safe and secure at all times. You can rest assured that your
                      transactions are safe with us.
                    </p>
                  </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- gallery -->
                <div id="gallery">
                    <div class="container">
                        <h3 class="main_title">gallery</h3>
                        <div class="section">
                            <div class="gallery-section" align="center">
                                <img src="images/gallery/1.jpg" alt=""/>
                                <img src="images/gallery/2.jpg" alt=""/>
                                <img src="images/gallery/3.jpg" alt=""/>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- About -->
                <div id="about">
                    <h3 class="main_title">ABOUT US</h3>
                    <div class="container">
                        <div class="about-img">
                            <img src="img/about.png" alt="about" usemap="#image-map"/>
                            <!-- Image Map Generated by http://www.image-map.net/ -->
                            <map name="image-map">
                                <area
                                        target=""
                                        alt="ater"
                                        title="ater"
                                        href="pages/market.php"
                                        coords="293,452,91"
                                        shape="circle"
                                />
                                <area
                                        target=""
                                        alt="misr"
                                        title="misr"
                                        href="functions/productDetails.php?pro-detail-id=?"
                                        coords="476,378,81"
                                        shape="circle"
                                />
                                <area
                                        target=""
                                        alt="bakela"
                                        title="bakela"
                                        href="pages/market.php"
                                        coords="114,341,91"
                                        shape="circle"
                                />
                                <area
                                        target=""
                                        alt="boleke"
                                        title="boleke"
                                        href="pages/market.php"
                                        coords="225,136,113"
                                        shape="circle"
                                />
                                <area
                                        target=""
                                        alt="shimbra"
                                        title="shimbra"
                                        href="functions/productDetails.php?pro-detail-id=?"
                                        coords="409,207,82"
                                        shape="circle"
                                />
                                <area
                                        target="_blank"
                                        alt="kitel"
                                        title="kitel"
                                        href="pages/login.php"
                                        coords="236,325,279,340,318,344,364,344,380,313,348,278,314,256,263,252,212,265"
                                        shape="poly"
                                />
                            </map>
                        </div>

                        <div class="about-section">
                            <p class="about-description">
                                We strive to offer excellent services to our prestigious customers &
                                are known for trusted & superior quality of products. All the
                                products are stored safely in our clean & updated store.
                                Understanding our customer's need & demand, we provide a wide
                                variety of pulses & cereals of high nutritional value. With the
                                professional approach to a traditional niche, we offer prompt &
                                on-time services.
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Team -->
<!--                <div id="team">-->
<!--                    <h3 class="main_title" align="center">Team</h3>-->
<!--                    <div class="team-container">-->
<!--                        --><?php
//                        $Devs = "SELECT * FROM developers";
//                        $DevsQuery = mysqli_query($this->DB_Connector, $Devs);
//                        while ($Developers=mysqli_fetch_assoc($DevsQuery)){
//                            ?>
<!--                            <div class="team-member">-->
<!--                                <img src="--><?php //echo $Developers['Picture']?><!--" width="200"/>-->
<!--                                <p class="title">--><?php //echo $Developers['First_Name'].$Developers['Last_name']?><!--</p>-->
<!--                                <p class="subtitle">--><?php //echo $Developers['Job']?><!--</p>-->
<!--                            </div>-->
<!---->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                </div>-->

            </main> <!-- Main Area -->
        </div>
        <?php
    }
}

$page = new Index();
$page->display();

?>
