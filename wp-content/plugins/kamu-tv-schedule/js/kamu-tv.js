// JavaScript Document
// v1.1

jQuery(function($) { 
	"use strict";
				
	$( document ).ready( function() {	
	
		// Check for URL parameters and set select options if they exist
		const queryString = window.location.search;
		
		//const queryString = window.location.term;
		
		const urlParams = new URLSearchParams(queryString);
		
		const station = urlParams.get('station');
		
		const daypart = urlParams.get('daypart');
		
		const date = urlParams.get('date');
		
		// If a date value not in URL - set the default of the form's hidden field to today
		// This allows us to include today's date in the URL parameters of the redirect URL if the user submits the form
		if( !date ) {
			
			var defaultDate = new Date();
			var offset = defaultDate.getTimezoneOffset();
			defaultDate = new Date(defaultDate.getTime() - (offset*60*1000));
			var today = defaultDate.toISOString().split('T')[0];
			
			$('.tv-date input').val(today);
			
		}
		
		if( $('.current-tv-schedule').length ) {
			
			$( '.current-tv-schedule .episode-desc' ).accordion({
				collapsible: true,
				active: false,
				header: 'h5',
			});
			
			if( $('#find-a-show').length ) {
				
				$( '.current-tv-schedule .series-wrapper + .episode-wrapper .episode-desc' ).accordion('option', 'active', 0);
				
			}
			
			if( $('#airdates').length ) {
				
				// Update page title
				let seriesTitle = $('.series-results .series-title').text();
				
				$('.entry-header .entry-title').text(seriesTitle);
				
				// Update page title tag
				let pageTitle = $('html head title');
				
				let pageTitleText = $('html head title').text();
				
				let pageTitleArray = pageTitleText.split("-");
				
				let updatedPageTitle = pageTitleArray[0] + " - " + seriesTitle + " - " + pageTitleArray[1];
				
				$(pageTitle).text(updatedPageTitle);

				// Open all episode descriptions
				let episodeDescs = $( '.current-tv-schedule .episode-wrapper .episode-desc' );
								
				$(episodeDescs).each( function() {
					
					$(this).accordion('option', 'active', 0);
					
					$(this).find('.ui-accordion-header').text('Hide Episode Info');
					
				});				
			}
			
			if( $('.search-results').length ) {
				
				let searchTerm = $('.search-results .search-term').text();
				
				$('.episode-title').each( function() {
					
					let episodeTitle = $(this).text();
					
					let searchIndex = episodeTitle.toLowerCase().indexOf(searchTerm);
				
					if( searchIndex >= 0) {
						
						let searchTermLength = searchTerm.length;
						
						let firstPart = episodeTitle.slice(0, searchIndex);
						
						let searchTermText = episodeTitle.slice( searchIndex, searchIndex + searchTermLength );
						
						let lastPart = episodeTitle.slice( searchIndex + searchTermLength );
						
						$(this).html('<h3>' + firstPart + '<mark>' + searchTermText + '</mark>' + lastPart + '</h3>');
						
					}
					
				});
			}

			// Set panel headline text based on whether the panel is open or closed
			$( '.current-tv-schedule .episode-desc .ui-accordion-header').on('click keydown', function() {

				let accordionHeadline = $(this).text();
				
				console.log('Accordion Headline: ' + accordionHeadline);
				
				let show = 'Show';
				let hide = 'Hide';

				if(accordionHeadline.indexOf(show) !== -1) {	
					accordionHeadline = accordionHeadline.replace(show, hide);
				}
				else {		

					accordionHeadline = accordionHeadline.replace(hide, show);		
				}

				$(this).text(accordionHeadline);

			});
		}
			
		if( $('#tv-schedule').length ) {
				
			const defaultStation = 'all';
			const defaultDaypart = 'now';

			let selectedStation;
			let selectedDaypart;

			if(station === 'kamu' || station === 'create-tv' || station === 'pbs-kids') {

				selectedStation = $('.filter-channels input[value="' + station + '"]');

			}
			else {

				selectedStation = $('.filter-channels input[value="' + defaultStation + '"]');
			}

			// Set selected station input value
			$(selectedStation).prop('checked', true);

			if(daypart === 'all-day' || daypart === 'primetime' || daypart === 'now' ) {

				selectedDaypart = $('.filter-daypart input[value="' + daypart + '"]');
			}
			else {

				selectedDaypart = $('.filter-daypart input[value="' + defaultDaypart + '"]');
			}

			// Set selected daypart input value
			$(selectedDaypart).prop('checked', true);


			let channel = $('.tv-schedule-filters .filter-channels input:checked').val();

			filterEpisodes(date, defaultDaypart, channel);				

			// Filter episodes by channel
			$( '.tv-schedule-filters .filter-channels input').on('click keydown', function() {

				let daypart = $('.tv-schedule-filters .filter-daypart input:checked').val();
				let channel = $(this).val();

				filterEpisodes(date, daypart, channel);

			});

			// Filter episodes by daypart
			$( '.tv-schedule-filters .filter-daypart input').on('click keydown', function() {

				let daypart = $(this).val();
				let channel = $('.tv-schedule-filters .filter-channels input:checked').val();

				filterEpisodes(date, daypart, channel);

			});
		}
		
		if( $('.tv-schedule-date').length ) {
			
			var prevDate = $('.prev-date');
			var nextDate = $('.next-date');
			
			var prevDateUrl = $('.prev-date').attr('href');
			var nextDateUrl = $('.next-date').attr('href');
			
			var selectedStation = $('.filter-channels input:checked').val();
			
//			console.log('Selected Station: ' + selectedStation);
			
			var selectedDaypart = $('.filter-daypart  input:checked').val();
			
//			console.log('Selected Daypart: ' + selectedDaypart);
			
			var searchTerm = urlParams.get('search');
			
			setNextPrevLinks(prevDate, nextDate, prevDateUrl, nextDateUrl, selectedStation, selectedDaypart);
			
			// Update links if TV Station value changes
			$('.filter-channels input').on('change', function() {
			
				selectedStation = $(this).val();
				
				setNextPrevLinks(prevDate, nextDate, prevDateUrl, nextDateUrl, selectedStation, selectedDaypart);
			});
			
			// Update links if Daypart value changes
			$('.filter-daypart input').on('change', function() {
			
				selectedDaypart = $(this).val();
				
				setNextPrevLinks(prevDate, nextDate, prevDateUrl, nextDateUrl, selectedStation, selectedDaypart);
			});
			
//			// Update links if Search Schedule value changes
//			$('.tv-schedule-search').on('input', function() {
//			
//				// Wait for user to finish typing search term to update the URL			
//				setTimeout( function() {
//					searchTerm = $('.tv-schedule-search input').val();
//					
//					searchTerm = searchTerm.replace(/ /g, '+');
//				
//					setNextPrevLinks(prevDate, nextDate, prevDateUrl, nextDateUrl, selectedStation, selectedDaypart, searchTerm);
//				}, 1000);
//				
//			});
		}
		
		
		
		$('.tv-download-schedule a').on('click', function(e) {
			e.preventDefault();
			printDownloadableSchedule();
		});
		
		$('.tv-download-schedule a').on('keypress', function(e) {
			e.preventDefault();
			printDownloadableSchedule();
		});
		
	});	
	
	function setNextPrevLinks(prevDate, nextDate, prevDateUrl, nextDateUrl, station, daypart) {
		
		var params = '&station=' + station + '&daypart=' + daypart;
		
//		// If a search term is in the URL parameters - include it in the Next and Prev link URL's
//		if( searchTerm ) {
//			
//			params += '&term=' + searchTerm;
//		}
				
		$(prevDate).attr('href', prevDateUrl + params );
		$(nextDate).attr('href', nextDateUrl + params );
	}
		
	function filterEpisodes(date, daypart, channel) {
		
		const dummyDate = '01/01/2099';
		
		const now = Date.parse( $('.datetime-now').text() );
		
		const currentTime = Date.parse(dummyDate + ' ' + $('.datetime-current-time').text() );
		
		const beginPrimetime = Date.parse(dummyDate + ' ' +  $('.datetime-begin-primetime').text() );
		const endPrimetime = Date.parse(dummyDate + ' ' +  $('.datetime-end-primetime').text() );
		
		let channelDisplay = 'All';
		let daypartDisplay = 'All Day';
				
		// Filter channel for all dayparts
		switch(daypart) {
			case 'all-day':			
				
				if( channel === 'all' ) {

					$('.ui-accordion-content').css('height', 'unset');
					$('.ui-accordion-content').css('display', 'none');
					
					$('.episode-wrapper').show();
					
				} else {
					
					$('.episode-airdate').each( function() {
					
						let episodeWrapper = $(this).closest('.episode-wrapper');
						
						if(episodeWrapper !== undefined) {
							
							let isChannel = $(episodeWrapper).hasClass('channel-' + channel);

							if(isChannel) {							
								$(episodeWrapper).show();
							} else {
								$(episodeWrapper).hide();	
							}

							channelDisplay = channel;
						}	
					});
				}
				
				daypartDisplay = 'All Day';
						
				break;
					
			case 'now':

				$('.episode-airdate-details').each( function() {
					
					let episodeAirtime = Date.parse(dummyDate + ' ' +  $(this).find('.formatted-airtime').text() );
					
					let episodeWrapper = $(this).closest('.episode-wrapper');
					
					if(episodeWrapper !== undefined) {

						if( channel === 'all' ) {

							if(episodeAirtime >= currentTime) {
								$(episodeWrapper).show();
							} else {
								$(episodeWrapper).hide();	
							}

							channelDisplay = 'All';

						} else {

							let isChannel = $(episodeWrapper).hasClass('channel-' + channel);

							if(episodeAirtime >= currentTime && isChannel) {
								$(episodeWrapper).show();
							} else {
								$(episodeWrapper).hide();	
							}

							channelDisplay = channel;
						}
					}
				});
				
				daypartDisplay = 'Now';
				
				break;
					
			case 'primetime':

				$('.episode-airdate-details').each( function() {
					
					let episodeAirtime = Date.parse(dummyDate + ' ' + $(this).find('.formatted-airtime').text());
					
					let episodeWrapper = $(this).closest('.episode-wrapper');
					
					if(episodeWrapper !== undefined) {
					
						if( channel === 'all' ) {

							if(episodeAirtime >= beginPrimetime && episodeAirtime <= endPrimetime )  {
								$(episodeWrapper).show();
							} else {
								$(episodeWrapper).hide();	
							}

							channelDisplay = 'All';

						} else {

							let isChannel = $(episodeWrapper).hasClass('channel-' + channel);

							if(episodeAirtime >= beginPrimetime && episodeAirtime <= endPrimetime && isChannel )  {
								$(episodeWrapper).show();
							} else {
								$(episodeWrapper).hide();	
							}

							channelDisplay = channel;
						}
						
					}
				});
				
				daypartDisplay = 'Primetime';

				break;
					
			default:
				break;	
		}
		
		// Format the channel
		let formattedChannelDisplay = '';
			
		switch(channelDisplay) {
			case 'kamu':
				formattedChannelDisplay = 'KAMU HDTV';
				break;
			case 'create-tv':
				formattedChannelDisplay = 'Create TV';
				break;
			case 'pbs-kids':
				formattedChannelDisplay = 'KAMU PBS Kids';
				break;
			default:
				formattedChannelDisplay = 'All';
				break;
				
		}
		
		// Set the current filters for print schedule
		$('.tv-current-filters .current-channel').text(formattedChannelDisplay);
		$('.tv-current-filters .current-daypart').text(daypartDisplay);

		
		// Display or remove the no episodes found message
		let visibleEpisodes = 0;
		
		$('.episode-wrapper').each( function() {
			
			if( $(this).css('display') !== 'none') {
				visibleEpisodes++;
			}
		});
		
		let noEpisodesFound = '<p id="no-episodes-msg"><strong>Sorry, no episodes were found on ' + formattedChannelDisplay + ' during that time.</strong></p>';
		
		$('#no-episodes-msg').remove();
		
		if(visibleEpisodes === 0) {
						
			$('.no-episodes').prepend( noEpisodesFound );
		}	
		else {
			
			$('#no-episodes-msg').remove();
		}
	}
	
	function printDownloadableSchedule(e) {
				
		var printSchedule = $('#tv-schedule').clone();
				
		// Set visibility
		$(printSchedule).find('.episode-details').css('display', 'block');
		//$(printSchedule).find('.current-tv-params .clear-results').css('display', 'none');
		
		$(printSchedule).find('.tv-schedule-form .tv-schedule-date').css('display', 'none');
		$(printSchedule).find('.tv-schedule-form .tv-schedule-filters').css('display', 'none');
		$(printSchedule).find('.tv-schedule-form .tv-schedule-search').css('display', 'none');
		$(printSchedule).find('.tv-current-filters').css('display', 'none');
		$(printSchedule).find('.datetime-now').css('display', 'none');
		$(printSchedule).find('.datetime-current-time').css('display', 'none');
		$(printSchedule).find('.datetime-begin-primetime').css('display', 'none');
		$(printSchedule).find('.datetime-end-primetime').css('display', 'none');

		var station = $('.tv-current-filters .current-channel').text();
		var daypart = $('.tv-current-filters .current-daypart').text();
		
		var date = $('.tv-schedule-date h2').text();
	
		$(printSchedule).prepend('<p class="airdate"><strong>Date:</strong> ' + date + '</p>');
		$(printSchedule).prepend('<p class="airdate"><strong>Station:</strong> ' + station + '</p>');
		$(printSchedule).prepend('<p class="airdate"><strong>Time:</strong> ' + daypart + '</p>');
		
		$(printSchedule).find('.episode-airdate .year').css('display', 'none');
		$(printSchedule).find('.episode-airdate .formatted-airtime').css('display', 'none');
		$(printSchedule).find('.episode-airdate .formatted-airdate').css('display', 'none');
		
		$(printSchedule).find('.episode-desc .ui-accordion-header').css('display', 'none');
		$(printSchedule).find('.episode-desc .ui-accordion-content').css('display', 'block');
		$(printSchedule).find('.episode-desc .ui-accordion-content .btn').css('display', 'none');
		
		var printStyles = '';
		
		printStyles += '<link rel="preconnect" href="http://fonts.googleapis.com">';
		printStyles += '<link rel="preconnect" href="http://fonts.gstatic.com" crossorigin>';
		printStyles += '<link href="http://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500&display=swap" rel="stylesheet">';
		
		printStyles += '<style>';
		
		printStyles += '@font-face { font-family: "PBSSans"; src: url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/EOT/PBSSans.eot"); src: url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/EOT/PBSSans.eot?#iefix") format("embedded-opentype"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/TTF/PBSSans.ttf") format("truetype"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/SVG/PBSSans.svg#PBSSans") format("svg"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/WOFF2/PBSSans.woff2") format("woff2"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/WOFF/PBSSans.woff") format("woff"); font-weight: normal; font-style: normal; }';		
		
		printStyles += '@font-face { font-family: "PBSSans-Bold"; src: url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/EOT/PBSSans-Bold.eot"); src: url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/EOT/PBSSans-Bold.eot?#iefix") format("embedded-opentype"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/TTF/PBSSans-Bold.ttf") format("truetype"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/SVG/PBSSans-Bold.svg#PBSSans-Bold") format("svg"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/WOFF2/PBSSans-Bold.woff2") format("woff2"), url("http://kamudev.wpengine.com/wp-content/themes/kamu/src/fonts/WOFF/PBSSans-Bold.woff") format("woff"); font-weight: normal; font-style: normal; }';		
		
		printStyles += 'h1 {font-family: "Oswald", sans-serif; font-style: normal; font-weight: 400; text-transform: uppercase; color: #500000;}';
		
		printStyles += 'h2 {font-family: "Oswald", sans-serif; font-style: normal; font-weight: 400; text-transform: none; color: #000000;}';
		
		printStyles += '.airdate {font-family: "PBSSans", sans-serif; font-style: normal; font-weight: 400; text-transform: none; color: #000000;}';
		
		printStyles += '.tv-current-filters p {font-family: "PBSSans", sans-serif; font-style: normal; font-weight: 400; text-transform: none; color: #000000;}';
		
		printStyles += '.tv-current-filters p strong {display: inline;}';
		printStyles += '.tv-current-filters p span {display: inline;}';
		
		printStyles += '.current-tv-schedule .episode-wrapper {page-break-inside: avoid;}';
		
		printStyles += '.current-tv-schedule .episode-wrapper {margin-bottom: 0; padding-bottom: 0; border-bottom: 1px solid #B4B4B4;}';
		
		printStyles += '.current-tv-schedule .series-title h3 {font-size: 28px; font-family: "Oswald", sans-serif; font-style: normal; font-weight: 400; text-transform: capitalize; color: #007BC3; margin-bottom: 0px; padding-bottom: 4px; border-bottom: 2px solid #007BC3;}';
		
		printStyles += '.current-tv-schedule .channel-name h4 {font-size: 18px; font-family: "PBSSans-Bold", sans-serif; text-transform: uppercase; font-weight: 600; text-align: right; margin-bottom: 0px; color: #000000;}';
		
		printStyles += '.current-tv-schedule .episode-title h4 {font-size: 18px; font-family: "PBSSans-Bold", sans-serif;     text-transform: uppercase; font-weight: 600; margin-bottom: 0px; color: #3B3B3B;}';
		
		printStyles += '.current-tv-schedule .episode-airdate-details {font-family: "PBSSans", sans-serif; margin-top: 15px; margin-bottom: 0px; padding-bottom: 0px; }';
		
		printStyles += '.current-tv-schedule .episode-details p {font-family: "PBSSans", sans-serif; margin-bottom: 0px; padding-bottom: 0px; }';
		
		printStyles += '.current-tv-schedule .episode-header .container {display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; margin-bottom: 0; }';
		
		printStyles += '.current-tv-params .tv-param-list {display: flex; flex-direction: row; justify-content: flex-start;}';
		
		printStyles += '.current-tv-params .tv-param-list em {font-family: "PBSSans", sans-serif; padding: 0; margin: 0;}';
		
		printStyles += '.current-tv-params .tv-param-list ul {font-family: "PBSSans", sans-serif; list-style: none; padding:0 0 0 6px; margin: 0;}';
		
		printStyles += '.bootstrap-wrapper .col-md-8 {-webkit-box-flex: 0; -webkit-flex: 0 0 66.6666666667%; -ms-flex: 0 0 66.6666666667%; flex: 0 0 66.6666666667%; max-width: 66.6666666667%;}';
		
		printStyles += '.bootstrap-wrapper .col-md-4 {-webkit-box-flex: 0; -webkit-flex: 0 0 33.3333333333%; -ms-flex: 0 0 33.3333333333%; flex: 0 0 33.3333333333%; max-width: 33.3333333333%;}';
		
		printStyles += '</style>';
		
		
		printElem( $(printSchedule).html(), printStyles );	

	}
	
	function printElem(elem, styles)
	{		
		var mywindow = window.open('', 'PRINT', 'height=600,width=800');

		mywindow.document.write('<html><head><title>' + document.title  + '</title>');		
		mywindow.document.write(styles);
		mywindow.document.write('</head><body >');
		
		mywindow.document.write('<h1>TV Schedule</h1>');
		mywindow.document.write('<div class="download-tv-schedule">');
		
		mywindow.document.write(elem);
		
		mywindow.document.write('</div></body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();

		return true;
	}

});