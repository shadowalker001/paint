<?php
require('classes/PdoDB.php');
require('classes/App.php');
$app = new App();
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $app->app_title ?> - Painting Services Company </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="static/img/favicon.png">
    <!-- CSS here -->
    <link rel="stylesheet" href="static/css/preloader.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/meanmenu.css">
    <link rel="stylesheet" href="static/css/animate.min.css">
    <link rel="stylesheet" href="static/css/owl.carousel.min.css">
    <link rel="stylesheet" href="static/css/swiper-bundle.css">
    <link rel="stylesheet" href="static/css/backToTop.css">
    <link rel="stylesheet" href="static/css/magnific-popup.css">
    <link rel="stylesheet" href="static/css/nice-select.css">
    <link rel="stylesheet" href="static/css/circularProgressBar.css">
    <link rel="stylesheet" href="static/css/fontAwesome5Pro.css">
    <link rel="stylesheet" href="static/css/flaticon.css">
    <link rel="stylesheet" href="static/css/default.css">
    <link rel="stylesheet" href="static/css/style.css">
    <link rel="stylesheet" href="vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="vendors/toaster/toastr.min.css">
</head>

<body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
      <![endif]-->

    <!-- Add your site or application content here -->

    <!-- pre loader area start -->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <div class="loading-icon text-center d-sm-flex align-items-center justify-content-center">
                    <img class="loading-logo mr-10" src="static/img/logo/logo-icon.png" alt="">
                    <img src="static/img/logo/logo-text.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- pre loader area end -->

    <!-- back to top start -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- back to top end -->

    <!-- header area start -->
    <?php require("inc.files/header.php"); ?>
    <!-- header area end -->

    <!-- sidebar area start -->
    <?php //require("inc.files/sidebar.php"); 
    ?>
    <!-- sidebar area end -->
    <div class="body-overlay"></div>
    <!-- sidebar area end -->

    <main>

        <!-- portfolio area start -->
        <section class="portfolio__area pt-125 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-6 offset-xxl-3 col-xl-8 offset-xl-2 col-lg-8 offset-lg-2">
                        <div class="section__title-wrapper mb-80 text-center">
                            <span class="section__title-pre section__title-pre-3 center">Latest Project</span>
                            <h2 class="section__title section__title-2">Explore Our Professional <?= @ucfirst($_GET['t']) ?> Projects</h2>
                        </div>
                    </div>
                </div>
                <?php
                $querySQL = "SELECT * FROM pt_categories WHERE status=1 AND category='" . @ucfirst($_GET['t']) . " Interior' ORDER BY id DESC";
                $db_handle = $dbh->prepare($querySQL);
                $db_handle->execute();
                $counter = 1;
                if ($db_handle->rowCount() > 0) {
                ?>
                    <div class="row">
                        <?php
                        while ($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)) {
                        ?>
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                <div class="portfolio__item-3 mb-30 p-relative fix">
                                    <div class="portfolio__thumb w-img">
                                        <img src="assets/files/<?= $paramGetFields->img_name ?>" alt="">
                                    </div>
                                    <div class="portfolio__content-3 transition-3 d-flex align-items-center justify-content-between">
                                        <div class="portfolio__content-3-inner">
                                            <h3 class="portfolio__title-3">
                                                <a href="#portfolio-details.html"><?= $app->app_title ?></a>
                                            </h3>
                                            <p>Quality Painting Services</p>
                                        </div>
                                        <div class="portfolio__plus-3 transition-3">
                                            <a href="assets/files/<?= $paramGetFields->img_name ?>" class="popup-image"><i class="far fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $counter++;
                        }
                        ?>
                    <?php } else { ?>
                        <div class="card-body">
                            <div class="alert alert-primary border-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fas fa-window-close align-middle font-16"></i></span></button>
                                <strong>Opps!</strong> No record found!
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="portfolio__more text-center mt-50">
                                <a href="home" class="r-btn r-btn-yellow">go back to home <i class="far fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    </div>
        </section>
        <!-- portfolio area end -->

    </main>

    <!-- footer area start -->
    <?php require("inc.files/footer.php"); ?>
    <!-- footer area end -->

    <!-- JS here -->
    <!-- <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> -->
    <script src="static/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="static/js/vendor/waypoints.min.js"></script>
    <script src="static/js/bootstrap.bundle.min.js"></script>
    <script src="static/js/meanmenu.js"></script>
    <script src="static/js/swiper-bundle.min.js"></script>
    <script src="static/js/owl.carousel.min.js"></script>
    <script src="static/js/magnific-popup.min.js"></script>
    <script src="static/js/parallax.min.js"></script>
    <script src="static/js/backToTop.js"></script>
    <script src="static/js/nice-select.min.js"></script>
    <script src="static/js/counterup.min.js"></script>
    <script src="static/js/jquery.appear.js"></script>
    <script src="static/js/jquery.knob.js"></script>
    <script src="static/js/ajax-form.js"></script>
    <script src="static/js/wow.min.js"></script>
    <script src="static/js/isotope.pkgd.min.js"></script>
    <script src="static/js/imagesloaded.pkgd.min.js"></script>
    <script src="vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="vendors/toaster/toastr.min.js"></script>
    <script src="static/js/main.js"></script>
    <script src="static/js/functions.js?<?= time() ?>"></script>
    <script>
        $(document).ready(function() {
            $('#home').appear(function() {
                resolveView("#home");
            });
            $('#about').appear(function() {
                resolveView("#about");
            });
            $('#services').appear(function() {
                resolveView("#services");
            });
            $('#portfolio').appear(function() {
                resolveView("#portfolio");
            });
            $('#contact').appear(function() {
                resolveView("#contact");
            });
            // Click event for any anchor tag that's href starts with #
            $('a[href^="#"]').click(function(event) {

                // The id of the section we want to go to.
                var id = $(this).attr("href");

                // An offset to push the content down from the top.
                var offset = 60;

                // Our scroll target : the top position of the
                // section that has the id referenced by our href.
                var target = $(id).offset().top - offset;

                // The magic...smooth scrollin' goodness.
                $('html, body').animate({
                    scrollTop: target
                }, 500);

                //make sure sidebar is removed from view
                $(".sidebar__area").removeClass("sidebar-opened");
                $(".body-overlay").removeClass("opened");

                //remove previous active link and make current link active
                $(this).parent().parent().children("li").removeClass("active");
                $(this).parent().addClass("active");

                //prevent the page from jumping down to our section.
                event.preventDefault();
            });
        });

        function resolveView(el) {
            //remove previous active link and make current link active
            $("#mobile-menu ul li").removeClass("active");
            $('#mobile-menu ul li a[href="' + el + '"]').parent().addClass("active");
        }
    </script>
</body>

</html>