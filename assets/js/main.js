/***************************************************
==================== JS INDEX ======================
****************************************************
01. PreLoader Js
02. Mobile Menu Js
03. Sidebar Js
04. Cart Toggle Js
05. Search Js
06. Sticky Header Js
07. Data Background Js
08. Testimonial Slider Js
09. Slider Js (Home 3)
10. Brand Js
11. Tesimonial Js
12. Course Slider Js
13. Masonary Js
14. Wow Js
15. Data width Js
16. Cart Quantity Js
17. Show Login Toggle Js
18. Show Coupon Toggle Js
19. Create An Account Toggle Js
20. Shipping Box Toggle Js
21. Counter Js
22. Parallax Js
23. InHover Active Js

****************************************************/

(function ($) {
"use strict";

	var windowOn = $(window);
	////////////////////////////////////////////////////
    // 01. PreLoader Js
	windowOn.on('load',function() {
		$("#loading").fadeOut(500);
	});

	////////////////////////////////////////////////////
    // 02. Mobile Menu Js
	$('#mobile-menu').meanmenu({
		meanMenuContainer: '.mobile-menu',
		meanScreenWidth: "991",
		meanExpand: ['<i class="fal fa-plus"></i>'],
	});


	////////////////////////////////////////////////////
    // 03. Sidebar Js
	$(".sidebar-toggle-btn").on("click", function () {
		$(".sidebar__area").addClass("sidebar-opened");
		$(".body-overlay").addClass("opened");
	});
	$(".sidebar__close-btn").on("click", function () {
		$(".sidebar__area").removeClass("sidebar-opened");
		$(".body-overlay").removeClass("opened");
	});


	////////////////////////////////////////////////////
    // 04. Cart Toggle Js
	$(".cart-toggle-btn").on("click", function () {
		$(".cartmini__wrapper").addClass("opened");
		$(".body-overlay").addClass("opened");
	});
	$(".cartmini__close-btn").on("click", function () {
		$(".cartmini__wrapper").removeClass("opened");
		$(".body-overlay").removeClass("opened");
	});
	$(".body-overlay").on("click", function () {
		$(".cartmini__wrapper").removeClass("opened");
		$(".sidebar__area").removeClass("sidebar-opened");
		$(".header__search-3").removeClass("search-opened");
		$(".body-overlay").removeClass("opened");
	});


	////////////////////////////////////////////////////
    // 05. Search Js
	$(".search-toggle").on("click", function () {
		$(".search__area").addClass("opened");
	});
	$(".search-close-btn").on("click", function () {
		$(".search__area").removeClass("opened");
	});


	////////////////////////////////////////////////////
    // 06. Sticky Header Js
	windowOn.on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 100) {
			$("#header-sticky").removeClass("sticky");
		} else {
			$("#header-sticky").addClass("sticky");
		}
	});


	////////////////////////////////////////////////////
    // 07. Data Background Js
	$("[data-background").each(function () {
		$(this).css("background-image", "url( " + $(this).attr("data-background") + "  )");
	});

  	////////////////////////////////////////////////////
    // 07. Nice Select Js
	$('select').niceSelect();

	////////////////////////////////////////////////////
    // 08. slider__active Slider Js

	if (jQuery(".slider__active").length > 0) {
		let sliderActive1 = ".slider__active";
		let sliderInit1 = new Swiper(sliderActive1, {
		  // Optional parameters
		  slidesPerView: 1,
		  slidesPerColumn: 1,
		  paginationClickable: true,
		  loop: true,
		  effect: 'fade',
	
		  autoplay: {
			delay: 5000,
		  },
	
		  // If we need pagination
		  pagination: {
			el: ".swiper-paginations",
			// dynamicBullets: true,
			clickable: true,
		  },
	
		  // Navigation arrows
		  navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		  },
	
		  a11y: false,
		});
	
		function animated_swiper(selector, init) {
		  let animated = function animated() {
			$(selector + " [data-animation]").each(function () {
			  let anim = $(this).data("animation");
			  let delay = $(this).data("delay");
			  let duration = $(this).data("duration");
	
			  $(this)
				.removeClass("anim" + anim)
				.addClass(anim + " animated")
				.css({
				  webkitAnimationDelay: delay,
				  animationDelay: delay,
				  webkitAnimationDuration: duration,
				  animationDuration: duration,
				})
				.one(
				  "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",
				  function () {
					$(this).removeClass(anim + " animated");
				  }
				);
			});
		  };
		  animated();
		  // Make animated when slide change
		  init.on("slideChange", function () {
			$(sliderActive1 + " [data-animation]").removeClass("animated");
		  });
		  init.on("slideChange", animated);
		}
	
		animated_swiper(sliderActive1, sliderInit1);
	  }

	if (jQuery(".slider__active-2").length > 0) {
		let sliderActive1 = ".slider__active-2";
		let sliderInit1 = new Swiper(sliderActive1, {
		  // Optional parameters
		  slidesPerView: 1,
		  slidesPerColumn: 1,
		  paginationClickable: true,
		  loop: true,
		  effect: 'fade',
	
		  autoplay: {
			delay: 5000,
		  },
	
		  // If we need pagination
		  pagination: {
			el: ".swiper-paginations",
			// dynamicBullets: true,
			clickable: true,
		  },
	
		  // Navigation arrows
		  navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		  },
	
		  a11y: false,
		});
	
		function animated_swiper(selector, init) {
		  let animated = function animated() {
			$(selector + " [data-animation]").each(function () {
			  let anim = $(this).data("animation");
			  let delay = $(this).data("delay");
			  let duration = $(this).data("duration");
	
			  $(this)
				.removeClass("anim" + anim)
				.addClass(anim + " animated")
				.css({
				  webkitAnimationDelay: delay,
				  animationDelay: delay,
				  webkitAnimationDuration: duration,
				  animationDuration: duration,
				})
				.one(
				  "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",
				  function () {
					$(this).removeClass(anim + " animated");
				  }
				);
			});
		  };
		  animated();
		  // Make animated when slide change
		  init.on("slideChange", function () {
			$(sliderActive1 + " [data-animation]").removeClass("animated");
		  });
		  init.on("slideChange", animated);
		}
	
		animated_swiper(sliderActive1, sliderInit1);
	  }

	var testimonialSlider = new Swiper('.testimonial__slider', {
		slidesPerView: 1,
        spaceBetween: 30,
		loop: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
		breakpoints: {  
			'1200': {
				slidesPerView: 3,
			},
			'992': {
				slidesPerView: 2,
			},
			'768': {
				slidesPerView: 1,
			},
			'576': {
				slidesPerView: 1,
			},
			'0': {
				slidesPerView: 1,
			},
		},
	});

	var testimonialSlider2 = new Swiper('.testimonial__slider-2', {
		slidesPerView: 1,
        spaceBetween: 30,
		loop:true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
		breakpoints: {  
			'1200': {
				slidesPerView: 1,
			},
			'992': {
				slidesPerView: 1,
			},
			'768': {
				slidesPerView: 1,
			},
			'576': {
				slidesPerView: 1,
			},
			'0': {
				slidesPerView: 1,
			},
		},
	});

	var testimonialSlider3 = new Swiper('.testimonial__slider-3', {
		slidesPerView: 1,
        spaceBetween: 30,
		loop:true,
        pagination: {
          el: ".testimonial-pagination",
          clickable: true,
        },
		breakpoints: {  
			'1200': {
				slidesPerView: 1,
			},
			'992': {
				slidesPerView: 1,
			},
			'768': {
				slidesPerView: 1,
			},
			'576': {
				slidesPerView: 1,
			},
			'0': {
				slidesPerView: 1,
			},
		},
	});

	var brandSlider = new Swiper('.brand__slider', {
		slidesPerView: 6,
        spaceBetween: 30,
		loop: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
		breakpoints: {  
			'1200': {
				slidesPerView: 6,
			},
			'992': {
				slidesPerView: 5,
			},
			'768': {
				slidesPerView: 3,
			},
			'576': {
				slidesPerView: 3,
			},
			'0': {
				slidesPerView: 2,
			},
		},
	});

	var postboxSlider = new Swiper('.postbox__slider', {
		slidesPerView: 1,
        spaceBetween: 0,
		loop: true,
		autoplay: {
		  delay: 3000,
		},
		// Navigation arrows
		navigation: {
			nextEl: ".postbox-slider-button-next",
			prevEl: ".postbox-slider-button-prev",
		},
		breakpoints: {  
			'1200': {
				slidesPerView: 1,
			},
			'992': {
				slidesPerView: 1,
			},
			'768': {
				slidesPerView: 1,
			},
			'576': {
				slidesPerView: 1,
			},
			'0': {
				slidesPerView: 1,
			},
		},
	});

	////////////////////////////////////////////////////
    // 13. Masonary Js
	$('.grid').imagesLoaded( function() {
		// init Isotope
		var $grid = $('.grid').isotope({
		  itemSelector: '.grid-item',
		  percentPosition: true,
		  masonry: {
			// use outer width of grid-sizer for columnWidth
			columnWidth: '.grid-item',
		  }
		});


	// filter items on button click
	$('.masonary-menu').on( 'click', 'button', function() {
	  var filterValue = $(this).attr('data-filter');
	  $grid.isotope({ filter: filterValue });
	});

	//for menu active class
	$('.masonary-menu button').on('click', function(event) {
		$(this).siblings('.active').removeClass('active');
		$(this).addClass('active');
		event.preventDefault();
	});

	});

	/* magnificPopup img view */
	$(".popup-image").magnificPopup({
		type: "image",
		gallery: {
			enabled: true,
		},
	});

	/* magnificPopup video view */
	$(".popup-video").magnificPopup({
		type: "iframe",
	});

	////////////////////////////////////////////////////
    // 14. Wow Js
	new WOW().init();

	////////////////////////////////////////////////////
    // 15. Data width Js
	$("[data-width]").each(function () {
		$(this).css("width", $(this).attr("data-width"));
	  });
	

	////////////////////////////////////////////////////
    // 16. Cart Quantity Js
	$('.cart-minus').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		return false;
	});
	$('.cart-plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});




	////////////////////////////////////////////////////
	// 17. Show Login Toggle Js
	$('#showlogin').on('click', function () {
		$('#checkout-login').slideToggle(900);
	});

	////////////////////////////////////////////////////
	// 18. Show Coupon Toggle Js
	$('#showcoupon').on('click', function () {
		$('#checkout_coupon').slideToggle(900);
	});

	////////////////////////////////////////////////////
	// 19. Create An Account Toggle Js
	$('#cbox').on('click', function () {
		$('#cbox_info').slideToggle(900);
	});

	////////////////////////////////////////////////////
	// 20. Shipping Box Toggle Js
	$('#ship-box').on('click', function () {
		$('#ship-box-info').slideToggle(1000);
	});

	////////////////////////////////////////////////////
	// 21. Counter Js
	$('.counter').counterUp({
		delay: 10,
		time: 1000
	});
	
	////////////////////////////////////////////////////
	// 22. Parallax Js
	if ($('.scene').length > 0 ) {
		$('.scene').parallax({
			scalarX: 10.0,
			scalarY: 15.0,
		}); 
	};

	////////////////////////////////////////////////////
    // 23. InHover Active Js
	$('.hover__active').on('mouseenter', function () {
		$(this).addClass('active').parent().siblings().find('.hover__active').removeClass('active');
	});



	if (typeof ($.fn.knob) != 'undefined') {
		$('.knob').each(function () {
		var $this = $(this),
		knobVal = $this.attr('data-rel');

		$this.knob({
		'draw': function () {
			$(this.i).val(this.cv + '%')
		}
		});

		$this.appear(function () {
		$({
			value: 0
		}).animate({
			value: knobVal
		}, {
			duration: 2000,
			easing: 'swing',
			step: function () {
			$this.val(Math.ceil(this.value)).trigger('change');
			}
		});
		}, {
		accX: 0,
		accY: -150
		});
	});
	}

})(jQuery);