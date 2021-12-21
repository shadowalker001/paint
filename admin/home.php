<?php
require('../classes/PdoDB.php');
require('../classes/App.php');
$app = new App();
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Sign In | <?=$app->app_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.png">

    <!-- Bootstrap Css -->
    <link href="../assets/css/bootstrap.min.css" id="abootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../assets/css/app.min.css" id="aapp-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../vendors/toaster/toastr.min.css">

</head>

<body class="authentication-bg">
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="home" class="mb-5 d-block auth-logo">
                            <img src="../assets/images/logo-dark.png" alt="" height="22" class="logo logo-dark">
                            <img src="../assets/images/logo-light.png" alt="" height="22" class="logo logo-light">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="card-body p-4">

                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Back Admin!</h5>
                                    <p class="text-muted">Sign in to continue.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form method="post" id="adinlogForm" autocomplete="off">
                                    <span class="text-center text-danger formSpan" id="formSpan"></span>

                                    <div class="mb-3">
                                        <label class="form-label" for="useremail">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"
                                            placeholder="Enter password">
                                    </div>

                                    <div class="mt-3 text-end">
                                        <button style="width: 100%;" class="btn btn-primary w-sm waves-effect waves-light"
                                            type="submit" id="smtBtn">Sign In <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="text-muted mb-0">Go back to home page ? <a href="../home"
                                                class="fw-medium text-primary"> Home</a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>©
                            <script>document.write(new Date().getFullYear())</script> <?=$app->app_title?>. Crafted with <i
                                class="fas fa-heart text-danger"></i> by <a href="https://nestuge.com">Nestuge</a>
                        </p>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="../assets/libs/jquery/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="../assets/libs/simplebar/simplebar.min.js"></script>
    <script src="../assets/libs/node-waves/waves.min.js"></script>
    <script src="../assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="../assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="../vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../vendors/toaster/toastr.min.js"></script>

    <!-- App js -->
    <script src="../assets/js/app.js"></script>
    <script src="../static/js/functions.js?<?=time()?>"></script>


</body>

</html>