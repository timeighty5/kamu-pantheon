<?php
/*
Plugin Name: KAMU TV Schedule
Description: A custom plugin to display the KAMU TV schedule.
Version: 1.1
Author: Inventive Group - Ryan Chadek
Author URI: https://inventive.io/
*/

$tv_schedule_path = '/tv/tv-schedule/';

/* Load Theme Stylesheet */
function enqueue_tv_plugin_styles() {
	/* Plugin Stylesheet */			
	wp_enqueue_style( 'kamu-tv-schedule-styles', plugins_url() . '/kamu-tv-schedule/css/kamu-tv-schedule-styles.css' );
	
}
add_action( 'wp_enqueue_scripts', 'enqueue_tv_plugin_styles', PHP_INT_MAX);

/* Load Custom Scripts */
function enqueue_tv_plugin_scripts() {			
	/* Custom Scripts */	
	wp_register_script('kamu-tv', plugins_url() . '/kamu-tv-schedule/js/kamu-tv.js', array( 'jquery' ), '1.0' );
	wp_enqueue_script('kamu-tv');
}
add_action( 'wp_enqueue_scripts', 'enqueue_tv_plugin_scripts');


/* Schedule XML Update */
//if( ! wp_next_scheduled( 'download_tv_schedules' ) ) {
	wp_schedule_event( strtotime( '2am' ), 'daily', 'download_tv_schedules' );
//}



// Connect via FTP and download the latest XML files
function download_tv_schedules($filenames) {	
	
	try
	{	
		// Open FTP connection to source
    	//$remote_sftp = new SFTPConnection('165.95.13.28', 22);
    	//$remote_sftp->login('xmlexpt', 'kamu397jdj^%kd6839');
		
		$sftp = new SFTPConnection('165.95.13.28', 22);
		$sftp->login('xmlexpt', 'kamu397jdj^%kd6839');
				
		// Open FTP connection to local
		//$local_sftp = new SFTPConnection('kamudev.sftp.wpengine.com', 2222);
		//$local_sftp->login('kamudev-team-kamu', 'vQ%Wt0mbG7*I');
		
		foreach($filenames as $filename) {
			
			$remote_filename = $filename;
			
			$remote_filepath = '/u/protrack/xmlexpt/' . $remote_filename;
									
			$remote_file_contents = $sftp->getFileContents($remote_filepath);
						
			//$local_sftp->updateSchedule($remote_filename, $remote_file_contents);
			
			updateSchedule($remote_filename, $remote_file_contents);
		}
		
	}
	catch (Exception $e)
	{
		echo $e->getMessage() . '\n';
	}
	
	
}


/* TV Schedule Form Shortcode */
function tv_schedule_form_func () { 
	
	date_default_timezone_set('America/Chicago');
	
	$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	$base_url = explode('?', $current_url);
	
	$arr = get_url_parameters();
	
	$is_valid_date = validate_date($arr['date']);
	
	// If there's a date parameter in the URL make sure it's valid 
	// If so - set the date to display
	if($is_valid_date && strlen($arr['date']) !== 0 ) {
		
		$url_date = strtotime($arr['date']);

		$formatted_date = date('l, M j', $url_date);

		$prev_date = date('Y-m-d', strtotime($arr['date'] .' -1 day'));
		$next_date = date('Y-m-d', strtotime($arr['date'] .' +1 day'));		
	}
	else {
		// Default to today
		$today = date('Y-m-d');		
		$formatted_date = date('l, M j');
		
		$prev_date = date('Y-m-d', strtotime($today .' -1 day'));
		$next_date = date('Y-m-d', strtotime($today .' +1 day'));
	}
	
	$prev_date = $base_url[0] . '?date=' . $prev_date;
	$next_date = $base_url[0] . '?date=' . $next_date;
		
	// TV Schedule - Current Filters
	$tv_form = '<div class="tv-current-filters">';
	$tv_form .= '<p><strong>Channel:</strong> <span class="current-channel"></span></p>';
	$tv_form .= '<p><strong>Time:</strong> <span class="current-daypart"></span></p>';
	$tv_form .= '</div>';
	
	
	// TV Schedule - Date Nav
	$tv_form .= '<div class="tv-schedule-date"><a href="'. $prev_date .'" class="prev-date"><i class="fa fa-chevron-left"></i></a><h2 class="text-center">'. $formatted_date .'<i class="fal fa-calendar-alt"></i></h2><a href="'. $next_date .'" class="next-date"><i class="fa fa-chevron-right"></i></a></div>';
	
	// TV Schedule - Filters
	$tv_form .= '<div class="tv-schedule-filters">';
	$tv_form .= do_shortcode('[wpforms id="1163" title="false"]');
	$tv_form .= '<div class="tv-download-schedule text-center"><a href="#" id="kamu-download-tv-schedule" target="_blank">Print Schedule <i class="fal fa-download"></i></a></div>';
	$tv_form .= '</div>';
	
	// TV Schedule - Search
	$tv_form .= '<div class="tv-schedule-search">';
	$tv_form .= do_shortcode('[wpforms id="1164" title="false"]');
	$tv_form .= '</div>';
			
	return $tv_form;
}
add_shortcode( 'tv_schedule_form', 'tv_schedule_form_func');


/* TV Schedule Output Shortcode */ 
function tv_schedule_func ($atts) { 
	
	$output;
	
	$params = get_params($atts);

	$date = $params['date'];
	$station = $params['station'];
	$daypart = $params['daypart'];
	$search = $params['search'];
	$show = $params['show'];
		
	$tv_schedules = get_tv_schedule($date, $station, $daypart, $search, $show);
	
	$tv_schedules = get_combined_schedules($tv_schedules);
	
	$output = get_results($tv_schedules, $date, $station, $daypart, $search, $show);
		
	return $output;	
	
}
add_shortcode( 'tv_schedule', 'tv_schedule_func');


