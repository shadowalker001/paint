<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');
$app = new App();
$app->safesession();
if (!isset($_SESSION['tappAdminId'])) {
    print '<script> self.location = "' . $app->server_root_dir('sign_in') . '" </script>';
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Admin Profile | <?= $app->app_title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png">

    <!-- Bootstrap Css -->
    <link href="../../assets/css/bootstrap.min.css" id="sbootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../../assets/css/app.min.css" id="sapp-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../../vendors/toaster/toastr.min.css">
    <style>
        .breadcrumb-item+.breadcrumb-item::before {
            float: left;
            padding-right: .5rem;
            color: #74788d;
            content: var(--bs-breadcrumb-divider, "<?= $app->appUrl ?>");
        }
        .userdp{
            height: 100px;
            width: 100px;
            border: 1px solid black;
            border-radius: 50%;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>


<body>

    <!-- <body data-layout="horizontal" data-topbar="colored"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php require('../inc.files/header.php') ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php require('../inc.files/sidebar.php') ?>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">My Profile</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"><?= $app->app_title ?></a></li>
                                        <li class="breadcrumb-item active">My Profile</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php
                    $db_handle = $dbh->prepare("SELECT * FROM pt_admin WHERE id=:id LIMIT 1");
                    $db_handle->execute(array(':id' => $_SESSION['tappAdminId']));
                    $sn = 1;
                    if ($db_handle->rowCount() > 0) {
                        while ($fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ)) {
                    ?>
                            <div class="row">
                                <div class="col-sm-8 offset-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <center>
                                                <span id="profile_picture_msg" class="text-center text-danger"></span>
                                                <input type="file" name="file" id="file" onchange="return validateUploadA()" style="display:none">
                                                <div class="userdp img-circle admin_hold_picture userdpbg" id="profilePicture" style="background-image: url('<?= $app->getCookie('admin_hold_picture'); ?>');">
                                                    <p class="mt-5" style="color: #a8a8a8;">click to change dp</p>
                                                </div>
                                            </center>
                                        </div>
                                        <div class="card-header">
                                            <h4 class="card-title text-center">Profile Picture</h4>
                                        </div>
                                    </div>
                                    <div class="card card-default mt-5">
                                        <div class="card-header">
                                            <h4 class="card-title">User Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" id="adminprofileForm" action="" autocomplete="off">
                                                <center><span class="formSpan text-danger" id="formSpan"></span>
                                                </center>
                                                <div class="form-group mb-3">
                                                    <label for="">Fullname</label>
                                                    <input name="fname" type="text" class="form-control" placeholder="Fullname" value="<?php echo $fetch_obj->fname ?>" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="">Email Address <span class="text-danger">*cannot be changed</span></label>
                                                    <input name="email" type="email" class="form-control" placeholder="Enter Email Address" value="<?php echo $fetch_obj->email ?>" disabled>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="">Phone <span class="text-danger">*cannot be changed</span></label>
                                                    <input name="phone" type="text" class="form-control" placeholder="Enter Phone Number" value="<?php echo $fetch_obj->phone ?>" disabled>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                    <button id="smtBtn" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Update Profile <i class="fas fa-sign-in-alt"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card card-default mt-5">
                                        <div class="card-header">
                                            <h4 class="card-title">Security</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" id="adminupdatePassword" action="" autocomplete="off">
                                                <center><span class="formSpanCPASS text-danger" id="formSpanCPASS"></span>
                                                </center>
                                                <div class="form-group mb-3">
                                                    <label for="">Current password</label>
                                                    <input name="old_password" id="old_password" type="password" class="form-control" placeholder="Enter Current password" autocomplete="new-password" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="">New password</label>
                                                    <input name="password" id="password" type="password" class="form-control" placeholder="Enter new password" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="">Retype password</label>
                                                    <input name="repeatPassword" id="repeatPassword" type="password" class="form-control" placeholder="Retype new password" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                    <button id="smtBtnPass" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Update Password <i class="fas fa-sign-in-alt"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                            </div> <!-- end row-->
                    <?php }
                    } else {
                        // print '<script type="text/javascript"> self.location = "'.$app->server_root_dir('user/dashboard').'" </script>';
                    } ?>


                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->


            <?php require('../inc.files/footer.php') ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="../../assets/libs/jquery/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="../../assets/libs/simplebar/simplebar.min.js"></script>
    <script src="../../assets/libs/node-waves/waves.min.js"></script>
    <script src="../../assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="../../assets/libs/jquery.counterup/jquery.counterup.min.js"></script>

    <!-- apexcharts -->
    <!-- <script src="../../assets/libs/apexcharts/apexcharts.min.js"></script> -->

    <!-- <script src="../../assets/js/pages/dashboard.init.js"></script> -->
    <script src="../../vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../../vendors/toaster/toastr.min.js"></script>

    <!-- App js -->
    <script src="../../assets/js/app.js"></script>
    <script src="../../static/js/functions.js?<?= time() ?>"></script>

</body>

</html>