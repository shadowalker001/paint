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
        <title>Admin Dashboard | <?=$app->app_title?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="../../assets/images/favicon.ico">

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
                content: var(--bs-breadcrumb-divider, "<?=$app->appUrl?>");
            }
        </style>
    </head>

    
    <body>

    <!-- <body data-layout="horizontal" data-topbar="colored"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            <?php require ('../inc.files/header.php') ?>
            <!-- ========== Left Sidebar Start ========== -->
            <?php require ('../inc.files/sidebar.php') ?>
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
                                    <h4 class="mb-0">Admin Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Project Verification System</a></li>
                                            <li class="breadcrumb-item active">Admin Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                            <div class="col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end mt-2" style="position: relative;">
                                            <div id="orders-chart" style="min-height: 46px;"><div id="apexchartsdy8jf2wp" class="apexcharts-canvas apexchartsdy8jf2wp apexcharts-theme-light" style="width: 45px; height: 46px;"><svg id="SvgjsSvg1298" width="45" height="46" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1300" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1299"><clipPath id="gridRectMaskdy8jf2wp"><rect id="SvgjsRect1302" width="51" height="47" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskdy8jf2wp"><rect id="SvgjsRect1303" width="49" height="49" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG1304" class="apexcharts-radialbar"><g id="SvgjsG1305"><g id="SvgjsG1306" class="apexcharts-tracks"><g id="SvgjsG1307" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.258536585365854" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747"></path></g></g><g id="SvgjsG1309"><g id="SvgjsG1311" class="apexcharts-series apexcharts-radial-series" seriesName="seriesx1" rel="1" data:realIndex="0"><path id="SvgjsPath1312" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 7.886204749611055 27.248309913566267" fill="none" fill-opacity="0.85" stroke="rgba(52,195,143,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.390243902439025" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="252" data:value="70" index="0" j="0" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 7.886204749611055 27.248309913566267"></path></g><circle id="SvgjsCircle1310" r="13.23658536585366" cx="22.5" cy="22.5" class="apexcharts-radialbar-hollow" fill="transparent"></circle></g></g></g><line id="SvgjsLine1313" x1="0" y1="0" x2="45" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1314" x1="0" y1="0" x2="45" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line></g><g id="SvgjsG1301" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend"></div></div></div>
                                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 46px; height: 47px;"></div></div><div class="contract-trigger"></div></div></div>
                                        <div>
                                            <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?=$app->getCount("pv_students") ?></span></h4>
                                            <p class="text-muted mb-0">Registered Students</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end mt-2" style="position: relative;">
                                            <div id="orders-chart" style="min-height: 46px;"><div id="apexchartsdy8jf2wp" class="apexcharts-canvas apexchartsdy8jf2wp apexcharts-theme-light" style="width: 45px; height: 46px;"><svg id="SvgjsSvg1298" width="45" height="46" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1300" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1299"><clipPath id="gridRectMaskdy8jf2wp"><rect id="SvgjsRect1302" width="51" height="47" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskdy8jf2wp"><rect id="SvgjsRect1303" width="49" height="49" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG1304" class="apexcharts-radialbar"><g id="SvgjsG1305"><g id="SvgjsG1306" class="apexcharts-tracks"><g id="SvgjsG1307" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.258536585365854" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747"></path></g></g><g id="SvgjsG1309"><g id="SvgjsG1311" class="apexcharts-series apexcharts-radial-series" seriesName="seriesx1" rel="1" data:realIndex="0"><path id="SvgjsPath1312" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 7.886204749611055 27.248309913566267" fill="none" fill-opacity="0.85" stroke="rgba(52,195,143,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.390243902439025" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="252" data:value="70" index="0" j="0" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 7.886204749611055 27.248309913566267"></path></g><circle id="SvgjsCircle1310" r="13.23658536585366" cx="22.5" cy="22.5" class="apexcharts-radialbar-hollow" fill="transparent"></circle></g></g></g><line id="SvgjsLine1313" x1="0" y1="0" x2="45" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1314" x1="0" y1="0" x2="45" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line></g><g id="SvgjsG1301" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend"></div></div></div>
                                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 46px; height: 47px;"></div></div><div class="contract-trigger"></div></div></div>
                                        <div>
                                            <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?=$app->getCount("pv_projects") ?></span></h4>
                                            <p class="text-muted mb-0">Registered Projects</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end mt-2" style="position: relative;">
                                            <div id="customers-chart" style="min-height: 46px;"><div id="apexchartsrj7njrl8" class="apexcharts-canvas apexchartsrj7njrl8 apexcharts-theme-light" style="width: 45px; height: 46px;"><svg id="SvgjsSvg1315" width="45" height="46" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1317" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1316"><clipPath id="gridRectMaskrj7njrl8"><rect id="SvgjsRect1319" width="51" height="47" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskrj7njrl8"><rect id="SvgjsRect1320" width="49" height="49" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG1321" class="apexcharts-radialbar"><g id="SvgjsG1322"><g id="SvgjsG1323" class="apexcharts-tracks"><g id="SvgjsG1324" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.258536585365854" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 22.497318152626402 7.134146575498747"></path></g></g><g id="SvgjsG1326"><g id="SvgjsG1328" class="apexcharts-series apexcharts-radial-series" seriesName="seriesx1" rel="1" data:realIndex="0"><path id="SvgjsPath1329" d="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 17.751690086433737 37.11379525038895" fill="none" fill-opacity="0.85" stroke="rgba(91,115,232,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4.390243902439025" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="198" data:value="55" index="0" j="0" data:pathOrig="M 22.5 7.134146341463413 A 15.365853658536587 15.365853658536587 0 1 1 17.751690086433737 37.11379525038895"></path></g><circle id="SvgjsCircle1327" r="13.23658536585366" cx="22.5" cy="22.5" class="apexcharts-radialbar-hollow" fill="transparent"></circle></g></g></g><line id="SvgjsLine1330" x1="0" y1="0" x2="45" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1331" x1="0" y1="0" x2="45" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line></g><g id="SvgjsG1318" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend"></div></div></div>
                                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 46px; height: 47px;"></div></div><div class="contract-trigger"></div></div></div>
                                        <div>
                                            <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?=$app->getCount("pv_students", "WHERE project_id !=0") ?></span></h4>
                                            <p class="text-muted mb-0">Assigned Projects</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row-->


                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


                <?php require ('../inc.files/footer.php') ?>
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
        <script src="../../static/js/functions.js?<?=time()?>"></script>

    </body>

</html>