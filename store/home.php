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
    <style>
        * {
            box-sizing: border-box
        }

        /* Set a style for all buttons */
        button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
        }

        button:hover {
            opacity: 1;
        }

        /* Float cancel and delete buttons and add an equal width */
        .cancelbtn,
        .submitbtn {
            float: left;
            width: 50%;
        }

        /* Add a color to the cancel button */
        .cancelbtn {
            background-color: #ccc;
            color: black;
        }

        /* Add a color to the delete button */
        .submitbtn {
            background-color: #fc8459;
        }

        /* Add padding and center-align text to the container */
        .container {
            padding: 16px;
            text-align: center;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: #474e5d;
            padding-top: 50px;
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto 15% auto;
            /* 5% from the top, 15% from the bottom and centered */
            border: 1px solid #888;
            width: 80%;
            /* Could be more or less, depending on screen size */
        }

        /* Style the horizontal ruler */
        hr {
            border: 1px solid #f1f1f1;
            margin-bottom: 25px;
        }

        /* The Modal Close Button (x) */
        .close {
            position: absolute;
            right: 35px;
            top: 15px;
            font-size: 40px;
            font-weight: bold;
            color: #f1f1f1;
        }

        .close:hover,
        .close:focus {
            color: #f44336;
            cursor: pointer;
        }

        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Change styles for cancel button and delete button on extra small screens */
        @media screen and (max-width: 300px) {

            .cancelbtn,
            .submitbtn {
                width: 100%;
            }
        }
    </style>
</head>

<body class="template-collection has-smround-btns has-loader-bg equal-height has-sm-container">
    <?php require("inc.files/header.php"); ?>
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
                <input type="hidden" name="cartIds" id="cartIds" value="<?= isset($_SESSION['cart']) ? str_replace('"', "'", json_encode($_SESSION['cart']['btnId'])) : '' ?>">
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
                    <div class="dataLoader" id="dataLoader">
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
                                                                    <!-- <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
                                                                        To Cart</button> -->
                                                                    <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" hasColor="<?= $fetch_obj->color!=''?'true':'false' ?>" type="button" class="btn btn-info btn-lg checkStatus" data-toggle="modal" data-target="#myModalss<?= $fetch_obj->id ?>">Add To Cart</button>
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
                                                                        <!-- <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add -->
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
                        <script>
                            document.write(new Date().getFullYear())
                        </script> <?= $app->app_title ?>. Crafted with <i class="icon-heart text-danger"></i> by <a target="_blank" href="https://nestuge.com">Nestuge</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-sticky">
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
        <a class="back-to-top js-back-to-top compensate-for-scrollbar" href="#" title="Scroll To Top">
            <i class="icon icon-angle-up"></i>
        </a>
        <div class="loader-horizontal js-loader-horizontal">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <?php
    $db_handle = $dbh->prepare("SELECT * FROM pt_products WHERE status=1 AND color != '' ORDER BY id DESC");
    $db_handle->execute();
    $sn = 1;
    if ($db_handle->rowCount() > 0) {
    ?>
        <?php
        while ($fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ)) {
            $btnId = AesCtr::encrypt($fetch_obj->id, 'aes256', 256);
        ?>
            <div id="myModal<?= $fetch_obj->id ?>" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="content-title m-0 text-center" style="width: 100%;">
                                <div class="content-title-inner">
                                    <div class="container">
                                        <h1>Choose Color</h1>
                                    </div><!-- /.container -->
                                </div><!-- /.content-title-inner -->
                            </div>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" role="form" id="contactForm">
                                <span id="formSpan" class="formSpan text-danger"></span>
                                <div class="row">
                                    <?php
                                    $color = $fetch_obj->color;
                                    if ($color != "") {
                                        $color = json_decode($color);
                                        echo '<select class="form-control colorId" name="colorId' . $fetch_obj->id . '" id="colorId' . $fetch_obj->id . '" class="form-control" required style="margin-left:5%;margin-right:5%; padding-y:5px; background-color:' . $color[0]->color . '">';
                                        for ($i = 0; $i < count($color); $i++) {
                                            # code...
                                            echo '<option class="form-control" color="' . $color[$i]->color . '" style="background-color:' . $color[$i]->color . '" value="' . ($i + 1) . '">' . $color[$i]->name . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo '<span><i>None</i></span>';
                                    }
                                    ?>
                                </div>
                                <div class="clearfix">
                                    <button type="button" class="cancelbtn" data-dismiss="modal">Cancel</button>
                                    <!-- <button style="display: none;" btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
                                        To Cart</button> -->
                                    <button type="button" class="submitbtn js-prd-addtocart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}' type="submit" id="smtBtn" onclick="doneModal('<?= $fetch_obj->id ?>', '<?= $fetch_obj->id ?>', '<?= $fetch_obj->price ?>');">Done</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
    <?php }
    } ?>
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
    <script>
        // Get the modal
        // var modal = document.getElementById('myModal');

        // // When the user clicks anywhere outside of the modal, close it
        // window.onclick = function(event) {
        //     if (event.target == modal) {
        //         modal.style.display = "none";
        //     }
        // }

        $('.checkStatus').click(function name(params) {
            cartIds = $("#cartIds").val();
            btnId = $(this).attr('btnId');
            hasColor = $(this).attr('hasColor');
            btnPrice = $(this).attr('btnPrice');
            if (cartIds.includes(btnId)) {
                toastr.success("Already in cart!");
                return;
            } else {
                if (hasColor=='true') {
                    $('#myModal' + btnId).modal('show');
                } else {
                    if (!cartIds.includes(btnId)) {
                        $.post(path + 'inc.files/process_script?mode=addToCart', {
                            btnId: btnId,
                            btnPrice: btnPrice
                        }, function(data) {
                            $('#formSpan').html(data);
                        });
                    }
                }
            }
            return false;
        });

        function doneModal(val, btnId, btnPrice) {
            $('#myModal' + val).modal('toggle');
            var value = $("#colorId" + val).val();
            // cartIds = $("#cartIds").val();
            $.post(path + 'inc.files/process_script?mode=doneModal', {
                name: 'colorId' + val,
                value: value,
                btnId: btnId,
                btnPrice: btnPrice,
            }, function(data) {
                $('#formSpan').html(data);
            });
        }
    </script>
    <?php
    if (isset($_SESSION['PAY_ERROR'])) {
        $app->sweetAlert('warning', 'We can not process your payment!');
        unset($_SESSION['PAY_ERROR']);
    }
    if (isset($_SESSION['PAY_SUCCESS'])) {
        $app->sweetAlert('success', 'Transaction successful, your product is ready for shipment and an email has been sent to you with your order details!');
        unset($_SESSION['PAY_SUCCESS']);
    }
    ?>
</body>

</html>