<?php
require('../classes/PdoDB.php');
require('../classes/App.php');
// Encryption files
require '../classes/Aes.php';     // AES PHP implementation
require '../classes/AesCtr.php';  // AES Counter Mode implementation
$app = new App();
$app->safesession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Store - <?= $app->app_title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="../static/img/favicon.png" />
    <link href="css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="css/vendor/vendor.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="fonts/icomoon/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../vendors/toaster/toastr.min.css">
</head>

<body class="template-collection has-smround-btns has-loader-bg equal-height has-sm-container">
    <header class="hdr-wrap">
        <div class="hdr">
            <div class="hdr-content">
                <div class="container">
                    <div class="row">
                        <!-- <div class="col-auto show-mobile">
                            <div class="menu-toggle"> <a href="category-listview.html#" class="mobilemenu-toggle"><i
                                        class="icon-menu"></i></a> </div>
                        </div> -->
                        <div class="col-auto hdr-logo">
                            <a href="../home" class="logo">
                                <img src="../static/img/logo/logo.png" alt="Logo"></a>
                        </div>
                        <div class="hdr-nav hide-mobile nav-holder justify-content-center px-4">
                            <ul class="mmenu mmenu-js">
                                <li class="mmenu-item--simple-"><a href="category-listview.html#" class="active">Store</a>

                                </li>

                            </ul>
                        </div>
                        <div class="hdr-links-wrap col-auto ml-auto">
                            <div class="hdr-inline-link">
                                <div class="search_container_desktop">
                                    <div class="dropdn dropdn_search dropdn_fullwidth">
                                        <a href="category-listview.html#" class="dropdn-link  js-dropdn-link only-icon"><i class="icon-search"></i><span class="dropdn-link-txt">Search</span></a>
                                        <div class="dropdn-content">
                                            <div class="container">
                                                <form method="get" action="category-listview.html#" class="search search-off-popular">
                                                    <input name="search" type="text" class="search-input input-empty" placeholder="What are you looking for?">
                                                    <button type="submit" class="search-button"><i class="icon-search"></i></button>
                                                    <a href="category-listview.html#" class="search-close js-dropdn-close"><i class="icon-close-thin"></i></a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdn dropdn_fullheight minicart">
                                    <a href="cart" class="dropdn-link js-dropdn-link minicart-link" data-panel="#dropdnMinicart">
                                        <i class="icon-basket"></i>
                                        <span class="minicart-qty cartItems"><?=isset($_SESSION['cart'])?count($_SESSION['cart']['btnId']):0 ?></span>
                                        <span class="minicart-total hide-mobile cartSumPrice">₦<?=isset($_SESSION['cart'])?number_format(array_sum($_SESSION['cart']['btnPrice'])):0 ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="page-content">
        <div class="holder breadcrumbs-wrap mt-0">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="../home">Home</a></li>
                    <li><span>Store</span></li>
                </ul>
            </div>
        </div>
        <div class="holder">
            <div class="container">
                <span class="text-center text-danger" id="formSpan"></span>
                <?php
                $db_handle = $dbh->prepare("SELECT * FROM pt_products WHERE status=1 ORDER BY id DESC");
                $db_handle->execute();
                $sn = 1;
                if ($db_handle->rowCount() > 0) {
                ?>
                    <div class="page-title text-center">
                        <h1>All Products</h1>
                    </div>
                    <div class="filter-row">
                        <div class="row">
                            <div class="items-count"><?= $db_handle->rowCount() ?> item(s)</div>
                            <div class="viewmode-wrap">
                                <div class="view-mode">
                                    <span class="js-horview d-none d-lg-inline-flex"><i class="icon-grid"></i></span>
                                    <span class="js-gridview"><i class="icon-grid"></i></span>
                                    <span class="js-listview"><i class="icon-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg aside">
                            <div class="prd-grid-wrap">
                                <div class="prd-listview product-listing data-to-show-3 data-to-show-md-3 data-to-show-sm-2 js-category-grid" data-grid-tab-content>
                                    <?php
                                    while ($fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ)) {
                                        $btnId = AesCtr::encrypt($fetch_obj->id, 'aes256', 256);
                                    ?>
                                        <div class="prd prd--style2 prd-labels--max prd-labels-shadow ">
                                            <div class="prd-inside">
                                                <div class="prd-img-area">
                                                    <a href="#product" class="prd-img image-hover-scale image-container">
                                                        <img src="../assets/files/<?= $fetch_obj->img_name ?>" alt="" srcset="">
                                                    </a>
                                                </div>
                                                <div class="prd-info">
                                                    <div class="prd-info-wrap">
                                                        <div class="prd-info-top">
                                                            <div class="prd-rating">
                                                                <?php
                                                                for ($i = 1; $i <= 5; $i++) {
                                                                    # code...
                                                                    if ($fetch_obj->rating >= $i) {
                                                                        echo '<i class="icon-star-fill fill"></i>';
                                                                    } else {
                                                                        echo '<i class="icon-star-fill"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="prd-rating justify-content-center">
                                                            <?php
                                                            for ($i = 1; $i <= 5; $i++) {
                                                                # code...
                                                                if ($fetch_obj->rating >= $i) {
                                                                    echo '<i class="icon-star-fill fill"></i>';
                                                                } else {
                                                                    echo '<i class="icon-star-fill"></i>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <h2 class="prd-title"><a href="#product"><?= $fetch_obj->title ?></a>
                                                        </h2>
                                                        <div class="prd-description">
                                                            <?= $fetch_obj->description ?>
                                                        </div>
                                                        <div class="prd-action">
                                                            <form action="#">
                                                                <!-- Pick Color<input type="color" name="" id=""> -->
                                                                <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
                                                                    To Cart</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="prd-hovers">
                                                        <div class="prd-price">
                                                            <div class="price-new">₦ <?= number_format($fetch_obj->price) ?></div>
                                                        </div>
                                                        <div class="prd-action">
                                                            <div class="prd-action-left">
                                                                <form action="#">
                                                                    <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
                                                                        To Cart</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $sn++;
                                    } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="card-body">
                        <div class="alert alert-info border-0" role="alert"><button type="button" class="btn" data-dismiss="alert" aria-label="Close"><i class="icon-close"></i><span aria-hidden="true"><i class="fa fa-close align-middle font-16"></i></span></button>
                            <center><strong>Opps!</strong> No record found!</center>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <footer class="page-footer footer-style-6 ">
        <div class="holder ">
            <div class="footer-shop-info">
                <div class="container">
                    <div class="text-icn-blocks-bg-row">
                        <div class="text-icn-block-footer">
                            <div class="icn">
                                <i class="icon-trolley"></i>
                            </div>
                            <div class="text">
                                <h4>Extra fast delivery</h4>
                                <p>Your order will be delivered 3-5 business days after all of your items are available
                                </p>
                            </div>
                        </div>
                        <div class="text-icn-block-footer">
                            <div class="icn">
                                <i class="icon-currency"></i>
                            </div>
                            <div class="text">
                                <h4>Best price</h4>
                                <p>We'll match the product prices of key online and local competitors for immediately
                                </p>
                            </div>
                        </div>
                        <div class="text-icn-block-footer">
                            <div class="icn">
                                <i class="icon-diplom"></i>
                            </div>
                            <div class="text">
                                <h4>Guarantee</h4>
                                <p>If the item you want is available, we can process a return and place a new order</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom footer-bottom--bg">
            <div class="container">
                <div class="footer-copyright text-center">
                        <p>©
                            <script>document.write(new Date().getFullYear())</script> <?=$app->app_title?>. Crafted with <i
                                class="icon-heart text-danger"></i> by <a target="_blank"  href="https://nestuge.com">Nestuge</a>
                        </p>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-sticky">
        <!-- <div class="sticky-addcart js-stickyAddToCart closed">
            <div class="container">
                <div class="row">
                    <div class="col-auto sticky-addcart_image">
                        <a href="product.html">
                            <img src="images/skins/fashion/products/product-01-1.webp.html" alt="" />
                        </a>
                    </div>
                    <div class="col col-sm-5 col-lg-4 col-xl-5 sticky-addcart_info">
                        <h1 class="sticky-addcart_title">Leather Pegged Pants</h1>
                        <div class="sticky-addcart_price">
                            <span class="sticky-addcart_price--actual">₦180.00</span>
                            <span class="sticky-addcart_price--old">₦210.00</span>
                        </div>
                    </div>
                    <div class="col-auto sticky-addcart_options  prd-block prd-block_info--style1">
                        <div class="select-wrapper">
                            <select class="form-control form-control--sm">
                                <option value="">--Please choose an option--</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto sticky-addcart_actions">
                        <div class="prd-block_qty">
                            <span class="option-label">Quantity:</span>
                            <div class="qty qty-changer">
                                <button class="decrease"></button>
                                <input type="number" class="qty-input" value="1" data-min="1" data-max="1000">
                                <button class="increase"></button>
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button class="btn js-prd-addtocart" data-fancybox data-src="#modalCheckOut">Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="popup-addedtocart js-popupAddToCart closed" data-close="50000">
            <div class="container">
                <div class="row">
                    <div class="popup-addedtocart-close js-popupAddToCart-close"><i class="icon-close"></i></div>
                    <div class="popup-addedtocart-cart js-open-drop" data-panel="#dropdnMinicart"><i class="icon-basket"></i></div>
                    <div class="col-auto popup-addedtocart_logo">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="images/logo-white-sm.webp" class="lazyload fade-up" alt="">
                    </div>
                    <div class="col popup-addedtocart_info">
                        <div class="row">
                            <a href="cart" class="col-auto popup-addedtocart_image">
                                <span class="image-container w-100">
                                    <img src="images/skins/fashion/products/product-01-1.webp.html" alt="" />
                                </span>
                            </a>
                            <div class="col popup-addedtocart_text">
                                <a href="cart" class="popup-addedtocart_title"></a>
                                <span class="popup-addedtocart_message">Added to <a href="cart" class="underline">Cart</a></span>
                                <span class="popup-addedtocart_error_message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto popup-addedtocart_actions">
                        <span>You can continue</span> <a href="cart" class="btn btn--invert btn--sm"><i class="icon-envelope-1"></i><span>Check out</span></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="sticky-addcart popup-selectoptions js-popupSelectOptions closed" data-close="500000">
            <div class="container">
                <div class="row">
                    <div class="popup-selectoptions-close js-popupSelectOptions-close"><i class="icon-close"></i></div>
                    <div class="col-auto sticky-addcart_image sticky-addcart_image--zoom">
                        <a href="category-listview.html#" data-caption="">
                            <span class="image-container"><img src="category-listview.html#" alt="" /></span>
                        </a>
                    </div>
                    <div class="col col-sm-5 col-lg-4 col-xl-5 sticky-addcart_info">
                        <h1 class="sticky-addcart_title"><a href="category-listview.html#">&nbsp;</a></h1>
                        <div class="sticky-addcart_price">
                            <span class="sticky-addcart_price--actual"></span>
                            <span class="sticky-addcart_price--old"></span>
                        </div>
                        <div class="sticky-addcart_error_message">Error Message</div>
                    </div>
                    <div class="col-auto sticky-addcart_options prd-block prd-block_info--style1">
                        <div class="select-wrapper">
                            <select class="form-control form-control--sm sticky-addcart_options_select">
                                <option value="none">Select Option please..</option>
                            </select>
                            <div class="invalid-feedback">Can't be blank</div>
                        </div>
                    </div>
                    <div class="col-auto sticky-addcart_actions">
                        <div class="prd-block_qty">
                            <span class="option-label">Quantity:</span>
                            <div class="qty qty-changer">
                                <button class="decrease"></button>
                                <input type="number" class="qty-input" value="2" data-min="1" data-max="10000">
                                <button class="increase"></button>
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button class="btn js-prd-addtocart">Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <a class="back-to-top js-back-to-top compensate-for-scrollbar" href="#" title="Scroll To Top">
            <i class="icon icon-angle-up"></i>
        </a>
        <div class="loader-horizontal js-loader-horizontal">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
        </div>
    </div>
    <!-- <div class="footer-sticky">
        <div class="payment-notification-wrap js-pn" data-visible-time="3000" data-hidden-time="3000" data-delay="500" data-from="Aberdeen,Bakersfield,Birmingham,Cambridge,Youngstown" data-products='[{"productname":"Leather Pegged Pants", "productlink":"product.html","productimage":"images/skins/fashion/products/product-01-1.webp"},{"productname":"Black Fabric Backpack", "productlink":"product.html","productimage":"images/skins/fashion/products/product-28-1.webp"},{"productname":"Combined Chunky Sneakers", "productlink":"product.html","productimage":"images/skins/fashion/products/product-23-1.webp"}]'>
            <div class="payment-notification payment-notification--squared">
                <div class="payment-notification-inside">
                    <div class="payment-notification-container">
                        <a href="category-listview.html#" class="payment-notification-image js-pn-link">
                            <img src="https://big-skins.com/frontend/foxic-html-demo/images/products/product-01.webp" class="js-pn-image" alt="">
                        </a>
                        <div class="payment-notification-content-wrapper">
                            <div class="payment-notification-content">
                                <div class="payment-notification-text">Someone purchased</div>
                                <a href="product.html" class="payment-notification-name js-pn-name js-pn-link">Applewatch</a>
                                <div class="payment-notification-bottom">
                                    <div class="payment-notification-when"><span class="js-pn-time">32</span> min ago
                                    </div>
                                    <div class="payment-notification-from">from <span class="js-pn-from">Riverside</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-notification-close"><i class="icon-close-bold"></i></div>
                    <div class="payment-notification-qw prd-hide-mobile js-prd-quickview" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i></div>
                </div>
            </div>
        </div>
    </div> -->
    <script src="js/vendor-special/lazysizes.min.js"></script>
    <script src="js/vendor-special/ls.bgset.min.js"></script>
    <script src="js/vendor-special/ls.aspectratio.min.js"></script>
    <script src="js/vendor-special/jquery.min.js"></script>
    <script src="js/vendor-special/jquery.ez-plus.js"></script>
    <script src="js/vendor/vendor.min.js"></script>
    <script src="../vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../vendors/toaster/toastr.min.js"></script>
    <script src="js/app-html.js"></script>
    <script src="../static/js/functions.js?<?=time()?>"></script>
</body>

</html>