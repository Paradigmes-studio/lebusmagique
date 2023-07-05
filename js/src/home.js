class Home {
  constructor() {
    // this.lastScrollTop = 0;
  }

  init() {

    window.addEventListener('resize', this._resize.bind(this));
    this._resize();

    // function setProperty(variable,value) {
    //   document.documentElement.style.setProperty(variable, value);
    // }


    // setTimeout(function(){
    //   // console.log(document.documentElement);
    //   document.body.classList.remove("loading");
    // }, 1000);

  }

  _resize() {

    //calculate vh
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);

  }
}
