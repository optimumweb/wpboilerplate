$(document).ready(function() {

	$('.ir').each(function() { $(this).IR(); });
	
	$('form.ajax, form.ajax-form').each(function() { $(this).ajaxForm(); });
	
});

$(window).load(function() {

	$('.valign, .vAlign').each(function() { $(this).vAlign(); });

});

/*
 * IR plugin
 *
 * Replaces elements with the 'ir' class with an image specified by 'data-ir'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.0
 * @package wpboilerplate
 */
 
(function($) {
	$.fn.extend({
		IR: function() {
			var $this = $(this);
			var ir = $this.data('ir');
			if ( typeof if != 'undefined' ) {
				$this.css('background-image', 'url(' + ir + ')');
			}
		}
	});
})(jQuery);


/*
 * VALIGN plugin
 *
 * Aligns vertically elements with the 'valign' class with reference either the parent element
 * or another element specified by 'data-ref'.
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.0
 * @package wpboilerplate
 */

(function($) {
	$.fn.extend({
		vAlign: function() {
			var $this = $(this);
			var $ref = ( typeof $this.data('ref') != 'undefined' ) ? $($this.data('ref')) : $this.parent();
			var thisHeight = $this.outerHeight(true), refHeight = $ref.outerHeight(true);
			var offset = Math.round( ( refHeight - thisHeight ) / 2 );
			$this.css('margin-top', offset + 'px').css('margin-bottom', offset + 'px');
		}
	});
})(jQuery);


/*
 * AJAX FORM plugin
 *
 * Validate and send a form asynchronously
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 2.0
 * @package wpboilerplate
 */

(function($) {

	$.fn.extend({
	
		ajaxForm: function() {

			// define form elements
			var $form = $(this);
			var $formSuccess = $form.find(".success");
			var $formError = $form.find(".error");
			var $formLoading = $form.find(".loading");
	
			// define form properties
			var formId = $form.attr("id");
			var formAction = $form.attr("action");
	
			// hide response messages and loading
			$formSuccess.hide();
			$formError.hide();
			$formLoading.hide();
	
			$form.submit(function(e) {
	
				// show that we are working in the background
				$formLoading.show();
	
				// assume no errors in submission
				var inputError = false;
				var inputErrorMsg = "";
	
				// validate all required fields
				$form.find(".required").each(function() {
					var $input = $(this);
					if ( $input.val() == "" ) {
						inputError = true;
						$input.removeClass("valid");
						$input.addClass("invalid");
					}
					else {
						$input.removeClass("invalid");
						$input.addClass("valid");
					}
				});
	
				// validate emails
				$form.find(".valid.email").each(function() {
					var $input = $(this);
					var emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					if ( !emailRegex.test( $input.val() ) ) {
						inputError = true;
						$input.removeClass("valid");
						$input.addClass("invalid");
					}
					else {
						$input.removeClass("invalid");
						$input.addClass("valid");
					}
				});
	
				if ( !inputError ) {
					$.ajax({
						type:	'POST',
						url:	formAction,
						data:	$form.serialize(),
						success: function(response) {
							if ( response == "success" ) {
								$formSuccess.fadeIn();
								$formError.hide();
								$form.children().not($formSuccess).hide();
							}
							else {
								$formSuccess.hide();
								$formError.fadeIn();
							}
							// trigger google analytics
							_gaq.push(['_trackPageview', '/form-sent/' + formId]);
						}
					});
				}
				else {
					$formSuccess.hide();
					$formError.fadeIn();
				}
	
				// hide the loading
				$formLoading.hide();
	
				// prevent default page load
				e.preventDefault();
	
			});
			
			// show that the form is ajax-enabled
			$form.addClass('ajax-enabled');

		}
		
	});

})(jQuery);