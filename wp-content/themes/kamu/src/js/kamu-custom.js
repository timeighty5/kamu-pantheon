// JavaScript Document

jQuery(function($) { 
	"use strict";
				
	$( document ).ready( function() {	
				
		/* ADA Compliance */
		// Update body classes based on whether the user is interacting via keyboard or mouse
		$( 'body' ).on('keydown', function() {
			$(this).addClass('keyboard-user');
			$(this).removeClass('mouse-user');
		});
		
		$( 'body' ).on('click', function() {
			$(this).addClass('mouse-user');
			$(this).removeClass('keyboard-user');
		});
		
		// Make Primary Menu elements accessible via keyboard
		if( $('#primary-menu').length ) {
			
			// Show sub-menu when a top-level menu item is hovered over 
			$('#primary-menu > li > a').on('mouseenter', function() {
				
				$('.menu-item').removeClass('menu-item-focus');
				
				$(this).closest('.menu-item').addClass('menu-item-focus');
				
				$('.sub-menu').removeClass('sub-menu-focus');
				
				$(this).next('.sub-menu').addClass('sub-menu-focus');
				
				$('.site-header').addClass('sub-menu-visible');
				
			});
			
			$('#primary-menu').on('mouseleave', function() {
								
				$('.menu-item').removeClass('menu-item-focus');
				
				$('.sub-menu').removeClass('sub-menu-focus');
				
				$('.site-header').removeClass('sub-menu-visible');

			});			
			
			// Show sub-menu when a top-level menu item receives focus
			$('#primary-menu > li > a').on('focus', function() {
				
				$('.menu-item').removeClass('menu-item-focus');
				
				$(this).closest('.menu-item').addClass('menu-item-focus');
				
				$('.site-header').addClass('sub-menu-visible');
			});
			
			// Hide all sub-menus when a user presses Shift + Tab from the very first menu item
			$('#primary-menu > li:first-child > a').on('keydown', function(event) {
				
				if ( (event.code === 'Tab' || event.which === 9) && event.shiftKey) {
					$('.menu-item').removeClass('menu-item-focus');
					
					$('.site-header').removeClass('sub-menu-visible');
				}
				
			});
			
			// Hide all sub-menus when a secondary menu item comes into focus
			$('#secondary-site-navigation a').focus( function() {
				
				$('.menu-item').removeClass('menu-item-focus');
					
				$('.site-header').removeClass('sub-menu-visible');
			});

			// Hide previous sub-menu when a user presses the Tab key while on the last sub-menu item 
			$('#primary-menu .sub-menu > .ad-placement a').on('keydown', function(event) {
								
				if ( (event.code === 'Tab' || event.which === 9) && !event.shiftKey) {
					$(this).closest('.sub-menu').closest('.menu-item').removeClass('menu-item-focus');
					
					$('.site-header').removeClass('sub-menu-visible');
				}
			});
			
			// Show last sub-menu when a user presses the Shift + Tab key from the first item of the secondary menu
			$('#secondary-site-navigation li:first-child > a').on('keydown', function(event) {
				
				if ( (event.code === 'Tab' || event.which === 9) && event.shiftKey) {
					
					$('#primary-menu li:last-child').addClass('menu-item-focus');
					
					$('.site-header').addClass('sub-menu-visible');
				}

			});
			
			// Show previous sub-menu when the user presses Shift + Tab from a top-level menu item
			$('#primary-menu > li > a').on('keydown', function(event) {
				
				if ( (event.code === 'Tab' || event.which === 9) && event.shiftKey) {
					
					$('.menu-item').removeClass('menu-item-focus');
					
					$(this).closest('.menu-item').prev('.menu-item').addClass('menu-item-focus');
					
				}
			});
												
		}
		

		/* Set custom styles of Newsletter Form for ADA compliance */
		if( $('.form-newsletter').length ) {
			
			$('.form-newsletter input').on('focus', function() {
				
				$(this).prev('label').addClass('field-focus');
				
			});
			
			$('.form-newsletter input').on('blur', function() {
				
				$(this).prev('label').removeClass('field-focus');
				
				setFieldValid( $(this) );
				
			});
			
			$('.form-newsletter input').on('keypress', function() {
				
				setFieldValid( $(this) );
			});
			
			function setFieldValid( inputField ) {
				
				if( $(inputField).hasClass('wpforms-valid') ) {
					$(inputField).prev('label').addClass('field-valid');
				}
				else {
					$(inputField).prev('label').removeClass('field-valid');
				}
				
			}
		}

		/* Slick slider */
		$('.slider').slick({
			dots: true,
			infinite: true,
			speed: 300,
			slidesToShow: 1,
			adaptiveHeight: true,
			//autoplay: true,
			autoplaySpeed: 2000,
			//fade: true,
			cssEase: 'linear'
		});
		
		
		// Ad Placements 
		if( $('#ad-placements').length ) {
			
			var ctr = 1;
			
			// Append ad placements to the end of each sub-menu
			$('#ad-placements .ad-placement').each( function() {
				
				$('#primary-menu > .menu-item:nth-child(' + ctr + ') .sub-menu').append($(this));
				
				ctr++;
				
			});			
		}
		
		// Programs A-Z
		if( $('.programs').length ) {
			
			// Check for URL parameters and set select options if they exist
			const queryString = window.location.search;
		
			const urlParams = new URLSearchParams(queryString);
		
			const filter = urlParams.get('filter');
						
			if( $('.alphabet-container').length ) {
				
				// Apply active style to alphabet filter
				if( filter !== null ) {
					
					if( filter === 'number' ) {
						
						$('.alphabet-container .letter-link.number').addClass('active');
					}
					else {
						
						$('.alphabet-container .letter-link').each( function() {
						
							if($(this).text() === filter) {

								$(this).addClass('active');
							}
						});
					}
					
				}
				else {
					$('.alphabet-container ul li:first-child a').addClass('active');
				}
			}

		}
		
		// Mobile Menu
		$('.off-canvas-screen, .off-canvas-open').on('click', function() {
			$('body').toggleClass('nav-open')
		})
	});	
	
	/* Newsletter form fix*/
	var formHeadline = $('.site-footer .form-wrapper h3.cta-headline');	
	var formHeadlineText = formHeadline.html();
	formHeadline.after('<p class="cta-headline">'+formHeadlineText+'</p>').remove();

});