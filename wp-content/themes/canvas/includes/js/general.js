/*-----------------------------------------------------------------------------------*/
/* Run scripts on jQuery(document).ready() */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function(){

	// FitVids - Responsive Videos
	jQuery( '.widget, .panel, .video' ).fitVids();
	if ( window.innerWidth < 768 ) {
		jQuery( '.entry' ).fitVids();
	}

	// Add class to parent menu items with JS until WP does this natively
	jQuery( 'ul.sub-menu, ul.children' ).parent( 'li' ).addClass( 'parent' );


/*-----------------------------------------------------------------------------------*/
/* Navigation */
/*-----------------------------------------------------------------------------------*/

	// Add the 'show-nav' class to the body when the nav toggle is clicked
	jQuery( '.nav-toggle' ).click(function(e) {

		// Prevent default behaviour
		e.preventDefault();

		// Add the 'show-nav' class
		jQuery( 'body' ).toggleClass( 'show-nav' );

		// Check if .top-navigation already exists
		if ( jQuery( '#navigation' ).find( '.top-navigation' ).size() ) return;
		if ( jQuery( '#navigation' ).find( '.top-menu' ).size() ) return;

		// If it doesn't, clone it (so it will still appear when resizing the browser window in desktop orientation) and add it.
		jQuery( '#top .top-menu' ).clone().appendTo( '#navigation .menus' );
		jQuery( '#top .top-navigation' ).clone().appendTo( '#navigation .menus' );
	});

	// Remove the 'show-nav' class from the body when the nav-close anchor is clicked
	jQuery('.nav-close').click(function(e) {

		// Prevent default behaviour
		e.preventDefault();

		// Remove the 'show-nav' class
		jQuery( 'body' ).removeClass( 'show-nav' );
	});

	// Remove 'show-nav' class from the body when user tabs outside of #navigation on handheld devices
    function wooBindEvent(element, eventName, eventHandler) {
		if (element.addEventListener){
			document.addEventListener(eventName, eventHandler, false);
		} else if (element.attachEvent){
			document.attachEvent('on'+eventName, eventHandler);
		}
	}
	wooBindEvent(document.getElementById('inner-wrapper'), 'touchstart', function () {
		if ( jQuery( 'body' ).hasClass( 'show-nav' ) ) {
			jQuery( 'body' ).removeClass( 'show-nav' );
		}
	});

/*-----------------------------------------------------------------------------------*/
/* Add rel="lightbox" to image links if the lightbox is enabled */
/*-----------------------------------------------------------------------------------*/

if ( jQuery( 'body' ).hasClass( 'has-lightbox' ) && ! jQuery( 'body' ).hasClass( 'portfolio-component' ) && window.innerWidth > 768 ) {
	jQuery( 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".png"]' ).each( function () {
		var imageTitle = '';
		if ( jQuery( this ).next().hasClass( 'wp-caption-text' ) ) {
			imageTitle = jQuery( this ).next().text();
		}

		if ( '' != imageTitle ) {
			jQuery( this ).attr( 'title', imageTitle );
		}

		if ( jQuery( this ).parents( '.gallery' ).length ) {
			var galleryID = jQuery( this ).parents( '.gallery' ).attr( 'id' );
			jQuery( this ).attr( 'rel', 'lightbox[' + galleryID + ']' );
		} else {
			jQuery( this ).attr( 'rel', 'lightbox' );
		}
	});

	jQuery( 'a[rel^="lightbox"]' ).prettyPhoto({social_tools: false});
}

/*-----------------------------------------------------------------------------------*/
/* Add alt-row styling to tables */
/*-----------------------------------------------------------------------------------*/

	jQuery( '.entry table tr:odd' ).addClass( 'alt-table-row' );
}); // End jQuery()