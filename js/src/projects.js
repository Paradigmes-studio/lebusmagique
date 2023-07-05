class Projects {
  constructor() {
      // this.lastScrollTop = 0;
  }

  init() {
    // console.log('Gallery init', $('.o-gallery-mozart__list'));
    let projects = $('.o-projects');
    if(projects.length) {
        // console.log('gallery?', gallery);
      let projectsSwiper = new Swiper(projects, {
        slidesPerView: 'auto',
        // slidesPerColumn: 2,
        // spaceBetween: 10,
        // slidesPerGroup: 2,
        centeredSlides: true,
        // spaceBetween: -5,
        loop: true,
        // loopFillGroupWithBlank: true,
        navigation: {
          nextEl: projects.find('.o-slider__next'),
          prevEl: projects.find('.o-slider__prev'),
        },
        // pagination: {
          // el: '.swiper-pagination',
          // clickable: true,
        // },
        // breakpoints: {
        //   768: {
        //     slidesPerView: 3,
        //   },
        //   1024: {
        //     slidesPerView: 4,
        //   },
          // 1280: {
          // },
        // }
      });
    }



  }
}
