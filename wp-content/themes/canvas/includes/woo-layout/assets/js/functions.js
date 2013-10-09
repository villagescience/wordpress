/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript in the admin for the Woo_Meta extension.
Date Created: 2011-04-11.
Author: Matty.
Since: 4.0.0


TABLE OF CONTENTS

- Setup Layout Selector.
- Layout Selector Selection Event.
- Layout Toggle and Show Active Layout.
- Setup Layout Managers.
- Interface JavaScript.
- Form Reset Logic.
- Form AJAX Submission.
- Menu Toggle Logic.
- Select Box Logic.
- Image Selector Logic.

- function center() - Centre an element.
- function update_dimensions() - Update dimensions.

-----------------------------------------------------------------------------------*/

jQuery(function($) {

	var logicType = '';

/*----------------------------------------
Setup Layout Selector.
----------------------------------------*/

var layoutSelector = '';
var currentLayoutWidth = jQuery( '.layout-width-value' ).text();
var currentLayoutType = jQuery( '#layout-type' ).attr( 'class' );

var imageDir = jQuery( 'input[name="woo-framework-image-dir"]' ).val();
jQuery( 'input[name="woo-framework-image-dir"]' ).remove();

var gutterSpacing = parseInt( jQuery( 'input[name="woo-gutter"]' ).val() );
jQuery( 'input[name="woo-gutter"]' ).remove();

if ( jQuery( '.section' ).length ) {

	// Create layout selector container.
	layoutSelector += '<h3 class="heading">Select Layout</h3><div class="layout-selector">';

	jQuery( '.layout-group .section' ).each( function () {
	
		var currentId = jQuery( this ).attr( 'id' );
		var selectedClass = '';
		var imageSelect = '1c';
		if ( currentId == currentLayoutType ) { selectedClass = ' active'; }
		
		switch ( currentId ) {
			
			case 'two-col-left':
				imageSelect = '2cl';
			break;
			
			case 'two-col-right':
				imageSelect = '2cr';
			break;
			
			case 'three-col-left':
				imageSelect = '3cl';
			break;
			
			case 'three-col-middle':
				imageSelect = '3cm';
			break;
			
			case 'three-col-right':
				imageSelect = '3cr';
			break;
			
		}
		
		layoutSelector += '<span class="' + currentId + '"><a href="#" id="' + currentId + '" class="layout-option ' + currentId + selectedClass + '">' + '<img src="' + imageDir + imageSelect + '.png" alt="' + jQuery( this ).find( '.heading' ).text() + '" />' + '</a></span>';
		
	});
	
	// Close layout selector container.
	layoutSelector += '<div class="clear"></div></div>';
	
	// Insert the layout selector code.
	jQuery( '#layout-width-notice' ).before( layoutSelector );
	
}

/*----------------------------------------
Layout Selector Selection Event.
----------------------------------------*/

if ( jQuery( '.layout-selector a.layout-option' ).length ) {
	jQuery( '.layout-selector a.layout-option' ).click( function () {
		jQuery( '.layout-selector a.layout-option.active' ).removeClass( 'active' );
		jQuery( this ).addClass( 'active' );
		
		var activeLayout = jQuery( this ).attr( 'id' );
		jQuery( '.layout-group .section:not(#' + activeLayout + ')' ).hide();
		jQuery( '.layout-group .section#' + activeLayout ).show();
		
		return false;
	});
}

/*----------------------------------------
Layout Toggle and Show Active Layout.
----------------------------------------*/

if ( jQuery( '.layout-selector a.layout-option.active' ).length && jQuery( '.layout-group .section' ).length ) {
	var activeLayout = jQuery( '.layout-selector a.layout-option.active' ).attr( 'id' );
	
	jQuery( '.layout-group .section:not(#' + activeLayout + ')' ).hide();
	jQuery( '.layout-group .section#' + activeLayout ).show();
}

/*----------------------------------------
Setup Layout Managers.
----------------------------------------*/

if ( jQuery( '.layout-group .section' ).length ) {
	jQuery( '.layout-group .section' ).each( function ( index, element ) {
		
		// Setup our layout DIV.
		var layoutDiv = jQuery( '<div />' ).addClass( 'layout-ui' ).css( 'height', '300' ).css( 'width', '596' );
		
		var divHtml = '';
		
		// Create the layout column DIVs dynamically.
		jQuery( this ).find( 'input.woo-input' ).each( function ( index, element ) {
			
			var divId = jQuery( this ).attr( 'id' );
			divId += '-column';
			var divClass = '';
			
			var columnName = jQuery( this ).prev( 'label' ).text();
			
			switch ( index ) {
				
				case 0:
					divClass += ' ui-layout-west';
				break;
				
				case 1:
					divClass += ' ui-layout-center';
				break;
				
				case 2:
					divClass += ' ui-layout-east';
				break;
				
			}
			
			divHtml += '<div id="' + divId + '" class="' + divClass + '"><span class="content">' + columnName + '<small>(approx. <span class="pixel-width">' + '' + '</span>%)</small></span></div>';
			
		});
		
		// Add the XHTML for display.
		layoutDiv.html( divHtml );
		
		if ( jQuery( layoutDiv ).find( 'div' ).length >= 1 ) {
		
			// Get the initial West and East dimensions.
			var westWidthPercent = jQuery( this ).find( '.controls input.woo-input:eq(0)' ).val();
			var centerWidthPercent = jQuery( this ).find( '.controls input.woo-input:eq(1)' ).val();
			var eastWidthPercent = jQuery( this ).find( '.controls input.woo-input:eq(2)' ).val();
			
			// Work out the pixel widths for the various columns.
			var onePercent = parseInt( layoutDiv.width() ) / 100;
			
			var westWidth = Math.ceil( onePercent * westWidthPercent );
			var eastWidth = Math.ceil( onePercent * eastWidthPercent );
			var centerWidth = parseInt( currentLayoutWidth ) - westWidthPercent - eastWidthPercent;
			
			centerWidth = Math.ceil( centerWidth );
				
			var layoutObj = layoutDiv.layout({
								closable:				false, 
								resizable:				true, 
								slidable:				false, 
								resizeWhileDragging: 	true, 
								west__resizable:		true, // Set to TRUE to activate dynamic margin
								east__resizable:		true, // Set to TRUE to activate dynamic margin
								east__resizerClass: 	'woo-resizer-east', 
								west__resizerClass: 	'woo-resizer-west', 
								east__size:				eastWidth, 
								west__size:				westWidth, 
								east__minSize:			10, 
								west__minSize:			10, 
								onresize: function ( name, element, state, options, layoutname ) {											
											update_dimensions( element );
										  }
							});
						
			setup_dimensions();
		
		}
		
		// Add the layout DIV after the heading.
		jQuery( this ).find( '.heading' ).after( layoutDiv );
		
		// Hide the input fields and the explanation DIV.
		jQuery( this ).find( '.controls, .explain' ).hide();
		
		// Set the dimensions display in the content area.
		jQuery( this ).find( 'div:eq(0) .pixel-width' ).text( westWidthPercent );
		jQuery( this ).find( 'div:eq(1) .pixel-width' ).text( centerWidthPercent );
		jQuery( this ).find( 'div:eq(2) .pixel-width' ).text( eastWidthPercent );
		
	});
	
}

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
		
			jQuery( '#' + sectionId ).fadeIn();
		
		});
	
		return false;
	});

