(function($) {
  "use strict";

  var settings = {
    slidesPerView: 6,
    breakpoints: {
      320: {
        slidesPerView: 1
      },
      576: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 3
      },
      992: {
        slidesPerView: 4
      },
      1024: {
        slidesPerView: 5
      }
    },
    transitionDuration: '0.3s',
    resizeTimeout: 0
  };
  var resizeTimeout;
  var carousels = [];

  var methods = {
    init: function(options) {
      settings = $.extend({}, settings, options);

      var carousel = $(this).find('.carousel-multiple');
      var items = carousel.children('.carousel-item');

      carousels.push({
        carousel: carousel[0],
        items: items
      });

      return this.each(function () {
        carousels[carousels.length - 1].carousel.style.setProperty('--transition-duration', settings.transitionDuration);
        methods._setEventHandler();
        methods._setSlides(carousels[carousels.length - 1]);
      });
    },
    destroy: function() {
      methods._removeEventHandler();
    },
    _setEventHandler: function() {
      window.addEventListener('resize', methods._windowResize, {passive: true})
    },
    _removeEventHandler: function() {
      window.removeEventListener('resize', methods._windowResize, {passive: true})
    },
    _setSlides: function(carousel) {
      var currentContents = $(carousel.carousel).children('.carousel-item:first-child').children().length;
      var slidesPerView;
      var breakpoint = null;

      $.each(Object.keys(settings.breakpoints), function(index, value) {
        value = parseInt(value);

        if (value >= window.innerWidth) {
          breakpoint = value;

          return false;
        }
      });

      if (breakpoint && settings.breakpoints[breakpoint].slidesPerView) {
        slidesPerView = settings.breakpoints[breakpoint].slidesPerView;
      } else {
        slidesPerView = settings.slidesPerView;
      }

      if (currentContents !== slidesPerView) {
        methods._removeClones(carousel);

        if (slidesPerView > 1) {
          $.each(carousel.items, function (index) {
            var item = $(carousel.items[index]);
            var next = $(carousel.items[index]).next();
            var i = 1;

            if (!next.length) {
              next = $(carousel.items[0]);
            }

            next.children(':first-child').clone().appendTo(item);

            ++i;

            for (i; i < slidesPerView; i++) {
              next = next.next();

              if (!next.length) {
                next = $(carousel.items[0]);
              }

              next.children(':first-child').clone().appendTo(item);
            }
          });
        }

        carousel.carousel.style.setProperty('--translate', 100 / slidesPerView + '%');
      }
    },
    _removeClones: function(carousel) {
      $.each(carousel.items, function (index, value) {
        $(value).children(':not(:first-child)').remove();
      });
    },
    _windowResize: function() {
      clearTimeout(resizeTimeout);

      resizeTimeout = setTimeout(function() {
        $.each(carousels, function(index, value) {
          methods._setSlides(value);
        });
      }, settings.resizeTimeout);
    }
  };

  $.fn.ocCarousel = function(action) {
    if (methods[action]) {
      return methods[action].apply(this.Array.prototype.slice.call(arguments, 1));
    } else if (typeof action === 'object' || !action) {
      return methods.init.apply(this, arguments);
    } else {
      console.info('Action ' + action + ' not found!');

      return this;
    }
  }
})(jQuery);