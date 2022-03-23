/**
 * Dashicon Select for Post Types Unlimited
 */
( function( $ ) {

	'use strict';

	var $metabox = $( '.ptu-metabox' );

	$metabox.on( 'click', '.ptu-meta-icon-select-wrap button', function( e ) {
		e.preventDefault();
		var $this   = $( this );
		var $parent = $this.parent( '.ptu-meta-icon-select-wrap' );
		var $modal  = $parent.find( '.ptu-meta-icon-select-modal' );
		$modal.addClass( 'ptu-meta-active' );
		$modal.find( '.ptu-meta-icon-select-search' ).focus();
	} );

	$metabox.on( 'click', '.ptu-meta-icon-select-modal-choices a', function( e ) {
		e.preventDefault();
		var $this = $( this );
		var $parent = $this.closest( '.ptu-meta-icon-select-wrap' );
		var $val = $this.attr( 'data-value' );
		$val = ( 0 == $val ) ? '' : $val;
		$parent.find( 'input[type="text"]' ).val( $val );
		$this.closest( '.ptu-meta-icon-select-modal' ).removeClass( 'ptu-meta-active' );
		$this.closest( '.ptu-meta-icon-select-wrap' ).find( '.ptu-meta-icon-select-preview .dashicons' ).attr( 'class', 'dashicons dashicons-' + $val );
	} );

	$metabox.on( 'click', '.ptu-meta-icon-select-modal button.ptu-meta-close', function( e ) {
		e.preventDefault();
		$( this ).closest( '.ptu-meta-icon-select-modal' ).removeClass( 'ptu-meta-active' );
	} );

	$( '.ptu-meta-icon-select-search' ).on( 'keyup', function() {
		var $this  = $( this );
		var value  = $this.val().toLowerCase();
		var $icons = $this.next().find( 'a' );
		$icons.filter( function() {
			$( this ).toggle( $( this ).attr( 'data-value' ).toLowerCase().indexOf( value ) > -1 );
		} );
	} );

	$( '.ptu-meta-icon-select-wrap > input[type="text"]' ).on( 'keyup', function() {
		var $this = $( this );
		var val   = $this.val().toLowerCase();
		$this.closest( '.ptu-meta-icon-select-wrap' ).find( '.ptu-meta-icon-select-preview .dashicons' ).attr( 'class', 'dashicons dashicons-' + val );
	} );

	$metabox.on( 'keydown', function() {
		if ( event.keyCode == 27 ) {
			var $modal = $( '.ptu-meta-icon-select-modal' );
			if ( $modal.hasClass( 'ptu-meta-active' ) ) {
				$modal.removeClass( 'ptu-meta-active' );
			}
		}
	} );

} ) ( jQuery );