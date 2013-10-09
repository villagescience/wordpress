/*-------------------------------------------------------------------------------------

FILE INFORMATION

Description: Custom toggle logic for "Meta Options".
Date Created: 2011-07-06.
Author: Cobus, Matty.
Since: 4.3.0


TABLE OF CONTENTS

- Logic for toggling of the "Slide Page" option, depending on page template.

-------------------------------------------------------------------------------------*/

jQuery(document).ready(function(){

/*-----------------------------------------------------------------------------------*/
/* - Logic for toggling of the "Slide Page" option, depending on page template. */
/*-----------------------------------------------------------------------------------*/

	var showValue = 'template-biz.php';
	var elementName = 'select#page_template';
	var toggleElements = 'select[name="_slide-page"]';
	
	// Hide elements to be hidden.
	jQuery( toggleElements ).parents( 'tr' ).hide();
	
	// Toggle the main elements on load.
	if ( jQuery( elementName ).val() == showValue ) {
		jQuery( toggleElements ).parents( 'tr' ).show();
	}
	
	// Toggle the "Slide Page" option on change.
	jQuery( elementName ).change( function ( e ) {
		if ( jQuery( elementName ).val() == showValue ) {
			jQuery( toggleElements ).parents( 'tr' ).show();
		} else {
			jQuery( toggleElements ).parents( 'tr' ).hide();
		}
	});

}); // End jQuery()