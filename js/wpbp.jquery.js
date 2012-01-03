$(document).ready(function() {
	
	/*
	* IR plugin
	* Replaces elements with 'ir' class with an image (specified by 'data-ir').
	*/
	$('.ir').each(function() { $(this).css('background-image', 'url(' + $(this).data('ir') + ')'); });
});