function whats_on_tonight_func($atts) {
		
	$args = shortcode_atts( array(
		
		'date'    => '',
		'station' => 'kamu',
		'daypart' => 'primetime',
		'search'  => '',
		'show' => '',
	
	), $atts);
	
	$date = $args['date'];
	$station = $args['station'];
	$daypart = $args['daypart'];
	$search = $args['search'];
	$show = $ags['show'];
	
	$tv_schedules = get_tv_schedule($date, $station, $daypart, $search, $show);
	
	$tv_schedules = get_combined_schedules($tv_schedules);
		
	$results_tonight = get_results_tonight($tv_schedules, $station);	
	
	return $results_tonight;
}
add_shortcode( 'whats_on_tonight', 'whats_on_tonight_func');


function search_results_func($atts) {
		
	global $tv_schedule_path;
	
	define('EPISODES_LIMIT', 3);
	
	$output;	
	$tv_form;
	
	$date = 'all';
	
	$params = get_params($atts);
	
	//$date = $params['date'];
	$station = $params['station'];
	$daypart = $params['daypart'];
	$search = $params['search'];
	$show = $params['show'];
	
	//$clear_results = '?date=' . $date . '&station=' . $station . '&daypart=' . $daypart;
	
	// TV Schedule - Search
	$tv_form .= '<div class="tv-schedule-search">';
	$tv_form .= do_shortcode('[wpforms id="1164" title="false"]');
	$tv_form .= '</div>';
	
	$output .= $tv_form;
			
	if($search !== '') {
		
		$output .= '<div class="current-tv-params">';
		$output .= '<div class="container">';
		$output .= '<div class="section-column col-md-8 search-results">';
		$output .= '<em>Showing results for:</em>';
		$output .= '<ul>';
			
			$search = str_replace('%20', ' ', $search);
			
			$output .= '<li class="search-term">' . $search . '</li>';

		$output .= '</ul>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '<div class="section-column col-md-4 clear-results">';
		$output .= '<p><strong><a href="'. $tv_schedule_path .'">Clear Search Results</a></strong></p>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';
		$output .= '</div> <!-- // current-tv-params -->';
		
	}

	// Get all shows that contain the search term
	$tv_schedules = get_tv_schedule($date, $station, $daypart, $search, $show);
		
	// Combine all the tv schedules into one array of episodes
	$tv_schedules = get_combined_schedules($tv_schedules);

	$temp_tv_schedules = [];
	
	// Get all episodes that have the search term
	for($x=0; $x < sizeof($tv_schedules); $x++) {		
			
			// Check to see if the search term is in the episode title or series title
			if( stripos($tv_schedules[$x]['episode_title'], $search) !== false || stripos($tv_schedules[$x]['series_title'], $search) !== false ) {
				
				array_push($temp_tv_schedules, $tv_schedules[$x]);
			}
			// Check to see if the search term is in the episode description
			elseif( stripos($tv_schedules[$x]['episode_desc'], $search) !== false ) { 
				
				array_push($temp_tv_schedules, $tv_schedules[$x]);
			}

	}
	
	if(!empty($temp_tv_schedules)) {
		
		// Update TV Schedules with only episodes matching search term
		$tv_schedules = $temp_tv_schedules;

		$show_titles = [];

		// Get all show titles in combined search results
		foreach($tv_schedules as $show) {

			array_push($show_titles, $show['series_title']);
		}

		// Work backwards through the show titles array and remove any search results that have more than our specified episode limit
		for($i=sizeof($show_titles); $i > 0; $i-- ) {

			$title_count = array_count_values_of($show_titles[$i], $show_titles);

			if($title_count > EPISODES_LIMIT) {

				unset($show_titles[$i]);
				unset($tv_schedules[$i]);
			}
		}
		
//		echo '<pre>';
//		print_r($tv_schedules);
//		echo '</pre>';

		$output .= get_results($tv_schedules, $date, $station, $daypart, $search, $show);
		
	}
	else {
		
		$output .= '<p><strong>Sorry, no results found for that search term!</strong></p>';
	}
		
	return $output;
	
}
add_shortcode( 'tv_schedule_search_results', 'search_results_func');


function airdates_results_func($atts) {
	
	$date = 'all';
	
	//$params = get_params($atts);
	
	$arr = get_url_parameters();
	
	$search = '';
	$show = $arr['show'];
	
	// Get all shows that contain the search term
	$tv_schedules = get_tv_schedule($date, $station, $daypart, $search, $show);
	
	// Combine all the tv schedules into one array of episodes
	$tv_schedules = get_combined_schedules($tv_schedules);

	$temp_tv_schedules = [];
	
	// Get all episodes that have the show term
	for($x=0; $x < sizeof($tv_schedules); $x++) {	
		
		// Break out the show terms separated by dashes
		$show_terms = explode('-', $show);
		
		$matching_series = FALSE;
		$matching_terms = [];
		
		foreach($show_terms as $term) {
			
			// Check to see if the show term is in the series title
			if( stripos($tv_schedules[$x]['series_title'], $term) !== false ) {
				
				array_push($matching_terms, $term);
				//$matching_series = TRUE;	
			}
		}		
		
		if(sizeof($show_terms) === sizeof($matching_terms)) {
			$matching_series = TRUE;
		}
		
		if($matching_series) {
			array_push($temp_tv_schedules, $tv_schedules[$x]);
		}
	}
	
	// Update TV Schedules with only episodes matching search term
	$tv_schedules = $temp_tv_schedules;
	
	$output .= get_results($tv_schedules, $date, $station, $daypart, $search, $show);
	
	return $output;
	
}
add_shortcode( 'airdates_results', 'airdates_results_func' );


// Takes an array of arrays of TV Schedules from each station and combines them into one array
function get_combined_schedules($tv_schedules) {
	
	$combined_schedules_array = [];
	
	foreach($tv_schedules as $tv_schedule_channel) {
		
		foreach($tv_schedule_channel as $tv_schedule) {
			
			array_push($combined_schedules_array, $tv_schedule);
		}
	}
	
	// Sort combined schedules
	mknatsort($combined_schedules_array, array('episode_datetime'));

	return $combined_schedules_array;
}


