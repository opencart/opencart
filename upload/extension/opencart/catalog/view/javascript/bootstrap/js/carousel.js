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
    resizeTimeout: 0
  };
  var carousel, items, resizeTimeout;

  var methods = {
    init: function(options) {
      self = this;
      settings = $.extend({}, settings, options);
      carousel = $(this).find('.carousel-multiple');
      items = carousel.children('.carousel-item');

      return this.each(function () {
        methods._setEventHandler();
        methods._setSlides();
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
    _setSlides: function() {
      var currentContents = $(carousel).children('.carousel-item:first-child').children().length;
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
        methods._removeClones();

        if (slidesPerView > 1) {
          $.each(items, function (index) {
            var item = $(items[index]);
            var next = $(items[index]).next();
            var i = 1;

            if (!next.length) {
              next = $(items[0]);
            }

            next.children(':first-child').clone().appendTo(item);

            ++i;

            for (i; i < slidesPerView; i++) {
              next = next.next();

              if (!next.length) {
                next = $(items[0]);
              }

              next.children(':first-child').clone().appendTo(item);
            }
          });
        }

        carousel[0].style.setProperty('--translate', 100 / slidesPerView + '%');
      }
    },
    _removeClones: function() {
      $.each(items, function (index, value) {
        $(value).children(':not(:first-child)').remove();
      });
    },
    _windowResize: function() {
      clearTimeout(resizeTimeout);

      resizeTimeout = setTimeout(function() {
        methods._setSlides();
      }, settings.resizeTimeout);
    }
  };

  $.fn.opencartCarousel = function(action) {
    if (methods[action]) {
      return methods[action].apply(this. Array.prototype.slice.call(arguments, 1));
    } else if (typeof action === 'object' || !action) {
      return methods.init.apply(this, arguments);
    } else {
      console.info('Action ' + action + ' not found!');

      return this;
    }
  }
})(jQuery);