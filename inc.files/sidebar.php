
      <!-- sidebar area start -->
      <div class="sidebar__area">
         <div class="sidebar__wrapper">
            <div class="sidebar__close">
               <button class="sidebar__close-btn" id="sidebar__close-btn">
                  <i class="fal fa-times"></i>
               </button>
            </div>
            <div class="sidebar__content">
               <div class="sidebar__logo mb-40">
                  <a href="index.html">
                  <img src="static/img/logo/logo.png" alt="logo">
                  </a>
               </div>
               <!-- <div class="sidebar__search mb-10">
                  <form action="index.html#">
                     <input type="text" placeholder="What are you searching for?">
                     <button type="submit" ><i class="far fa-search"></i></button>
                  </form>
               </div> -->
               <div class="mobile-menu fix"></div>
               <div class="sidebar__text d-none d-lg-block">
                  <p>Painting as a service</p>
               </div>
               <div class="sidebar__img d-none d-lg-block mb-20">
                  <div class="row gx-2">
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-1.jpg">
                              <img src="static/img/sidebar/sidebar-1.jpg" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-2.jpg">
                              <img src="static/img/sidebar/sidebar-2.jpg" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-3.jpg">
                              <img src="static/img/sidebar/sidebar-3.jpg" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-4.jpg">
                              <img src="static/img/sidebar/sidebar-4.jpg" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-5.jpg">
                              <img src="static/img/sidebar/sidebar-5.jpg" alt="">
                           </a>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="sidebar__single-img w-img mb-10">
                           <a class="popup-image" href="static/img/sidebar/sidebar-6.jpg">
                              <img src="static/img/sidebar/sidebar-6.jpg" alt="">
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sidebar__map d-none d-lg-block mb-15">
                  <iframe src="<?=$app->mapApi?>"></iframe>
               </div>
               <div class="sidebar__contact mt-30 mb-20">
                  <h4>Contact Info</h4>

                  <ul>
                     <li class="d-flex align-items-center">
                        <div class="sidebar__contact-icon mr-15">
                           <i class="fal fa-map-marker-alt"></i>
                        </div>
                        <div class="sidebar__contact-text">
                           <a target="_blank" href="<?=$app->mapLoc?>"><?=$app->address?></a>
                        </div>
                     </li>
                     <li class="d-flex align-items-center">
                        <div class="sidebar__contact-icon mr-15">
                           <i class="far fa-phone"></i>
                        </div>
                        <div class="sidebar__contact-text">
                           <a href="tel:<?=$app->phone?>"><?=$app->phone?></a>
                        </div>
                     </li>
                     <li class="d-flex align-items-center">
                        <div class="sidebar__contact-icon mr-15">
                           <i class="fal fa-envelope"></i>
                        </div>
                        <div class="sidebar__contact-text">
                           <a href="mailto:<?=$app->mail_uname?>"><span class="__cf_email__" data-cfemail="cdbeb8bdbda2bfb98da0aca4a1e3aea2a0"><?=$app->mail_uname?></span></a>
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="sidebar__social">
                  <ul>
                     <li><a href="index.html#"><i class="fab fa-facebook-f"></i></a></li>
                     <li><a href="index.html#"><i class="fab fa-twitter"></i></a></li>
                     <li><a href="index.html#"><i class="fab fa-youtube"></i></a></li>
                     <li><a href="index.html#"><i class="fab fa-linkedin"></i></a></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- sidebar area end --> 