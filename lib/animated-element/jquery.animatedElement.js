$(document).ready(function() {

    function elementIsInViewport($element)
    {
        var elementOffset = $element.offset(),
            elementTop = elementOffset.top,
            elementHeight = $element.outerHeight(),
            elementBottom = elementTop + elementHeight,
            windowTop = window.scrollY,
            windowHeight = window.innerHeight,
            windowBottom = windowTop + windowHeight;

        return elementBottom > windowTop && elementTop < windowBottom;
    }

    $(window).on('load scroll resize', function () {

        $('.animated-element').each(function() {
            var $element = $(this);

            if ( elementIsInViewport($element) ) {

                var duration = $element.data('duration') || 1000,
                    delay    = $element.data('delay')    || 0;

                if ( $element.hasClass('from-left') )   { setTimeout(function() { $element.animate({opacity: 1, left: 0},   {duration: duration}); }, delay); }
                if ( $element.hasClass('from-right') )  { setTimeout(function() { $element.animate({opacity: 1, right: 0},  {duration: duration}); }, delay); }
                if ( $element.hasClass('from-top') )    { setTimeout(function() { $element.animate({opacity: 1, top: 0},    {duration: duration}); }, delay); }
                if ( $element.hasClass('from-bottom') ) { setTimeout(function() { $element.animate({opacity: 1, bottom: 0}, {duration: duration}); }, delay); }
                if ( $element.hasClass('appear') )      { setTimeout(function() { $element.animate({opacity: 1},            {duration: duration}); }, delay); }
                if ( $element.hasClass('lift') )        { setTimeout(function() { $element.animate({opacity: 1, bottom: 0}, {duration: duration}); }, delay); }
                if ( $element.hasClass('drop') )        { setTimeout(function() { $element.animate({opacity: 1, top: 0},    {duration: duration}); }, delay); }

            }
        });

    });

});
