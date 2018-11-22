jQuery(function ($) {

    'use strict';

    /*==============================================================*/
    // Search
    /*==============================================================*/

    (function () {

        $('.fa-search').on('click', function() {
            $('.search').fadeIn(500, function() {
                $(this).toggleClass('search-toggle');
            });
        });

        $('.search-close').on('click', function() {
            $('.search').fadeOut(500, function() {
                $(this).removeClass('search-toggle');
            });
        });

    }());



    /*==============================================================*/
    // Menu add class
    /*==============================================================*/
    (function () {
        function menuToggle(){
            var windowWidth = $(window).width();
            if(windowWidth > 767 ){
                $(window).on('scroll', function(){
                    if( $(window).scrollTop()>60 ){
                        $('.navbar').addClass('navbar-fixed-top');
                    } else {
                        $('.navbar').removeClass('navbar-fixed-top');
                    };
                    if( $(window)){
                        $('#home-three .navbar').addClass('navbar-fixed-top');
                    }
                });
            }else{

                $('.navbar').addClass('navbar-fixed-top');

            };
        }

        menuToggle();
    }());

    /*==============================================================*/
    // Revolution Slider 5
    /*==============================================================*/
    if ($(".slider-revolution-5-container").length>0) {
        $(".tp-bannertimer").show();

        $('body:not(.transparent-header) .slider-revolution-5-container .slider-banner-fullscreen').revolution({
            sliderType:"standard",
            sliderLayout:"fullscreen",
            delay:4000,
            autoHeight:"on",
            responsiveLevels:[1199,991,767,480],
            fullScreenOffsetContainer: ".header-container, .offset-container",
            navigation: {
                onHoverStop: "off",
                arrows: {
                    style: "hades",
                    enable:true,
                    tmp: '<div class="tp-title-wrap"></div>',
                    left : {
                        h_align:"left",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0,
                    },
                    right : {
                        h_align:"right",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0
                    }
                },
                bullets:{
                    style:"",
                    enable:true,
                    hide_onleave:true,
                    direction:"horizontal",
                    space: 3,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:20
                }
            },
            gridwidth:1140,
            gridheight:750
        });
        $('.transparent-header .slider-revolution-5-container .slider-banner-fullscreen').revolution({
            sliderType:"standard",
            sliderLayout:"fullscreen",
            delay:1000,
            autoHeight:"on",
            responsiveLevels:[1199,991,767,480],
            fullScreenOffsetContainer: ".header-top, .offset-container",
            navigation: {
                onHoverStop: "off",
                arrows: {
                    style: "hades",
                    enable:false,
                    tmp: '<div class="tp-title-wrap"></div>',
                    left : {
                        h_align:"left",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0,
                    },
                    right : {
                        h_align:"right",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0
                    }
                },
                bullets:{
                    style:"",
                    enable:true,
                    hide_onleave:true,
                    direction:"horizontal",
                    space: 3,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:20
                }
            },
            gridwidth:1140,
            gridheight:750
        });
        $('.slider-revolution-5-container .slider-banner-fullwidth').revolution({
            sliderType:"standard",
            sliderLayout:"fullwidth",
            delay:4000,
            gridwidth:1140,
            gridheight:700,
            responsiveLevels:[1199,991,767,480],
            navigation: {
                onHoverStop: "off",
                arrows: {
                    style: "hades",
                    enable:true,
                    tmp: '<div class="tp-title-wrap"></div>',
                    left : {
                        h_align:"left",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0,
                    },
                    right : {
                        h_align:"right",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0
                    }
                },
                bullets:{
                    style:"hades",
                    enable:true,
                    hide_onleave:true,
                    direction:"horizontal",
                    space: 3,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:20
                }
            }
        });
        $('.slider-revolution-5-container .slider-banner-fullwidth-big-height').revolution({
            sliderType:"standard",
            sliderLayout:"fullwidth",
            delay:8000,
            gridwidth:1140,
            gridheight:650,
            responsiveLevels:[1199,991,767,480],
            navigation: {
                onHoverStop: "off",
                arrows: {
                    style: "hebe",
                    enable:true,
                    tmp: '<div class="tp-title-wrap"><span class="tp-arr-titleholder">{{title}}</span></div>',
                    left : {
                        h_align:"left",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0,
                    },
                    right : {
                        h_align:"right",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0
                    }
                },
                bullets:{
                    style:"hesperiden",
                    enable:true,
                    hide_onleave:true,
                    direction:"horizontal",
                    space: 3,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:20
                }
            }
        });
        $('.slider-revolution-5-container .slider-banner-boxedwidth').revolution({
            sliderType:"standard",
            sliderLayout:"auto",
            delay:8000,
            gridwidth:1140,
            gridheight:450,
            responsiveLevels:[1199,991,767,480],
            shadow: 1,
            navigation: {
                onHoverStop: "off",
                arrows: {
                    style: "hebe",
                    enable:true,
                    tmp: '<div class="tp-title-wrap"><span class="tp-arr-titleholder">{{title}}</span></div>',
                    left : {
                        h_align:"left",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0,
                    },
                    right : {
                        h_align:"right",
                        v_align:"center",
                        h_offset:0,
                        v_offset:0
                    }
                },
                bullets:{
                    style:"",
                    enable:true,
                    hide_onleave:true,
                    direction:"horizontal",
                    space: 3,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:20
                }
            }
        });
        $('.slider-revolution-5-container .slider-banner-fullscreen-hero:not(.dotted)').revolution({
            sliderType:"hero",
            sliderLayout:"fullscreen",
            autoHeight:"on",
            gridwidth:1140,
            gridheight:650,
            responsiveLevels:[1199,991,767,480],
            delay: 9000,
            fullScreenOffsetContainer: ".header-top, .offset-container"
        });
        $('.slider-revolution-5-container .slider-banner-fullscreen-hero.dotted').revolution({
            sliderType:"hero",
            sliderLayout:"fullscreen",
            autoHeight:"on",
            gridwidth:1140,
            gridheight:650,
            dottedOverlay:"twoxtwo",
            delay: 9000,
            responsiveLevels:[1199,991,767,480],
            fullScreenOffsetContainer: ".header-top, .offset-container"
        });
        $('.slider-revolution-5-container .slider-banner-fullwidth-hero:not(.dotted)').revolution({
            sliderType:"hero",
            sliderLayout:"fullwidth",
            gridwidth:1140,
            gridheight:650,
            responsiveLevels:[1199,991,767,480],
            delay: 9000
        });
        $('.slider-revolution-5-container .slider-banner-fullwidth-hero.dotted').revolution({
            sliderType:"hero",
            sliderLayout:"fullwidth",
            gridwidth:1140,
            gridheight:650,
            responsiveLevels:[1199,991,767,480],
            delay: 9000,
            dottedOverlay:"twoxtwo"
        });
        $('.slider-revolution-5-container .slider-banner-carousel').revolution({
            sliderType:"carousel",
            sliderLayout:"fullwidth",
            dottedOverlay:"none",
            delay:5000,
            navigation: {
                keyboardNavigation:"off",
                keyboard_direction: "horizontal",
                mouseScrollNavigation:"off",
                mouseScrollReverse:"default",
                onHoverStop:"off",
                arrows: {
                    style:"erinyen",
                    enable:true,
                    hide_onmobile:false,
                    hide_onleave:false,
                    tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div>    <div class="tp-arr-img-over"></div>	<span class="tp-arr-titleholder">{{title}}</span> </div>',
                    left: {
                        h_align:"left",
                        v_align:"center",
                        h_offset:30,
                        v_offset:0
                    },
                    right: {
                        h_align:"right",
                        v_align:"center",
                        h_offset:30,
                        v_offset:0
                    }
                },
                thumbnails: {
                    style:"",
                    enable:true,
                    width:160,
                    height:120,
                    min_width:100,
                    wrapper_padding:30,
                    wrapper_color:"#373737",
                    wrapper_opacity:"1",
                    tmp:'<span class="tp-thumb-img-wrap">  <span class="tp-thumb-image"></span></span>',
                    visibleAmount:9,
                    hide_onmobile:false,
                    hide_onleave:false,
                    direction:"horizontal",
                    span:true,
                    position:"outer-bottom",
                    space:20,
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:0
                }
            },
            carousel: {
                maxRotation: 65,
                vary_rotation: "on",
                minScale: 55,
                vary_scale: "off",
                horizontal_align: "center",
                vertical_align: "center",
                fadeout: "on",
                vary_fade: "on",
                maxVisibleItems: 5,
                infinity: "off",
                space: -150,
                stretch: "off"
            },
            visibilityLevels:[1240,1024,778,480],
            gridwidth:600,
            gridheight:600,
            lazyType:"none",
            spinner:"off",
            stopLoop:"off",
            stopAfterLoops:-1,
            stopAtSlide:-1,
            shuffle:"off",
            autoHeight:"off",
            disableProgressBar:"on",
            hideThumbsOnMobile:"off",
            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0,
            shadow: 0
        });
        $('.slider-revolution-5-container .slider-banner-carousel-2').revolution({
            sliderType:"carousel",
            sliderLayout:"fullwidth",
            dottedOverlay:"none",
            delay:9000,
            navigation: {
                keyboardNavigation:"off",
                keyboard_direction: "horizontal",
                mouseScrollNavigation:"off",
                mouseScrollReverse:"default",
                onHoverStop:"on",
                tabs: {
                    style:"gyges",
                    enable:true,
                    width:220,
                    height:80,
                    min_width:220,
                    wrapper_padding:0,
                    wrapper_color:"transparent",
                    tmp:'<div class="tp-tab-content">  <span class="tp-tab-date">{{param1}}</span>  <span class="tp-tab-title">{{title}}</span></div><div class="tp-tab-image"></div>',
                    visibleAmount: 6,
                    hide_onmobile: true,
                    hide_under:1240,
                    hide_onleave:true,
                    hide_delay:200,
                    direction:"vertical",
                    span:true,
                    position:"inner",
                    space:0,
                    h_align:"left",
                    v_align:"center",
                    h_offset:0,
                    v_offset:0
                }
            },
            carousel: {
                horizontal_align: "center",
                vertical_align: "center",
                fadeout: "on",
                maxVisibleItems: 5,
                infinity: "on",
                space: 0,
                stretch: "off",
                showLayersAllTime: "off",
                easing: "Power3.easeInOut",
                speed: "800"
            },
            responsiveLevels:[1199,991,767,575],
            visibilityLevels:[1199,991,767,575],
            gridwidth:[800,700,500,500],
            gridheight:[600,600,500,500],
            lazyType:"single",
            shadow:0,
            stopAfterLoops:-1,
            stopAtSlide:-1,
            shuffle:"off",
            autoHeight:"off",
            hideThumbsOnMobile:"off",
            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0
        });
    };


    /*==============================================================*/
    // Parallax Scrolling
    /*==============================================================*/

    (function () {
        function parallaxInit() {
            $("#ticket").parallax("50%", 0.3);
            $("#choose-color").parallax("50%", 0.3);
            $("#blue #choose-color").parallax("50%", 0.3);
        }
        parallaxInit();
    }());


    /*==============================================================*/
    // Tabs Slide
    /*==============================================================*/
    (function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(this).attr('href');
            $(target).css('top','-'+$(window).width()+'px');
            var top = $(target).offset().top;
            $(target).css({top:top}).animate({"top":"0px"}, "-20");
        })
    }());


    /*==============================================================*/
    // Magnific Popup
    /*==============================================================*/

    (function () {
        $('.image-link').magnificPopup({
            gallery: {
                enabled: true
            },
            type: 'image'
        });
        $('.feature-image .image-link').magnificPopup({
            gallery: {
                enabled: false
            },
            type: 'image'
        });
        $('.image-popup').magnificPopup({
            type: 'image'
        });
        $('.video-link').magnificPopup({
            type:'iframe'
        });
    }());


    /*==============================================================*/
    // Home Text Slide
    /*==============================================================*/
    (function () {
        var win = $(window),
            foo = $('#typer');
        foo.typer(["Inroducing The World's Best", "Get New Idea's - New Concept", "Build Your Dream With"]);
        foo = $('#promotion h1');
        foo.typer(["Want to Work with Us?", "Make your dreams come true"]);
    }());


    /*==============================================================*/
    // Twenty20 Plugin
    /*==============================================================*/
    (function () {
        $(window).load(function() {
            $(".layer-slide").twentytwenty();
        });
    }());


    /*==============================================================*/
    // Accordion
    /*==============================================================*/

    (function () {
        $('.faqs .collapse').on('shown.bs.collapse', function(){
            $(this).parent().find(".fa-plus-circle").removeClass("fa-plus-circle").addClass("fa-minus-circle");
        }).on('hidden.bs.collapse', function(){
            $(this).parent().find(".fa-minus-circle").removeClass("fa-minus-circle").addClass("fa-plus-circle");
        });

        $('.faqs .panel-heading').on('click', function() {
            if (!$(this).hasClass('active'))
            {
                $('.panel-heading').removeClass('active');
                $(this).addClass('active');
                setIconOpened(this);
            }
            else
            {
                if ($(this).find('b').hasClass('opened'))
                {
                    setIconOpened(null);
                }
                else
                {
                    setIconOpened(this);
                }
            }
        });

    }());



    /*==============================================================*/
    // projects Filter
    /*==============================================================*/

    (function () {
        $(window).load(function(){
            var $portfolio_selectors = $('.project-filter >ul>li>a');
            var $portfolio = $('#projects');
            $portfolio.isotope({
                itemSelector : '.project-content',
                layoutMode : 'fitRows'
            });

            $portfolio_selectors.on('click', function(){
                $portfolio_selectors.removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter');
                $portfolio.isotope({ filter: selector });
                return false;
            });

        });

    }());



    /*==============================================================*/
    // Architect Filter
    /*==============================================================*/

    (function () {
        $(window).load(function(){
            var $portfolio_selectors = $('.architect-filter >ul>li>a');
            var $portfolio = $('#all-architect');
            $portfolio.isotope({
                itemSelector : '.architect',
                layoutMode : 'fitRows'
            });

            $portfolio_selectors.on('click', function(){
                $portfolio_selectors.removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter');
                $portfolio.isotope({ filter: selector });
                return false;
            });

        });

    }());


    /*==============================================================*/
    // Google Map
    /*==============================================================*/


    (function(){

        var map;

        map = new GMaps({
            el: '#gmap',
            lat: 43.04446,
            lng: -76.130791,
            scrollwheel:false,
            zoom: 6,
            zoomControl : true,
            panControl : false,
            streetViewControl : false,
            mapTypeControl: false,
            overviewMapControl: false,
            clickable: false
        });

        var image = 'images/map-icon.png';
        map.addMarker({
            lat: 43.04446,
            lng: -76.130791,
            icon: image,
            animation: google.maps.Animation.DROP,
            verticalAlign: 'bottom',
            horizontalAlign: 'center',
            backgroundColor: '#d3cfcf',
            infoWindow: {
                content: '<div class="map-info"><address>ThemeRegion<br />234 West 25th Street <br />New York</address></div>',
                borderColor: 'red',
            }
        });

        var styles = [

            {
                "featureType": "road",
                "stylers": [
                    { "color": "#E21243" }
                ]
            },{
                "featureType": "landscape",
                "stylers": [
                    { "color": "#f7f7f7" }
                ]
            },{
                "elementType": "labels.text.fill",
                "stylers": [
                    { "color": "#d3cfcf" }
                ]
            },{
                "featureType": "poi",
                "stylers": [
                    { "color": "#ffffff" }
                ]
            },{
                "elementType": "labels.text",
                "stylers": [
                    { "saturation": 1 },
                    { "weight": 0.1 },
                    { "color": "#555555" }
                ]
            }

        ];

        map.addStyle({
            styledMapName:"Styled Map",
            styles: styles,
            mapTypeId: "map_style"
        });

        map.setStyle("map_style");
    }());



});
