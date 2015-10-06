jQuery( document ).ready(function( $ ) {

	/* NaN fix */

	function getNum(val)
	{
	   if (isNaN(val)) 
	     return 0;
	   else
	     return val;
	}

	/* Header navigation */

	function scrolltracknav(){

		var sticky = $( 'header' ).attr( 'data-sticky' );

		if( sticky && sticky == 1 ){
			if( $( document ).scrollTop() > 300 ){
				if( !$( 'header' ).hasClass( 'sticky' ) ){
					$( 'header' ).addClass( 'sticky' );
					$( 'nav.primary' ).addClass('animated fadeInDown');
				}
			} else if( $( document ).scrollTop() < 120 ){
				if( $( 'header' ).hasClass( 'sticky' ) ){
					$( 'header' ).removeClass( 'sticky' );
					$( 'nav.primary' ).removeClass( 'animated fadeInDown' );
				}
			}
		}

		if( $( document ).scrollTop() > 300 ) {
			$( '.back-top' ).show().addClass('animated fadeInUp');
		} else {
			$( '.back-top' ).removeClass('animated fadeInUp').hide();
		}

	}

	$(window).load(scrolltracknav);

	$(window).scroll(scrolltracknav);


	$('.navbar-toggle').on('click', function(){
		if(!$(this).hasClass('collapsed')){
			$(this).children().addClass('fa-bars');
			$(this).children().removeClass('fa-times');
		}else{
			$(this).children().removeClass('fa-bars');
			$(this).children().addClass('fa-times');
		}
	});


	function headerheight(){
		$( 'header.top' ).css('height','');
		var geth = $( 'header.top' ).height();

		if( geth != $( 'header.top' ).css('height') && geth > 0 ) {
			$( 'header.top' ).css( 'height', geth );
		}


		/* Page builder columns height fix */
		$( '.panel-grid' ).each(function( e ) {
			if( $(this).children().length > 1 ) {

				$(this).children().css( 'height', '' );
				if( $(window).width() > 763 ) {
					$(this).children().css( 'height', $(this).height() );
				}

			}
		});


		/* Services widget height */
		$( '.services-list' ).css( 'height', '' );
		$( '.services-list' ).each(function() {
			$(this).css( 'height', $(this).height()+2 );
		});
	}

	$(window).load(headerheight);

	$(window).resize(headerheight);
	

	/* Slider widget */

	$(window).load(function(){
		$( '.page-slideshow' ).addClass( 'page-loading' );
		$( '.loading-animation-spinner' ).hide();
		$( '.loading-animation' ).fadeOut('slow');
		$( 'body' ).css('overflow', 'visible');
	});

	$('.slideshow').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		infinite: true,
		dots: true,
		pauseOnHover: true
	});

	var centerthis = false;
	if($('.partner-item').length > 8){
		centerthis = true;
	}

	$('.partners-list').slick({
		autoplay: true,
		autoplaySpeed: 6000,
		slidesToShow: 3,
		dots: false,
		infinite: true,
		pauseOnHover: false,
		centerMode: centerthis,
		variableWidth: true,
		prevArrow: '<button type="button" class="slick-prev partners-prev"><i class="fa fa-angle-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next partners-next"><i class="fa fa-angle-right"></i></button>',
		responsive: [
		    {
		      breakpoint: 1000,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1,
		        arrows: true,
		        variableWidth: false,
		      }
		    }
		],
	});

	$('.testimonials-list').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: false,
		infinite: true,
		speed: 800,
		slidesToShow: 1,
		/*adaptiveHeight: true,*/
		pauseOnHover: true,
		arrows: false,
		fade: true,
	});

	/* Full images */

	$(".portfolio-list .portfolio-item").tosrus({
		caption : {
			add : true
		},
		buttons    : "inline",
		/*effect     : "fade",*/
	});

	$('.post-gallery-list .post-gallery-item').tosrus({
		caption : {
			add : true
		},
		buttons    : "inline",
		effect     : "fade",
	});


	/* Iframe overlay */

    $( '.googlemap-overlay' ).on('click', function() {
    	$(this).hide();
    });
    
    $( '.googlemap-iframe' ).mouseleave(function() {
    	 $( '.googlemap-overlay' ).show();
    });


	/* Scroll to top */

	$(".back-top").click(function() {
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});


	/* Service widget */

	$( '.service-item' ).addClass('animated fadeInDown');


	/* Categories load more */

	$( '.category-show-all' ).on('click', function() {
		$(this).remove();
		$('.page-category').addClass('page-category-no-limit');
		return false;
	});


	/* Blog grid */

	$(window).load(function (){

		$('.blog-list').masonry({
			itemSelector: '.blog-item',
			columnWidth: 0,
			gutter: 0
		}).masonry('reloadItems');

	});


	/* Counter widget */

	$('.countup-circle').counterUp({
	    delay: 10,
	    time: 1500
	});


	/* Fast fix for Redux freamework bug in customizer (plugin is adding plain array data in top and bottom of live page) */

	if( !$("head").html() && $(".live-customizer-mode-enabled").length ){
		$("body").css( "font-size", "0px" );
		$("header, footer, #wrapper").css( "font-size", "13px" );
		$(".header-details").addClass( "customizer-fix" );
	}
	

	/* Fix for partners widget */

	$( '.partner-item a' ).on( 'click', function() {
		if( $(this).attr( 'href' ) == '#' ) {
			return false;
		}
	});

});