function get_params($atts) {
	
	$params = [];
	
	$args = shortcode_atts( array(
		
		'date'    => '',
		'station' => 'kamu',
		'daypart' => 'all-day',
		'search'  => '',
	
	), $atts);
		
	$date      = $args['date'];
	$station   = $args['station'];
	$daypart   = $args['daypart'];
	$search    = $args['search'];
	
	$arr = get_url_parameters();
	
	if($arr['date']) {
		$date = $arr['date'];
	}
	
	if($arr['station']) {
		$station = $arr['station'];
	}
	
	if($arr['daypart']) {
		$daypart = $arr['daypart'];
	}
	
	if($arr['search']) {
		$search = $arr['search'];
	}
	
	$params['date'] = $date;
	$params['station'] = $station;
	$params['daypart'] = $daypart;
	$params['search'] = $search;
	
	
	return $params;
}

function get_tv_schedule($date, $station, $daypart, $search, $show) {
		
	define('BASE_URL', 'https://' . $_SERVER['SERVER_NAME']);
	define('KAMU_XML', '12-1.xml');
	define('CREATE_TV_XML', '12-2.xml');
	define('PBS_KIDS_XML', '12-3.xml');
	define('TWENTY_FOUR_HOURS', 1*24*60*60);
	define('XML_FILEPATH', plugins_url() . '/kamu-tv-schedule/xml/');
		
	date_default_timezone_set('America/Chicago');
	
	$tv_schedules = [];
	
	$filename = '';
	$filenames = [KAMU_XML, CREATE_TV_XML, PBS_KIDS_XML];
	
	// Check if the local file needs to be updated
	if( needs_update(KAMU_XML) || needs_update(CREATE_TV_XML) || needs_update(PBS_KIDS_XML) ) {
		
		// If so - connect via FTP and download updated schedules
		download_tv_schedules($filenames);
	} 
		
	/*
	switch($station) {
		case 'kamu':				
			$filename = KAMU_XML;
			break;
		case 'create-tv':
			$filename = CREATE_TV_XML;
			break;
		case 'pbs-kids':
			$filename = PBS_KIDS_XML;
			break;
		default:
			$filename = KAMU_XML;			
			break;
	}*/
	
	
	// Check if the local file needs to be updated
	/*if( needs_update($filename) ) {		

		// If so - connect via FTP and download updated schedules
		download_tv_schedules($filenames);
	}*/
	
	foreach($filenames as $filename) {
		
		$xml_feed = '';
	
		// Get the feed
		$xml_feed = file_get_contents(XML_FILEPATH . $filename);

		// Set the defaults	
		$content = simplexml_load_string($xml_feed);

		if ($content === FALSE) {
			echo 'There were errors parsing the XML file.\n';

			foreach(libxml_get_errors() as $error) {
				echo $error->message;
			}
			exit;
		}

		$objJsonDocument = json_encode($content, JSON_PRETTY_PRINT);
		$arrOutput = json_decode($objJsonDocument, TRUE);

		$series = $arrOutput['series'];
		
		$default_date = date('Y-m-d');

		if(strlen($date) !== 0) {
			$target_date = $date;
		}
		elseif($search) {
			$target_date = 'all';
			$daypart = 'all-day';
		}
		else {
			$target_date = $default_date;
		}
		
		if($search) {
			
			$tv_schedule = search_all_episodes($series, $search);
			
		}
		elseif($show) {
			
			$tv_schedule = search_all_episodes($series, $show);
		}
		else {
			
			$tv_schedule = get_episodes($series, $target_date, $daypart, $search);

		}

		array_push($tv_schedules, $tv_schedule);
		
	}
			
	return $tv_schedules;
}


