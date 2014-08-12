/*
 * WPBP.js
 */

$(function() {

    $(document).ready(function() {

        $('.ir, .imageReplace').imageReplace();

        $('form.ajax, form.ajax-form').ajaxForm();

        $('.smartbox').smartbox();

        $('.simpleSlider').simpleSlider();

        $('.simpleCarousel').simpleCarousel();

        $('.valign, .vAlign').vAlign();

        $('.fullCenter').center();

        $('ul.dropdownNav').dropdownNav();

        $('.same-height-as').sameHeightAs();

        $('body').wpbpModal();

        $('.wpbp-tabs').wpbpTabs();

    });

    $(window).bind('load resize', function() {

        $('.valign, .vAlign').vAlign();

    });

});

/*
 * imageReplace
 *
 * Replaces elements with the 'ir' class with an image specified by 'data-ir'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

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

});


/*
 * vAlign
 *
 * Aligns vertically elements with the 'valign' class with reference either the parent element
 * or another element specified by 'data-ref'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

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

});

/*
 * ajaxForm
 *
 * Validate and send a form asynchronously
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.1
 * @package wpboilerplate
 */

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
            var formEnctype = $form.attr('enctype');

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

                // validation settings
                var validClass = 'valid success';
                var invalidClass = 'invalid error';

                // validate all required fields
                $form.find('.required').each(function() {

                    var $input = $(this);
                    var $group = $input.parents('.control-group').first();

                    if ( $input.val() == '' ) {
                        inputError = true;
                        $input.removeClass( validClass ).addClass( invalidClass );
                        $group.removeClass( validClass ).addClass( invalidClass );
                    }

                    else {
                        $input.removeClass( invalidClass ).addClass( validClass );
                        $group.removeClass( invalidClass ).addClass( validClass );
                    }

                });

                // validate emails
                $form.find('.valid.email, .valid[type="email"]').each(function() {

                    var $input = $(this);

                    var emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                    if ( !emailRegex.test( $input.val() ) ) {
                        inputError = true;
                        $input.removeClass( validClass ).addClass( invalidClass ).parents('.control-group').removeClass( validClass ).addClass( invalidClass );
                    }

                    else {
                        $input.removeClass( invalidClass ).addClass( validClass ).parents('.control-group').removeClass( invalidClass ).addClass( validClass );
                    }

                });

                if ( !inputError ) {

                    $form.trigger('valid');

                    $.ajax({
                        type:	     formMethod,
                        url:	     formAction,
                        data:	     $form.serialize(),
                        contentType: formEnctype,
                        statusCode: {
                            200: function() {
                                $formSuccess.fadeIn();
                                $formFields.hide();
                                $form.trigger('success').addClass('sent');
                                // trigger google analytics
                                if ( typeof _gaq != 'undefined' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Success', formId]);
                                }
                            },
                            400: function() {
                                $formWarning.fadeIn();
                                $form.trigger('warning');
                                // trigger google analytics
                                if ( typeof _gaq != 'undefined' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Warning', formId]);
                                }
                            },
                            500: function() {
                                $form.trigger('error');
                                $formError.fadeIn();
                                // trigger google analytics
                                if ( typeof _gaq != 'undefined' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Error', formId]);
                                }
                            }
                        }
                    });

                }

                else {
                    $form.trigger('invalid');
                    $formSuccess.hide();
                    $formWarning.fadeIn();
                    $formError.error();
                }

                // hide the loading
                $formLoading.hide();

            });

            // show that the form is ajax-enabled
            $form.trigger('enabled').addClass('ajax-enabled');

        });
    };

});


