const init = () => {
  AOS.init()

  const videosCollection = document.querySelectorAll('video')
  videosCollection.forEach(video => {
    video.play()
  })

  const mobile = window.matchMedia('(max-width: 992px)')

  /*************************************************************************
   ** Scroll ****************************************************************
   *********************************************************************** */
  gsap.registerPlugin(ScrollTrigger)

  const locoScroll = new LocomotiveScroll({
    el: document.querySelector("[data-scroll-container]"),
    smooth: true,
    mobile: {
      smooth: true,
    }
  })


  if (!mobile.matches) {
    locoScroll.on('scroll', ScrollTrigger.update)

    ScrollTrigger.scrollerProxy("[data-scroll-container]", {
      scrollTop(value) {
        return arguments.length
          ? locoScroll.scrollTo(value, 0, 0)
          : locoScroll.scroll.instance.scroll.y
      },
      getBoundingClientRect() {
        return {
          top: 0,
          left: 0,
          width: window.innerWidth,
          height: window.innerHeight
        }
      },
      pinType: document.querySelector("[data-scroll-container]").style.transform
        ? "transform"
        : "fixed"
    })

    const videoHomeFrame = document.querySelector('.s-video__frame')
    if (videoHomeFrame) {
      const tlVideo = gsap.timeline()
      const videoEl = videoHomeFrame.querySelector('video')

      videoHomeFrame.addEventListener('click', () => {
        videoEl.muted = !videoEl.muted
      })

      tlVideo.fromTo('.s-video__frame', {
        width: '80%',
        height: '80%',
        onComplete: function(){
          $('.s-video__text').css('visibility', 'visible');
        }
      }, {
        width: '100%',
        height: '100%',
        onComplete: function(){
          $('.s-video__text').css('visibility', 'hidden');
        },
        onStart: function(){
          $('.s-video__text').css('visibility', 'visible');
        }
      })

      ScrollTrigger.create({
        animation: tlVideo,
        pin: '.s-video',
        trigger: '.s-video',
        start: 'top',
        end: "+=1000",
        markers: false,
        scrub: true,
        scroller: '[data-scroll-container]'
      })
    }

    const tlHeader = gsap.timeline()

    tlHeader.to('.s-header', { opacity: 0 })
      .to('.s-header', { y: -120 })

    ScrollTrigger.create({
      animation: tlHeader,
      trigger: '.s-header',
      start: 'top',
      end: "+=500",
      scrub: true,
      scroller: '[data-scroll-container]'
    })
  }

  document.addEventListener('lazyloaded', function(){
   locoScroll.update()
  })


  // each time the window updates, we should refresh ScrollTrigger and then update LocomotiveScroll. 
  ScrollTrigger.addEventListener("refresh", () => locoScroll.update());

  // after everything is set up, refresh() ScrollTrigger and update LocomotiveScroll because padding may have been added for pinning, etc.
  ScrollTrigger.refresh();

  /*************************************************************************
   ** Menu Mobile ***********************************************************
   *********************************************************************** */
  
  const heroAbout = document.querySelector('.s-hero-about')

  if (heroAbout) {
    const heroAboutVideo = heroAbout.querySelector('video')

    heroAbout.addEventListener('click', () => {
      heroAboutVideo.muted = !heroAboutVideo.muted
    })
  }

  /*************************************************************************
   ** Menu Mobile ***********************************************************
   *********************************************************************** */
  const btnOpenMenu = document.querySelectorAll('[data-js=btn-menu]')

  btnOpenMenu.forEach(btn => {
    btn.addEventListener('click', (e) => {
      handleMenu()
    })
  })

  const handleMenu = () => {
    if (document.body.classList.contains('menu-opened')) {
      document.body.classList.add('menu-opened-left')

      setTimeout(() => {
        document.body.classList.remove('menu-opened-left')
        document.body.classList.add('menu-hide')
      }, 900)

      setTimeout(() => {
        document.body.classList.remove('menu-hide')
      }, 1300)
    }


    document.body.classList.toggle('menu-opened')
    document.documentElement.classList.toggle('overflow-y-hidden')
  }

  /*************************************************************************
   ** Filter ****************************************************************
   *********************************************************************** */

  const jobsGrid = document.querySelector('[data-js=jobs-grid]')

  if (jobsGrid) {
    const btnJobsFilter = document.querySelectorAll('[data-js=jobs-filter] [data-filter]')
    const jobsGridItem = Array.from(jobsGrid.children)

    jobsGridItem.forEach((job, index) => {
      const currentIndex = index + 1

      if (currentIndex <= 4) {
        job.classList.add(`job-${currentIndex}`)
      }
    })

    const jobsGridFilter = new Isotope(jobsGrid, {
      itemSelector: '.job',
      layoutMode: 'fitRows',
      transitionDuration: 1000,
    })

    btnJobsFilter.forEach(btn => {
      btn.addEventListener('click', (e) => {
        btnJobsFilter.forEach(btn => btn.classList.remove('active'))
        e.target.classList.add('active')
        const filterValue = e.target.dataset.filter === 'all' ? '*' : `[data-filter=${e.target.dataset.filter}]`

        let counter = 1

        jobsGridItem.forEach((job, index) => {
          const currentIndex = index + 1
          job.classList = 'job'

          if (job.dataset.filter === e.target.dataset.filter && e.target.dataset.filter != 'all') {
            job.classList.add(`job-${counter}`)
            counter++
          }

          if (currentIndex <= 4 && e.target.dataset.filter == 'all') {
            job.classList.add(`job-${currentIndex}`)
          }
        })

        jobsGridFilter.arrange({
          filter: filterValue
        })

        locoScroll.update()
      })
    })
  }


  /*************************************************************************
   ** Slides ****************************************************************
   *********************************************************************** */
  const awardsSlider = new Swiper('[data-js=awards-slider]', {
    // slidesPerView: 1,
    // spaceBetween: 30,
    pagination: {
      el: '[data-js=awards-slider-pagination]',
      clickable: true,
    },
    breakpoints: {
      576: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
    },
  })

  const mockupsSlider = new Swiper('[data-slider=mockups]', {
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: {
      el: '[data-js=mockups-slider-pagination]',
      clickable: true,
    },
    breakpoints: {
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 4,
      },
      1200: {
        slidesPerView: 5,
      },
    },
  })

  const jobsSlider = new Swiper('[data-slider=jobs]', {
    slidesPerView: 1,
    breakpoints: {
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
    },
  })

  const testimonialsSlider = new Swiper('[data-slider=testimonials]', {
    slidesPerView: 1, 
    spaceBetween: 30,
    speed: 1000,
    effect: 'fade',
    loop: true,
    breakpointBase: 'container',
    autoplay: {
      delay: 8000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    pagination: {
      el: '[data-js=testimonials-pagination]',
      clickable: true,
      // dynamicBullets: true,
      // dynamicMainBullets: 5,
      renderBullet: function (index, className) {
        return `<span class="${className}">${String(index + 1).padStart(2, '0')}</span>`
      },
    },
    navigation: {
      prevEl: '[data-js=testimonials-btn-prev]',
      nextEl: '[data-js=testimonials-btn-next]',
    },
  })

  const awardsSliderWrapper = document.querySelector('[data-js=awards-slider] .swiper-wrapper')

  if (!mobile.matches) {
    if (awardsSliderWrapper) {
      awardsSlider.destroy();
      awardsSliderWrapper.classList = 's-awards__grid'
    }
  }


  // ####################
  // MOUSE #############
  // ####################
  const cursor = document.querySelector('[data-js=cursor]')

  document.addEventListener('mousemove', (e) => {
    if (e.target.closest('a') && !e.target.parentElement.classList.contains('s-header__menu__link') && !e.target.parentElement.classList.contains('s-header__logo')) {
      cursor.style.transform = `translate3d(${e.clientX}px, ${e.clientY}px, 0) scale(1)`
      cursor.classList.add('cursor-active')
    } else {
      cursor.classList.remove('cursor-active')
      cursor.style.transform = `translate3d(${e.clientX}px, ${e.clientY}px, 0) scale(.4)`
    }

    // cursor.style.transform = `translate3d(${e.clientX}px, ${e.clientY}px, 0)`
  })

  /*************************************************************************
   ** Marquee **************************************************************
   *********************************************************************** */
  const marqueeTitleCollection = document.querySelectorAll('.s-marquee .s-marquee__title')

  if (marqueeTitleCollection.length > 0) {
    gsap.utils.toArray('.s-marquee__title').forEach((line, i) => {
      const links = line.querySelectorAll("span"),
      tl = horizontalLoop(links, {
        repeat: -1, 
        speed: 1 + i * 0.1,
        reversed: i ? true : false,
        paddingRight: parseFloat(gsap.getProperty(links[0], "marginRight", "px")) // otherwise first element would be right up against the last when it loops. In this layout, the spacing is done with marginRight.
      })
    })

    /*
    This helper function makes a group of elements animate along the x-axis in a seamless, responsive loop.

    Features:
    - Uses xPercent so that even if the widths change (like if the window gets resized), it should still work in most cases.
    - When each item animates to the left or right enough, it will loop back to the other side
    - Optionally pass in a config object with values like "speed" (default: 1, which travels at roughly 100 pixels per second), paused (boolean),  repeat, reversed, and paddingRight.
    - The returned timeline will have the following methods added to it:
      - next() - animates to the next element using a timeline.tweenTo() which it returns. You can pass in a vars object to control duration, easing, etc.
      - previous() - animates to the previous element using a timeline.tweenTo() which it returns. You can pass in a vars object to control duration, easing, etc.
      - toIndex() - pass in a zero-based index value of the element that it should animate to, and optionally pass in a vars object to control duration, easing, etc. Always goes in the shortest direction
      - current() - returns the current index (if an animation is in-progress, it reflects the final index)
      - times - an Array of the times on the timeline where each element hits the "starting" spot. There's also a label added accordingly, so "label1" is when the 2nd element reaches the start.
    */
    function horizontalLoop(items, config) {
      items = gsap.utils.toArray(items);
      config = config || {};
      let tl = gsap.timeline({repeat: config.repeat, paused: config.paused, defaults: {ease: "none"}, onReverseComplete: () => tl.totalTime(tl.rawTime() + tl.duration() * 100)}),
        length = items.length,
        startX = items[0].offsetLeft,
        times = [],
        widths = [],
        xPercents = [],
        curIndex = 0,
        pixelsPerSecond = (config.speed || 1) * 100,
        snap = config.snap === false ? v => v : gsap.utils.snap(config.snap || 1), // some browsers shift by a pixel to accommodate flex layouts, so for example if width is 20% the first element's width might be 242px, and the next 243px, alternating back and forth. So we snap to 5 percentage points to make things look more natural
        totalWidth, curX, distanceToStart, distanceToLoop, item, i;
      gsap.set(items, { // convert "x" to "xPercent" to make things responsive, and populate the widths/xPercents Arrays to make lookups faster.
        xPercent: (i, el) => {
          let w = widths[i] = parseFloat(gsap.getProperty(el, "width", "px"));
          xPercents[i] = snap(parseFloat(gsap.getProperty(el, "x", "px")) / w * 100 + gsap.getProperty(el, "xPercent"));
          return xPercents[i];
        }
      });
      gsap.set(items, {x: 0});
      totalWidth = items[length-1].offsetLeft + xPercents[length-1] / 100 * widths[length-1] - startX + items[length-1].offsetWidth * gsap.getProperty(items[length-1], "scaleX") + (parseFloat(config.paddingRight) || 0);
      for (i = 0; i < length; i++) {
        item = items[i];
        curX = xPercents[i] / 100 * widths[i];
        distanceToStart = item.offsetLeft + curX - startX;
        distanceToLoop = distanceToStart + widths[i] * gsap.getProperty(item, "scaleX");
        tl.to(item, {xPercent: snap((curX - distanceToLoop) / widths[i] * 100), duration: distanceToLoop / pixelsPerSecond}, 0)
          .fromTo(item, {xPercent: snap((curX - distanceToLoop + totalWidth) / widths[i] * 100)}, {xPercent: xPercents[i], duration: (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond, immediateRender: false}, distanceToLoop / pixelsPerSecond)
          .add("label" + i, distanceToStart / pixelsPerSecond);
        times[i] = distanceToStart / pixelsPerSecond;
      }
      function toIndex(index, vars) {
        vars = vars || {};
        (Math.abs(index - curIndex) > length / 2) && (index += index > curIndex ? -length : length); // always go in the shortest direction
        let newIndex = gsap.utils.wrap(0, length, index),
          time = times[newIndex];
        if (time > tl.time() !== index > curIndex) { // if we're wrapping the timeline's playhead, make the proper adjustments
          vars.modifiers = {time: gsap.utils.wrap(0, tl.duration())};
          time += tl.duration() * (index > curIndex ? 1 : -1);
        }
        curIndex = newIndex;
        vars.overwrite = true;
        return tl.tweenTo(time, vars);
      }
      tl.next = vars => toIndex(curIndex+1, vars);
      tl.previous = vars => toIndex(curIndex-1, vars);
      tl.current = () => curIndex;
      tl.toIndex = (index, vars) => toIndex(index, vars);
      tl.times = times;
      if (config.reversed) {
        tl.vars.onReverseComplete();
        tl.reverse();
      }
      return tl;
    }
  }

  /*************************************************************************
  ** Trigger form ***********************************************************
  *********************************************************************** */
  const toastyParams = {
    duration: 5000,
    close: true,
    gravity: 'bottom', // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
  }

  $(".form").on('submit', (e) => {
    e.preventDefault()
    const btnSubmit = document.querySelector('.form [type=submit]')
    btnSubmit.disabled = true
    btnSubmit.classList.add('loading')

    formResponse(e.target).then(({ success, data }) => {
      let isEmpty = false
      const formInputs = document.querySelectorAll('.form [data-required]')

      formInputs.forEach(input => {
        console.log(input.type)
        if (!input.value || (input.type === 'checkbox' && !input.checked)) {
          input.classList.add('invalid')
          isEmpty = true
        } else {
          input.classList.remove('invalid')
        }
      })

      if (isEmpty) {
        Toastify({
          text: data,
          className: 'toasty general',
          ...toastyParams,
        }).showToast()
      } else {
        Toastify({
          text: data,
          className: `toasty ${success ? 'success' : 'error'}`,
          ...toastyParams,
        }).showToast()

        if (success) document.querySelector('.form').reset()
      }

      btnSubmit.disabled = false
      btnSubmit.classList.remove('loading')
    })
  })

  /*************************************************************************
  ** Masks *****************************************************************
  *********************************************************************** */
  const masks = {
    phone (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{2})(\d)/, '($1) $2')
        .replace(/(\d{4})(\d)/, '$1-$2')
        .replace(/(\d{4})-(\d)(\d{4})/, '$1$2-$3')
        .replace(/(-\d{4})\d+?$/, '$1')
    },
  }
  
  document.querySelectorAll('[data-mask]').forEach(input => {
    const field = input.dataset.mask
  
    input.addEventListener('input', e => {
      e.target.value = masks[field](e.target.value)
    }, false)
  })

  /*************************************************************************
  ** Files *****************************************************************
  *********************************************************************** */
  const inputFiles = document.querySelectorAll('[type=file]')

  inputFiles.forEach(input => {
    const fileLabel = input.parentElement.querySelector('.form__file__label')
    const fileBtnClose = input.parentElement.querySelector('.form__file__close')

    input.addEventListener('change', (e) => {
      fileLabel.textContent = e.target.value
      input.parentElement.classList.add('active')
    })
    
    fileBtnClose.addEventListener('click', (e) => {
      console.log('dasfasdfasdfsd')
      input.value = ''
      fileLabel.textContent = 'Se necessário, anexe algum arquivo clicando aqui.'
      input.parentElement.classList.remove('active')
    })
  })

  // windown.onload = function(){
    // $('.main-home .s-video video').prop('muted', true);
    $('.main-home .s-video video source').prop('src', $('.main-home .s-video video source').data('src'))
    $('.main-home .s-video video').prop('muted', true); 
  // }

  $('.main-home .s-video video').click(function(){
    if( $('.main-home .s-video video').prop('muted') === false )
      $('.main-home .s-video video').prop('muted', true);
    else
      $('.main-home .s-video video').prop('muted', false);
      
    return false;
  });
}

document.addEventListener("DOMContentLoaded", () => {
  init()
})

/*************************************************************************
** Ajax form *************************************************************
*********************************************************************** */
function formResponse(form) {
  if (form.getAttribute("enctype") == "multipart/form-data") {
    let data = new FormData(form);
    return $.ajax({
      type: "POST",
      enctype: "multipart/form-data",
      url: $(form).attr("action"),
      data: data,
      processData: false,
      contentType: false,
    });
  } else {
    return $.ajax({
      type: "POST",
      url: $(form).attr("action"),
      data: $(form).serialize(),
    });
  }
}