function search_all_episodes($series, $search_term) {
	
	define('NOW', strtotime('now'));
	define('ONE_WEEK_AWAY', strtotime('+1 week'));
	define('EPISODES_LIMIT', 3);
	
	$target_date = 'all';
	
	$search_terms = explode('-', $search_term);
	
	$matching_episodes = [];
	
	foreach($series as $show) {
		
		
//					echo '<pre>';
//					print_r($show);
//					echo '</pre>';
		
		$temp_array = [];
		
		foreach($search_terms as $term) {
		
			// Push the series if the search term is found in the series title
			if( stripos($show['series_title'], $term) !== false ) {

				array_push($temp_array, $show);

			}
			else {

				// Populate episode titles if the title is an empty
				foreach($show['episode'] as $episode) {
					
					// The episode title is blank
					//if( is_array($episode['episode_title']) ) {
					if( is_array($episode['episode_title']) && empty($episode['episode_title']) ) {
											
						if(sizeof($episode['episode_number']) > 0) {
							$show['episode']['episode_title'] = 'Episode #' . $episode['episode_number'];
						}
					}

					// The episode description is blank
					//if( is_array($episode['episode_desc']) ) {
					if( is_array($episode['episode_desc']) && empty($episode['episode_desc']) ) {

						$show['episode']['episode_desc'] = $show['series_desc'];
					}
					
					
				}

				// Push the series if the search term is found in the episode title
				if( stripos($episode['episode_title'], $term) !== false ) {

					array_push($temp_array, $show);

				}

				// Push the series if the search term is found in the episode description								
				if( stripos($episode['episode_desc'], $term) !== false ) {

					array_push($temp_array, $show);

				}
			}
		}

		if(sizeof($temp_array) > 0) {
			$matching_episodes[$show['series_title']] = $temp_array;				
		}
				
	}
		
	$search_results = [];
	
	// Process search results
	foreach($matching_episodes as $series) {
		
		foreach($series as $show) {

			$series_title = $show['series_title'];
			
			// More than one episode in this series
			if(sizeof['episode']['episode_title'] === 0) {
								
				$episode_ctr = 0;

				foreach($show['episode'] as $episode) {

					if($episode_ctr < EPISODES_LIMIT) {

						// Multiple scheduled dates of this episode
						if(sizeof($episode['schedule']['schedule_date']) === 0) {

							foreach($episode['schedule'] as $schedule) {

								$episode_datetime = strtotime($schedule['schedule_date']);

								if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {

									$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

									if( !in_array($formatted_episode, $search_results)) {
										array_push($search_results, $formatted_episode);	
									}
								} 
							}
						}
						else { // only one scheduled date of this episode

							$episode_datetime = strtotime($schedule['schedule_date']);

							if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {
								
								$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

								array_push($search_results, $formatted_episode);

							}
						} 
					}


				}

				$episode_ctr++;
				
			}
			else { // only one episode in this series
				
				if( sizeof($show['episode']['episode_title']) === 0 ) {
										
					$episode_ctr = 0;

					foreach($show['episode'] as $episode) {
						
//						echo '<p>Episode Title 2: ' . $episode['episode_title'] . '</p>';
						
						if( strlen($episode['episode_title']) === 1 ) {
							
//							echo '<pre>Show Episode Details: ';
//							print_r($show['episode']);
//							echo '</pre>';
						}
						
						if($episode_ctr < EPISODES_LIMIT) {

							// Multiple scheduled dates of this episode
							if(sizeof($episode['schedule']['schedule_date']) === 0) {

								foreach($episode['schedule'] as $schedule) {

									$episode_datetime = strtotime($schedule['schedule_date']);

									if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {

										$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

										if( !in_array($formatted_episode, $search_results)) {
											array_push($search_results, $formatted_episode);	
										}
									} 
								}
							}
							else { // only one scheduled date of this episode

								$episode_datetime = strtotime($schedule['schedule_date']);

								if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {

									$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

									array_push($search_results, $formatted_episode);

								}
							} 
						}


					}

					$episode_ctr++;
					
				}
				else {
					
					$episode_ctr = 0;
					
					// Multiple air dates exist for this episode
					if(sizeof($show['episode']['schedule']) === 0) {
						
//						echo '<p>Episode Schedule === 0</p>';
						
						foreach($show['episode']['schedule'] as $schedule) {
							
							$episode_datetime = strtotime($schedule['schedule_date']);
							
							if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {
								
								$episode['episode_title'] = $episode_title;

								$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

								if( !in_array($formatted_episode, $search_results)) {
									array_push($search_results, $formatted_episode);	
								}
							} 
						}
						
					}
					else {
						
						echo '<p>Episode Schedule !== 0</p>';
						
						// Multiple air dates exist for this episode
						if(sizeof($show['episode']['schedule']['schedule_date']) === 0) {
							
//							echo '<p>Episode Schedule Date === 0</p>';
							
							foreach($show['episode']['schedule'] as $schedule) {
								
								$episode_datetime = strtotime($schedule['schedule_date']);
								
								$episode['episode_title'] = $episode_title;
								
								$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

								if( !in_array($formatted_episode, $search_results)) {
									array_push($search_results, $formatted_episode);	
								}
							}
							
						}
						else {
//							
//							echo '<p>Episode Schedule Date !== 0</p>';
							
							$episode_datetime = strtotime($show['episode']['schedule']['schedule_date']);

							if( $episode_datetime >= NOW && $episode_datetime <= ONE_WEEK_AWAY) {
								
								$episode['episode_title'] = $episode_title;

								$formatted_episode = get_formatted_episode($show, $episode, $episode_datetime);

								if( !in_array($formatted_episode, $search_results)) {
									array_push($search_results, $formatted_episode);	
								}
							} 
						}
					}	
				}				
 			}
		}
	}
	
//	echo '<pre>';
//	print_r($search_results);
//	echo '</pre>';
	
	$show_titles = [];
		
	// Sort Current Episodes
	mknatsort($search_results, array('series_title'));
			
	// Remove any duplicates
	$temp = array();
	$keys = array();
	
	foreach($search_results as $result ) {	
		
		foreach ($result as $key => $data) {

			//unset($data['episode_datetime']);

			if ( !in_array($data, $temp) ) {

				$temp[] = $data;				
				$keys[$key] = true;
			}
		}
	}
	
	return $search_results;
}


