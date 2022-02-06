<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');

// Encryption files
require '../../classes/Aes.php';     // AES PHP implementation
require '../../classes/AesCtr.php';  // AES Counter Mode implementation

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
    <title>Admin Edit Slider | <?= $app->app_title ?></title>
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
                                <h4 class="mb-0">Edit Slider</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"><?= $app->app_title ?></a></li>
                                        <li class="breadcrumb-item active">Edit Slider</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php
                    $btnId = AesCtr::decrypt($_GET['btnId'], 'aes256', 256);
                    if (!$btnId) {
                        print '<script type="text/javascript"> self.location = "' . $app->server_root_dir('admin/dashboard/manage_Sliders') . '" </script>';
                    }

                    $db_handle = $dbh->prepare("SELECT * FROM pt_sliers WHERE id=:id LIMIT 1");
                    $db_handle->execute(array(':id' => $btnId));
                    $sn = 1;
                    if ($db_handle->rowCount() > 0) {
                        while ($fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ)) {
                    ?>

                            <div class="row" style="overflow: auto;">
                                <div class="col-sm-8 offset-2">
                                    <div class="card mt-5">
                                        <div class="card-body">
                                            <form method="POST" id="updateSliderForm" action="" autocomplete="off">
                                                <center><span class="formSpan text-danger" id="formSpan"></span>
                                                </center>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="">Slider Title</label>
                                                            <input type="text" name="title" id="title" value="<?php echo $fetch_obj->title ?>" class="form-control" placeholder="slider title" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for=""> Sldier Subtitle</label><br>
                                                            <input type="text" name="subtitle" id="subtitle" value="<?php echo $fetch_obj->subtitle ?>" class="form-control" placeholder="slider subtitle" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group mb-3">
                                                            <label for="">Slider Picture <i class="text-danger">*not required if you do not wish to update the previous Picture</i></label>
                                                            <input type="file" name="file" id="file" class="form-control" accept="image/*">
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
                                                            <input type="hidden" id="btnId" name="btnId" value="<?= $btnId ?>">
                                                            <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                            <button id="smtBtn" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Add Slider <i class="fas fa-sign-in-alt"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-sm-8 col-sm-8 offset-2">
                                    <div class="card card-default mt-5">
                                        <div class="card-header">
                                            <h4 class="card-title">Add Slider Color(s)</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" id="addColorForm" action="" autocomplete="off">
                                                <center><span class="formSpanAddColor text-danger" id="formSpanAddColor"></span>
                                                </center>
                                                <div class="form-group mb-3">
                                                    <label for="">Choose Color</label>
                                                    <input name="ccolor" id="ccolor" type="color" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="">Color Name</label>
                                                    <input name="cname" id="cname" type="text" class="form-control" placeholder="Enter color name" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <input type="hidden" id="btnId" name="btnId" value="<?= $btnId ?>">
                                                    <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                    <button id="smtBtnColor" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Add <i class="fas fa-plus"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    option:hover {
                                        background-color: transparent !important;
                                    }
                                </style>
                                <div class="col-sm-8 col-sm-8 offset-2">
                                    <div class="card card-default mt-5">
                                        <div class="card-header">
                                            <h4 class="card-title">Remove Slider Color(s)</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" id="removeColorForm" action="" autocomplete="off">
                                                <center><span class="formSpanRemoveColor text-danger" id="formSpanRemoveColor"></span>
                                                </center>
                                                <div class="form-group mb-3">
                                                    <!-- <label for="">Choose Color</label> -->
                                                    <select name="colorId" id="colorId" class="form-control" required>
                                                        <?php
                                                        $color = $fetch_obj->color;
                                                        if ($color != "") {
                                                            echo '<option value="">Choose Color</option>';
                                                            $color = json_decode($color);
                                                            for ($i = 0; $i < count($color); $i++) {
                                                                # code...
                                                                echo '<option color="' . $color[$i]->color . '" style="background-color:' . $color[$i]->color . '" value="' . ($i + 1) . '">' . $color[$i]->name . ' (' . $color[$i]->color . ')</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="">No color added</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <input type="hidden" id="btnId" name="btnId" value="<?= $btnId ?>">
                                                    <input type="hidden" name="byepass" id="byepass" value="egutsen">
                                                    <button id="smtBtnColorRemove" type="submit" name="submit" class="btn btn-outline-primary" style="width: 100%;">Remove <i class="fas fa-minus"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row-->

                    <?php }
                    } else {
                        // print '<script type="text/javascript"> self.location = "'.$app->server_root_dir('user/view_listing').'" </script>';
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
    <script>
        $("#colorId").on('change', function() {
            $(this).css('background-color', $(this).find(':selected').attr('color'));
        })
    </script>
</body>

</html>