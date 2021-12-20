<?php
require('../classes/PdoDB.php');
require('../classes/App.php');
$app = new App();
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
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body class="template-collection has-smround-btns has-loader-bg equal-height has-sm-container">
    <header class="hdr-wrap">
        <div class="hdr">
            <div class="hdr-content">
                <div class="container">
                    <div class="row">
                        <!-- <div class="col-auto show-mobile">
                            <div class="menu-toggle"> <a href="#" class="mobilemenu-toggle"><i
                                        class="icon-menu"></i></a> </div>
                        </div> -->
                        <div class="col-auto hdr-logo">
                            <a href="../home" class="logo">
                                <img src="../static/img/logo/logo.png"
                                    alt="Logo"></a>
                        </div>
                        <div class="hdr-nav hide-mobile nav-holder justify-content-center px-4">
                            <ul class="mmenu mmenu-js">
                                <li class="mmenu-item--simple-"><a href="#" class="active">Cart</a>
                                    
                                </li>
                                
                            </ul>
                        </div>
                        <div class="hdr-links-wrap col-auto ml-auto">
                            <div class="hdr-inline-link">
                                <div class="search_container_desktop">
                                    <div class="dropdn dropdn_search dropdn_fullwidth">
                                        <a href="#"
                                            class="dropdn-link  js-dropdn-link only-icon"><i
                                                class="icon-search"></i><span class="dropdn-link-txt">Search</span></a>
                                        <div class="dropdn-content">
                                            <div class="container">
                                                <form method="get" action="#"
                                                    class="search search-off-popular">
                                                    <input name="search" type="text" class="search-input input-empty"
                                                        placeholder="What are you looking for?">
                                                    <button type="submit" class="search-button"><i
                                                            class="icon-search"></i></button>
                                                    <a href="#"
                                                        class="search-close js-dropdn-close"><i
                                                            class="icon-close-thin"></i></a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdn dropdn_fullheight minicart">
                                    <a href="cart.html" class="dropdn-link js-dropdn-link minicart-link"
                                        data-panel="#dropdnMinicart">
                                        <i class="icon-basket"></i>
                                        <span class="minicart-qty">3</span>
                                        <span class="minicart-total hide-mobile">₦180</span>
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
					<li><a href="home">Store</li>
					<li><span>Cart</span></li>
                </ul>
            </div>
        </div>
		<div class="holder">
			<div class="container">
				<div class="page-title text-center">
					<h1>Shopping Cart</h1>
				</div>
				<div class="row">
					<div class="col-lg-11 col-xl-13">
						<div class="cart-table">
							<div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
								<div class="cart-table-prd-image text-center">
									Image
								</div>
								<div class="cart-table-prd-content-wrap">
									<div class="cart-table-prd-info">Name</div>
									<div class="cart-table-prd-qty">Quantity & Color</div>
									<div class="cart-table-prd-price">Price</div>
									<div class="cart-table-prd-action">&nbsp;</div>
								</div>
							</div>
							<div class="cart-table-prd">
								<div class="cart-table-prd-image">
									<a href="#" class="prd-img">
                                        <img src="images/hero-1.png" alt="" srcset="">
                                        </a>
								</div>
								<div class="cart-table-prd-content-wrap">
									<div class="cart-table-prd-info">
										<div class="cart-table-prd-price">
											<div class="price-old">₦200.00</div>
											<div class="price-new">₦180.00</div>
										</div>
										<h2 class="cart-table-prd-name"><a href="#">Leather Pegged Pants</a>
										</h2>
									</div>
									<div class="cart-table-prd-qty">
										<div class="qty qty-changer">
											<button class="decrease"></button>
											<input type="text" class="qty-input" value="2" data-min="0" data-max="1000">
											<button class="increase"></button>
										</div>
                                        <input type="color" name="" id="">
									</div>
									<div class="cart-table-prd-price-total">
										₦360.00
									</div>
								</div>
								<div class="cart-table-prd-action">
									<a href="#" class="cart-table-prd-remove" data-tooltip="Remove Product"><i
											class="icon-recycle"></i></a>
								</div>
							</div>
						</div>
						<div class="text-center mt-1"><a href="#" class="btn btn--grey">Clear All</a></div>
					</div>
					<div class="col-lg-7 col-xl-5 mt-3 mt-md-0">
						<div class="cart-promo-banner">
							<div class="cart-promo-banner-inside">
								<div class="txt1">Save 50%</div>
								<div class="txt2">Only Today!</div>
							</div>
						</div>
						<div class="card-total">
							<div class="row d-flex">
								<div class="col card-total-txt">Total</div>
								<div class="col-auto card-total-price text-right">₦ 475.00</div>
							</div>
							<div class="card-text-info text-center">
								<h5>Standart shipping</h5>
								<!-- <p><b>10 - 11 business days</b><br>1 item ships from the U.S. and will be delivered in
									10 - 11 business days</p> -->
							</div>
						</div>
						<div class="mt-2"></div>
						<div class="panel-group panel-group--style1 prd-block_accordion" id="productAccordion">
							<div class="panel">
								<div id="collapse1" class="panel-collapse collapse show">
									<div class="panel-body">
										<label>State:</label>
										<div class="form-group select-wrapper select-wrapper-sm">
											<select class="form-control form-control--sm">
												<option value="Enugu">Enugu</option>
											</select>
										</div>
										<label>Address:</label>
										<div class="form-group">
											<input type="text" class="form-control form-control--sm">
										</div>
										<label>Phone:</label>
										<div class="form-group">
											<input type="text" class="form-control form-control--sm">
										</div>
									</div>
								</div>

							<button class="btn btn--full btn--lg"><span>Checkout</span></button>
							</div>
						</div>
					</div>
				</div>
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
                    <a href="#"><?=$app->app_title?></a> ©<?=date('Y') ?> copyright
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-sticky">
        <div class="sticky-addcart js-stickyAddToCart closed">
            <div class="container">
                <div class="row">
                    <div class="col-auto sticky-addcart_image">
                        <a href="#">
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
        </div>
        <div class="popup-addedtocart js-popupAddToCart closed" data-close="50000">
            <div class="container">
                <div class="row">
                    <div class="popup-addedtocart-close js-popupAddToCart-close"><i class="icon-close"></i></div>
                    <div class="popup-addedtocart-cart js-open-drop" data-panel="#dropdnMinicart"><i
                            class="icon-basket"></i></div>
                    <div class="col-auto popup-addedtocart_logo">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                            data-src="images/logo-white-sm.webp" class="lazyload fade-up" alt="">
                    </div>
                    <div class="col popup-addedtocart_info">
                        <div class="row">
                            <a href="#" class="col-auto popup-addedtocart_image">
                                <span class="image-container w-100">
                                    <img src="images/skins/fashion/products/product-01-1.webp.html" alt="" />
                                </span>
                            </a>
                            <div class="col popup-addedtocart_text">
                                <a href="#" class="popup-addedtocart_title"></a>
                                <span class="popup-addedtocart_message">Added to <a href="#"
                                        class="underline">Cart</a></span>
                                <span class="popup-addedtocart_error_message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto popup-addedtocart_actions">
                        <span>You can continue</span> <a href="#"
                            class="btn btn--grey btn--sm js-open-drop" data-panel="#dropdnMinicart"><i
                                class="icon-basket"></i><span>Check Cart</span></a> <span>or</span> <a
                            href="#" class="btn btn--invert btn--sm"><i
                                class="icon-envelope-1"></i><span>Check out</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-addcart popup-selectoptions js-popupSelectOptions closed" data-close="500000">
            <div class="container">
                <div class="row">
                    <div class="popup-selectoptions-close js-popupSelectOptions-close"><i class="icon-close"></i></div>
                    <div class="col-auto sticky-addcart_image sticky-addcart_image--zoom">
                        <a href="#" data-caption="">
                            <span class="image-container"><img src="#" alt="" /></span>
                        </a>
                    </div>
                    <div class="col col-sm-5 col-lg-4 col-xl-5 sticky-addcart_info">
                        <h1 class="sticky-addcart_title"><a href="#">&nbsp;</a></h1>
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
        </div>
        <a class="back-to-top js-back-to-top compensate-for-scrollbar" href="#"
            title="Scroll To Top">
            <i class="icon icon-angle-up"></i>
        </a>
        <div class="loader-horizontal js-loader-horizontal">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
        </div>
    </div>
    <script src="js/vendor-special/lazysizes.min.js"></script>
    <script src="js/vendor-special/ls.bgset.min.js"></script>
    <script src="js/vendor-special/ls.aspectratio.min.js"></script>
    <script src="js/vendor-special/jquery.min.js"></script>
    <script src="js/vendor-special/jquery.ez-plus.js"></script>
    <script src="js/vendor/vendor.min.js"></script>
    <script src="js/app-html.js"></script>
</body>

</html>