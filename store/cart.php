<?php
require('../classes/PdoDB.php');
require('../classes/App.php');
$app = new App();
$app->safesession();
// print_r($_SESSION['cart']);
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
    <?php require("inc.files/header.php"); ?>
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
                    <span class="formSpan text-danger" id="formSpan"></span>
                </div>
                <?php
                if (isset($_SESSION['cart'])) {
                ?>
                    <form id="checkoutForm-" action="inc.files/process?checkoutForm" method="post">
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
                                    <?php
                                    $cart = $_SESSION["cart"];
                                    $cartIds = implode("' OR id = '", $cart["btnId"]);
                                    $db_handle = $dbh->prepare("SELECT * FROM pt_products WHERE status=1 AND (id = '$cartIds') ORDER BY id DESC");
                                    $db_handle->execute();
                                    $sn = 1;
                                    while ($fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                        <div class="cart-table-prd">
                                            <div class="cart-table-prd-image">
                                                <a href="#" class="prd-img">
                                                    <img src="../assets/files/<?= $fetch_obj->img_name ?>" alt="" srcset="">
                                                </a>
                                            </div>
                                            <div class="cart-table-prd-content-wrap">
                                                <div class="cart-table-prd-info">
                                                    <div class="cart-table-prd-price">
                                                        <div class="price-old">₦<?= number_format(round($fetch_obj->price * .1, -1) + $fetch_obj->price) ?></div>
                                                        <div class="price-new">₦<?= number_format($fetch_obj->price) ?></div>
                                                    </div>
                                                    <h2 class="cart-table-prd-name"><a href="#"><?= $fetch_obj->title ?></a>
                                                    </h2>
                                                </div>
                                                <div class="cart-table-prd-qty">
                                                    <div class="qty qty-changer">
                                                        <button type="button" class="decrease" btnId="<?= $fetch_obj->id ?>" price="<?= $fetch_obj->price ?>"></button>
                                                        <input min="1" btnId="<?= $fetch_obj->id ?>" name="qtyVal<?= $fetch_obj->id ?>" id="qtyVal<?= $fetch_obj->id ?>" type="number" class="qty-input" value="1" max="1000">
                                                        <button type="button" class="increase" btnId="<?= $fetch_obj->id ?>" price="<?= $fetch_obj->price ?>"></button>
                                                    </div>
                                                    <?php
                                                    // $color = $fetch_obj->color;
                                                    // if ($color != "") {
                                                    //     $color = json_decode($color);
                                                    //     echo '<select class="colorId" name="colorId' . $fetch_obj->id . '" id="colorId' . $fetch_obj->id . '" class="form-control" required style="height: 20px; width:auto; padding-y:5px; background-color:' . $color[0]->color . '">';
                                                    //     for ($i = 0; $i < count($color); $i++) {
                                                    //         # code...
                                                    //         echo '<option color="' . $color[$i]->color . '" style="background-color:' . $color[$i]->color . '" value="' . ($i + 1) . '">' . $color[$i]->name . '</option>';
                                                    //     }
                                                    //     echo '</select>';
                                                    // }else{
                                                    //     echo '<span><i>None</i></span>';
                                                    // }
                                                    $color = $fetch_obj->color;
                                                    if(isset($_SESSION["last_post"]['colorId'.$fetch_obj->id])){
                                                        $pt = $_SESSION["last_post"]['colorId'.$fetch_obj->id];
                                                        if ($color != "") {
                                                            $color = json_decode($color);
                                                            echo '<input type="color" name="" id="" value="'.$color[$pt-1]->color.'" disabled>';
                                                            // echo '<button type="button" style="background-color: ' . $color[$pt-1]->color . '; ">' . $color[$pt-1]->name . '</button>';
                                                            // echo '<select class="colorId" name="colorId' . $fetch_obj->id . '" id="colorId' . $fetch_obj->id . '" class="form-control" required style="height: 20px; width:auto; padding-y:5px; background-color:' . $color[$pt-1]->color . '">';
                                                            // echo '<option color="' . $color[$pt]->color . '" style="background-color:' . $color[$pt]->color . '">' . $color[$pt-1]->name . '</option>';
                                                            // // for ($i = 0; $i < count($color); $i++) {
                                                            // //     # code...
                                                            // //     echo '<option color="' . $color[$i]->color . '" style="background-color:' . $color[$i]->color . '" value="' . ($i + 1) . '">' . $color[$i]->name . '</option>';
                                                            // // }
                                                            // echo '</select>';
                                                        }else{
                                                            echo '<span><i>None</i></span>';
                                                        }
                                                    }else{
                                                        echo '<span><i>None</i></span>';
                                                    }
                                                    ?>
                                                    
                                                    <!-- </select> -->
                                                </div>
                                                <div class="cart-table-prd-price-total qtyTotal<?= $fetch_obj->id ?>">
                                                    ₦<?= number_format($fetch_obj->price) ?>
                                                </div>
                                            </div>
                                            <div class="cart-table-prd-action">
                                                <a btnId="<?= $fetch_obj->id ?>" href="#" class="cart-table-prd-remove removeQty" data-tooltip="Remove Product"><i class="icon-recycle"></i></a>
                                            </div>
                                        </div>
                                    <?php $sn++;
                                    } ?>
                                </div>
                                <div class="text-center mt-1"><a href="#" class="btn btn--grey clearAll">Clear All</a></div>
                            </div>
                            <div class="col-lg-7 col-xl-5 mt-3 mt-md-0">
                                <!-- <div class="cart-promo-banner">
                                    <div class="cart-promo-banner-inside">
                                        <div class="txt1">Save 50%</div>
                                        <div class="txt2">Only Today!</div>
                                    </div>
                                </div> -->
                                <div class="card-total">
                                    <div class="row d-flex">
                                        <div class="col card-total-txt">Total</div>
                                        <div class="col-auto card-total-price text-right cartSumPrice">₦<?= isset($_SESSION['cart']) ? number_format(array_sum($_SESSION['cart']['btnPrice'])) : 0 ?></div>
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
                                                <label>Name:</label>
                                                <div class="form-group">
                                                    <input type="text" name="name" id="name" class="form-control form-control--sm" required>
                                                </div>
                                                <label>Email:</label>
                                                <div class="form-group">
                                                    <input type="email" name="email" id="email" class="form-control form-control--sm" required>
                                                </div>
                                                <label>Phone:</label>
                                                <div class="form-group">
                                                    <input type="text" name="phone" id="phone" class="form-control form-control--sm" required>
                                                </div>
                                                <label>State:</label>
                                                <div class="form-group select-wrapper select-wrapper-sm">
                                                    <select id="state" name="state" class="form-control form-control--sm" required>
                                                        <option value="">Select State...</option>
                                                        <option value="Abia">Abia</option>
                                                        <option value="Adamawa">Adamawa</option>
                                                        <option value="AkwaIbom">AkwaIbom</option>
                                                        <option value="Anambra">Anambra</option>
                                                        <option value="Bauchi">Bauchi</option>
                                                        <option value="Bayelsa">Bayelsa</option>
                                                        <option value="Benue">Benue</option>
                                                        <option value="Borno">Borno</option>
                                                        <option value="Cross River">Cross River</option>
                                                        <option value="Delta">Delta</option>
                                                        <option value="Ebonyi">Ebonyi</option>
                                                        <option value="Edo">Edo</option>
                                                        <option value="Ekiti">Ekiti</option>
                                                        <option value="Enugu">Enugu</option>
                                                        <option value="FCT">FCT</option>
                                                        <option value="Gombe">Gombe</option>
                                                        <option value="Imo">Imo</option>
                                                        <option value="Jigawa">Jigawa</option>
                                                        <option value="Kaduna">Kaduna</option>
                                                        <option value="Kano">Kano</option>
                                                        <option value="Katsina">Katsina</option>
                                                        <option value="Kebbi">Kebbi</option>
                                                        <option value="Kogi">Kogi</option>
                                                        <option value="Kwara">Kwara</option>
                                                        <option value="Lagos">Lagos</option>
                                                        <option value="Nasarawa">Nasarawa</option>
                                                        <option value="Niger">Niger</option>
                                                        <option value="Ogun">Ogun</option>
                                                        <option value="Ondo">Ondo</option>
                                                        <option value="Osun">Osun</option>
                                                        <option value="Oyo">Oyo</option>
                                                        <option value="Plateau">Plateau</option>
                                                        <option value="Rivers">Rivers</option>
                                                        <option value="Sokoto">Sokoto</option>
                                                        <option value="Taraba">Taraba</option>
                                                        <option value="Yobe">Yobe</option>
                                                        <option value="Zamfara">Zamafara</option>
                                                    </select>
                                                </div>
                                                <label>Address:</label>
                                                <div class="form-group">
                                                    <input type="text" name="address" id="address" class="form-control form-control--sm" required>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="totalCartSum" id="totalCartSum" value="<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']['btnPrice']) : 0 ?>">
                                        <button type="submit" name="smtBtn" id="smtBtn" class="btn btn--full btn--lg"><span>Checkout</span></button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="card-body">
                        <div class="alert border-0" style="background-color: #fc8459;" role="alert"><button type="button" class="btn" data-dismiss="alert" aria-label="Close"><i class="icon-close"></i><span aria-hidden="true"><i class="fa fa-close align-middle font-16"></i></span></button>
                            <center><strong>Opps!</strong> cart is empty!</center>
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
                    <a href="#"><?= $app->app_title ?></a> ©<?= date('Y') ?> copyright
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-sticky">
        <a class="back-to-top js-back-to-top compensate-for-scrollbar" href="#" title="Scroll To Top">
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
    <script src="../vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../vendors/toaster/toastr.min.js"></script>
    <script src="js/app-html.js"></script>
    <script src="../static/js/functions.js?<?= time() ?>"></script>
    <?php
        if(isset($_SESSION['PAY_ERROR'])){
            $app->sweetAlert('warning', 'We can not process your payment!');
            unset($_SESSION['PAY_ERROR']);
        }
        if(isset($_SESSION['PAY_SUCCESS'])){
            $app->sweetAlert('success', 'Transaction successful, your product is ready for shipment and an email has been sent to you with your order details!');
            unset($_SESSION['PAY_SUCCESS']);
        }
    ?>
</body>

</html>