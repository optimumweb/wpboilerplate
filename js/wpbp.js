/*
 * WPBP.js
 */

!function ($) {

	$(function () {

		$(document).ready(function() {

			$('.ir, .imageReplace').imageReplace();

			$('form.ajax, form.ajax-form').ajaxForm();

			$('.smartbox').smartbox();

			$('.simpleSlider').simpleSlider();

			$('.valign, .vAlign').vAlign();

			$('ul.dropdownNav').dropdownNav();

			$('.same-height-as').sameHeightAs();

		});

		$(window).bind('load resize', function() {

			$('.valign, .vAlign').vAlign();

		});

	})

}(window.jQuery);


/*
 * imageReplace
 *
 * Replaces elements with the 'ir' class with an image specified by 'data-ir'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.imageReplace = function() {

			return this.each(function() {

				var $this = $(this);
				var ir = $this.data('ir');

				if ( typeof ir != 'undefined' ) {
					$this.css('background-image', 'url(' + ir + ')');
				}

			});

		};

	})

}(window.jQuery);


/*
 * vAlign
 *
 * Aligns vertically elements with the 'valign' class with reference either the parent element
 * or another element specified by 'data-ref'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.vAlign = function() {

			return this.each(function() {

				var $this = $(this);
				var $ref = ( typeof $this.data('ref') != 'undefined' ) ? $($this.data('ref')) : $this.parent();
				var thisHeight = $this.outerHeight(), refHeight = $ref.outerHeight();
				var offset = Math.round( ( refHeight - thisHeight ) / 2 );

				$this.css('top', offset + 'px');
				$this.addClass('valigned');

			});

		};

	})

}(window.jQuery);

/*
 * ajaxForm
 *
 * Validate and send a form asynchronously
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.ajaxForm = function() {

			return this.each(function() {

				// define form elements
				var $form = $(this);
				var $formFields = $form.find('.fields');
				var $formSuccess = $form.find('.success');
				var $formError = $form.find('.error');
				var $formWarning = $form.find('.warning');
				var $formLoading = $form.find('.loading');

				// define form properties
				var formId = $form.attr('id');
				var formAction = $form.attr('action');
				var formMethod = $form.attr('method');

				// hide response messages and loading
				$formSuccess.hide();
				$formWarning.hide();
				$formError.hide();
				$formLoading.hide();

				// track form start
				var formStarted = false;
				$formFields.find('input, textarea').keypress(function() {
					if ( !formStarted ) {
						formStarted = true;
						$form.addClass('started');
						// trigger google analytics
						if ( typeof _gaq != 'undefined' ) {
							_gaq.push(['_trackEvent', 'AjaxForms', 'Start', formId]);
						}
					}
				});

				$form.submit(function(e) {

					// prevent default page load
					e.preventDefault();

					$formSuccess.hide();
					$formWarning.hide();
					$formError.hide();

					// show that we are working in the background
					$formLoading.show();

					// assume no errors in submission
					var inputError = false;

					// validate all required fields
					$form.find('.required').each(function() {

						var $input = $(this);

						if ( $input.val() == '' ) {
							inputError = true;
							$input.removeClass('valid').addClass('invalid');
						}

						else {
							$input.removeClass('invalid').addClass('valid');
						}

					});

					// validate emails
					$form.find('.valid.email, .valid[type="email"]').each(function() {

						var $input = $(this);

						var emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

						if ( !emailRegex.test( $input.val() ) ) {
							inputError = true;
							$input.removeClass('valid').addClass('invalid');
						}

						else {
							$input.removeClass('invalid').addClass('valid');
						}

					});

					if ( !inputError ) {

						$.ajax({
							type:	formMethod,
							url:	formAction,
							data:	$form.serialize(),
							statusCode: {
								200: function() {
									$formSuccess.fadeIn();
									$formFields.hide();
									$form.addClass('sent').trigger('ajax-form-sent');
									// trigger google analytics
									if ( typeof _gaq != 'undefined' ) {
										_gaq.push(['_trackEvent', 'AjaxForms', 'Success', formId]);
									}
								},
								400: function() {
									$formWarning.fadeIn();
									// trigger google analytics
									if ( typeof _gaq != 'undefined' ) {
										_gaq.push(['_trackEvent', 'AjaxForms', 'Warning', formId]);
									}
								},
								500: function() {
									$formError.fadeIn();
									// trigger google analytics
									if ( typeof _gaq != 'undefined' ) {
										_gaq.push(['_trackEvent', 'AjaxForms', 'Error', formId]);
									}
								}
							},
							success: function(data) {
								console.log( data );
							}
						});

					}

					else {
						console.log('Input error detected!');
						$formSuccess.hide();
						$formWarning.fadeIn();
						$formError.error();
					}

					// hide the loading
					$formLoading.hide();

				});

				// show that the form is ajax-enabled
				$form.addClass('ajax-enabled');

			});
		};

	})

}(window.jQuery);


/*
 * center
 *
 * Centers an element in the window
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.center = function() {

			return this.each(function() {

				var $this = $(this);
				var thisWidth = $this.outerWidth(true);
				var thisHeight = $this.outerHeight(true);

				var $window = $(window);
				var windowWidth = $window.width();
				var windowHeight = $window.height();
				var scrollTop = $window.scrollTop();

				var offsetTop = Math.round( ( windowHeight - thisHeight ) / 2 ) + scrollTop;
				var offsetLeft = Math.round( ( windowWidth - thisWidth ) / 2 );

				$this.css({
					position: 'absolute',
					top: offsetTop + 'px',
					left: offsetLeft + 'px'
				});

			});

		};

	})

}(window.jQuery);


/*
 * simpleSlider
 *
 * Simplest jQuery slider possible. Takes an element and cycles through its children with fadeIn/fadeOut effects.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.0
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.simpleSlider = function() {

			return this.each(function() {

				var $this = $(this);
				var period = $this.data('period') || 5000;
				var fxSpeed = $this.data('fx-speed') || 500;
				var $slides = $this.children();
				var N = $slides.size();
				var paused = false;

				$this.hover(
					function() { paused = true; },
					function() { paused = false; }
				);

				var i = 1;

				$slides.hide().first().show();

				setInterval(function() {
					if ( !paused ) {
						i = ( i == N ) ? 1 : (i + 1);
						$slides.fadeOut(fxSpeed).eq(i-1).delay(fxSpeed).fadeIn(fxSpeed);
					}
				}, period);

			});

		};

	})

}(window.jQuery);


/*
 * smartbox
 *
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.smartbox = function(options, callback) {

			return this.each(function() {

				var $this = $(this);
				var $title = $this.find('.title');
				var $content = $this.find('.content');

				var id = $this.attr('id');

				// class=ajax: content loads async.
				if ( $this.hasClass('ajax') ) {

					var $ajaxTrigger = $this.find('a.ajax-trigger').first();

					var ajaxSrc = $this.data('src').replace('#',' #') || $ajaxTrigger.attr('href').replace('#',' #');

					// class=ajax+lazy: content loads async. on trigger
					if ( $this.hasClass('lazy') ) {

						$ajaxTrigger.click(function(e) {

							e.preventDefault();

							if ( !$this.hasClass('ajax-loaded') ) {

								$content.load(ajaxSrc, function() {
									$this.addClass('ajax-loaded');
								});

							}

						});
					}

					// content loads on page load
					else {
						$content.load(ajaxSrc, function() {
							$this.addClass('ajax-loaded');
						});
					}

				}

				// class=collapsible: box can be collapsed/opened
				if ( $this.hasClass('collapsible') ) {

					var $collapseTrigger = $this.find('.collapse-trigger, .title');

					var collapseTriggerTarget = function() {
						$collapseTrigger.each(function() {
							if ( typeof $(this).attr('target') != 'undefined' ) {
								return $(this).attr('target');
							}
						});
						return null;
					}

					if ( collapseTriggerTarget() != null ) {
						var $collapseTriggerTarget = $( collapseTriggerTarget() );
						if ( $collapseTriggerTarget.size() > 0 ) {
							var $content = $collapseTriggerTarget;
						}
					}

					if ( $content.is(':visible') ) {
						$this.addClass('open').removeClass('closed');
						$content.show();
					}

					else {
						$this.addClass('closed').removeClass('open');
						$content.hide();
					}

					if ( typeof id != 'undefined' && typeof window.location.hash != 'undefined' && window.location.hash == '#' + id ) {
						$this.addClass('open').removeClass('closed');
						$content.slideDown();
					}

					$collapseTrigger.click(function(e) {

						e.preventDefault();

						if ( $this.hasClass('open') ) {
							$content.slideUp('slow', function() {
								$this.addClass('closed').removeClass('open');
							});
						}

						else {
							$content.slideDown('slow', function() {
								$this.addClass('open').removeClass('closed');
							});
							if ( typeof id != 'undefined' ) {
								$this.attr('id', id + '-tmp');
								window.location.hash = id;
								$this.attr('id', id);
							}
						}

					});

				}

			});

		};

	})

}(window.jQuery);


/*
 * dropdownNav
 *
 * Converts a list into a select dropdown for navigation
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.99
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.dropdownNav = function() {

			return this.each(function() {

				var $list = $(this);
				var $select = $( document.createElement('select') ).insertBefore( $(this).hide() ).addClass( $(this).attr('class') );

				$('a', this).each(function() {
					var	$option = $( document.createElement('option') ).appendTo( $select ).val( this.href ).html( $(this).html() );
				});

				$list.remove();

				$select.change(function() {
					window.location.href = $(this).val();
				});

			});

		};

	})

}(window.jQuery);


/*
 * sameHeightAs
 *
 * Makes an element the same height as another
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.1
 * @package wpboilerplate
 */

!function($) {

	$(function() {

		"use strict"; // jshint ;_;

		jQuery.fn.sameHeightAs = function() {

			return this.each(function() {

				var $this = $(this);
				var ref = $this.data('ref');

				if ( ref && $(ref).size() ) {
					var $ref = $(ref);
					$this.height( $ref.height() );
				}

			});

		};

	})

}(window.jQuery);