function get_episodes($series, $target_date, $daypart, $search) {
	
	$current_shows = [];
	$current_episodes = [];
	$episode_results = [];
			
	/* Process and display the contents of the XML file*/
	foreach($series as $show) {

		// Gather all the air dates for this show
		foreach( $show['episode'] as $episode ) {

			$air_dates_array = [];

			if( isset($episode['schedule']) && is_array($episode['schedule']) ) {

				if( array_key_exists('schedule', $episode) ) {

					// Add schedule dates to Air Dates array
					foreach($episode['schedule'] as $schedule) {

						if(isset($schedule['schedule_date'])) {

							array_push($air_dates_array, $schedule['schedule_date']);

						}
						else {

							if (preg_match('/:/', $schedule)) {

								array_push($air_dates_array, $schedule);

							}
						}

					} // end foreach

				} // endif array_key_exists

			} // endif 

			// If target date found in Air Dates Array, add it to the Current Shows array
			for($x=0; $x < sizeof($air_dates_array); $x++) {

				$show_air_date = strtotime($air_dates_array[$x]);

				$formatted_show_air_date = date('Y-m-d', $show_air_date);

				if ($target_date === $formatted_show_air_date) {

					array_push($current_shows, $show);	

				}
			} // end for loop

		} // end foreach $episode

	} // end foreach $show
	

	// No shows found
	if(empty($current_shows)) {		
		$episode_results = '<h2>No Shows Found</h2>';
		$episode_results .= '<p>Sorry, we couldn\'t find any shows on that day.</p>';
	}
	else { // Get episodes
		
		foreach($current_shows as $series) {

			foreach($series['episode'] as $episode_details) {

				$episode_title = '';
				$episode_num = '';
				$episode_desc = '';
				$episode_airdate = '';

				// If multiple airdates for this episode exist
				if ( strlen($episode_details['schedule']['schedule_date']) === 0 ) {

					foreach($episode_details['schedule'] as $this_showing) {

						$airdate = $this_showing['schedule_date'];
						
						$formatted_airdate = get_formatted_airdate($airdate, 'Y-m-d');

						if( $formatted_airdate === $target_date ) {
							
							$episode_datetime = strtotime($airdate);

							$episode_to_add = get_formatted_episode($series, $episode_details, $episode_datetime);

							array_push($current_episodes, $episode_to_add);
						}
						
						
					}
				}
				else { // Only one airdate for this episode exists

					$airdate = $episode_details['schedule']['schedule_date'];
					
					$formatted_airdate = get_formatted_airdate($airdate, 'Y-m-d');

						if( $formatted_airdate === $target_date ) {

							$episode_datetime = strtotime($airdate);

							$episode_to_add = get_formatted_episode($series, $episode_details, $episode_datetime);

							array_push($current_episodes, $episode_to_add);
						}
				}	
			}
		}
	
		// Sort Current Episodes
		mknatsort($current_episodes, array('episode_datetime'));

		// Remove any duplicates
		$temp = array();
		$keys = array();

		foreach ($current_episodes as $key => $data) {

			unset($data['episode_datetime']);

			if ( !in_array($data, $temp) ) {

				$temp[] = $data;				
				$keys[$key] = true;
			}
		}

		// Get all unique episodes
		$unique_episodes = array_intersect_key($current_episodes, $keys);

		// Filter episodes by Daypart
		if($daypart === 'primetime' ) {

			$daypart_episodes = [];

			$primetime_start = '7:00 pm';
			$primetime_end = '10:00 pm';

			$start_time = DateTime::createFromFormat('h:i a', $primetime_start);
			$end_time = DateTime::createFromFormat('h:i a', $primetime_end);

			// Add primetime episodes to daypart episodes array
			foreach($unique_episodes as $episode) {

				$show_time = DateTime::createFromFormat('h:i a', $episode['episode_airtime']);

				if($show_time >= $start_time && $show_time < $end_time) {

					array_push($daypart_episodes, $episode);
				}
			}

			$unique_episodes = $daypart_episodes;
		}

		// Check for search 
		if($search) {

			$search_results_episodes = [];

			$search = str_replace('%20', ' ', $search);

			foreach($unique_episodes as $episode) {

				// Check episode elements for search
				if( strpos(strtolower( $episode['series_title'] ), strtolower( $search )) !== false ) {	

					array_push($search_results_episodes, $episode);
				}
				else if( strpos(strtolower( $episode['episode_title'] ), strtolower( $search )) !== false ) {

					array_push($search_results_episodes, $episode);
				}
				else if( strpos(strtolower($episode['episode_num'] ), strtolower( $search )) !== false ) {

					array_push($search_results_episodes, $episode);
				}
				else if( strpos(strtolower( $episode['episode_desc'] ), strtolower( $search )) !== false ) {

					array_push($search_results_episodes, $episode);

				}
			}

			if(sizeof($search_results_episodes) > 0) {
				$episode_results = $search_results_episodes;
			}
			else {
				$episode_results = '<h2>No Shows Found</h2>';
				$episode_results .= '<p>Sorry, we couldn\'t find any shows on this day with that search search.</p>';
			}

		}
		else { // No search search present. Display all episodes on this day.	

			$episode_results = $unique_episodes;
		}
		
	}
		
	return $episode_results;
}


/*
// Add episode to current episodes array if episode date matches target date
function process_episode($series, $current_episodes, $episode_details, $target_date, $airdate) {
	
	$formatted_airdate = get_formatted_airdate($airdate, 'Y-m-d');

	if( $formatted_airdate === $target_date ) {

		$episode_to_add = get_formatted_episode($series, $episode_details, $airdate);
		
		array_push($current_episodes, $episode_to_add);
	}
	
	return $current_episodes;
}*/


// Desearchine if the local file was modified over 24 hours ago and needs to be updated
function needs_update($filename) {
	
	$result = false;
	
	$time = time();
	
	$time_diff = $time - filemtime($filename);
	
	clearstatcache(true, XML_FILEPATH . $filename);	

	if(!file_exists($filename) ) {
		
		$result = true;
	}
	else if( $time_diff >= TWENTY_FOUR_HOURS ) {
		
		$result = true;
	}
	/*
	else {
		
		echo $filename . ' was last modified: ' . date ('F d Y H:i:s.', filemtime($filename));
	}*/
		
	return $result;
}

// Check whether date is valid
function validate_date($date, $format = 'Y-m-d')
{
	$result = false;
	
    $d = DateTime::createFromFormat($format, $date);
	
	if($d && $d->format($format) === $date) {
		$result = true;
	}
	
	return $result;
}

// Return the formatted Episode airdate
function get_formatted_airdate($airdate, $format) {
	
	$this_airdate = strtotime($airdate);
	
	//$formatted_airdate = date('Y-m-d', $this_airdate);
	
	$formatted_airdate = date($format, $this_airdate);
	
	return $formatted_airdate;
} 

// Return the formatted Episode airtime
function get_formatted_airtime($airdate) {
	
	$this_airdate = strtotime($airdate);
	
	$formatted_airtime = date('g:i a', $this_airdate);
	
	return $formatted_airtime;
} 

