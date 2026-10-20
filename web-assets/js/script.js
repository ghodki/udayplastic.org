
wow = new WOW({ animateClass: 'animated', offset: 50, callback: function (box) { console.log("WOW: animating <" + box.tagName.toLowerCase() + ">") } }); wow.init();

$('.bannerslider').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    dots: false,
    autoplay: false,
    smartSpeed: 1000,
    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
    responsive: {
        0: {
            items: 1,
            nav:false,
            dots:true,
        },
        600: {
            items: 1,
            nav:false,
            dots:true,
        },
        1000: {
            items: 1
        }
    }
})


$('.news-catslider').owlCarousel({
    loop: false,
    margin: 5,
    nav: true,
    dots: false,
    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
})

$('.ourpro-proslider').owlCarousel({
    loop:false,
    margin:20,
    nav:true,
    dots:false,
    navText:["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
})

$('.upcoming-proslider').owlCarousel({
    loop: false,
    margin: 20,
    nav: true,
    dots: false,
    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
})

$('.certificates-slider').owlCarousel({
    loop: false,
    margin: 20,
    nav: true,
    dots: false,
    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
    responsive: {
        0: {
            items: 2
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
})

$('.clients-slider').owlCarousel({
    loop: true,
    margin: 20,
    nav: false,
    dots: false,
    autoplay: true,
    slideTransition: 'linear',
    autoplaySpeed: 6000,
    smartSpeed: 6000,
    center: true,
    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
})

$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $("header").addClass("headercolor");
    } else {
        $("header").removeClass("headercolor");
    }
});

$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $("header").addClass("headercolor");
    } else {
        $("header").removeClass("headercolor");
    }
});

$(function () {
    $(".navbar-toggler").click(function () {
        $("header .navbar").toggleClass("headercolor");
    });
});