$(document).ready(function() {

    $('.animate-element').bind('inview', function() {
        var $this = $(this),
            duration = $this.data('duration') || 1000;

        if ( $this.hasClass('from-left') ) { $this.animate({left: 0}, duration); }
        if ( $this.hasClass('from-right') ) { $this.animate({right: 0}, duration); }
        if ( $this.hasClass('from-top') ) { $this.animate({top: 0}, duration); }
        if ( $this.hasClass('from-bottom') ) { $this.animate({bottom: 0}, duration); }
        if ( $this.hasClass('appear') ) { $this.fadeTo(duration, 1); }
        if ( $this.hasClass('lift') ) { $this.fadeTo(duration/2, 1); $this.animate({bottom: 0}, duration); }
        if ( $this.hasClass('drop') ) { $this.fadeTo(duration/2, 1); $this.animate({top: 0}, duration); }
    });

});