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
   <?php require("inc.files/sidebar.php"); ?>
   <!-- sidebar area end -->
   <div class="body-overlay"></div>
   <!-- sidebar area end -->

   <main>

      <?php
      $querySQL = "SELECT * FROM pt_sliders WHERE status=1 ORDER BY id DESC";
      $db_handle = $dbh->prepare($querySQL);
      $db_handle->execute();
      $counter = 1;
      if ($db_handle->rowCount() > 0) {
      ?>
         <!-- slider area start -->
         <section class="slider__area" id="home">
            <div class="slider__active swiper-container">
               <div class="swiper-wrapper">
                  <?php
                  while ($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)) {
                     //   $id = $paramGetFields->id;
                     //   $btnId = AesCtr::encrypt($id, 'aes256', 256);
                  ?>
                     <div class="single-slider swiper-slide slider__height slider__overlay d-flex align-items-center" data-background="assets/files/<?php echo $paramGetFields->img_name; ?>">
                        <div class="container">
                           <div class="row">
                              <div class="col-xxl-12">
                                 <div class="slider__content text-center">
                                    <span data-animation="fadeInUp" data-delay=".2s"><?php echo $paramGetFields->subtitle; ?></span>
                                    <h2 class="slider__title" data-animation="fadeInUp" data-delay=".4s"><?php echo $paramGetFields->title; ?></h2>

                                    <div class="slider__btn" data-animation="fadeInUp" data-delay=".6s">
                                       <a href="#contact" class="r-btn r-btn-green mr-10">get started <i class="far fa-arrow-right"></i></a>
                                       <a href="store" class="r-btn">our services<i class="far fa-arrow-right"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php
                     $counter++;
                  }
                  ?>

               </div>
               <div class="slider__nav">
                  <div class="swiper-button-prev swiper-slider-nav">
                     <i class="fal fa-angle-left"></i>
                  </div>
                  <div class="swiper-button-next swiper-slider-nav">
                     <i class="far fa-angle-right"></i>
                  </div>
               </div>
            </div>
         </section>
      <?php } else { ?>
         <!-- slider area start -->
         <section class="slider__area" id="home">
            <div class="slider__active swiper-container">
               <div class="swiper-wrapper">
                  <div class="single-slider swiper-slide slider__height slider__overlay d-flex align-items-center" data-background="static/img/slider/slider-1.jpg">
                     <div class="container">
                        <div class="row">
                           <div class="col-xxl-12">
                              <div class="slider__content text-center">
                                 <span data-animation="fadeInUp" data-delay=".2s">10 Years Of Experience</span>
                                 <h2 class="slider__title" data-animation="fadeInUp" data-delay=".4s"><?= $app->app_title ?></h2>

                                 <div class="slider__btn" data-animation="fadeInUp" data-delay=".6s">
                                    <a href="#contact" class="r-btn r-btn-green mr-10">get started <i class="far fa-arrow-right"></i></a>
                                    <a href="store" class="r-btn">our services<i class="far fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="single-slider swiper-slide slider__height slider__overlay d-flex align-items-center" data-background="static/img/slider/slider-2.jpg">
                     <div class="container">
                        <div class="row">
                           <div class="col-xxl-12">
                              <div class="slider__content text-center">
                                 <span data-animation="fadeInUp" data-delay=".2s">10 Years Of Experience</span>
                                 <h2 class="slider__title" data-animation="fadeInUp" data-delay=".4s"><?= $app->app_title ?></h2>

                                 <div class="slider__btn" data-animation="fadeInUp" data-delay=".6s">
                                    <a href="#contact" class="r-btn r-btn-green mr-10">get started <i class="far fa-arrow-right"></i></a>
                                    <a href="store" class="r-btn">our services<i class="far fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="single-slider swiper-slide slider__height slider__overlay d-flex align-items-center" data-background="static/img/slider/slider-3.jpg">
                     <div class="container">
                        <div class="row">
                           <div class="col-xxl-12">
                              <div class="slider__content text-center">
                                 <span data-animation="fadeInUp" data-delay=".2s">10 Years Of Experience</span>
                                 <h2 class="slider__title" data-animation="fadeInUp" data-delay=".4s"><?= $app->app_title ?></h2>

                                 <div class="slider__btn" data-animation="fadeInUp" data-delay=".6s">
                                    <a href="#contact" class="r-btn r-btn-green mr-10">get started <i class="far fa-arrow-right"></i></a>
                                    <a href="store" class="r-btn">our services<i class="far fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

               </div>
               <div class="slider__nav">
                  <div class="swiper-button-prev swiper-slider-nav">
                     <i class="fal fa-angle-left"></i>
                  </div>
                  <div class="swiper-button-next swiper-slider-nav">
                     <i class="far fa-angle-right"></i>
                  </div>
               </div>
            </div>
         </section>
      <?php } ?>
      <!-- slider area end -->
      <!-- about area start -->
      <?php require("inc.files/about.php"); ?>
      <!-- about area end -->

      <!-- services area start -->
      <?php require("inc.files/services.php"); ?>
      <!-- services area end -->

      <!-- portfolio area start -->
      <?php require("inc.files/portfolio.php"); ?>
      <!-- portfolio area end -->

      <!-- testimonial area start -->
      <?php //require("inc.files/testimonial.php"); 
      ?>
      <!-- testimonial area end -->

      <!-- contact area start -->
      <?php require("inc.files/contact.php"); ?>
      <!-- contact area end -->

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