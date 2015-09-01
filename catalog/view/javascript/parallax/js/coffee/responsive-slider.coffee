###!
  # Responsive Slider widget script
  # by w3widgets
  #
  # Author: Lukasz Kokoszkiewicz
  #
  # Copyright © w3widgets 2013 All Rights Reserved
###

do ($ = jQuery) ->
  # SLIDER CLASS DEFINITION
  # =======================
  Slider = ( element, options ) ->
    @$element = element
    @$slides = @$element.find( '.slides ul li' )
    if @$slides.length < 1
      @$slides = @$element.find( '[data-group="slides"] ul li' )
    @$prevNext = @$element.find( '[data-jump]' )
    @$pages = @$element.find( '[data-jump-to]' )
    @$slidesContainer = @$element.find( '[data-group="slides"]' )
    @$rel = @$element.find( '[data-group="slides"] ul' )
    @$rel.css 'position', 'relative'
    @slideChangeInProgress = false
    @interval = false;
    @options = options
    @current = 2
    # for external use
    @slide = 1
    
    @set 2, true # real first element
    @options.onInit.call( @ )
    @runAnimations()

    null
  
  Slider.prototype =
    getGlobalWidth: () ->
      @$element.width()
    
    updateControls: () ->
      # make current page indicator active
      @$pages.removeClass( 'active' )
      @$pages.filter( '[data-jump-to=' + ( @current - 1 ) + ']' ).addClass( 'active' )
    
    runAnimations: () ->
      r = @
      captions = $( @$slides[ @current - 1 ] ).find( '[data-animate]' )
      captions.each () ->
        $caption = $( this )
        # animate
        r.options.animations[ $caption.data( 'animate' ) ] $caption, $caption.data( 'delay' ), $caption.data( 'length' )
    
    hideAnimatedCaptions: ( slide ) ->
      $( @$slides[ slide - 1 ] ).find( '[data-animate]' ).css( {'opacity': 0} )

    calculateScroll: ( slide ) ->
      gWidth = @getGlobalWidth()
      (slide - 1) * gWidth
      
    resize: () ->
      @$rel.css 'right', @calculateScroll( @current )
    
    jump: ( slide, transitionTime = @options.transitionTime, noanimation = false ) ->
      r = @

      # if we are on the same slide do not run captions animations
      if slide == r.current
        noanimation = true

      # dont do anything when slide
      # number greaten then number of slides
      if @$slides.length >= slide and !@slideChangeInProgress
        gWidth = @getGlobalWidth()
        if !noanimation
          @hideAnimatedCaptions slide

        step = undefined;

        if @options.parallax
          @currentBgPosition = parseInt r.$slidesContainer.css 'background-position'
          @moveStartScroll = parseInt( @$rel.css( 'right' ), 10 )

          step = () ->
            position = Math.round(r.currentBgPosition-(r.moveStartScroll-parseInt(r.$rel.css( 'right' ), 10))*r.options.parallaxDistance*r.options.parallaxDirection) + 'px 0'
            r.$slidesContainer.css 'background-position', position
            
        animateOptions =
          duration: transitionTime
          step: step
          done: () ->
            if slide == 1
              r.hideAnimatedCaptions r.$slides.length-1
              r.set r.$slides.length-1
            else if slide == r.$slides.length
              r.hideAnimatedCaptions 2
              r.set 2
            else
              r.current = slide
              r.slide = slide - 1
            r.updateControls()
            if !noanimation
              r.runAnimations()
            r.options.onSlideChange.call( r )
            null
          always: () ->
            r.slideChangeInProgress = false
            null

        @slideChangeInProgress = true
        @$rel.animate {'right': @calculateScroll( slide )}, animateOptions 
      null
      
    set: ( slide, init = false ) ->
      gWidth = @getGlobalWidth()
      @$rel.css 'right', @calculateScroll( slide )
      @current = slide
      @slide = slide - 1
      @updateControls()
      null

    movestart: ( e ) ->
      if (e.distX > e.distY and e.distX < -e.distY) or (e.distX < e.distY and e.distX > -e.distY)
        e.preventDefault()
      else
        @stop()
        if @options.parallax
          @currentBgPosition = parseInt @$slidesContainer.css 'background-position'
        @hideAnimatedCaptions @current - 1
        @hideAnimatedCaptions @current + 1
        @moveStartScroll = parseInt( @$rel.css( 'right' ), 10 )
        @$rel.stop()
        @$rel.addClass('drag');
        @timeStart = new Date()
      
    move: ( e ) ->
      if @options.parallax
        position = Math.round(@currentBgPosition-e.distX*@options.parallaxDistance*@options.parallaxDirection) + 'px 0'
        @$slidesContainer.css 'background-position', position
      @$rel.css 'right', ( @moveStartScroll - e.distX )
      
    moveend: ( e ) ->
      absDist = Math.abs( e.distX )
      timeDelta = ( new Date() ).getTime() - @timeStart.getTime()
      gWidth = @getGlobalWidth()
      distLeftFrac = absDist / gWidth
      transitionTime = ( timeDelta / distLeftFrac ) * ( 1 - distLeftFrac )
      transitionTime = if transitionTime < 1000 then transitionTime else 1000
      @$rel.removeClass('drag');

      if absDist < gWidth / @options.moveDistanceToSlideChange # change slide in distance greater then 33%
        @jump( @current, transitionTime, true )
      else
        if e.distX < 0
          @next( transitionTime )
        else
          @prev( transitionTime )
    
    stop: ( permanent = true ) ->
      clearInterval( @interval )
      if permanent
        @$element.off 'mouseover'
        @$element.off 'mouseleave'
      null

    start: () ->
      r = @
      @interval = setInterval ( () -> r.next() ), @options.interval

    autoplay: () ->
      r = @
      @stop()
      @start()

      # mouse over and out events
      @$element.on 'mouseover', () ->
        r.stop( false )
      @$element.on 'mouseleave', () ->
        r.stop( false )
        r.start()

    prev: ( transitionTime = @options.transitionTime, noanimation = false ) ->
      @jump( @current - 1, transitionTime, noanimation )
      @options.onSlidePrev.call( @ )
      @options.onSlidePageChange.call( @ )
    
    next: ( transitionTime = @options.transitionTime, noanimation = false ) ->
      @jump( @current + 1, transitionTime, noanimation )
      @options.onSlideNext.call( @ )
      @options.onSlidePageChange.call( @ )
      
  # MAIN PLUGIN FUNCTION
  # ====================
  $.fn.responsiveSlider = ( option ) ->
    r = @
    # build the options variable from the data given by user
    options = $.extend {}, $.fn.responsiveSlider.defaults, typeof option == 'object' && option
    options.animations = $.fn.responsiveSlider.animations

    publicFunc =
      next: 'next'
      prev: 'prev'
      stop: 'stop'
      start: 'autoplay'

    # Removed
    # -------------------------------------
    #clearAutoplay = ( $this, interval ) ->
    #  clearInterval( interval )
    #  $this.off 'mouseover'
    #  $this.off 'mouseleave'
    #  null
    # -------------------------------------

    # INIT SLIDER
    init = ( $this ) ->
      # support for metadata plugin
      options = if $.metadata then $.extend( {}, options, $this.metadata() ) else options

      # duplicate last and first slide
      # get all slides
      slides = $this.find( 'ul li' )
      # get the first and the last one
      if slides.length > 1
        $firstSlide = $( slides[0] )
        $lastSlide  = $( slides[slides.length-1] )
        
        # put last slide before first
        $firstSlide.before( $lastSlide.clone() )
        # and the other way around
        $lastSlide.after( $firstSlide.clone() )

      $this.data 'slider', ( data = new Slider $this, options )

      if options.autoplay
        data.interval = setInterval ( () -> data.next() ), options.interval
        data.autoplay()
        # Removed
        # --------------------------
        #$this.on 'mouseover', () ->
        #  data.stop()
        #
        #$this.on 'mouseleave', () ->
        #  data.stop()
        #  data.start()
        # --------------------------
      
      $( window ).on 'resize', () ->
        data.resize()
      
      # slider next, prev click
      $this.find( '[data-jump]' ).on 'click', () ->
        data[ $( this ).data( 'jump' ) ]()
        false
      
      # slider pager click
      $this.find( '[data-jump-to]' ).on 'click', () ->
        data.jump $( this ).data( 'jump-to' ) + 1
        # Removed
        # -------------------------------------
        #options.onSlidePageChange.call( data )
        # -------------------------------------
        false
      
      # slider swipe
      if options.touch
        $this.find( '[data-group="slide"]' )
          .on 'movestart', ( e, $this ) ->
            # Removed
            # --------------------------------
            #clearAutoplay $this, data.interval
            # --------------------------------
            data.movestart e
          .on 'move', ( e ) ->
            data.move e
          .on 'moveend', ( e ) ->
            data.moveend e

    # CREATE SLIDER FOR EACH SELECTED ELEMENT
    run = () ->
      r.each () ->
        $this = $( @ )

        # create "slider" data variable
        data = $this.data 'slider'
        
        # create slider object on init
        if !data
          init $this, options
        else if typeof option == 'string'
          data[ publicFunc[option] ]()
        else if typeof option == 'number'
          data.jump Math.abs( option ) + 1
        
        $this

    # Run slider only when page is fully loaded
    if $.fn.responsiveSlider.run
      run()
    else
      $(window).on 'load', run
      $.fn.responsiveSlider.run = true

  # ANIMATION FUNCTIONS
  # ===================
  $.fn.responsiveSlider.animations =
    slideAppearRightToLeft: ( $caption, delay = 0, length = 300 ) ->
      css =
        'margin-left': 100
        'margin-right': -100
      $caption.css css
      animate = () ->
        css =
          'margin-left': 0
          'margin-right': 0
          'opacity': 1
        $caption.animate css, length
      if delay > 0 then setTimeout animate, delay else animate()

    slideAppearLeftToRight: ( $caption, delay = 0, length = 300 ) ->
      css =
        'margin-left': -100
        'margin-right': 100
      $caption.css css
      animate = () ->
        css =
          'margin-left': 0
          'margin-right': 0
          'opacity': 1
        $caption.animate css, length
      if delay > 0 then setTimeout animate, delay else animate()

    slideAppearUpToDown: ( $caption, delay = 0, length = 300 ) ->
      css =
        'margin-top': 100
        'margin-bottom': -100
      $caption.css css
      animate = () ->
        css =
          'margin-top': 0
          'margin-bottom': 0
          'opacity': 1
        $caption.animate css, length
      if delay > 0 then setTimeout animate, delay else animate()

    slideAppearDownToUp: ( $caption, delay = 0, length = 300 ) ->
      css =
        'margin-top': -100
        'margin-bottom': 100
      $caption.css css
      animate = () ->
        css =
          'margin-top': 0
          'margin-bottom': 0
          'opacity': 1
        $caption.animate css, length
      if delay > 0 then setTimeout animate, delay else animate()

  # PLUGIN DEFAULTS
  $.fn.responsiveSlider.defaults =
    autoplay: false
    interval: 5000
    touch: true
    parallax: false
    parallaxDistance: 1/10;
    parallaxDirection: 1;
    transitionTime: 300
    moveDistanceToSlideChange: 4
    onSlideChange: () ->
    onSlideNext: () ->
    onSlidePrev: () ->
    onSlidePageChange: () ->
    onInit: () ->

  $.fn.responsiveSlider.run = false
  
  spy = $('[data-spy="responsive-slider"]')

  if ( spy.length )
    opts = {}
    if autoplay = spy.data 'autoplay' then opts.autoplay = autoplay
    if interval = spy.data 'interval' then opts.interval = interval
    if parallax = spy.data 'parallax' then opts.parallax = parallax
    if parallaxDistance = spy.data 'parallax-distance' then opts.parallaxDistance = parseInt parallaxDistance, 10
    if parallaxDirection = spy.data 'parallax-direction' then opts.parallaxDirection = parseInt parallaxDirection, 10
    if !touch = spy.data 'touch' then opts.touch = touch
    if transitionTime = spy.data 'transitiontime' then opts.transitionTime = transitionTime
    spy.responsiveSlider( opts )

  null