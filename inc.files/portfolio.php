<!-- portfolio area start -->
<section class="portfolio__area portfolio__bg portfolio__padding pb-100 pl-55 pr-55" id="portfolio">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-8 offset-xxl-2 col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                <div class="section__title-wrapper mb-80 text-center">
                    <span class="section__title-pre section__title-pre center">Our Works</span>
                    <h2 class="section__title section__title">Our completed works delivered to our clients</h2>
                </div>
            </div>
        </div>
        <?php
        $querySQL = "SELECT * FROM pt_categories WHERE status=1 ORDER BY RAND() LIMIT 5";
        $db_handle = $dbh->prepare($querySQL);
        $db_handle->execute();
        $counter = 1;
        if ($db_handle->rowCount() > 0) {
        ?>
            <div class="row">
                <?php
                while ($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)) {
                ?>
                    <div class="col-xxl-<?= $counter > 3 ? 6 : 4 ?> col-xl-<?= $counter > 3 ? 6 : 4 ?> col-lg-6">
                        <div class="portfolio__item p-relative mb-30">
                            <div class="portfolio__thumb w-img">
                                <img src="assets/files/<?= $paramGetFields->img_name ?>" alt="">

                                <div class="portfolio__plus transition-3">
                                    <a class="popup-image" href="assets/files/<?= $paramGetFields->img_name ?>">
                                        <i class="far fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="portfolio__content transition-3">
                                <h3 class="portfolio__title">
                                    <a href="#portfolio-details.html"><?= $app->app_title ?></a>
                                </h3>
                                <p>Quality Painting Services</p>
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
            </div>
</section>
<!-- portfolio area end -->