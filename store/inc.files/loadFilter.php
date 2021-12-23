<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');
// Encryption files
require '../../classes/Aes.php';     // AES PHP implementation
require '../../classes/AesCtr.php';  // AES Counter Mode implementation
$app = new App();
$app->safesession();
extract($_POST);
$db_handle = $dbh->prepare("SELECT * FROM pt_products WHERE status=1 AND title LIKE '%$finder%' ORDER BY id DESC");
$db_handle->execute();
$sn = 1;
if ($db_handle->rowCount() > 0) {
?>
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
                                                <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
                                                    To Cart</button>
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
                                                    <button btnId="<?= $fetch_obj->id ?>" btnPrice="<?= $fetch_obj->price ?>" class="btn js-prd-addtocart addToCart" data-product='{"name": "<?= $fetch_obj->title ?>", "path":"../assets/files/<?= $fetch_obj->img_name ?>", "url":"cart", "aspect_ratio":0.778}'>Add
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
<?php } else { ?>
    <div class="card-body">
        <div class="alert alert-info border-0" role="alert"><button type="button" class="btn" data-dismiss="alert" aria-label="Close"><i class="icon-close"></i><span aria-hidden="true"><i class="fa fa-close align-middle font-16"></i></span></button>
            <center><strong>Opps!</strong> No record found!</center>
        </div>
    </div>
<?php } ?>