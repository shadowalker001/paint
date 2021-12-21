<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');
$app = new App();
$app->safesession();
if (!isset($_SESSION['tappAdminId'])) {
    print '<script> self.location = "' . $app->server_root_dir('admin') . '" </script>';
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Admin Add Product | <?= $app->app_title ?></title>
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
                                <h4 class="mb-0">Add Product</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"><?= $app->app_title ?></a></li>
                                        <li class="breadcrumb-item active">Add Product</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-sm-8 offset-2">
                            <div class="card mt-5">
                                <div class="card-body">
                                    <form method="POST" id="addproductForm" action="" autocomplete="off">
                                        <center><span class="formSpan text-danger" id="formSpan"></span>
                                        </center>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Product Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="project title" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <label for=""> Product Description</label><br>
                                                    <textarea name="desc" id="desc" maxlength="535" rows="3" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Product Price (₦)</label>
                                                    <input type="number" min="5" name="price" id="price" class="form-control" placeholder="project price" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Product Rating</label>
                                                    <select name="rating" id="rating" class="form-control">
                                                        <option value="1">1/5 Star</option>
                                                        <option value="2">2/5 Star</option>
                                                        <option value="3">3/5 Star</option>
                                                        <option value="4">4/5 Star</option>
                                                        <option value="5" selected>5/5 Star</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Product Picture</label>
                                                    <input type="file" name="file" id="file" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <div class="progress">
                                                    <div id="progressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                        0%
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                    <button id="smtBtn" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Add Product <i class="fas fa-sign-in-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end row-->


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
    <script>
        $('#title, #desc').on("keyup", function(params) {
            title = $("#title").val();
            desc = $("#desc").val();
            if (title != '' && desc != '') {
                $.post(path + 'inc.files/process_script?mode=processProject', {
                    title: title
                }, function(data) {
                    $('#relatedProjects').html(data);
                });
            }
            return false;
        })
    </script>
</body>

</html>