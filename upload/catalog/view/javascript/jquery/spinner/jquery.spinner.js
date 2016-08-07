/*! jQuery spinner - v0.1.6 - 2015-03-09
* https://github.com/xixilive/jquery-spinner
* Copyright (c) 2015 xixilive; Licensed MIT */
;(function($){
  "use strict";

  var spinningTimer;
  var Spinning = function(el, options){
    options = $.extend({}, options);
    this.$el = el;
    this.options = $.extend({}, Spinning.rules.defaults, Spinning.rules[options.rule] || {}, options);
    this.min = parseFloat(this.options.min) || 0;
    this.max = parseFloat(this.options.max) || 0;

    this.$el
      .on('focus.spinner', $.proxy(function(e){
        e.preventDefault();
        $(document).trigger('mouseup.spinner');
        this.oldValue = this.value();
      }, this))
      .on('change.spinner', $.proxy(function(e){
        e.preventDefault();
        this.value(this.$el.val());
      }, this))
      .on('keydown.spinner', $.proxy(function(e){
        var dir = {38: 'up', 40: 'down'}[e.which];
        if(dir){
          e.preventDefault();
          this.spin(dir);
        }
      }, this));
    
    //init input value
    this.oldValue = this.value();
    this.value(this.$el.val());
    return this;
  };

  Spinning.rules = {
    defaults: {min: null, max: null, step: 1, precision:0},
    currency: {min: 0.00, max: null, step: 0.01, precision: 2},
    quantity: {min: 1, max: 999, step: 1, precision:0},
    percent:  {min: 1, max: 100, step: 1, precision:0},
    month:    {min: 1, max: 12, step: 1, precision:0},
    day:      {min: 1, max: 31, step: 1, precision:0},
    hour:     {min: 0, max: 23, step: 1, precision:0},
    minute:   {min: 1, max: 59, step: 1, precision:0},
    second:   {min: 1, max: 59, step: 1, precision:0}
  };

  Spinning.prototype = {
    spin: function(dir){
      if (this.$el.attr('disabled') === 'disabled') {
          return;
      }

      this.oldValue = this.value();
      var step = $.isFunction(this.options.step) ? this.options.step.call(this, dir) : this.options.step;
      switch(dir){
        case 'up':
          this.value(this.oldValue + Number(step, 10));
          break;
        case 'down':
          this.value(this.oldValue - Number(step, 10));
          break;
      }
    },

    value: function(v){
      if(v === null || v === undefined){
        return this.numeric(this.$el.val());
      }
      v = this.numeric(v);
      
      var valid = this.validate(v);
      if(valid !== 0){
        v = (valid === -1) ? this.min : this.max;
      }
      this.$el.val(v.toFixed(this.options.precision));

      if(this.oldValue !== this.value()){
        //changing.spinner
        this.$el.trigger('changing.spinner', [this.value(), this.oldValue]);

        //lazy changed.spinner
        clearTimeout(spinningTimer);
        spinningTimer = setTimeout($.proxy(function(){
          this.$el.trigger('changed.spinner', [this.value(), this.oldValue]);
        }, this), Spinner.delay);
      }
    },

    numeric: function(v){
      v = this.options.precision > 0 ? parseFloat(v, 10) : parseInt(v, 10);
      // If the variable is a number
      if (!isNaN(parseFloat(v)) && isFinite(v)) {
        return v;
      } else {
        return v || this.options.min || 0; 
      }
    },

    validate: function(val){
      if(this.options.min !== null && val < this.min){
        return -1;
      }
      if(this.options.max !== null && val > this.max){
        return 1;
      }
      return 0;
    }
  };

  var Spinner = function(el, options){
    options = $.extend({}, options);
    this.$el = el;
    this.$spinning = $("[data-spin='spinner']", this.$el);
    if(this.$spinning.length === 0){
      this.$spinning = $(":input[type='text']", this.$el);
    }
    this.spinning = new Spinning(this.$spinning, $.extend(this.$spinning.data(), options));

    this.$el
      .on('click.spinner', "[data-spin='up'],[data-spin='down']", $.proxy(this.spin, this))
      .on('mousedown.spinner', "[data-spin='up'],[data-spin='down']", $.proxy(this.spin, this));

    $(document).on('mouseup.spinner', $.proxy(function(){
      clearTimeout(this.spinTimeout);
      clearInterval(this.spinInterval);
    }, this));

    if(options.delay){
      this.delay(options.delay);
    }
    if(options.changed){
      this.changed(options.changed);
    }
    if(options.changing){
      this.changing(options.changing);
    }
  };

  Spinner.delay = 500;

  Spinner.prototype = {
    constructor: Spinner,

    spin: function(e){
      var dir = $(e.currentTarget).data('spin');
      switch(e.type){
        case 'click':
          e.preventDefault();
          this.spinning.spin(dir);
          break;

        case 'mousedown':
          if(e.which === 1){
            this.spinTimeout = setTimeout($.proxy(this.beginSpin, this, dir), 300);
          }
          break;
      }
    },

    delay: function(ms){
      var delay = parseInt(ms, 10);
      if(delay >= 0){
        this.constructor.delay = delay + 100;
      }
    },

    value: function(){
      return this.spinning.value();
    },

    changed: function(fn){
      this.bindHandler('changed.spinner', fn);
    },

    changing: function(fn){
      this.bindHandler('changing.spinner', fn);
    },

    bindHandler: function(t, fn){
      if($.isFunction(fn)){
        this.$spinning.on(t, fn);
      }else{
        this.$spinning.off(t);
      }
    },

    beginSpin: function(dir){
      this.spinInterval = setInterval($.proxy(this.spinning.spin, this.spinning, dir), 100);
    }
  };

  $.fn.spinner = function(options, value){
    return this.each(function(){
      var self = $(this), data = self.data('spinner');
      if(!data){
        self.data('spinner', (data = new Spinner(self, $.extend({}, self.data(), options))));
      }
      if(options === 'delay' || options === 'changed' || options === 'changing'){
        data[options](value);
      }
      if(options === 'step' && value){
        data.spinning.options.step = value;
      }
      if(options === 'spin' && value){
        data.spinning.spin(value);
      }
    });
  };

  $(function(){
    $('[data-trigger="spinner"]').spinner();
  });
})(jQuery);
