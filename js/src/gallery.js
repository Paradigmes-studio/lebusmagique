class Gallery {
  constructor() {
      // this.lastScrollTop = 0;
  }

  init() {
    let galleries = $('.o-gallery__list');

    if(galleries.length) {
      galleries.each(function(i,gallery){
        // console.log('gallery?', gallery);
        let gallerySwiper = new Swiper(gallery, {
          slidesPerView: 'auto',
          // slidesPerColumn: 2,
          // spaceBetween: 10,
          // slidesPerGroup: 2,
          // centeredSlides: true,
          // spaceBetween: -5,
          // loop: true,
          // loopFillGroupWithBlank: true,
          // pagination: {
            // el: '.swiper-pagination',
            // clickable: true,
          // },
          navigation: {
            nextEl: $(gallery).next('.o-gallery__navigation').find('.o-slider__next'),
            prevEl: $(gallery).next('.o-gallery__navigation').find('.o-slider__prev'),
          },
          // breakpoints: {
          //   768: {
          //   },
          //   1024: {
          //     spaceBetween: -10,
          //   },
          //   1280: {
          //     spaceBetween: -20,
          //   },
          // }
        });
      });
    }



  }
}