/*
 * center
 *
 * Centers an element in the window
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.center = function() {

        return this.each(function() {

            var $this      = $(this);
            var thisWidth  = $this.outerWidth(true);
            var thisHeight = $this.outerHeight(true);

            var $window      = $(window);
            var windowWidth  = $window.width();
            var windowHeight = $window.height();
            var scrollTop    = $window.scrollTop();

            var offsetTop  = Math.max( Math.round( ( windowHeight - thisHeight ) / 2 ) + scrollTop, 0 );
            var offsetLeft = Math.max( Math.round( ( windowWidth - thisWidth ) / 2 ), 0 );

            $this.css({
                position: 'absolute',
                top: offsetTop + 'px',
                left: offsetLeft + 'px'
            });

        });

    };

});


/*
 * simpleSlider
 *
 * Simplest jQuery slider possible. Takes an element and cycles through its children with fadeIn/fadeOut effects.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.0
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.simpleSlider = function() {

        return this.each(function() {

            var $this      = $(this),
                period     = $this.data('period') || 5000,
                fxSpeed    = $this.data('fx-speed') || 500,
                hoverPause = $this.data('hover-pause') || "no",
                slideshow  = $this.data('slideshow') || "yes",
                $slides    = $this.find('.slides').children(),
                N          = $slides.size(),
                $fireNext  = $this.find('.next'),
                $firePrev  = $this.find('.prev'),
                paused     = false,
                now        = new Date(),
                fireTime   = new Date(0),
                skip       = false;

            if ( hoverPause == "yes" ) {
                $this.hover(
                    function() { paused = true; },
                    function() { paused = false; }
                );
            }

            if ( N <= 1 ) {
                slideshow = "no";
                $fireNext.hide();
                $firePrev.hide();
            }

            $slides.hide().first().show().addClass('current');

            $this.bind('fireNext', function() {
                var $current = $slides.filter('.current'),
                    $next = $current.next().size() ? $current.next() : $slides.first();

                $current.fadeOut(fxSpeed, function() {
                    $next.fadeIn(fxSpeed).addClass('current');
                }).removeClass('current');

                fireTime = new Date();
            });

            $this.bind('firePrev', function() {
                var $current = $slides.filter('.current'),
                    $prev = $current.prev().size() ? $current.prev() : $slides.last();

                $current.fadeOut(fxSpeed, function() {
                    $prev.fadeIn(fxSpeed).addClass('current');
                }).removeClass('current');

                fireTime = new Date();
            });

            $fireNext.click(function(e) {
                e.preventDefault();
                $this.trigger('fireNext');
            });

            $firePrev.click(function(e) {
                e.preventDefault();
                $this.trigger('firePrev');
            });

            setInterval(function() {
                now = new Date();
                skip = ( now.getTime() - fireTime.getTime() ) < period;

                if ( !paused && !skip && slideshow == "yes" ) {
                    $this.trigger('fireNext');
                }
            }, period);

        });

    };

});


/*
 * simpleCarousel
 *
 * Simple jQuery Carousel.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.0
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.simpleCarousel = function() {

        return this.each(function() {

            var $this      = $(this),
                $container = $this.find('.container'),
                $items     = $container.children(),
                speed      = $this.data('speed') || 50,
                hoverPause = $this.data('hover-pause') || "yes";

            $this.css('overflow', 'hidden');

            var infinity = 1E6,
                duration = infinity / speed;

            console.log(duration);

            $container.animate({marginLeft: -infinity}, duration, 'linear', function() {});
        });

    };

});


/*
 * smartbox
 *
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.1
 * @package wpboilerplate
 */

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

});


/*
 * dropdownNav
 *
 * Converts a list into a select dropdown for navigation
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.99
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.dropdownNav = function() {

        return this.each(function() {

            var $list = $(this).hide();
            var $select = $( document.createElement('select') ).insertBefore( $(this) ).addClass( $(this).attr('class') );

            $('a', this).each(function() {
                var	$option = $( document.createElement('option') ).appendTo( $select ).val( this.href ).html( $(this).html() );
                if ( $(this).parent('li').hasClass('current-menu-item') ) {
                    $option.attr('selected', 'selected');
                }
            });

            $list.remove();

            $select.change(function() {
                window.location.href = $(this).val();
            });

        });

    };

});


/*
 * sameHeightAs
 *
 * Makes an element the same height as another
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.1
 * @package wpboilerplate
 */

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

});


