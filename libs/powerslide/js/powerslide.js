(function( $ ) {
	$.fn.powerslide = function() {
		return this.each(function() {
			
			// set core objects as jQuery variables
			
			var $powerslide = $(this);
			
			var $viewport = $powerslide.find('.viewport');
			var $slides = $viewport.find('.slides');
			var $slide = $slides.children();
			
			var $play = $powerslide.find('.play').size() ? $powerslide.find('.play') : $powerslide.append('<a href="#" class="play hidden"></a>').find('.play');
			var $pause = $powerslide.find('.pause').size() ? $powerslide.find('.pause') : $powerslide.append('<a href="#" class="pause hidden"></a>').find('.pause');
			
			var $prev = $powerslide.find('.prev').size() ? $powerslide.find('.prev') : $powerslide.append('<a href="#" class="prev hidden"></a>').find('.prev');
			var $next = $powerslide.find('.next').size() ? $powerslide.find('.next') : $powerslide.append('<a href="#" class="next hidden"></a>').find('.next');
			
			var $goto = $powerslide.find('.goto');
			
			// set options
			
			var autoplay = $powerslide.data('autoplay') || 'no';
			var hoverPause = $powerslide.data('hover-pause') || ( ( autoplay == 'yes' ) ? 'yes' : 'no' );
			var period = $powerslide.data('period') || 5000;
			var speed = $powerslide.data('speed') || 1000;
			
			// initialize slider
			
			var N = $slide.size(); // number of slides
			
			$prev.addClass('disabled');
			( N > 1 ) ? $next.addClass('enabled') : $next.addClass('disabled');
			
			var offset = 0; // css left offset
			
			var totalOffset = 0;
			var maxSlideHeight = 0;
			
			$slide.each(function(i) {
				var $this = $(this);
				$this.data('index', i);
				$this.addClass('slide').addClass('slide_' + i);
				$this.data('offset', totalOffset);
				totalOffset += $this.outerWidth(true);
				maxSlideHeight = Math.max(maxSlideHeight, $this.outerHeight(true));
			});
			
			$slides.width(totalOffset);
			$viewport.height(maxSlideHeight);
			
			var csi = 0; // current slide index
			
			// actions
			
			// pause on hover
			if ( hoverPause == 'yes' ) {
				$viewport.hover(
					function() {
						$powerslide.trigger('pause');
					}, function() {
						$powerslide.trigger('play');
					}
				);
			}
			
			// go to
			
			$powerslide.bind('goto', function(e, index) {
			
				if ( index < 0 ) { index = 0; }
				if ( index > N - 1 ) { index = N - 1; }
				
				$powerslide.trigger('sliding');
				
				var offset = -$slide.eq(index).data('offset');
				
				$slides.stop(true,true).animate(
					{ left: offset + 'px' },
					speed,
					function() {
						csi = index;
						
						$slide.removeClass('current').eq(csi).addClass('current');
						$goto.removeClass('active').filter('.slide_' + csi).addClass('active');
						
						$powerslide.trigger('slided');
						
						( csi == N - 1 ) ? $next.addClass('disabled').removeClass('enabled') : $next.addClass('enabled').removeClass('disabled');
						( csi == 0 ) ? $prev.addClass('disabled').removeClass('enabled') : $prev.addClass('enabled').removeClass('disabled');
					}
				);
			});
			
			$goto.each(function() {
				var $this = $(this);
				var ts = $this.attr('href');
				var $ts = $slides.find(ts);
				if ( $ts.size() == 1 ) {
					var tsi = $ts.data('index');
					$this.data('tsi', tsi);
					$this.addClass('slide_' + tsi);
				}
				else {
					$this.addClass('invalid');
				}
			});
			
			$goto.filter('.slide_' + csi).addClass('active');
			
			$goto.click(function(e) {
				e.preventDefault();
				var $this = $(this);
				if ( !$this.hasClass('invalid') ) {
					var tsi = $this.data('tsi');
					$powerslide.trigger('goto', [tsi]);
				}
			});
			
			// next
			
			$powerslide.bind('next', function(e) {
				var nsi = csi + 1; // next slide index
				$powerslide.trigger('goto', [nsi]);
			});
			
			$next.click(function(e) {
				e.preventDefault();
				if ( $(this).hasClass('enabled') ) {
					$powerslide.trigger('next');
				}
			});
			
			// prev
			
			$powerslide.bind('prev', function(e) {
				var psi = csi - 1; // prev slide index
				$powerslide.trigger('goto', [psi]);
			});
			
			$prev.click(function(e) {
				e.preventDefault();
				if ( $(this).hasClass('enabled') ) {
					$powerslide.trigger('prev');
				}
			});
			
			// play
			
			var slideshow;
			
			$powerslide.bind('play', function(e) {
				slideshow = setInterval(function() {
					var nsi = ( csi == N - 1 ) ? 0 : csi + 1;
					$powerslide.trigger('goto', [nsi]);
				}, period);
				$play.addClass('disabled').removeClass('enabled');
			});
			
			if ( autoplay == 'yes' ) {
				$powerslide.trigger('play');
			}
			
			$play.click(function(e) {
				e.preventDefault();
				$powerslide.trigger('play');
			});
			
			// pause
			
			$powerslide.bind('pause', function(e) {
				clearInterval(slideshow);
				$pause.addClass('disabled').removeClass('enabled');
			});
			
			$pause.click(function(e) {
				e.preventDefault();
				$powerslide.trigger('pause');
			});
			
			// initialized by now
			$powerslide.trigger('init');
			
		});
	};
})( jQuery );