// Return a formatted Episode array
function get_formatted_episode($series, $episode, $airdatetime) {
		
	$series_title = $series['series_title'];
	
	if( is_array($series['series_desc']) && empty($series['series_desc'])) {
		$series_desc = 'No description available.';	
	}
	else {
		$series_desc = $series['series_desc'];	
	}
		
//	echo '<pre>Episode Title: ';
//	echo $episode['episode_title'];
//	echo '</pre>';
	
	if(is_array($episode['episode_title']) && empty($episode['episode_title'])) {
		$episode_title = 'Episode #'. $episode['episode_number'];
	}
	else {
		$episode_title = $episode['episode_title'];	
	}
				
	$episode_num = $episode['episode_number'];

					
	if( empty( $episode['episode_desc'] ) ) {
		
		if( empty( $series['series_desc'] ) ) {
			$episode_desc = 'No description available.';
		}
		else {
			$episode_desc = $series['series_desc'];		
		}
	}
	else {
		$episode_desc = $episode['episode_desc'];
	}
		
	if(sizeof($episode['schedule'][0]) === 0) {
		
		$schedule_id = $episode['schedule']['schedule_id'];
		$schedule_channel = $episode['schedule']['schedule_channel'];
	}
	else {
		$schedule_id = $episode['schedule'][0]['schedule_id'];
		$schedule_channel = $episode['schedule'][0]['schedule_channel'];
	}
	
	switch($schedule_channel) {
		case '12-1':
			$channel = 'kamu';
			break;
		case '12-2';
			$channel = 'create-tv';
			break;
		case '12-3';
			$channel = 'pbs-kids';
			break;
		default:
			$channel = '';
			break;
			
	}
	
	$episode_date = date('Y-m-d', $airdatetime);
	$episode_day_month = date('l, M. j', $airdatetime);
	$episode_airtime = date('g:i a', $airdatetime);
	$episode_day = date('d', $airdatetime);
	$episode_month = date('m', $airdatetime);
	$episode_year = date('Y', $airdatetime);
	
	$formatted_airtime = date('G:i:s', $airdatetime);
	$formatted_airdate = date('Y-m-d H:i:s', $airdatetime);
	
	$episode_datetime = $airdatetime;

	$episode_info = [
		'channel' => $channel,
		'series_title' => $series_title,
		'series_desc' => $series_desc,
		'episode_title' => $episode_title,
		'episode_num' => $episode_num,
		'episode_desc' => $episode_desc,
		'episode_date' => $episode_date,
		'episode_day' => $episode_day,
		'episode_month' => $episode_month,
		'episode_year' => $episode_year,
		'episode_day_month' => $episode_day_month,
		'episode_airtime' => $episode_airtime,
		'formatted_airtime' => $formatted_airtime,
		'formatted_airdate' => $formatted_airdate,
		'episode_datetime' => $episode_datetime,
		'schedule_id' => $schedule_id,
	];
	
	return $episode_info;
}


function mknatsort ( &$data_array, $keys, $reverse=false, $ignorecase=false ) {
    // make sure $keys is an array
    if (!is_array($keys)) $keys = array($keys);
    usort($data_array, sortcompare($keys, $reverse, $ignorecase) );
}

function sortcompare($keys, $reverse=false, $ignorecase=false) {
    return function ($a, $b) use ($keys, $reverse, $ignorecase) {
        $cnt=0;
        // check each key in the order specified
        foreach ( $keys as $key ) {
            // check the value for ignorecase and do natural compare accordingly
            $ignore = is_array($ignorecase) ? $ignorecase[$cnt] : $ignorecase;
            $result = $ignore ? strnatcasecmp ($a[$key], $b[$key]) : strnatcmp($a[$key], $b[$key]);
            // check the value for reverse and reverse the sort order accordingly
            $revcmp = is_array($reverse) ? $reverse[$cnt] : $reverse;
            $result = $revcmp ? ($result * -1) : $result;
            // the first key that results in a non-zero comparison desearchines
            // the order of the elements
            if ( $result != 0 ) break;
            $cnt++;
        }
        return $result;
    };
} // end sortcompare()


