<?php $link = basename($_SERVER ['PHP_SELF'], ".php"); ?>
<!-- header area start -->
<header>
    <div class="header__area">
        <div class="header__top grey-bg pl-55 pr-55 header__padding d-none d-lg-block">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xxl-6 col-xl-8 col-lg-8">
                        <div class="header__top-left">
                            <div class="header__info">
                                <ul>
                                    <li>
                                        <a target="_blank"
                                            href="<?=$app->mapLoc?>"><i
                                                class="far fa-map-marker-alt"></i> <?=$app->address?></a>
                                    </li>
                                    <li>
                                        <a href="mailto:<?=$app->mail_uname?>"><i class="far fa-envelope-open"></i>
                                            <span class="__cf_email__"
                                                data-cfemail="88fbfdf8f8e7fafcc8efe5e9e1e4a6ebe7e5"><?=$app->mail_uname?></span></a>
                                    </li>
                                    <li>
                                        <a href="tel:<?=$app->phone?>"><i class="fal fa-phone"></i> <?=$app->phone?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-4 col-lg-4">
                        <div class="header__top-right d-flex align-items-center justify-content-end">
                            <div class="header__lang">
                                <select>
                                    <option>English</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="header-sticky" class="header__bottom pl-55 pr-55 header__padding">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="logo">
                            <a href="home">
                                <img src="static/img/logo/logo.png" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-7 d-none d-lg-block">
                        <div class="header__bottom-mid d-flex align-items-center">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="<?php if ($link == "home") {echo "active"; } ?>">
                                            <a href="#home">Home</a>
                                        </li>
                                        <li class="<?php if ($link == "about") {echo "active"; } ?>">
                                            <a href="#about">About</a>
                                        </li>
                                        <li class="<?php if ($link == "services") {echo "active"; } ?>">
                                            <a href="#services">Services</a>
                                        </li>
                                        <li class="<?php if ($link == "portfolio") {echo "active"; } ?>">
                                            <a href="#portfolio">Portfolio</a>
                                        </li>
                                        <li class="<?php if ($link == "contact") {echo "active"; } ?>">
                                            <a href="#contact">Conatct</a>
                                        </li>
                                        <li class="<?php if ($link == "store") {echo "active"; } ?>">
                                            <a href="store" target="_blank">Store</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-lg-2 col-md-6 col-sm-6 col-6">
                        <div class="header__bottom-right d-flex align-items-center justify-content-end">
                            <div class="header__social mr-35 d-none d-xl-block">
                                <ul>
                                    <li><a href="#home#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#home#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#home#"><i class="fab fa-youtube"></i></a></li>
                                    <li><a href="#home#"><i class="fab fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <div class="header__action">
                                <button type="button" class="dot-hamburger-btn sidebar-toggle-btn">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end -->