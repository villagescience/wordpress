jQuery(document).ready(function($){

/*-----------------------------------------------------------------------------------*/
/* PrettyPhoto (lightbox) */
/*-----------------------------------------------------------------------------------*/

	if ( window.innerWidth > 768 ) {
		$("a[rel^='lightbox']").prettyPhoto({social_tools: false});
	}

/*-----------------------------------------------------------------------------------*/
/* Portfolio thumbnail hover effect */
/*-----------------------------------------------------------------------------------*/

	jQuery('#portfolio img').mouseover(function() {
		jQuery(this).stop().fadeTo(300, 0.5);
	});
	jQuery('#portfolio img').mouseout(function() {
		jQuery(this).stop().fadeTo(400, 1.0);
	});

/*-----------------------------------------------------------------------------------*/
/* Portfolio tag toggle on page load, based on hash in URL */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.port-cat a' ).length ) {
		var currentHash = '';
		currentHash = window.location.hash;
		
		// If we have a hash, begin the logic.
		if ( currentHash != '' ) {
			currentHash = currentHash.replace( '#', '' );
			
			if ( jQuery( '#portfolio .' + currentHash ).length ) {
			
				// Select the appropriate item in the category menu.
				jQuery( '.port-cat a.current' ).removeClass( 'current' );
				jQuery( '.port-cat a[rel="' + currentHash + '"]' ).addClass( 'current' );
				
				// Show only the items we want to show.
				jQuery( '#portfolio .post' ).hide();
				jQuery( '#portfolio .' + currentHash ).fadeIn( 400 );
			
			}
		}

	}

/*-----------------------------------------------------------------------------------*/
/* Portfolio tag sorting */
/*-----------------------------------------------------------------------------------*/
								
	jQuery( '.port-cat a' ).click( function( evt ) {
		var clicked_cat = jQuery(this).attr( 'rel' );
		
		jQuery( '.port-cat a.current' ).removeClass( 'current' );
		jQuery( this ).addClass( 'current' );
		
		if(clicked_cat == 'all'){
			jQuery( '#portfolio .post' ).hide().fadeIn( 200 );
		} else {
			jQuery( '#portfolio .post' ).hide();
			jQuery( '#portfolio .' + clicked_cat ).fadeIn( 400 );
		 }
		//eq_heights();
		evt.preventDefault();
	})												
});

jQuery( window ).load( function ( $ ) {
	// Thanks @johnturner, I owe you a beer!
	var postMaxHeight = 0;
	jQuery( '#portfolio .post' ).each(function (i) {
		var elHeight = jQuery( this ).outerHeight();
		if( parseInt( elHeight ) > postMaxHeight ) {
			postMaxHeight = parseInt( elHeight );
		}
	});
	jQuery( '#portfolio .post' ).each( function (i) {
		jQuery( this ).css( 'height', postMaxHeight + 'px' );
	});
});