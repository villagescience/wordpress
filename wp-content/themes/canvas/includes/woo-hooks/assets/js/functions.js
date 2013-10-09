/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript in the admin for the Woo_Meta extension.
Date Created: 2011-03-21.
Author: Matty.
Since: 4.0.0


TABLE OF CONTENTS

- Interface JavaScript.
- Form Reset Logic.
- Form AJAX Submission.
- Menu Toggle Logic.

- function center() - Centre an element.

-----------------------------------------------------------------------------------*/

jQuery(function($) {

	var logicType = '';

/*----------------------------------------
Interface JavaScript.
----------------------------------------*/

	// Show the first section.
		
	jQuery( '#woo-nav ul li:first, .content-section:first' ).addClass( 'current' );
	
	jQuery( '#content .content-section:first' ).hide().fadeIn();
	jQuery( '#content .content-section:not(:first)' ).hide();
	
	// Toggle to the desired section on click.
	
	jQuery( '#woo-nav ul li a' ).click( function () {
	
		jQuery( '#woo-nav ul li.current' ).removeClass( 'current' );
		
		jQuery( this ).parents( 'li' ).addClass( 'current' );
		
		var sectionId = jQuery( this ).attr( 'href' );
		
		sectionId = sectionId.replace( '#', '' );
		
		jQuery( '.content-section:not( #' + sectionId + ' )' ).fadeOut( 'fast', function () {
		
			jQuery( '.content-section.current' ).removeClass( 'current' );
		
			jQuery( '#' + sectionId ).addClass( 'current' ).fadeIn();
		
		});
	
		return false;
	
	});

/*----------------------------------------
Form Reset Logic.
----------------------------------------*/
	
jQuery( 'form#wooform input.reset-button' ).click( function () {
	
	logicType = 'reset';
	
	var confirmed = confirm( 'Are you sure you want to reset these options? All customised hooks will be lost!' );
	
	if ( ! confirmed ) { return false; }
	
});

jQuery( 'form#wooform input.submit-button:not(.reset-button)' ).click( function () {
	
	logicType = 'save';
	
});
	
/*----------------------------------------
Form AJAX Submission.
----------------------------------------*/

	jQuery( 'form#wooform' ).submit( function ( e ) {
	
		e.preventDefault();
	
		jQuery( 'img.ajax-loading-img' ).fadeIn( 'slow' );
		jQuery( 'input[type="submit"]' ).attr( 'disabled', 'disabled' );
		
		var formAction = jQuery( this ).attr( 'action' );
		var formData = jQuery( this ).serialize();
		
		switch ( logicType ) {
		
			case 'reset':
				formData = '&woohooks_reset=true&' + formData;
				formAction = formAction.replace( 'updated=true', 'reset=true' );
			break;
			
			case 'save':
				formData = '&woohooks_update=true&' + formData;
			break;
		
		}
		
		if ( formAction ) {
		
			var jqxhr = jQuery.post( formAction, formData, function( response, textStatus, jqXHRObj ) {
				
				var successMessage = jQuery( response ).find( '.updated' );
			
				var successDiv = jQuery( '<div></div>' ).attr( 'id', 'woo-popup-' + logicType ).addClass( 'woo-save-popup' );
				successDiv.html( '<div class="woo-save-' + logicType + '">' + successMessage.text() + '</div>' );
				
				successDiv.center();
				
				jQuery(window).scroll( function( e ) { 
				
					successDiv.center();
				
				});
				
				jQuery(window).resize( function( e ) { 
				
					successDiv.center();
				
				});
				
				successDiv.css( 'display', 'block' ).fadeIn( 'slow' );
				
				jQuery( 'form#wooform' ).before( successDiv );
				
				window.setTimeout(function(){
					successDiv.fadeOut( 'slow', function () {
						successDiv.remove();
					});					
				}, 2000);
			
				jQuery( 'img.ajax-loading-img' ).fadeOut( 'slow' );
				jQuery( 'input[type="submit"]' ).removeAttr( 'disabled' );
				
			});
		
		}
		
			return false;
	
	});

/*----------------------------------------
Menu Toggle Logic.
----------------------------------------*/
	
	jQuery( '#support-links .submit-button' ).before( '<a href="#" id="expand_options">[+]</a> ' );
	
	jQuery( 'a#expand_options' ).toggle(
		function () {
			jQuery( this ).text( '[-]' );
			jQuery( '.group h2' ).show();
			jQuery( '#woo_container #content' ).css( 'width', '785px' );
			jQuery( '#woo-nav' ).hide();
			jQuery( '.content-section' ).show();
			return false;
		}, 
		function () {
			jQuery( this ).text( '[+]' );
			jQuery( '.group h2' ).hide();
			jQuery( '#woo_container #content' ).removeAttr( 'style' );
			jQuery( '#woo-nav' ).show();
			jQuery( '.content-section:not(.current)' ).hide();
			return false;
		}
	);
	
/*----------------------------------------
center() - Centre an element.
----------------------------------------*/

jQuery.fn.center = function () {
	this.stop().animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"}, 500 );
	this.css( "left", 250 );
	return this;
}

}); // End jQuery()