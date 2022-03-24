<header class="hdr-wrap">
        <div class="hdr">
            <div class="hdr-content">
                <div class="container">
                    <div class="row">
                        <!-- <div class="col-auto show-mobile">
                            <div class="menu-toggle"> <a href="category-listview.html#" class="mobilemenu-toggle"><i
                                        class="icon-menu"></i></a> </div>
                        </div> -->
                        <div class="col-auto hdr-logo">
                            <a href="../home" class="logo">
                                <img src="../static/img/logo/logo.png" alt="Logo"></a>
                        </div>
                        <div class="hdr-nav hide-mobile nav-holder justify-content-center px-4">
                            <ul class="mmenu mmenu-js">
                                <li class="mmenu-item--simple-"><a href="#" class="active">Store</a>

                                </li>

                            </ul>
                        </div>
                        <div class="hdr-links-wrap col-auto ml-auto">
                            <div class="hdr-inline-link">
                                <div class="search_container_desktop">
                                    <div class="dropdn dropdn_search dropdn_fullwidth">
                                        <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; if($actual_link==$app->server_root_dir('store/home')||$actual_link==$app->server_root_dir('store/')){ ?>
                                        <a href="#" class="dropdn-link  js-dropdn-link only-icon"><i class="icon-search"></i><span class="dropdn-link-txt">Search</span></a>
                                        <?php } ?>
                                        <div class="dropdn-content">
                                            <div class="container">
                                                <form method="post" id="searchForm" class="search search-off-popular">
                                                    <input name="search" id="searchProducts" type="text" class="search-input input-empty" placeholder="What are you looking for?">
                                                    <button type="submit" class="search-button"><i class="icon-search"></i></button>
                                                    <a href="#" class="search-close js-dropdn-close"><i class="icon-close-thin"></i></a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdn dropdn_fullheight minicart">
                                    <a href="cart" class="dropdn-link js-dropdn-link minicart-link" data-panel="#dropdnMinicart">
                                        <i class="icon-basket"></i>
                                        <span class="minicart-qty cartItems"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']['btnId']) : 0 ?></span>
                                        <span class="minicart-total hide-mobile cartSumPrice">₦<?= isset($_SESSION['cart']) ? number_format(array_sum($_SESSION['cart']['btnPrice'])) : 0 ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>