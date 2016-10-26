    $(window).load(function() {
        $(".owl-carousel").owlCarousel({
            items:1,
            autoHeight:true,
            dots:true,
            lazyLoad: true,
            nav:true,
            navElement:'button',
            navText:["&lsaquo;","&rsaquo;"],
            loop:true
            
        });
    });