(function($) {

	$.fn.ajaxForm = function() {

		// define form elements
		var $form = $(this);
		var $formSuccess = $form.find(".success");
		var $formError = $form.find(".error");

		// define form properties
		var formId = $form.attr("id");
		var formAction = $form.attr("action");

		// hide response messages
		$formSuccess.hide();
		$formError.hide();

		$form.submit(function(e) {

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

			// prevent default page load
			e.preventDefault();

		});

	}

})(jQuery);