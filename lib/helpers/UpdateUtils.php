<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_UpdateUtils')) {

    class S2bAia_UpdateUtils {
        
        
        public static function upgrade(){
            $current_db_version = get_option('s2baia_database_version', 0);
            if($current_db_version < 2){
                self::version2();
                update_option('s2baia_database_version', 2);
            }
            
            if($current_db_version < 3){
                self::version3();
                update_option('s2baia_database_version', 3);
            }
            
            if($current_db_version < 4){
                self::version4();
                update_option('s2baia_database_version', 4);
            }
            
            if($current_db_version < 5){
                self::version5();
                update_option('s2baia_database_version', 5);
            }
            if($current_db_version < 6){
                self::version6();
                update_option('s2baia_database_version', 6);
            }
            
        }
        
        public static function version2(){
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql1 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 's2baia_settings_groups' . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `typeof_key` int(11) NOT NULL DEFAULT "1" COMMENT "1- image dall-e, 2 - prompt chatgpt"  ,
                `typeof_element` int(11) NOT NULL DEFAULT "1" COMMENT "1- selectbox, 2 - text, 3 - checkbox, 4 - radiogroup, 5 - textarea"  ,
                `group_key`  varchar(100)  ,
                `group_value`  varchar(100)  ,
                `gordering` int(11) NOT NULL DEFAULT "0"  ,
                `disabled`  SMALLINT NOT NULL DEFAULT "0"
                ) ENGINE = INNODB '. $charset_collate;
            dbDelta( $sql1 );
                
            $sql2 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 's2baia_settings' . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_settings_key` int(11) NOT NULL DEFAULT "0"   ,
                `skey`  varchar(255)  ,
                `svalue`  varchar(255)  ,
                `sordering` int(11) NOT NULL DEFAULT "0"  ,
                `disabled`  SMALLINT NOT NULL DEFAULT "0"
                ) ENGINE = INNODB '. $charset_collate;
            
            
            dbDelta( $sql2 );
            self::checkUpdateVersion2();
            update_option( "s2baia_database_version", 2 );
                
        }
        
        public static function getGroups(){
            
            global $wpdb;
            $groups = [];
            $records = $wpdb->get_results($wpdb->prepare("SELECT a.id, a.group_key, a.group_value FROM " . $wpdb->prefix . "s2baia_settings_groups as a "
                    . "  WHERE a.typeof_key = %d "
                    . " AND a.disabled = 0  ORDER BY a.id ", 1));
            

            foreach($records as $rec){
                $id = $rec->id;
                $group_value = $rec->group_value;
                $groups[$group_value] = $id;
            }
            return $groups;
        }
        
        public static function checkUpdateVersion2(){
            global $wpdb;
            $groups = [];
            $table_name = $wpdb->prefix . "s2baia_settings_groups";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name))) == $table_name ) {
                if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix ."s2baia_settings_groups") == 0 ){
                    $groups = self::version2DataAddGroups();
                }else{
                    $groups = self::getGroups();
                }
            }
            
            $table_name = $wpdb->prefix . "s2baia_settings";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name))) == $table_name ) {
                if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix ."s2baia_settings") == 0 ){
                    self::version2DataAddSettings($groups);
                }
            }
        }
        
        public static function version2DataAddGroups(){
            global $wpdb;
            $group_ids = [];

            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_artist_opt',
                'group_value' => 'Artist',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Artist'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_camera_opt',
                'group_value' => 'Camera',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Camera'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_color_opt',
                'group_value' => 'Color',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Color'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_composition_opt',
                'group_value' => 'Composition',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Composition'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_lighting_opt',
                'group_value' => 'Lighting',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Lighting'] = $wpdb->insert_id;
            
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_photography_opt',
                'group_value' => 'Photography',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Photography'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_resolution_opt',
                'group_value' => 'Resolution',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Resolution'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_size_opt',
                'group_value' => 'Size',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Size'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_special-effects_opt',
                'group_value' => 'Special effects',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Special effects'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_style_opt',
                'group_value' => 'Style',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Style'] = $wpdb->insert_id;
            
            $wpdb->insert($wpdb->prefix . 's2baia_settings_groups', array(
                'typeof_key' => 1,
                'typeof_element' => 1,
                'group_key' => 's2baia_models_opt',
                'group_value' => 'Models',
            ),
            array('%d', '%d', '%s', '%s'));
            
            $group_ids['Models'] = $wpdb->insert_id;
            
            return $group_ids;
        }
        
        public static function version2DataAddSettings($group_ids = []){
            global $wpdb;
            
           
            ///insert data into s2baia_settings table
            
            
            $version2_settings = self::version2GetSettings();
            
            foreach($group_ids as $gr_idx => $id_settings_key){
                if(isset($version2_settings[$gr_idx])){
                    $gr_settings = $version2_settings[$gr_idx];
                    foreach ($gr_settings as $set_key=>$set_value){
                        $wpdb->insert($wpdb->prefix . 's2baia_settings', array(
                            'id_settings_key' => $id_settings_key,
                            'skey' => $set_key,
                            'svalue' => $set_value,
                        ),
                        array('%d', '%s', '%s'));
                    }
                }
            }
        }
        
        public static function version2GetSettings(){
            return  array(
	'Artist'          => array(
			'Salvador Dalí'             => 'Salvador Dalí',
			'Leonardo da Vinci'         => 'Leonardo da Vinci',
			'Michelangelo'              => 'Michelangelo',
			'Albrecht Dürer'            => 'Albrecht Dürer',
			'Alfred Sisley'             => 'Alfred Sisley',
			'Andrea Mantegna'           => 'Andrea Mantegna',
			'Andy Warhol'               => 'Andy Warhol',
			'Amedeo Modigliani'         => 'Amedeo Modigliani',
			'Camille Pissarro'          => 'Camille Pissarro',
			'Caravaggio'                => 'Caravaggio',
			'Caspar David Friedrich'    => 'Caspar David Friedrich',
			'Cézanne'                   => 'Cézanne',
			'Claude Monet'              => 'Diego Velázquez',
			'Eugène Delacroix'          => 'Eugène Delacroix',
			'Frida Kahlo'               => 'Frida Kahlo',
			'Gustav Klimt'              => 'Gustav Klimt',
			'Henri Matisse'             => 'Henri Matisse',
			'Henri de Toulouse-Lautrec' => 'Henri de Toulouse-Lautrec',
			'Jackson Pollock'           => 'Jackson Pollock',
			'Jasper Johns'              => 'Jasper Johns',
			'Joan Miró'                 => 'Joan Miró',
			'John Singer Sargent'       => 'John Singer Sargent',
			'Johannes Vermeer'          => 'Johannes Vermeer',
			'Mary Cassatt'              => 'Mary Cassatt',
			'M. C. Escher'              => 'M. C. Escher',
			'Paul Cézanne'              => 'Paul Cézanne',
			'Paul Gauguin'              => 'Paul Gauguin',
			'Paul Klee'                 => 'Paul Klee',
			'Pierre-Auguste Renoir'     => 'Pierre-Auguste Renoir',
			'Pieter Bruegel the Elder'  => 'Pieter Bruegel the Elder',
			'Piet Mondrian'             => 'Piet Mondrian',
			'Pablo Picasso'             => 'Pablo Picasso',
			'Rembrandt'                 => 'Rembrandt',
			'René Magritte'             => 'René Magritte',
			'Raphael'                   => 'Raphael',
			'Sandro Botticelli'         => 'Sandro Botticelli',
			'Titian'                    => 'Titian',
			'Theo van Gogh'             => 'Theo van Gogh',
			'Vincent van Gogh'          => 'Vincent van Gogh',
			'Vassily Kandinsky'         => 'Vassily Kandinsky',
			'Winslow Homer'             => 'Winslow Homer',
			'None'                      => 'None',

	),
	'Style'           => array(
			'Surrealism'             => 'Surrealism',
			'Abstract'               => 'Abstract',
			'Abstract Expressionism' => 'Abstract Expressionism',
			'Action painting'        => 'Action painting',
			'Art Brut'               => 'Art Brut',
			'Art Deco'               => 'Art Deco',
			'Art Nouveau'            => 'Art Nouveau',
			'Baroque'                => 'Baroque',
			'Byzantine'              => 'Byzantine',
			'Classical'              => 'Classical',
			'Color Field'            => 'Color Field',
			'Conceptual'             => 'Conceptual',
			'Cubism'                 => 'Cubism',
			'Dada'                   => 'Dada',
			'Expressionism'          => 'Expressionism',
			'Fauvism'                => 'Fauvism',
			'Figurative'             => 'Figurative',
			'Futurism'               => 'Futurism',
			'Gothic'                 => 'Gothic',
			'Hard-edge painting'     => 'Hard-edge painting',
			'Hyperrealism'           => 'Hyperrealism',
			'Impressionism'          => 'Impressionism',
			'Japonisme'              => 'Japonisme',
			'Luminism'               => 'Luminism',
			'Lyrical Abstraction'    => 'Lyrical Abstraction',
			'Mannerism'              => 'Mannerism',
			'Minimalism'             => 'Minimalism',
			'Naive Art'              => 'Naive Art',
			'New Realism'            => 'New Realism',
			'Neo-expressionism'      => 'Neo-expressionism',
			'Neo-pop'                => 'Neo-pop',
			'Op Art'                 => 'Op Art',
			'Opus Anglicanum'        => 'Opus Anglicanum',
			'Outsider Art'           => 'Outsider Art',
			'Pop Art'                => 'Pop Art',
			'Photorealism'           => 'Photorealism',
			'Pointillism'            => 'Pointillism',
			'Post-Impressionism'     => 'Post-Impressionism',
			'Realism'                => 'Realism',
			'Renaissance'            => 'Renaissance',
			'Rococo'                 => 'Rococo',
			'Romanticism'            => 'Romanticism',
			'Street Art'             => 'Street Art',
			'Superflat'              => 'Superflat',
			'Symbolism'              => 'Symbolism',
			'Tenebrism'              => 'Tenebrism',
			'Ukiyo-e'                => 'Ukiyo-e',
			'Western Art'            => 'Western Art',
			'YBA'                    => 'YBA',
			'None'                   => 'None',

	),
	'Photography'     => array(
			'Portrait'         => 'Portrait',
			'Landscape'        => 'Landscape',
			'Abstract'         => 'Abstract',
			'Action'           => 'Action',
			'Aerial'           => 'Aerial',
			'Agricultural'     => 'Agricultural',
			'Animal'           => 'Animal',
			'Architectural'    => 'Architectural',
			'Architectural'    => 'Architectural',
			'Astrophotography' => 'Astrophotography',
			'Bird photography' => 'Bird photography',
			'Black and white'  => 'Black and white',
			'Candid'           => 'Candid',
			'Cityscape'        => 'Cityscape',
			'Close-up'         => 'Close-up',
			'Commercial'       => 'Commercial',
			'Conceptual'       => 'Conceptual',
			'Corporate'        => 'Corporate',
			'Documentary'      => 'Documentary',
			'Event'            => 'Event',
			'Family'           => 'Family',
			'Fashion'          => 'Fashion',
			'Fine art'         => 'Fine art',
			'Food'             => 'Food',
			'Food photography' => 'Food photography',
			'Glamour'          => 'Glamour',
			'Industrial'       => 'Industrial',
			'Lifestyle'        => 'Lifestyle',
			'Macro'            => 'Macro',
			'Nature'           => 'Nature',
			'Night'            => 'Night',
			'Product'          => 'Product',
			'Sports'           => 'Sports',
			'Street'           => 'Street',
			'Travel'           => 'Travel',
			'Underwater'       => 'Underwater',
			'Wedding'          => 'Wedding',
			'Wildlife'         => 'Wildlife',
			'None'             => 'None',

	),
	'Lighting'        => array(
			'None'              => 'None',            
			'Ambient'           => 'Ambient',
			'Artificial light'  => 'Artificial light',
			'Backlight'         => 'Backlight',
			'Black light'       => 'Black light',
			'Black light'       => 'Black light',
			'Candle light'      => 'Candle light',
			'Chiaroscuro'       => 'Chiaroscuro',
			'Cloudy'            => 'Cloudy',
			'Cloudy'            => 'Cloudy',
			'Continuous light'  => 'Continuous light',
			'Contre-jour'       => 'Contre-jour',
			'Direct light'      => 'Direct light',
			'Direct sunlight'   => 'Direct sunlight',
			'Diffused light'    => 'Diffused light',
			'Firelight'         => 'Firelight',
			'Flash'             => 'Flash',
			'Flat light'        => 'Flat light',
			'Fluorescent'       => 'Fluorescent',
			'Fog'               => 'Fog',
			'Front light'       => 'Front light',
			'Golden hour'       => 'Golden hour',
			'Hard light'        => 'Hard light',
			'Hazy sunlight'     => 'Hazy sunlight',
			'High key'          => 'High key',
			'Incandescent'      => 'Incandescent',
			'Key light'         => 'Key light',
			'LED'               => 'LED',
			'Low key'           => 'Low key',
			'Moonlight'         => 'Moonlight',
			'Natural light'     => 'Natural light',
			'Neon'              => 'Neon',
			'Open shade'        => 'Open shade',
			'Overcast'          => 'Overcast',
			'Paramount'         => 'Paramount',
			'Party lights'      => 'Party lights',
			'Photoflood'        => 'Photoflood',
			'Quarter light'     => 'Quarter light',
			'Reflected light'   => 'Reflected light',
			'Reflected light'   => 'Reflected light',
			'Shaded'            => 'Shaded',
			'Shaded light'      => 'Shaded light',
			'Silhouette'        => 'Silhouette',
			'Silhouette'        => 'Silhouette',
			'Silhouette'        => 'Silhouette',
			'Softbox'           => 'Softbox',
			'Soft light'        => 'Soft light',
			'Split lighting'    => 'Split lighting',
			'Stage lighting'    => 'Stage lighting',
			'Studio light'      => 'Studio light',
			'Sunburst'          => 'Sunburst',
			'Tungsten'          => 'Tungsten',
			'Umbrella lighting' => 'Umbrella lighting',
			'Underexposed'      => 'Underexposed',
			'Venetian blinds'   => 'Venetian blinds',
			'Warm light'        => 'Warm light',
			'White balance'     => 'White balance',


	),
	'Camera'          => array(
			'None'                            => 'None',
			'Aperture'                        => 'Aperture',
			'Active D-Lighting'               => 'Active D-Lighting',
			'Auto Exposure Bracketing'        => 'Auto Exposure Bracketing',
			'Auto Focus Mode'                 => 'Auto Focus Mode',
			'Auto Focus Point'                => 'Auto Focus Point',
			'Auto Lighting Optimizer'         => 'Auto Lighting Optimizer',
			'Auto Rotate'                     => 'Auto Rotate',
			'Aspect Ratio'                    => 'Aspect Ratio',
			'Audio Recording'                 => 'Audio Recording',
			'Auto ISO'                        => 'Auto ISO',
			'Chromatic Aberration Correction' => 'Chromatic Aberration Correction',
			'Color Space'                     => 'Color Space',
			'Continuous Shooting'             => 'Continuous Shooting',
			'Distortion Correction'           => 'Distortion Correction',
			'Drive Mode'                      => 'Drive Mode',
			'Dynamic Range'                   => 'Dynamic Range',
			'Exposure Compensation'           => 'Exposure Compensation',
			'Flash Mode'                      => 'Flash Mode',
			'Focus Mode'                      => 'Focus Mode',
			'Focus Peaking'                   => 'Focus Peaking',
			'Frame Rate'                      => 'Frame Rate',
			'GPS'                             => 'GPS',
			'Grid Overlay'                    => 'Grid Overlay',
			'High Dynamic Range'              => 'High Dynamic Range',
			'Highlight Tone Priority'         => 'Highlight Tone Priority',
			'Image Format'                    => 'Image Format',
			'Image Stabilization'             => 'Image Stabilization',
			'Interval Timer Shooting'         => 'Interval Timer Shooting',
			'ISO'                             => 'ISO',
			'ISO Auto Setting'                => 'ISO Auto Setting',
			'Lens Correction'                 => 'Lens Correction',
			'Live View'                       => 'Live View',
			'Long Exposure Noise Reduction'   => 'Long Exposure Noise Reduction',
			'Manual Focus'                    => 'Manual Focus',
			'Metering Mode'                   => 'Metering Mode',
			'Movie Mode'                      => 'Movie Mode',
			'Movie Quality'                   => 'Movie Quality',
			'Noise Reduction'                 => 'Noise Reduction',
			'Picture Control'                 => 'Picture Control',
			'Picture Style'                   => 'Picture Style',
			'Quality'                         => 'Quality',
			'Self-Timer'                      => 'Self-Timer',
			'Shutter Speed'                   => 'Shutter Speed',
			'Time-lapse Interval'             => 'Time-lapse Interval',
			'Time-lapse Recording'            => 'Time-lapse Recording',
			'Virtual Horizon'                 => 'Virtual Horizon',
			'Video Format'                    => 'Video Format',
			'White Balance'                   => 'White Balance',
			'Zebra Stripes'                   => 'Zebra Stripes',

	),
	'Composition'     => array(
			'None'                   => 'None',            
			'Rule of Thirds'         => 'Rule of Thirds',
			'Asymmetrical'           => 'Asymmetrical',
			'Balance'                => 'Balance',
			'Centered'               => 'Centered',
			'Close-up'               => 'Close-up',
			'Color blocking'         => 'Color blocking',
			'Contrast'               => 'Contrast',
			'Cropping'               => 'Cropping',
			'Diagonal'               => 'Diagonal',
			'Documentary'            => 'Documentary',
			'Environmental Portrait' => 'Environmental Portrait',
			'Fill the Frame'         => 'Fill the Frame',
			'Framing'                => 'Framing',
			'Golden Ratio'           => 'Golden Ratio',
			'High Angle'             => 'High Angle',
			'Leading Lines'          => 'Leading Lines',
			'Long Exposure'          => 'Long Exposure',
			'Low Angle'              => 'Low Angle',
			'Macro'                  => 'Macro',
			'Minimalism'             => 'Minimalism',
			'Negative Space'         => 'Negative Space',
			'Panning'                => 'Panning',
			'Patterns'               => 'Patterns',
			'Photojournalism'        => 'Photojournalism',
			'Point of View'          => 'Point of View',
			'Portrait'               => 'Portrait',
			'Reflections'            => 'Reflections',
			'Saturation'             => 'Saturation',
			'Scale'                  => 'Scale',
			'Selective Focus'        => 'Selective Focus',
			'Shallow Depth of Field' => 'Shallow Depth of Field',
			'Silhouette'             => 'Silhouette',
			'Simplicity'             => 'Simplicity',
			'Snapshot'               => 'Snapshot',
			'Street Photography'     => 'Street Photography',
			'Symmetry'               => 'Symmetry',
			'Telephoto'              => 'Telephoto',
			'Texture'                => 'Texture',
			'Tilt-Shift'             => 'Tilt-Shift',
			'Time-lapse'             => 'Time-lapse',
			'Tracking Shot'          => 'Tracking Shot',
			'Travel'                 => 'Travel',
			'Triptych'               => 'Triptych',
			'Ultra-wide'             => 'Ultra-wide',
			'Vanishing Point'        => 'Vanishing Point',
			'Viewpoint'              => 'Viewpoint',
			'Vintage'                => 'Vintage',
			'Wide Angle'             => 'Wide Angle',
			'Zoom Blur'              => 'Zoom Blur',
			'Zoom In/Zoom Out'       => 'Zoom In/Zoom Out',

	),
	'Resolution'      => array(
			'4K (3840x2160)'    => '4K (3840x2160)',
			'1080p (1920x1080)' => '1080p (1920x1080)',
			'720p (1280x720)'   => '720p (1280x720)',
			'480p (854x480)'    => '480p (854x480)',
			'2K (2560x1440)'    => '2K (2560x1440)',
			'1080i (1920x1080)' => '1080i (1920x1080)',
			'720i (1280x720)'   => '720i (1280x720)',
			'None'              => 'None',
	),
	'Color'           => array(
			'RGB'       => 'RGB',
			'CMYK'      => 'CMYK',
			'Grayscale' => 'Grayscale',
			'HEX'       => 'HEX',
			'CMY'       => 'CMY',
			'HSL'       => 'HSL',
			'HSV'       => 'HSV',
			'LAB'       => 'LAB',
			'LCH'       => 'LCH',
			'LUV'       => 'LUV',
			'XYZ'       => 'XYZ',
			'YUV'       => 'YUV',
			'YIQ'       => 'YIQ',
			'YCbCr'     => 'YCbCr',
			'YPbPr'     => 'YPbPr',
			'YDbDr'     => 'YDbDr',
			'YCoCg'     => 'YCoCg',
			'YCgCo'     => 'YCgCo',
			'YCC'       => 'YCC',
			'None'      => 'None',
	),
	'Special effects' => array(
			'None'                    => 'None',            
			'Cinemagraph'             => 'Cinemagraph',
			'3D'                      => '3D',
			'Add Noise'               => 'Add Noise',
			'Black and White'         => 'Black and White',
			'Blur'                    => 'Blur',
			'Bokeh'                   => 'Bokeh',
			'Brightness and Contrast' => 'Brightness and Contrast',
			'Camera Shake'            => 'Camera Shake',
			'Clarity'                 => 'Clarity',
			'Color Balance'           => 'Color Balance',
			'Color Pop'               => 'Color Pop',
			'Color Temperature'       => 'Color Temperature',
			'Cross Processing'        => 'Cross Processing',
			'Crop and Rotate'         => 'Crop and Rotate',
			'Dehaze'                  => 'Dehaze',
			'Denoise'                 => 'Denoise',
			'Diffuse Glow'            => 'Diffuse Glow',
			'Displace'                => 'Displace',
			'Distort'                 => 'Distort',
			'Double exposure'         => 'Double exposure',
			'Duotone'                 => 'Duotone',
			'Edge Detection'          => 'Edge Detection',
			'Emboss'                  => 'Emboss',
			'Exposure'                => 'Exposure',
			'Fish Eye'                => 'Fish Eye',
			'Flare'                   => 'Flare',
			'Flip'                    => 'Flip',
			'Fractalius'              => 'Fractalius',
			'Glowing Edges'           => 'Glowing Edges',
			'Gradient Map'            => 'Gradient Map',
			'Grayscale'               => 'Grayscale',
			'Halftone'                => 'Halftone',
			'HDR'                     => 'HDR',
			'HDR Look'                => 'HDR Look',
			'High Pass'               => 'High Pass',
			'Hue and Saturation'      => 'Hue and Saturation',
			'Impressionist'           => 'Impressionist',
			'Infrared'                => 'Infrared',
			'Invert'                  => 'Invert',
			'Lens Correction'         => 'Lens Correction',
			'Lens flare'              => 'Lens flare',
			'Lomo Effect'             => 'Lomo Effect',
			'Motion Blur'             => 'Motion Blur',
			'Night Vision'            => 'Night Vision',
			'Oil Painting'            => 'Oil Painting',
			'Old Photo'               => 'Old Photo',
			'Orton Effect'            => 'Orton Effect',
			'Panorama'                => 'Panorama',
			'Pinch'                   => 'Pinch',
			'Pixelate'                => 'Pixelate',
			'Polar Coordinates'       => 'Polar Coordinates',
			'Posterize'               => 'Posterize',
			'Radial Blur'             => 'Radial Blur',
			'Rain'                    => 'Rain',
			'Reflect'                 => 'Reflect',
			'Ripple'                  => 'Ripple',
			'Sharpen'                 => 'Sharpen',
			'Slow motion'             => 'Slow motion',
			'Stop-motion'             => 'Stop-motion',
			'Solarize'                => 'Solarize',
			'Starburst'               => 'Starburst',
			'Sunburst'                => 'Sunburst',
			'Timelapse'               => 'Timelapse',
			'Tilt-shift'              => 'Tilt-shift',
			'Vignette'                => 'Vignette',
			'Zoom blur'               => 'Zoom blur',

	),
	'Size'            => array(
			'256x256'   => '256x256',
			'512x512'   => '512x512',
			'1024x1024' => '1024x1024',
	),
	'Models'            => array(
		
			'dall-e-3'   => 'dall-e-3',
			'dall-e-2'   => 'dall-e-2',
			
	),
	
);
        }
        
        public static function getSettingsGroupsKeys(){
            return array(
                    's2baia_artist_opt',
                    's2baia_camera_opt',
                    's2baia_color_opt',
                    's2baia_composition_opt',
                    's2baia_lighting_opt',
                    's2baia_models_opt',
                    's2baia_photography_opt',
                    's2baia_resolution_opt',
                    's2baia_size_opt',
                    's2baia_special-effects_opt',
                    's2baia_style_opt'
                );
        }
        
        public static function version3(){
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql1 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 's2baia_chatbots' . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_author` int(11) NOT NULL DEFAULT "0"  ,
                `type_of_chatbot` int(11) NOT NULL DEFAULT "1"  ,
                `hash_code`  varchar(100)  NOT NULL DEFAULT "",
                `bot_options`  text DEFAULT NULL ,
                `comment`  text  DEFAULT NULL,
                `datetimecreated`  varchar(50) NOT NULL DEFAULT "" ,
                `disabled`  SMALLINT NOT NULL DEFAULT "0"
                ) ENGINE = INNODB '. $charset_collate;
            dbDelta( $sql1 );
                
            $sql2 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 's2baia_chats_log' . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_user` int(11) NOT NULL DEFAULT "0"   ,
                `id_bot` int(11) NOT NULL DEFAULT "0"   ,
                `question` text  DEFAULT NULL  ,
                `answer`  text  DEFAULT NULL  ,
                `model` varchar(100)  NOT NULL DEFAULT ""  ,
                `ipaddress` varchar(200)  NOT NULL DEFAULT ""  ,
                `comments` varchar(255)  NOT NULL DEFAULT ""  ,
                `disabled`  SMALLINT NOT NULL DEFAULT "0"
                ) ENGINE = INNODB '. $charset_collate;
            
            
            dbDelta( $sql2 );
            self::checkUpdateVersion3();
            
            update_option( "s2baia_database_version", 3 );
                
        }
        
        public static function version4(){
            global $wpdb;
            $table_name = $wpdb->prefix . "s2baia_chatbots";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name))) == $table_name ) {
                if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix ."s2baia_chatbots") == 0 ){
                    $wpdb->update($wpdb->prefix . 's2baia_chatbots', 
                    array(
                        'type_of_chatbot' => 2), 
                    array('hash_code' => 'assistant'),
                    array('%d'),
                    array('%s'));
                }
            }
            
            $table_name2 = $wpdb->prefix . "s2baia_chats_log";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name2))) == $table_name2 ) {
                if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix ."s2baia_chats_log") == 0 ){
                    $wpdb->query(
                    "ALTER TABLE `".$wpdb->prefix."s2baia_chats_log` "
                       . "ADD `parent_id` int(11) DEFAULT 0,  "
                       . "ADD `correct_answer`  text  DEFAULT NULL,  "
                       . "ADD `rated` int(11) NOT NULL DEFAULT 0    "     
                       . ";"
                    );
                }
            }
            
        }
        
        public static function checkUpdateVersion3(){
            global $wpdb;
            $table_name = $wpdb->prefix . "s2baia_chatbots";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name))) == $table_name ) {
                if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix ."s2baia_chatbots") == 0 ){
                    self::version3DataAddBots();
                }
            }
            
            
        }
        
        public static function version3DataAddBots(){
            global $wpdb;
            $today = time();
            $default_option = [
                'background_color'=>'#ffffff',
                'header_text_color'=>'#ffffff',
                'color'=>'#ffefea',
                'header_color'=>'#0C476E',
                'send_button_color'=>'#0E5381',
                'send_button_text_color'=>'ffffff',
                'position'=>'right',
                'icon_position'=>'bottom-right',
                'chat_icon_size'=>70,
                'chat_width'=> 25,
                'chat_width_metrics'=>'%',
                'chat_height'=>55,
                'chat_height_metrics'=>'%',
                'greeting_message' => 0,
                'greeting_message_text'=>esc_html__('Hello! I am an AI Assistant. How can I help you?','s2b-ai-aiassistant'),
                'message_placeholder'=>esc_html__('Ctrl+Enter to send request','s2b-ai-aiassistant'),
                'chatbot_name'=>'GPT Assistant',
                'compliance_text'=>'',
                'chat_temperature'=>0.8,
                'chat_model'=>'gpt-3.5-turbo-16k',
                'chat_top_p'=>1,
                'max_tokens'=>2048,
                'frequency_penalty'=> 0,
                'presence_penalty'=> 0,
                'context'=>'',
                'language'=>'english',
                'message_font_size'=>16,
                'message_margin'=>7,
                'message_border_radius'=> 10,
                'chatbot_border_radius'=> 10,
                'message_bg_color'=>'#1476B8',
                'message_text_color'=>'#ffffff',
                'response_bg_color'=>'#5AB2ED',
                'response_text_color'=>'#000000',
                'response_icons_color'=>'#000',
                'access_for_guests'=> 1,
                'chatbot_picture_url' => '',
                'send_button_text' => esc_html__('Send','s2b-ai-aiassistant'),
                'clear_button_text' => esc_html__('Clear','s2b-ai-aiassistant')
                ];
                    
            $wpdb->insert($wpdb->prefix . 's2baia_chatbots', array(
                'hash_code' => 'default',
                'datetimecreated' => $today,
                'bot_options' => json_encode($default_option)
            ),
            array( '%s', '%s', '%s'));
            
            $wpdb->insert($wpdb->prefix . 's2baia_chatbots', array(
                'hash_code' => 'assistant',
                'datetimecreated' => $today,
            ),
            array( '%s', '%s'));
           
        }
        
        
        public static function version5(){
            global $wpdb;
            $table_name = $wpdb->prefix . "s2baia_chatbots";
            if ( $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE  %s",$wpdb->esc_like($table_name))) == $table_name ) {
                    $wpdb->update($wpdb->prefix . 's2baia_chatbots', 
                    array(
                        'type_of_chatbot' => 2), 
                    array('hash_code' => 'assistant'),
                    array('%d'),
                    array('%s'));

            }
            
            
            
        }
        
        public static function version6(){
            
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            global $wpdb;
            $table_name = $wpdb->prefix . "s2baia_chats_log";
            $sql = "DROP TABLE IF EXISTS $table_name";
            $wpdb->query( $sql );
            $charset_collate = $wpdb->get_charset_collate();    
            $sql2 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 's2baia_messages_log' . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_user` int(11) NOT NULL DEFAULT "0"   ,
                `typeof_message` int(11) NOT NULL DEFAULT "1"   ,
                `id_assistant` int(11) NOT NULL DEFAULT "0"   ,
                `messages` text  DEFAULT NULL  ,
                `parameters`  text  DEFAULT NULL  ,
                `ipaddress` varchar(200)  NOT NULL DEFAULT ""  ,
                `chat_id` varchar(50)  NOT NULL DEFAULT ""  ,
                `comments` varchar(255)  NOT NULL DEFAULT ""  ,
                `created` varchar(50)  NOT NULL DEFAULT ""  ,
                `updated` varchar(50)  NOT NULL DEFAULT ""  ,
                `selected`  SMALLINT NOT NULL DEFAULT "0"
                ) ENGINE = INNODB '. $charset_collate;
            
            dbDelta( $sql2 );
            
            
        }
        
    }

}
