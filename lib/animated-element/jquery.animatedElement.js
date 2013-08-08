$(document).ready(function() {

    $('.animated-element').bind('inview', function() {
        var $this    = $(this),
            duration = $this.data('duration') || 1000,
            delay    = $this.data('delay')    || 0;

        if ( $this.hasClass('from-left') )   { setTimeout(function() { $this.animate({opacity: 1, left: 0},   {duration: duration}); }, delay); }
        if ( $this.hasClass('from-right') )  { setTimeout(function() { $this.animate({opacity: 1, right: 0},  {duration: duration}); }, delay); }
        if ( $this.hasClass('from-top') )    { setTimeout(function() { $this.animate({opacity: 1, top: 0},    {duration: duration}); }, delay); }
        if ( $this.hasClass('from-bottom') ) { setTimeout(function() { $this.animate({opacity: 1, bottom: 0}, {duration: duration}); }, delay); }
        if ( $this.hasClass('appear') )      { setTimeout(function() { $this.animate({opacity: 1},            {duration: duration}); }, delay); }
        if ( $this.hasClass('lift') )        { setTimeout(function() { $this.animate({opacity: 1, bottom: 0}, {duration: duration}); }, delay); }
        if ( $this.hasClass('drop') )        { setTimeout(function() { $this.animate({opacity: 1, top: 0},    {duration: duration}); }, delay); }
    });

    $('.animated-children').bind('inview', function() {
        var $this     = $(this),
            $children = $this.children(),
            duration  = $this.data('duration') || 1000,
            delay     = $this.data('delay')    || 0;

        if ( $this.hasClass('from-left') )   { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, left: 0},   {duration: duration}); }); }, delay); }
        if ( $this.hasClass('from-right') )  { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, right: 0},  {duration: duration}); }); }, delay); }
        if ( $this.hasClass('from-top') )    { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, top: 0},    {duration: duration}); }); }, delay); }
        if ( $this.hasClass('from-bottom') ) { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, bottom: 0}, {duration: duration}); }); }, delay); }
        if ( $this.hasClass('appear') )      { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1},            {duration: duration}); }); }, delay); }
        if ( $this.hasClass('lift') )        { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, bottom: 0}, {duration: duration}); }); }, delay); }
        if ( $this.hasClass('drop') )        { setTimeout(function() { $children.each(function() { $(this).animate({opacity: 1, top: 0},    {duration: duration}); }); }, delay); }
    });

});