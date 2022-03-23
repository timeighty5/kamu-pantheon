<?php // Silence is golden.
// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

  //
  // Set a unique slug-like ID
  $prefix = 'sc_';

  //
  // Create a metabox
  CSF::createMetabox( $prefix, array(
    'title'     => 'Radio Player Configuration',
    'post_type' => 'streamcast',
    'data_type' => 'unserialize',
    'context'   => 'normal', // The context within the screen where the boxes should display. `normal`, `side`, `advanced`
  ) );

  //
  // Create a section
  CSF::createSection( $prefix, array(
    'title'  => 'Required fields are marked with an * (asterisk)',
    'fields' => array(






		array(
			'id'      => 'opt-radio',
			'type'    => 'radio',
			'title'   => 'Radio Player Type *',
			'desc'    => esc_html__( 'You must choose radio player type first to get related settings fields.', 'streamcast' ),
			'options' => array(
				'plyr'     => 'Minimal',
				'standard' => 'Standard',					
				'advanced' => 'Advanced',
				'ultimate' => 'Ultimate',
			),
			'default' => 'ultimate',
			'inline'  => true,
		),


      array(
        'id'            => 'stream_url',
        'type'          => 'text',
        'dependency'   => array( 'opt-radio', '!=', 'ultimate' ),
        'title'         => esc_html__( 'Stream URL*', 'streamcast' ),
        'button_title'  => esc_html__( 'Add or Upload File', 'streamcast' ),
        'remove_title'  => esc_html__( 'Remove Mp3', 'streamcast' ),
      ),


      array(
          'id'    => 'station_name',
          'type'  => 'text',
		  'class' => 'hayat-readyonly',			  
		  'dependency' => array( 'opt-radio|opt-radio', '!=|!=', 'plyr|ultimate' ),
          'title' => esc_html__( 'Station Name*', 'streamcast' ),          
      ),
      array(
          'id'    => 'welcome_msgs',
          'type'  => 'text',
		  'dependency' => array( 'opt-radio|opt-radio', '!=|!=', 'plyr|ultimate' ),
		  'class' => 'hayat-readyonly',		  
          'title' => esc_html__( 'Welcome Message*', 'streamcast' ),
      ),
      array(
        'id'       => 'player_skin',
        'type'     => 'select',
        'title'    => esc_html__( 'Skin', 'streamcast' ),
        'default'  => 'mcclean',
		'dependency' => array( 'opt-radio', '==', 'standard' ),
		'class' => 'hayat-readyonly',			
        'options'     => array(
          ''  => '==Official Skins==',
          'mcclean'  => 'McClean (180x60)',
          'radiovoz'  => 'RadioVoz (220x69)',
          'faredirfare'  => 'Faredirfare (269x52)',
          'tweety'  => 'Tweety (189x62)',
          'compact'  => 'Compact (191x46)',
          'cassette'  => 'Tim Simz - Cassette (200x120)',
          'repvku-100'  => 'Repvku-100 (100x25)',
          'darkconsole'  => 'DarkConsole (190x62)',
          'tiny'  => 'Tiny (130x60)',
          'universelle'  => 'Universelle (155x65)',
          'uuskin'  => 'UUSkin (166x83)',
          'e76'  => 'E76 (130x75)',
          'original'  => 'Original (329x21)',
          'arvyskin'  => 'Arvy Skin [M] (560x30)',
          'eastanbul'  => 'Eastanbul (467x26)',
          'substream'  => 'Substream (180x30)',
          'banita'  => 'BANita (110x25)',
          'listen-live'  => 'Listen Live (250x100)',
          'easyplay'  => 'EasyPlay (231x30)',
          'stockblue'  => 'Stockblue (476x26)',
          'largebayfm'  => 'LargeBayFM (451x90)',
          'simple-blue'  => 'Simple Blue [M] (300x122)',
          'simple-gray'  => 'Simple Gray [M] (300x122)',
          'simple-green'  => 'Simple Green [M] (300x122)',
          'simple-orange'  => 'Simple Orange [M] (300x122)',
          'simple-red'  => 'Simple Red [M] (300x122)',
          'simple-violet'  => 'Simple Violet [M] (300x122)',
          'scradio'=>'SCRadio (160x100)',
          'repvku-115'=>'Repvku-115 (115x25)',
          'rb1'=>'RB1 (250x70)',
          'tandem-115'=>'Tandem-115 (115x25)',
          'simcha-232-toggle'=>'Simcha-232 [T] (232x58)',
          'simcha-232'=>'Simcha-232 (232x58)',
          'simcha-320'=>'Simcha-320 (320x58)',
          'kplayer'=>'KPlayer (220x200)',
          'appy'=>'Appy [T] (250x213)',
          'blueberry'=>'Blueberry (338x102)',
          'oldradio'=>'OldRadio (205x132)',
          'oldradio-christmas'=>'OldRadio Christmas (205x132)',
          'oldstereo'=>'OldStereo (318x130)',
          'xm'=>'Xm (234x66)',
          'abrahadabra'=>'Abrahadabra (100x141)',
          'abrahadabra2'=>'Abrahadabra 2 (100x141)',
          'wmp'=>'WMP (386x47)',
          'radioport'=>'Radioport (700x150)',
          'alberto'=>'Alberto (250x95)',
          'ff'=>'FF (288x68)',
          'neon'=>'Neon (240x76)',
          'player-stm'=>'Player STM (128x30)',
          'neonslim'=>'NeonSlim (501x32)',
          'greyslim'=>'GreySlim (494x35)',
          'demon'=>'Demon (468x117)',
          'xavi'=>'Xavi (250x95)',
          'xavi2'=>'Xavi2 (95x95)',
          'xavi3'=>'Xavi3 (250x95)',
          'minimal'=>'Minimal (220x80)',
          'grind'=>'Grind (400x336)',
          'cpr-180'=>'CPR-180 (180x40)',
          'ammascota'=>'Am Mascota (290x100)',
          'miniradio'=>'MiniRadio (275x112)',
          'myradio'=>'My Radio (262x165)',
          'terawhite'=>'Terawhite (255x100)',
          'kelabu-yellow'=>'Kelabu Yellow (253x100)',
          'cristal'=>'Cristal (300x113)',
          'bintang'=>'Bintang (300x113)',
          'tatarradiosi'=>'Tatar Radiosi (418x150)',
          'redsradiosml'=>'Reds Radio SML (500x158)',
          'bogusblue'=>'BogusBlue (660x266)',
          'bones'=>'Bones (341x125)',
          'combat'=>'Combat (675x247)',
          'dragonblues'=>'DragonBlues (400x145)',
          'lemon'=>'Lemon (410x60)',
          'limed'=>'Limed (397x115)',
          'longtail'=>'Longtail (498x61)',
          'pinhead'=>'Pinhead (421x120)',
          'retro'=>'Retro (669x259)',
          'silvertune'=>'Silvertune (200x104)',
          'testskin'=>'Test/Develop (189x61)',
          'retromatic'=>'Retromatic (700x150)',
          'retromaticsmall'=>'Retromatic Small (298x150)',
          'e90'=>'E90 (190x59)',
          'shmusic'=>'SH Music (300x190)',
          'brujulalatina'=>'Brújula Latina (330x100)',
          'adn'=>'ADN (700x150)',

        )
      ),

      array(
        'id'      => 'autoplay',
        'type'    => 'switcher',
		'dependency' => array( 'opt-radio', '==', 'standard' ),
        'title'   => esc_html__( 'Auto Play', 'streamcast' ),
		'class' => 'hayat-readyonly',		
        'default' => true // or false
      ),


      
      array(
        'id'       => 'volume',
        'type'     => 'spinner',
		'dependency' => array( 'opt-radio', '==', 'standard' ),
        'title'    => esc_html__( 'Initial Volume', 'streamcast' ), 
		'class' => 'hayat-readyonly',		
        'default'  => '65',
        'min'      => '0',
        'max'      => '100',
        'unit'     => '%',
      ),

      array(
        'id'            => 'artwork',
        'type'          => 'upload',
		 'dependency'   => array( 'opt-radio', '==', 'advanced' ),
        'title'         => esc_html__( 'ArtWork', 'streamcast' ),
        'button_title'  => esc_html__( 'Add or Upload Artwork Image', 'streamcast' ),
        'remove_title'  => esc_html__( 'Remove', 'streamcast' ),
		'class' => 'hayat-readyonly',		
        'desc'  => esc_html__( '94x94 px photo is the standard artwork size, accepted file type .png, .jpeg, .jpg ', 'streamcast' ),
      ),	  
      array(
        'id'      => 'autoplay',
        'type'    => 'switcher',
		 'dependency' => array( 'opt-radio', '==', 'advanced' ),
        'title'   => esc_html__( 'Auto Play', 'streamcast' ),
		'class' => 'hayat-readyonly',		
        'default' => true // or false
      ),	  
      array(
        'id'       => 'volume',
        'type'     => 'spinner',
		 'dependency' => array( 'opt-radio', '==', 'advanced' ),
        'title'    => esc_html__( 'Initial Volume', 'streamcast' ),
		'class' => 'hayat-readyonly',        
        'default'  => '65',
        'min'      => '0',
        'max'      => '100',
        'unit'     => '%',
      ),
      array(
        'id'      => 'timeholder',
        'type'    => 'switcher',
		'class' => 'hayat-readyonly',		
		 'dependency' => array( 'opt-radio', '==', 'advanced' ),
        'title'   => esc_html__( 'Show Time', 'streamcast' ),
        'default' => true // or false
      ),
      array(
        'id'      => 'background',
        'type'    => 'color',
		'class' => 'hayat-readyonly',		
		'dependency' => array( 'opt-radio', '==', 'advanced' ),
        'title'   => esc_html__( 'Background color', 'streamcast' ),
        'default' => '#f09f8b' // or false
      ),


	  
	 array(
	  'id'         => 'player_postiion',
	  'type'       => 'radio',
	  'title'      => 'Player Position',
	  'class' => 'hayat-readyonly',	  
	  'dependency' => array( 'opt-radio|opt-radio', '!=|!=', 'standard|ultimate' ),
	  'options'    => array(
		'left' => 'Left',
		'' => 'Center',
		'right' => 'Right',

	  ),
	  'default'    => '',
	  'inline'    => true
	),












            // Ultimate


            array(
                'id'         => 'streamProvider',
                'type'       => 'button_set',
                'title'      => 'Stream Provider *',
                'options'    => array(
                    'shout-cast' => 'SHOUT cast',
                    'ice-cast'   => 'Ice cast',
                ),
                'default'    => 'shout-cast',
               'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            array(
                'id'         => 'streamURL',
                'type'       => 'text',
                'title'      => 'Stream URL *',
                'default'    => 'http://74.208.71.58',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            array(
                'id'         => 'streamPort',
                'type'       => 'number',
                'title'      => 'Stream Port *',
                'default'    => '8000',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            array(
                'id'         => 'streamMountPoint',
                'type'       => 'text',
                'title'      => 'Stream Mount Point *',
                'dependency' => array( 'streamProvider|opt-radio', '==|==', 'ice-cast|ultimate' ),
                'default'    => 'house.320k.mp3',
            ),



            array(
                'id'         => 'radioName',
                'type'       => 'text',
                'title'      => 'Station Name',
                'default'    => 'Station Name',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            // Customization
            array(
                'id'         => 'playerWidth',
                'type'       => 'text',
                'title'      => 'Player Width',
                'default'    => '1100px',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),
            array(
                'id'         => 'radioImage',
                'library'       => 'image',
                'type'       => 'media',
                'title'      => 'Poster Image',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),
			
            array(
                'id'         => 'bgImage',
                'library'       => 'image',				
                'type'       => 'media',
				'class' => 'hayat-readyonly',
                'title'      => 'Player Background Image',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            array(
                'id'         => 'playerColors',
                'type'       => 'button_set',
                'title'      => 'Player Colors',
                'options'    => array(
                    'theme'  => 'Theme',
                    'custom' => 'Custom Color',
                ),
                'default'    => 'theme',
				'class' => 'hayat-readyonly',
                'dependency' => array( 'opt-radio', '==', 'ultimate' ),
            ),

            // Themes
            array(
                'id'         => 'playerThemes',
                'type'       => 'button_set',
                'title'      => 'Player Themes',
                'options'    => array(
                    'dodgerBlue'    => 'Dodger Blue',
                    'bittersweet'   => 'Bittersweet',
                    'lightSeaGreen' => 'Light Sea Green',
                ),
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'theme|ultimate' ),
                'default'    => 'dodgerBlue',
				'class' => 'hayat-readyonly',
            ),

            // Custom Colors
            array(
                'id'         => 'playerOverlayColor',
                'type'       => 'color',
                'title'      => 'Player Overlay Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
				'class' => 'hayat-readyonly',
                'default'    => 'rgba(15, 17, 21, 0.5)',
            ),
            array(
                'id'         => 'imgBorderColor',
                'type'       => 'color',
                'title'      => 'Thumbnail Border Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
				'class' => 'hayat-readyonly',
                'default'    => 'rgba(255, 255, 255, 0.2)',
            ),
            array(
                'id'         => 'contentColor',
                'type'       => 'color',
                'title'      => 'Content Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
				'class' => 'hayat-readyonly',
                'default'    => '#fff',
            ),
            array(
                'id'         => 'btnHoverColor',
                'type'       => 'color',
                'title'      => 'Button Hover Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
				'class' => 'hayat-readyonly',
                'default'    => 'orangered',
            ),
            array(
                'id'         => 'progressColor',
                'type'       => 'color',
                'title'      => 'Progress Active Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
				'class' => 'hayat-readyonly',
                'default'    => 'orangered',
            ),
            array(
                'id'         => 'visualizerColor',
                'type'       => 'color',
                'title'      => 'Visualizer Color',
                'dependency' => array( 'playerColors|opt-radio', '==|==', 'custom|ultimate' ),
                'default'    => 'orangered',
				'class' => 'hayat-readyonly',
            ),

            array(
                'id'       => 'custom_css',
                'type'     => 'code_editor',
                'title'    => 'Custom CSS',
                'desc'     => 'This field is optional. ',
                'default'  => '/* Your Custom CSS here	  */',
				'class' => 'hayat-readyonly',
                'sanitize' => false,
                'settings' => array(
                    'mode' => 'css',
                ),

            ),



















	  
    )
  ) );

}


function stp_exclude_fields_before_save( $data ) {

  $exclude = array(
    'station_name',
    'welcome_msgs',
    'player_skin',
    'autoplay',
    'volume',
    'artwork',
    'timeholder',
    'background',
    'player_postiion',
    'custom_css',
  );

  foreach ( $exclude as $id ) {
    unset( $data[$id] );
  }

  return $data;

}

add_filter( 'csf_sc__save', 'stp_exclude_fields_before_save', 10, 1 );