// Display the episodes
function get_results($episodes, $date, $station, $daypart, $search, $show) {
	
	// Set the default timezone to use.
	date_default_timezone_set('America/Chicago');
	
	define('SHOW_AIRDATES_THRESHOLD', 3);
	define('BEGIN_PRIMETIME', '18:00:00');
	define('END_PRIMETIME', '19:00:00');
	
	
	global $tv_schedule_path;
		
	$output = '';

	//$clear_results = '?date=' . $date . '&station=' . $station . '&daypart=' . $daypart;
	
	//$format = '%Y-%m-%d %H:%M:%S';
	//$strf = strftime($format);
					
	$output .= '<div class="current-tv-schedule">';
		
	// Output Datetimes used for filtering
	if($search === '' && ($show === '' || empty($show) )) {
	
		$now = date("Y-m-d H:i:s");
		
		$current_time = date("G:i:s");
		
		$display_month = $episodes[0]['episode_month'];
		$display_day = $episodes[0]['episode_day'];
		$display_year = $episodes[0]['episode_year'];
		
		$output .= '<div class="datetime-now">' . $now . '</div>';		
		$output .= '<div class="datetime-current-time">' . $current_time . '</div>';
		
		$output .= '<div class="datetime-begin-primetime">' . BEGIN_PRIMETIME . '</div>';
		$output .= '<div class="datetime-end-primetime">' . END_PRIMETIME . '</div>';
		
		$output .= '<div class="no-episodes"></div>';
	}
	else {
										
		$episodes = get_episodes_by_series($episodes);
		
		if( !empty($show) && $search === '') {
			
			$today = date('l, M. j');
			$one_week_away = strtotime('+7 day');
			$end_date = date('l, M. j', $one_week_away);

			$output .= '<div class="series-description">';
			$output .= '<div class="container">';
			$output .= '<div class="section-column col-md-12">';			
			$output .= '<p>' . $episodes[0]['series_desc'] . '</p>';
			$output .= '</div> <!-- // section-column -->';
			$output .= '</div> <!-- // container -->';
			$output .= '</div> <!-- // series-description -->';
			
			$output .= '<div class="series-results">';
			$output .= '<div class="container">';
			$output .= '<div class="section-column col-md-12">';			
			$output .= '<p><em>Showing airdates for <strong class="series-title">'. $episodes[0]['series_title'] .'</strong> from <strong>'. $today .'</strong> to <strong>'. $end_date .'</strong></em></p>';
			$output .= '</div> <!-- // section-column -->';
			$output .= '</div> <!-- // container -->';
			$output .= '</div> <!-- // series-description -->';
			
		}
	}	
	
	$series_title_array = [];
	
	// Output episodes
	for($i = 0; $i < count($episodes); ++$i) {
				
		// Get decoded series title
		$series_name = urldecode($episodes[$i]['series_title']);
				
		// Convert to lower case
		$series_name = strtolower($series_name);
		
		// Replace spaces with hyphens
		$series_name = str_replace(' ', '-', $series_name); 
				
		// Remove any other special characters
		$series_name = preg_replace('/[^A-Za-z0-9\-:]/', '', $series_name);
		
		// Replace double-hyphens with single hyphens
		$series_name = str_replace('--', '-', $series_name);
		
		// Remove colons
		$series_name = str_replace(':', '', $series_name);
		
		$show_airdates_btn = FALSE;
		$output_series_title = TRUE;
				
		// Apply AP style to airtime formatting
		$formatted_airtime = str_replace(array('am','pm'), array('a.m.','p.m.'), $episodes[$i]['episode_airtime'] );
		$formatted_airtime = str_replace(':00', '', $formatted_airtime );
		
		switch($episodes[$i]['channel']) {
			case 'kamu':
				$channel_name = 'KAMU HDTV';
				break;
			case 'create-tv':
				$channel_name = 'Create TV';
				break;
			case 'pbs-kids':
				$channel_name = 'KAMU PBS Kids';
				break;
			default:
				$channel_name = '';
				break;
		}

		// Output for search results episodes
		if($search !== '') {
						
			if( $i < count($episodes)) {
												
				// Determine if this episode series title is different than the previous one				
				if( $episodes[$i]['series_title'] !== $episodes[$i-1]['series_title'] ) {
					
					// Determine if this episode series title is different than the next one	
					if ( $episodes[$i]['series_title'] === $episodes[$i+1]['series_title'] ) {
						
						$output_series_title = TRUE;
					}
					else {

						$show_airdates_btn = TRUE;
					}
				}
				else {
					
					$output_series_title = FALSE;
					
					// Determine if this is the last episode in the series results
					if ( $episodes[$i]['series_title'] !== $episodes[$i+1]['series_title'] ) {
						$show_airdates_btn = TRUE;
					}
				}
				
				// Add the Series Title to our array so we can count the number of episodes per series
				array_push($series_title_array, $episodes[$i]['series_title']);
				
			}
			
			if($output_series_title) {
				$output .= '<div class="series-wrapper">';
				$output .= '<h2>' . $episodes[$i]['series_title'] . '</h2>';
				$output .= '</div>';
			}
		}
		else {
			$show_airdates_btn = FALSE;
		}
			
		$output .= '<div class="episode-wrapper channel-'. $episodes[$i]['channel'] .'">';
		$output .= '<div class="episode-header">';
		
		$output .= '<div class="container">';
		
		// Output for search results and show episodes
		if($search !== '' || ($show !== '' && !empty($show))) {
			$output .= '<div class="section-column col-md-8 episode-title">';
			$output .= '<h3>';
			$output .= $episodes[$i]['episode_title'];
			$output .= '</h3>';
			$output .= '</div> <!-- // section-column -->';
		}
		else {
			$output .= '<div class="section-column col-md-8 series-title">';
			$output .= '<h3>';
			$output .= $episodes[$i]['series_title'];
			$output .= '</h3>';
			$output .= '</div> <!-- // section-column -->';
		}

		$output .= '<div class="section-column col-md-4 channel-name">';
		$output .= '<h4>';
		$output .= $channel_name;
		$output .= '</h4>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';

		// Output for default episodes
		if($search === '' && ($show === '' || empty($show))) {
			
			$output .= '<div class="container">';
			$output .= '<div class="section-column col-md-12 episode-title">';
			$output .= '<h4>';
			$output .= $episodes[$i]['episode_title'];
			$output .= '</h4>';
			$output .= '</div> <!-- // section-column -->';
			$output .= '</div> <!-- // container -->';
		}
					
		$output .= '</div> <!-- // episode-header -->';

		$output .= '<div class="episode-details">';

		$output .= '<div class="container">';
		$output .= '<div class="section-column col-md-12 episode-airdate-details">';
		$output .= '<span class="episode-airtime">' . $episodes[$i]['episode_airtime'] . '</span> &bull; ';
		$output .= '<span class="episode-airdate">' . $episodes[$i]['episode_day_month'];
		$output .= '<span class="year">'. $episodes[$i]['episode_year'] .'</span>';
		$output .= '<span class="formatted-airtime">'. $episodes[$i]['formatted_airtime'] .'</span>';
		$output .= '<span class="formatted-airdate">'. $episodes[$i]['formatted_airdate'] .'</span>';
		$output .= '</span>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';

		$output .= '<div class="container">';
		$output .= '<div class="section-column col-md-12 episode-desc">';
		$output .= '<h5 aria-label="show episode info for '. $episodes[$i]['episode_title'] .'">Show Episode Info</h5>';
		$output .= '<div>';
		$output .= '<p>';
		$output .= $episodes[$i]['episode_desc'];
		$output .= '</p>';
		
		// Output for default episodes
		if($search === '' && ( $show === '' || empty($show) ) ) {
			
			//$series_title = strtolower($episodes[$i]['series_title']);
			
			$airdates_path = '/tv/tv-schedule/airdates/?show=' . $series_name;
			
			$output .= '<p>';
			$output .= '<a class="btn btn-blue" href="'.$airdates_path .'">TV Show Details <i class="fa-solid fa-arrow-right-long"></i></a>';
			$output .= '</p>';
		}
		
		$output .= '</div>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';		
		$output .= '</div> <!-- // episode-details -->';
				
		$output .= '</div> <!-- // episode-wrapper -->';
				
		if($search !== '') {
			
			// Get count of episodes in current Series Title
			if(in_array($episodes[$i]['series_title'], $series_title_array)) {
				
				$num_episodes = array_count_values_of($episodes[$i]['series_title'], $series_title_array);
			}
			
			// Display View All Airdates button if this is the last episode listed in the series AND there are more episodes
			// than our defined minimum number of episodes in the series
			if($show_airdates_btn && $num_episodes >= SHOW_AIRDATES_THRESHOLD) {
			
				$output .= '<div class="series-airdates">';
				$output .= '<div class="container">';
				$output .= '<div class="section-column col-md-12">';

				$output .= '<a class="btn" href="/tv-schedule/airdates/?show='. $series_name .'">View All Airdates <i class="fa-solid fa-arrow-right-long"></i></a>';

				$output .= '</div> <!-- // section-column -->';
				$output .= '</div> <!-- // container -->';
				$output .= '</div> <!-- // series-airdates -->';
			}
		}
	}
	
	if( $show !== '' && !empty($show) && $search === '') {
		
		// TV Schedule - Search
		$tv_form .= '<div class="tv-schedule-search">';
		$tv_form .= do_shortcode('[wpforms id="1164" title="false"]');
		$tv_form .= '</div>';
		
		$output .= '<div class="search-again">';
		$output .= '<div class="container">';
		$output .= '<div class="section-column col-md-12">';
		$output .= '<h2>Find A Show</h2>';
		$output .= '<p>Not what you’re looking for? Try something else!</p>';
		$output .= $tv_form;
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';
		$output .= '</div> <!-- // search-again -->';
	}
		
	if($search !== '' && $show === '') {
		
		$output .= '<div class="back-to-today">';
		$output .= '<div class="container">';
		$output .= '<div class="section-column col-md-12">';
		$output .= '<p>';
		$output .= '<a class="btn btn-blue" href="'. $tv_schedule_path .'">See Today\'s TV Schedule <i class="fa-solid fa-arrow-right-long"></i></a>';
		$output .= '</p>';
		$output .= '</div> <!-- // section-column -->';
		$output .= '</div> <!-- // container -->';
		$output .= '</div> <!-- // back-to-today -->';
	}
		
	$output .= '</div>';
	
	return $output;
}