/*
 * wpbpModal
 *
 *
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.2
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.wpbpModal = function() {

        return this.each(function() {

            var fadeDuration = 250;
            var blanketOpacity = 0.5;

            var $blanket = $('#wpbp-modal-blanket');
            var $anchors = $('a[href^="#"]');
            var $modalBoxes = $('.wpbp-modal-box');

            $anchors.each(function() {

                var $this = $(this);
                var href = $this.attr('href');
                var $href = $(href);

                if ( $href.size() == 1 && $href.hasClass('wpbp-modal-box') ) {
                    $this.data('wpbp-target-modal-box', href).addClass('wpbp-modal-trigger');
                }

            });

            $modalBoxes.bind('open', function() {

                var $this = $(this);

                $blanket.fadeTo(fadeDuration, blanketOpacity, function() {

                    $this.fadeIn(fadeDuration, function() {

                        $this.trigger('opened').addClass('wpbp-modal-box-opened');

                    });

                });

            }).bind('close', function() {

                    var $this = $(this);

                    $this.fadeOut(fadeDuration, function() {

                        $blanket.fadeOut(fadeDuration);

                        $this.trigger('closed').removeClass('wpbp-modal-box-opened');

                    });

                }).center();

            $('.wpbp-modal-trigger').click(function(e) {

                e.preventDefault();

                var $this = $(this);
                var target = $this.data('wpbp-target-modal-box');
                var $target = $( target );

                if ( $target.size() == 0 || !$target.hasClass('wpbp-modal-box') ) {
                    console.log('Error: Target "' + target + '" is not a modal box!');
                }
                else {

                    if ( $this.hasClass('wpbp-modal-open') ) {
                        $target.trigger('open');
                    }
                    else if ( $this.hasClass('wpbp-modal-close') ) {
                        $target.trigger('close');
                    }
                    else if ( $target.hasClass('wpbp-modal-box-opened') ) {
                        $target.trigger('close');
                    }
                    else {
                        $target.trigger('open');
                    }

                }

            });

            // trigger open on boxes open by default
            $modalBoxes.filter('.wpbp-modal-open').trigger('open');

            // close modal boxes when the blanket is clicked
            $blanket.click(function() {
                $modalBoxes.trigger('close');
            });

            // close modal boxes when the ESC key is pressed
            $(window).keyup(function(e) {
                if ( e.which == 27 ) {
                    $modalBoxes.trigger('close');
                }
            });

        });

    };

});


/*
 * wpbpTabs
 *
 *
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 0.1
 * @package wpboilerplate
 */

$(function() {

    "use strict"; // jshint ;_;

    jQuery.fn.wpbpTabs = function() {

        return this.each(function() {

            var $this     = $(this),
                $anchors  = $this.children('.tab-anchors').find('a'),
                $contents = $this.children('.tab-contents').children('.tab');

            $contents.hide().filter('.active').show();

            $anchors.bind('fire', function() {
                var $anchor       = $(this),
                    anchorTarget  = $anchor.attr('href'),
                    $anchorTarget = $(anchorTarget);

                $contents.removeClass('active').hide();
                $anchorTarget.addClass('active').show();

                $anchors.removeClass('active');
                $anchor.addClass('active');
            });

            $anchors.click(function(e) {
                e.preventDefault();
                $(this).trigger('fire');
            });

            if ( $this.hasClass('slideshow') ) {
                var speed = $this.data('speed') ? $this.data('speed') : 10000,
                    skip = 0,
                    inView = true;

                var slideshow = window.setInterval(function() {
                    if ( skip < 0 && inView ) {
                        var $nextAnchor = $anchors.filter('.active').parent().next().find('a');
                        if ( $nextAnchor.size() == 0 ) $nextAnchor = $anchors.first()
                        $nextAnchor.trigger('fire');
                    } else {
                        --skip;
                    }
                }, speed);

                $anchors.click(function(e) {
                    skip = 2;
                });

                var $window = $(window),
                    thisHeight = parseInt( $this.outerHeight(true) ),
                    thisOffset = parseInt( $this.offset().top );

                $window.scroll(function() {
                    inView = ( $window.scrollTop() + $window.height() ) > thisOffset && $window.scrollTop() < ( thisOffset + thisHeight );
                });
            }

            if ( $this.hasClass('same-height') ) {
                var maxHeight = 0;
                $contents.each(function() {
                    if ( $(this).height() > maxHeight ) {
                        maxHeight = $(this).height();
                    }
                });
                $contents.height(maxHeight);
            }

        });

    };

});
