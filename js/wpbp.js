/*
 * WPBP.js
 */

(function($, window, document) {

    $(function() {

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

        $('.wpbp-parallax').wpbpParallax();

        $('.wpbp-responsive-nav').wpbpResponsiveNav();

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

    $.fn.imageReplace = function() {

        return this.each(function() {

            var $this = $(this),
                ir    = $this.data('ir');

            if ( typeof ir != 'undefined' ) {
                $this.css('background-image', 'url(' + ir + ')');
            }

        });

    };


    /*
     * vAlign
     *
     * Aligns vertically elements with the 'valign' class with reference either the parent element
     * or another element specified by 'data-ref'.
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 1.1
     * @package wpboilerplate
     */

    $.fn.vAlign = function() {

        return this.each(function() {

            var $this      = $(this),
                $ref       = typeof $this.data('ref') != 'undefined' ? $($this.data('ref')) : $this.parent(),
                thisHeight = $this.outerHeight(), refHeight = $ref.outerHeight(),
                offset     = Math.round( ( refHeight - thisHeight ) / 2 );

            $this.css('top', offset + 'px').addClass('valigned');

        });

    };


    /*
     * ajaxForm
     *
     * Validate and send a form asynchronously
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 2.1
     * @package wpboilerplate
     */

    $.fn.ajaxForm = function() {

        return this.each(function() {

            // define form elements
            var $form        = $(this),
                $formFields  = $form.find('.fields'),
                $formSuccess = $form.find('.success'),
                $formError   = $form.find('.error'),
                $formWarning = $form.find('.warning'),
                $formLoading = $form.find('.loading');

            // define form properties
            var formId      = $form.attr('id'),
                formAction  = $form.attr('action'),
                formMethod  = $form.attr('method'),
                formEnctype = $form.attr('enctype');

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
                    if ( typeof _gaq == 'object' ) {
                        _gaq.push(['_trackEvent', 'AjaxForms', 'Start', formId]);
                    }
                    else if ( typeof ga == 'function' ) {
                        ga('send', 'event', 'AjaxForms', 'Start', formId);
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
                                if ( typeof _gaq == 'object' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Success', formId]);
                                }
                                else if ( typeof ga == 'function' ) {
                                    ga('send', 'event', 'AjaxForms', 'Success', formId);
                                }
                            },
                            400: function() {
                                $formWarning.fadeIn();
                                $form.trigger('warning');
                                // trigger google analytics
                                if ( typeof _gaq == 'object' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Warning', formId]);
                                }
                                else if ( typeof ga == 'function' ) {
                                    ga('send', 'event', 'AjaxForms', 'Warning', formId);
                                }
                            },
                            500: function() {
                                $form.trigger('error');
                                $formError.fadeIn();
                                // trigger google analytics
                                if ( typeof _gaq == 'object' ) {
                                    _gaq.push(['_trackEvent', 'AjaxForms', 'Error', formId]);
                                }
                                else if ( typeof ga == 'function' ) {
                                    ga('send', 'event', 'AjaxForms', 'Error', formId);
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


    /*
     * center
     *
     * Centers an element in the window
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 1.1
     * @package wpboilerplate
     */

    $.fn.center = function() {

        return this.each(function() {

            var $this        = $(this),
                thisWidth    = $this.outerWidth(true),
                thisHeight   = $this.outerHeight(true),
                $window      = $(window),
                windowWidth  = $window.width(),
                windowHeight = $window.height(),
                scrollTop    = $window.scrollTop();

            var offsetTop  = Math.max( Math.round( ( windowHeight - thisHeight ) / 2 ) + scrollTop, 0),
                offsetLeft = Math.max( Math.round( ( windowWidth - thisWidth ) / 2 ), 0 );

            $this.css({
                position: 'absolute',
                top:      offsetTop + 'px',
                left:     offsetLeft + 'px'
            });

        });

    };


    /*
     * simpleSlider
     *
     * Simplest jQuery slider possible. Takes an element and cycles through its children with fadeIn/fadeOut effects.
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 2.0
     * @package wpboilerplate
     */

    $.fn.simpleSlider = function() {

        return this.each(function() {

            var $this      = $(this),
                period     = $this.data('period') || 5000,
                fxSpeed    = $this.data('fx-speed') || 500,
                hoverPause = $this.data('hover-pause') || "no",
                slideshow  = $this.data('slideshow') || "yes",
                $slides    = $this.find('.slides').children(),
                N          = $slides.size(),
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
            }

            $slides.each(function() {
                $(this).attr('id', 'sss_' + Math.round(Math.random() * 1E15));
            })

            if ( $this.hasClass('addFire') ) {
                var i = 1;
                $this.append('<div class="fireThose"></div>');
                var $fireThose = $this.find('.fireThose');
                $slides.each(function() {
                    $fireThose.append('<a class="fireThat" href="#' + $(this).attr('id') + '">' + i++ + '</a>');
                });
                $this.append('<a class="fireNext" href="#next"></a>');
                $this.append('<a class="firePrev" href="#prev"></a>');
                $this.append('<a class="firePause" href="#pause"></a>');
            }

            $slides.hide().first().show().addClass('current');

            $this.bind('fireNext', function(e) {
                var $current = $slides.filter('.current'),
                    $next = $current.next().size() ? $current.next() : $slides.first();

                $current.fadeOut(fxSpeed, function() {
                    $next.fadeIn(fxSpeed).addClass('current');
                    $this.trigger('setActiveFire');
                }).removeClass('current');

                fireTime = new Date();
            });

            $this.bind('firePrev', function(e) {
                var $current = $slides.filter('.current'),
                    $prev = $current.prev().size() ? $current.prev() : $slides.last();

                $current.fadeOut(fxSpeed, function() {
                    $prev.fadeIn(fxSpeed).addClass('current');
                    $this.trigger('setActiveFire');
                }).removeClass('current');

                fireTime = new Date();
            });

            $this.bind('fireThat', function(e, fireThat) {
                var $current = $slides.filter('.current'),
                    $that = $slides.filter(fireThat);

                $current.fadeOut(fxSpeed, function() {
                    $that.fadeIn(fxSpeed).addClass('current');
                    $this.trigger('setActiveFire');
                }).removeClass('current');

                fireTime = new Date();
            });

            $this.bind('setActiveFire', function() {
                var $current = $slides.filter('.current');

                $('.fireThat').removeClass('active').each(function() {
                    if ( $(this).attr('href') == '#' + $current.attr('id') ) {
                        $(this).addClass('active');
                    }
                });
            });

            $this.bind('firePause', function() {
                paused = !paused;

                if ( paused ) {
                    $this.addClass('paused');
                } else {
                    $this.removeClass('paused');
                }
            });

            $this.on('click', '.fireNext', function(e) {
                e.preventDefault();
                $this.trigger('fireNext');
            });

            $this.on('click', '.firePrev', function(e) {
                e.preventDefault();
                $this.trigger('firePrev');
            });

            $this.on('click', '.fireThat', function(e) {
                e.preventDefault();
                var fireThat = $(this).attr('href');
                $this.trigger('fireThat', [fireThat]);
            });

            $this.on('click', '.firePause', function(e) {
                e.preventDefault();
                $this.trigger('firePause');
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


    /*
     * simpleCarousel
     *
     * Simple jQuery Carousel.
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 1.0
     * @package wpboilerplate
     */

    $.fn.simpleCarousel = function() {

        return this.each(function() {

            var $this      = $(this),
                $container = $this.find('.container'),
                $items     = $container.children(),
                speed      = $this.data('speed') || 10000,
                hoverPause = $this.data('hover-pause') || "yes",
                offset     = 0;

            $this.css('overflow', 'hidden');
            $container.css('width', '100000%').css('position', 'relative');

            setInterval(function() {
                offset -= 940;

                $container.animate({left: offset + 'px'}, speed, 'linear');

                $container.children().insertAfter($container.children().last())
            }, speed);
        });

    };


    /*
     * smartbox
     *
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 2.1
     * @package wpboilerplate
     */

    $.fn.smartbox = function(options, callback) {

        return this.each(function() {

            var $this    = $(this),
                $title   = $this.find('.title'),
                $content = $this.find('.content'),
                id       = $this.attr('id');

            // class=ajax: content loads async.
            if ( $this.hasClass('ajax') ) {

                var $ajaxTrigger = $this.find('a.ajax-trigger').first(),
                    ajaxSrc      = $this.data('src').replace('#',' #') || $ajaxTrigger.attr('href').replace('#',' #');

                if ( $this.hasClass('lazy') ) {
                    // class=ajax+lazy: content loads async. on trigger

                    $ajaxTrigger.click(function(e) {

                        e.preventDefault();

                        if ( !$this.hasClass('ajax-loaded') ) {

                            $content.load(ajaxSrc, function() {
                                $this.addClass('ajax-loaded');
                            });

                        }

                    });
                } else {
                    // content loads on page load
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
                } else {
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


    /*
     * dropdownNav
     *
     * Converts a list into a select dropdown for navigation
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.99
     * @package wpboilerplate
     */

    $.fn.dropdownNav = function() {

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


    /*
     * sameHeightAs
     *
     * Makes an element the same height as another
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.1
     * @package wpboilerplate
     */

    $.fn.sameHeightAs = function() {

        return this.each(function() {

            var $this = $(this),
                ref   = $this.data('ref');

            if ( ref && $(ref).size() ) {
                var $ref = $(ref);
                $this.height( $ref.height() );
            }

        });

    };


    /*
     * wpbpModal
     *
     *
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.2
     * @package wpboilerplate
     */

    $.fn.wpbpModal = function() {

        return this.each(function() {

            var fadeDuration   = 250,
                blanketOpacity = 0.5;

            var $blanket    = $('#wpbp-modal-blanket'),
                $anchors    = $('a[href^="#"]'),
                $modalBoxes = $('.wpbp-modal-box');

            $anchors.each(function() {

                var $this = $(this),
                    href  = $this.attr('href'),
                    $href = $(href);

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

                var $this   = $(this),
                    target  = $this.data('wpbp-target-modal-box'),
                    $target = $( target );

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


    /*
     * wpbpTabs
     *
     *
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.1
     * @package wpboilerplate
     */

    $.fn.wpbpTabs = function() {

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
                $anchorTarget.addClass('active').show().trigger('activated');

                $anchors.removeClass('active');
                $anchor.addClass('active').trigger('activated');
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

    /*
     * wpbpParallax
     *
     *
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.1
     * @package wpboilerplate
     */

    $.fn.wpbpParallax = function() {

        return this.each(function() {

            var $cover = $(this);

            $(window).on('load scroll resize', function() {

                var coverOffsetTop  = $cover.offset().top,
                    coverHeight     = $cover.outerHeight(true),
                    coverBgPosStart = $cover.data('wpbp-parallax-start') || 75,
                    coverBgPosEnd   = $cover.data('wpbp-parallax-end') || 25;

                var windowHeight = $(window).height(),
                    scrollStart  = Math.max(coverOffsetTop - windowHeight, 0),
                    scrollEnd    = coverOffsetTop + coverHeight;

                var scrollTop = $(window).scrollTop();

                if ( scrollTop >= scrollStart && scrollTop <= scrollEnd ) {

                    var scrollProgress = ( scrollTop - scrollStart ) / ( scrollEnd - scrollStart ),
                        coverBgPosY    = scrollProgress * ( parseInt(coverBgPosEnd) - parseInt(coverBgPosStart) ) + parseInt(coverBgPosStart);

                    $cover.css('background-position-y', coverBgPosY + '%');

                }

            });

        });

    };

    /*
     * wpbpResponsiveNav
     *
     *
     * @author Jonathan Roy <jroy@optimumweb.ca>
     * @version 0.1
     * @package wpboilerplate
     */

    $.fn.wpbpResponsiveNav = function() {

        return this.each(function() {

            var $nav     = $(this),
                navID    = $nav.attr('id'),
                navLabel = $nav.data('responsive-nav-label') || "Menu";

            $('<select class="responsive-nav" data-responsive-nav-for="' + navID + '"></select>').insertAfter($nav);

            var $responsiveNav = $('.responsive-nav[data-responsive-nav-for="' + navID + '"]');

            $responsiveNav.append('<option value="">' + navLabel + '</option>');

            $responsiveNav.hide();

            $nav.find('a').each(function() {

                var $navItem      = $(this),
                    navItemLabel  = $navItem.text(),
                    navItemURL    = $navItem.attr('href'),
                    navItemTarget = $navItem.attr('target');

                $responsiveNav.append('<option></option>');

                var $navOption = $responsiveNav.find('option').last();

                $navOption.text(navItemLabel).val(navItemURL);

                if ( navItemTarget != undefined ) {
                    $navOption.data('target', navItemTarget);
                }

            });

            $responsiveNav.on('change', function() {

                var $selectedNavOption = $(this).find('option:selected');

                if ( $selectedNavOption.size() == 1 ) {

                    var selectedNavOptionURL    = $selectedNavOption.val(),
                        selectedNavOptionTarget = $selectedNavOption.data('target');

                    if ( selectedNavOptionURL ) {
                        if ( selectedNavOptionTarget != undefined ) {
                            window.open(selectedNavOptionURL, selectedNavOptionTarget);
                        } else {
                            window.location.href = selectedNavOptionURL;
                        }
                    }

                }

            });

            $nav.on('wpbpResponsiveNav:toggle', function() {
                if ( $(window).width() > 768 ) {
                    $nav.show();
                    $responsiveNav.hide();
                } else {
                    $nav.hide();
                    $responsiveNav.show();
                }
            }).trigger('wpbpResponsiveNav:toggle');

            $(window).on('resize', function() {
                $nav.trigger('wpbpResponsiveNav:toggle');
            });

        });

    };


}(window.jQuery, window, document));
