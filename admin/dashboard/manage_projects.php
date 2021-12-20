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
    <title>Admin Manage Projects | <?= $app->app_title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    <link href="../../assets/libs/admin-resources/rwd-table/rwd-table.min.css" rel="stylesheet" type="text/css">

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
                                <h4 class="mb-0">Manage Projects</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Project Verification System</a></li>
                                        <li class="breadcrumb-item active">Manage Projects</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mt-5">
                                <?php
                                $querySQL = "SELECT * FROM pv_projects";
                                $db_handle = $dbh->prepare($querySQL);
                                $db_handle->execute();
                                $counter = 1;
                                if ($db_handle->rowCount() > 0) {
                                ?>
                                    <div class="card-body">
                                        <span class="text-center text-danger formSpan" id="formSpan"></span>
                                        <div class="table-responsive">
                                            <table class="table table-editable table-nowrap align-middle table-edits">
                                                <thead>
                                                    <tr style="cursor: pointer;">
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)) {
                                                        $id = $paramGetFields->id;
                                                    ?>
                                                    <tr data-id="<?=$counter?>" style="cursor: pointer;">
                                                        <td><?=$counter?></td>
                                                        <td><?php echo $paramGetFields->title; ?></td>
                                                        <td><?php echo $paramGetFields->description; ?></td>
                                                        <td><span style="width: 100%;" id="userSpan<?= $paramGetFields->id ?>" class="btn btn-sm btn-<?= ($paramGetFields->status == '0') ?  'warning' : 'success'; ?>"><?= ($paramGetFields->status == '0') ? 'Inactive' :  'Active'; ?></span></td>
                                                        <td>
                                                            <a style="width: 100%;" btnId='<?= $paramGetFields->id ?>' id="user<?= $paramGetFields->id ?>" href="#" class="btn btn-<?= ($paramGetFields->status == '0') ?  'success activateProject' : 'warning deactivateProject'; ?>"><i class="fas fa-key"></i> <?= ($paramGetFields->status == '0') ?  'Activate' : 'Deactivate'; ?></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        $counter++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <div class="card-body">
                                            <div class="alert alert-primary border-0" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fas fa-window-close align-middle font-16"></i></span></button>
                                                <strong>Opps!</strong> No record found!
                                            </div>
                                        </div>
                                    <?php } ?>
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
        <script src="../../assets/libs/table-edits/build/table-edits.min.js"></script>

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