/*----------------------------------------
Form Reset Logic.
----------------------------------------*/
	
jQuery( 'form#wooform input.reset-button' ).click( function () {
	logicType = 'reset';
	
	var confirmed = confirm( 'Are you sure you want to reset these options? All customised data will be lost!' );
	
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
				formData = '&woolayout_reset=true&' + formData;
				formAction = formAction.replace( 'updated=true', 'reset=true' );
			break;
			
			case 'save':
				formData = '&woolayout_update=true&' + formData;
			break;
		
		}
		
		if ( formAction ) {
		
			var jqxhr = jQuery.post( formAction, formData, function( response, textStatus, jqXHRObj ) {
				
				var successMessage = jQuery( response ).find( '.updated' );
			
				var successDiv = jQuery( '<div></div>' ).attr( 'id', 'woo-popup-' + logicType ).addClass( 'woo-save-popup' );
				successDiv.html( '<div class="woo-save-' + logicType + '">' + successMessage.text() + '</div>' );
				
				// If it's the reset, reload the content to reflect the new data.
				if ( logicType == 'reset' ) {
				
					document.location = formAction;
				
				}
				
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
Select Box Logic.
----------------------------------------*/

	if ( jQuery( 'select.woo-input' ).length ) {
	
		// Load the first item into a <span> tag above the select box.
		jQuery( 'select.woo-input' ).each( function () {
		
			var currentItemDisplay = jQuery( '<span></span>' );
			var currentItem = jQuery( this ).find( 'option:selected' );
			if ( ! currentItem ) {
				currentItem = jQuery( this ).find( 'option:eq(0)' );
			}
			
			currentItemDisplay.text( currentItem.text() );
			
			jQuery( this ).before( currentItemDisplay );
			
		});
		
		// Adjust the main item if the select box changes.
		jQuery( 'select.woo-input' ).change( function () {
		
			var selectedItem = jQuery( this ).find( 'option:selected' ).text();
			if ( selectedItem ) {
				jQuery( this ).prev( 'span' ).text( selectedItem );
			}
		
		});
	}

/*----------------------------------------
Image Selector Logic.
----------------------------------------*/

jQuery( '.woo-radio-img-img' ).click( function(){
	jQuery( this ).parent().parent().find( '.woo-radio-img-selected' ).removeClass( 'woo-radio-img-selected' );
	jQuery(this).addClass( 'woo-radio-img-selected' );
	
});
jQuery('.woo-radio-img-label').hide();
jQuery('.woo-radio-img-img').show();
jQuery('.woo-radio-img-radio').hide();
	
/*----------------------------------------
center() - Centre an element.
----------------------------------------*/

jQuery.fn.center = function () {
	this.stop().animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"}, 500 );
	this.css( "left", 250 );
	return this;
}

/*----------------------------------------
update_dimensions() - Update dimensions.
----------------------------------------*/

function update_dimensions ( element ) {
	
	var layoutParent = element.parents( '.layout-ui' );
	var layoutWidth = parseInt( layoutParent.width() );

	// Factor out the resizer.
	var resizerWidth = layoutParent.find( 'span.ui-draggable' ).width();
	var resizerPercentage = Math.ceil( ( resizerWidth / layoutWidth ) * 100 );

	var currentLayoutWidth = parseInt( jQuery( '.layout-width-value' ).text() );
	
	layoutParent.children( 'div' ).each( function () {
		
		var columnId = jQuery( this ).attr( 'id' );
		var inputId = columnId.replace( '-column', '' );
		var columnWidth = parseInt( jQuery( this ).width() );
		var newPercentage = ( columnWidth / layoutWidth ) * 100;
		
		newPercentage = Math.ceil( newPercentage );
		
		var onePercent = parseInt( currentLayoutWidth ) / 100;
		
		onePercent = Math.ceil( onePercent );
		
		var newPixelWidth = onePercent * newPercentage;
		
		jQuery( this ).find( '.pixel-width' ).text( newPercentage );
		
		jQuery( 'input#' + inputId ).val( newPercentage );
		
	});
												
} // End update_dimensions()

/*----------------------------------------
setup_dimensions() - Setup dimensions.
----------------------------------------*/

function setup_dimensions () {
	var layoutWidth = parseInt( jQuery( '.layout-ui' ).width() );
	
	var currentLayoutWidth = parseInt( jQuery( '.layout-width-value' ).text() );

	jQuery( '.layout-ui' ).each( function () {
		jQuery( this ).children( 'div' ).each( function () {

			// Factor out the resizer.
			// var resizerWidth = layoutParent.find( 'span.ui-draggable' ).width();
			// var resizerPercentage = Math.ceil( ( resizerWidth / layoutWidth ) * 100 );

			var fullWidth = currentLayoutWidth;
			var adjustedLayoutWidth = layoutWidth;
			
			jQuery( this ).parent( 'div' ).find( 'span' ).each( function ( i ) {
				fullWidth -= jQuery( this ).width();
				adjustedLayoutWidth -= jQuery( this ).width();
			});
			
			var columnId = jQuery( this ).attr( 'id' );
			var inputId = columnId.replace( '-column', '' );
			var columnWidth = parseInt( jQuery( this ).width() );
			
			var newPercentage = ( columnWidth / layoutWidth ) * 100;
			newPercentage = Math.ceil( newPercentage );

			var onePercent = parseInt( fullWidth ) / 100;
			onePercent = Math.ceil( onePercent );
			
			var newPixelWidth = onePercent * newPercentage;
			
			jQuery( this ).find( '.pixel-width' ).text( newPercentage );
			
		});
	
	});											
} // End setup_dimensions()
}); // End jQuery()