//@prepros-prepend header.js
//@prepros-prepend projects.js
//@prepros-prepend gallery.js

(function() {
  "use strict";



  class Main {
    constructor() {

      // const _home = new Home();
      // _home.init();

      const _header = new Header();
      _header.init();

      const _projects = new Projects();
      _projects.init();

      const _gallery = new Gallery();
      _gallery.init();

      // const _contentToggle = new ContentToggle();
      // _contentToggle.init();

      // const _gallery = new Gallery();
      // _gallery.init();

      $(".js-modal-btn").each(function(i,el){
        $(el).modalVideo({ channel: /\D/.test( $(el).attr('data-video-id') ) ? 'youtube' : 'vimeo' });
      });


      //Select jolis avec Dropkickjs
      $('select').dropkick({
        mobile: true
      });

      // TEMP: grid for integration
      if(/local\.|localhost/.test(window.location.href)) {
        $('<div class="grid12 grid12-big o-wrapper"></div>').appendTo('body');
      }


    }
  }

  const _main = new Main();


}());
