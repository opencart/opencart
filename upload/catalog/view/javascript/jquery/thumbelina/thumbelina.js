/*
    Thumbelina Content Slider
    V1.0 Rev 1302190900

    A lightweight horizontal and vertical content slider designed for image thumbnails.
    http://www.starplugins.com/thumbelina

    Developed by Star Plugins
    http://www.starplugins.com

    Copyright 2013, Star Plugins, www.starplugins.com
    License: GNU General Public License, version 3 (GPL-3.0)
    http://www.opensource.org/licenses/gpl-3.0.html
*/
;(function($) {
    $.fn.Thumbelina = function(settings) {
        var $container = this,      // Handy reference to container.
            $list = $('ul',this),   // Handy reference to the list element.
            moveDir = 0,            // Current direction of movement.
            pos = 0,                // Current actual position.
            destPos = 0,            // Current destination position.
            listDimension = 0,      // Size (width or height depending on orientation) of list element.
            idle = 0,
            outerFunc,
            orientData;              // Stores function calls and CSS attribute for horiz or vert mode.
        
        // Add thumblina CSS class, and create an inner wrapping container, within which the list will slide with overflow hidden.
        $list.addClass('thumbelina').wrap('<div style="position:absolute;overflow:hidden;width:100%;height:100%;">');
        // Create settings by merging user settings into defaults.
        settings = $.extend({}, $.fn.Thumbelina.defaults, settings);
        
        // Depending on vertical or horizontal, get functions to call and CSS attribute to change.
        if(settings.orientation === 'vertical') 
            orientData = {outerSizeFunc:  'outerHeight', cssAttr: 'top', display: 'block'};
        else
            orientData = {outerSizeFunc:  'outerWidth', cssAttr: 'left', display: 'inline-block'};
       
        // Apply display type of list items.
        $('li',$list).css({display: orientData.display});
        
        // Function to bind events to buttons.
        var bindButEvents = function($elem,dir) {
            $elem.bind('mousedown mouseup touchend touchstart',function(evt) {
                if (evt.type==='mouseup' || evt.type==='touchend') moveDir = 0;
                else moveDir = dir;
                return false;
            });
        };
        
        // Bind the events.
        bindButEvents(settings.$bwdBut,1);
        bindButEvents(settings.$fwdBut,-1);
        
        // Store ref to outerWidth() or outerHeight() function.
        outerFunc = orientData.outerSizeFunc; 
   
        // Function to animate. Moves the list element inside the container.
        // Does various bounds checks.
        var animate = function() {
            var minPos;
            
            // If no movement or resize for 100 cycles, then go into 'idle' mode to save CPU.
            if (!moveDir && pos === destPos && listDimension === $container[outerFunc]() ) {  
                idle++;
                if (idle>100) return;
            }else {
                 // Make a note of current size for idle comparison next cycle.
                listDimension = $container[outerFunc]();
                idle = 0;
            }
          
            // Update destination pos.
            destPos += settings.maxSpeed * moveDir;
     
            // Work out minimum scroll position.
            // This will also cause the thumbs to drag back out again when increasing container size.
            minPos = listDimension - $list[outerFunc]();
            
          
            // Minimum pos should always be <= 0;
            if (minPos > 0) minPos = 0;
            // Bounds check (maximum advance i.e list moving left/up)
            if (destPos < minPos) destPos = minPos;
            // Bounds check (maximum retreat i.e list moving right/down)
            if (destPos>0) destPos = 0;
            
            // Disable/enable buttons depending min/max extents.
            if (destPos === minPos) settings.$fwdBut.addClass('disabled');
            else settings.$fwdBut.removeClass('disabled');
            if (destPos === 0) settings.$bwdBut.addClass('disabled');
            else settings.$bwdBut.removeClass('disabled');
            
            // Animate towards destination with a simple easing calculation.
            pos += (destPos - pos) / settings.easing;
            
            // If within 1000th of a pixel to dest, then just 'snap' to exact value.
            // Do this so pos will end up exactly == destPos (deals with rounding errors).
            if (Math.abs(destPos-pos)<0.001) pos = destPos;
            
            $list.css(orientData.cssAttr, Math.floor(pos));
        };
        
        setInterval(function(){
            animate();
        },1000/60);
    };
    
    $.fn.Thumbelina.defaults = {
        orientation:    "horizontal",   // Orientation mode, horizontal or vertical.
        easing:         1,              // Amount of easing (min 1) larger = more drift.
        maxSpeed:       15,              // Max speed of movement (pixels per cycle).
        $bwdBut:   null,                // jQuery element used as backward button.
        $fwdBut:    null                // jQuery element used as forward button.
    };
    
})(jQuery);

