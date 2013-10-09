jQuery( document ).ready( function( $ ) {
/*-----------------------------------------------------------------------------------*/
/* Feedback slide/fade setup. */
/*-----------------------------------------------------------------------------------*/
	if ( jQuery( '.feedback' ).length ) {
		jQuery( '.feedback' ).each( function () {
			var effect = 'none';
			
			var thisObj = jQuery( this );

			if ( thisObj.hasClass( 'fade' ) ) { effect = 'fade'; }
			
			if ( effect != 'none' ) {
				var autoSpeed = 5000;
				var fadeSpeed = 350;
				
				if ( jQuery( this ).parent().find( 'input[name="auto_speed"]' ).length && ( jQuery( this ).parent().find( 'input[name="auto_speed"]' ).val() != '' ) ) {
					autoSpeed = parseFloat( jQuery( this ).parent().find( 'input[name="auto_speed"]' ).val() );
					jQuery( this ).parent().find( 'input[name="auto_speed"]' ).remove();
				}
				
				if ( jQuery( this ).parent().find( 'input[name="fade_speed"]' ).length && ( jQuery( this ).parent().find( 'input[name="fade_speed"]' ).val() != '' ) ) {
					fadeSpeed = parseFloat( jQuery( this ).parent().find( 'input[name="fade_speed"]' ).val() );
					jQuery( this ).parent().find( 'input[name="fade_speed"]' ).remove();
				}

				thisObj.flexslider({
					selector: '.feedback-list > .quote', 
					animation: effect, 
					slideshowSpeed: autoSpeed, 
					animationSpeed: fadeSpeed, 
					controlNav: false, 
					prevText: wooFeedbackL10n.prevButton, 
					nextText: wooFeedbackL10n.nextButton
				});
			}
		});
	}				
						
}); // End jQuery()