function get_episodes_by_series($episodes) {
	
	// First sort episodes by Series Title
	mknatsort($episodes, array('series_title'));
	
	$series_titles = [];

	// Get all series titles
	for($i = 0; $i < count($episodes); $i++) {

		if(!in_array($episodes[$i]['series_title'], $series_titles)) {
			array_push( $series_titles, $episodes[$i]['series_title']);
		}
	}

	$series_episodes = [];
	
	// Group episodes by series
	foreach($series_titles as $series_title) {
		
		$matching_episodes = [];
		
		for($i = 0; $i < count($episodes); $i++) {

			$episode_series_title = $episodes[$i]['series_title'];
			
			// Get all the episodes from this series
			if($series_title === $episode_series_title) {
	
				array_push($matching_episodes, $episodes[$i]);
			}
		}
		
		mknatsort($matching_episodes, array('episode_datetime'));

		// Add the matching episodes to the episodes by series array
		$series_episodes[$series_title] = $matching_episodes;
	}
	
	$combined_episodes = [];
	
	// Combine the results into one array
	foreach($series_episodes as $series) {
	
		foreach($series as $episode) {

			array_push($combined_episodes, $episode);
		}
		
	}
	
	$series_episodes = $combined_episodes;
		
	mknatsort($series_episodes, array('series_title'));
	
	return $series_episodes;
}


// Count the number of instances of a value in the array
function array_count_values_of($value, $array) {
    $counts = array_count_values($array);
    return $counts[$value];
}


// Get list of What's On Tonight
function get_results_tonight($episodes, $station) {
	
	global $tv_schedule_path;
	
	$output = '';
	
	$output .= '<ul class="whats-on-tonight">';
		
	foreach($episodes as $episode) {
		
		if($episode['channel'] === $station) {
			
			// Apply AP style to airtime formatting
			$formatted_airtime = str_replace(array('am','pm'), array('a.m.','p.m.'), $episode['episode_airtime'] );
			$formatted_airtime = str_replace(':00', '', $formatted_airtime );

			$output .= '<li>';
			$output .= '<span class="episode-airtime"><strong>' . $formatted_airtime . '</strong></span>';
			$output .= '<span class="series-title">' . $episode['series_title'] . '</span>';
			$output .= '</li>';
		}
	}
	
	$output .= '<li class="full-schedule"><a href="'. $tv_schedule_path .'">View Full Schedule</a></li>';
		
	$output .= '</ul>';
	
	return $output;
}

/**
 * Include Form Fields on Confirmation URL
 *
 */
function ea_wpforms_include_form_fields_on_confirmation( $url, $form_id, $fields ) {
    $args = array();
    foreach( $fields as $field ) {
        if( !empty( $field['value'] ) )
			
			$field_id = '';
			$field_value = '';
			
			switch($field['id']) {
				case '1':
					$field_id = 'station';
					break;
				case '2':
					$field_id = 'daypart';
					break;
				case '3':
					$field_id = 'search';
					break;
				case '4':
					$field_id = 'date';
					break;
				default:
					break;
			}
		
			if(!empty($field['value'])) {
				$args[$field_id] = !empty( $field['value_raw'] ) ? $field['value_raw'] : $field['value'];
			}
					
    }
    return esc_url_raw( add_query_arg( $args, $url ) );
}
add_filter( 'wpforms_process_redirect_url', 'ea_wpforms_include_form_fields_on_confirmation', 10, 3 );


/* SFTP Connection */
class SFTPConnection
{
    private $connection;
    private $sftp;
	
	public function __construct($host, $port=22)
    {		
        $this->connection = @ssh2_connect($host, $port);
		
        if (! $this->connection)
            throw new Exception('Could not connect to $host on port $port.');
    }
	
	public function login($username, $password)
    {		
        if (! @ssh2_auth_password($this->connection, $username, $password))
            throw new Exception('Could not authenticate with username $username ' .
                                'and password $password.');

        $this->sftp = @ssh2_sftp($this->connection);
		
        if (! $this->sftp)
            throw new Exception('Could not initialize SFTP subsystem.');
    }
	
	public function getFileContents($filename) {
		
		$sftp = $this->sftp;
		$stream = fopen('ssh2.sftp://'.$sftp.$filename, 'r');
		
		if (! $stream)
            throw new Exception('Could not open file: ' . $filename . '.');
		
		$contents = file_get_contents('ssh2.sftp://'.$sftp.$filename);
		
		return $contents;
	}
		
	
}


function updateSchedule($filename, $file_contents) {
		
	$file = plugin_dir_path(__FILE__) . 'xml/' . $filename;

	if (file_exists($file)) {

		wp_delete_file( $file ); //delete file here.
	}

	// Create file
	$file = fopen(plugin_dir_path(__FILE__) . 'xml/' . $filename, 'w') or die('Unable to open file for writing!');

	fwrite($file, $file_contents);

	// Update file modification time
	touch($filename);

	fclose($file);
		
}



?>