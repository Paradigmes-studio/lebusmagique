class ContentToggle {
    constructor() {

    }

    init() {


      //expandable content
      let contentToggles = $('.content-toggle > button');
      if(contentToggles.length) {

        contentToggles.each(function(i,contentToggle){
          if($(contentToggle).is(':visible'))
          $(contentToggle).on('click', function(e){
            let content = $(this).next('.content-toggle__content');
            let contentInner = content.find('.content-toggle__content-inner');
            if($(contentToggle).attr('aria-expanded') == 'false') {
              // console.log('aria-expanded false', content, contentInner.get(0).clientHeight);
              $(contentToggle).attr('aria-expanded', true);
              content.addClass('is-expanded');
              content.css('max-height', contentInner.get(0).clientHeight+$(contentToggle).get(0).clientHeight);
            } else {
              // console.log('aria-expanded true', content);
              $(this).attr('aria-expanded', false);
              content.removeClass('is-expanded');
              if(undefined !== content.attr('data-max-height') && content.attr('data-max-height') != '') {
                // console.log('max-height', content.attr('data-max-height'));
                // console.log('max-height', eval(content.attr('data-max-height')));
                // console.log('(contentToggle).get(0).clientHeight', $(contentToggle).get(0).clientHeight);
                content.css('max-height', eval(content.attr('data-max-height')));
              } else {
                content.css('max-height', '');
              }
            }
          })
        });


        const breakpoints = [320, 1024, 1280, 1440];

        const generateMatches = (breakpoints, cb) => breakpoints.map((breakpoint) => {
          const mql = window.matchMedia(`(min-width: ${breakpoint}px)`); // create a MediaQueryList

          // create the listener and return the handler, so it can be canceled
          return mql.addListener((e) => cb(e, breakpoint));
        });

        const myFunc = (e, breakpoint) => {
          this.resizeContentToggles(breakpoint);
          // console.log(`${breakpoint}: ${e.matches}`);
        }

        const listeners = generateMatches(breakpoints, myFunc);

        $(window).on('resize', _.debounce(this._resize.bind(this), 400));
        this._resize();

      }

    }

    resizeContentToggles(breakpoint){
      // console.log('resizeContentToggles breakpoint', breakpoint, $(window).width());
      let contentToggles = $('.content-toggle > button');
      // console.log('contentToggles', contentToggles);
      contentToggles.each(function(i,contentToggle){
        let content = $(contentToggle).next('.content-toggle__content');
        let contentInner = content.find('.content-toggle__content-inner');
        let maxHeight = contentInner.get(0).clientHeight+$(contentToggle).get(0).clientHeight;

        if(breakpoint == 1024 && $(window).width() >= 1024) {
            //open and disable button
            // console.log('resizeContentToggles open and disable button');
            $(contentToggle).attr('aria-expanded', true);
            $(contentToggle).closest('.content-toggle').addClass('is-expanded');
            content.addClass('is-expanded');
            content.css('max-height', contentInner.get(0).clientHeight+$(contentToggle).get(0).clientHeight);
            $(contentToggle).css('display', 'none');
        } else if(breakpoint == 1024 && $(window).width() < 1024) {
            $(contentToggle).css('display', '');
        }
        if(undefined !== content.attr('data-max-height') && content.attr('data-max-height') != '') {

          $(this).attr('aria-expanded', false);
          $(contentToggle).closest('.content-toggle').removeClass('is-expanded');
          content.removeClass('is-expanded');
          let newMaxHeight = eval(content.attr('data-max-height'));
          content.css('max-height', newMaxHeight);
        } else {
          content.css('max-height', '');
        }
      });

    }


    _resize() {

      // console.log('_resize', $(window).width());
      let contentToggles = $('.content-toggle > button');
      // console.log('contentToggles', contentToggles);
      contentToggles.each(function(i,contentToggle){
        let content = $(contentToggle).next('.content-toggle__content');
        let contentInner = content.find('.content-toggle__content-inner');
        if(undefined !== content.attr('data-max-height') && content.attr('data-max-height') != '') {
          let containerHeight = $(content).height();
          let contentHeight = contentInner.outerHeight(true)-parseInt(contentInner.css('margin-bottom'));
          // console.log('contentHeight', contentHeight, 'containerHeight', containerHeight, content.attr('data-max-height'), eval(content.attr('data-max-height')));
          //disable/enable button

          if($(window).width() >= 1024) {
            //open and disable button
            // console.log('_resize open and disable button');
            $(contentToggle).attr('aria-expanded', true);
            $(contentToggle).closest('.content-toggle').addClass('is-expanded');
            content.addClass('is-expanded');
            content.css('max-height', contentHeight);
            $(contentToggle).css('display', 'none');
          } else {

            if($(contentToggle).attr('aria-expanded') == 'false') {
              let newMaxHeight = eval(content.attr('data-max-height'));
              content.css('max-height', newMaxHeight);
            } else {
              content.css('max-height', containerHeight);
            }
            // if(contentHeight > containerHeight)
            $(contentToggle).css('display', (contentHeight > containerHeight) ? '' : 'none');
          }
        }
      });

    }
}
