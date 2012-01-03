$(document).ready(function() {
	
	/*
	 * IR plugin
	 * Replaces elements with the 'ir' class with an image specified by 'data-ir'.
	 */
	$('.ir').each(function() {
		$(this).css('background-image', 'url(' + $(this).data('ir') + ')');
	});
	
});

$(window).load(function() {

	/*
	 * VALIGN plugin
	 * Aligns vertically elements with the 'valign' class with reference either the parent element
	 * or another element specified by 'data-ref'.
	 */
	$('.valign').each(function() {
		$this = $(this);
		$ref = ( typeof $this.data('ref') != 'undefined' ) ? $this.data('ref') : $this.parent();
		thisHeight = $this.height(), refHeight = $ref.height();
		offset = Math.round( ( refHeight - thisHeight ) / 2 );
		$this.css('margin-top', offset + 'px').css('margin-bottom', offset + 'px');
	});

});