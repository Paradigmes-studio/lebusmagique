class Header {

  constructor() {

  }

  init() {

    let _self = this;
    console.log('Header init!', _self, $('#menu-toggle:not(.has-events-click)'));
    // toggle mobile menu
    $('#menu-toggle:not(.has-events-click)').on('click', function(e) {

        e.preventDefault();
        e.stopPropagation();

        if($(this).attr('aria-expanded') == "true") {

            // close menu
            $(this).attr('aria-expanded', false);
            $(this).closest('header').removeClass('is-expanded');

        } else {

          // open menu
          $(this).attr('aria-expanded', true);
          $(this).closest('header').addClass('is-expanded');

        }

        // unfocus menu button
        $(this).blur();

    }).addClass('has-events-click');

    $('.text-yellow-background.top').on('click', function(e){
      e.preventDefault();
      e.stopPropagation();
      if($(window).width() >= 1280) {
        $('html, body').animate({scrollTop: $('.text-yellow-background.bottom').offset().top-$('body > header').height()}, 400, "swing");
      }
    });


    window.addEventListener("scroll", event => {
      let fromTop = window.scrollY + 0;

      if(fromTop >= 90) {
        if(!$('body').hasClass('is-scrolled')) {
          $('body').addClass('is-scrolled');
        }
      } else {
        if($('body').hasClass('is-scrolled')) {
          $('body').removeClass('is-scrolled');
        }
      }

    });

    // landing slider
    let landingSlider = $('.section-landing-top .top-image');
    if(landingSlider.find('.swiper-slide').length > 1) {

    }
    let landingSwiper = new Swiper(landingSlider, {
      slidesPerView: 1,
      autoplay: {
        delay: 6000,
      },
      navigation: {
        nextEl: $('.section-landing-top .o-slider__next'),
        prevEl: $('.section-landing-top .o-slider__prev'),
      },
    });

  }
}
