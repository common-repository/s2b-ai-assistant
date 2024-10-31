<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('S2bAia_Utils')) {

    class S2bAia_Utils {
        /*
          retrieves list of models from option table that are used in Edit tab of metabox
         * returns models in format [1=>'gpt-4o',2=>'gpt-3.5-turbo-16k'....];
         *          */

        public static function getEditModels() {

            $edit_models = [];
            $gpt_models = unserialize(get_option('s2baia_chatgpt_models', []));
            $i = 1;
            foreach ($gpt_models as $mkey => $mval) {
                if ($mval == 3) {
                    $edit_models[$i] = $mkey;
                    $i++;
                }
            }
            return $edit_models; //in format [1=>'gpt-3.5-turbo',2=>'gpt-3.5-turbo-16k'];
        }

        /*
          retrieves list of models from option table that are used in Expert tab of metabox
         * returns models in format [1=>'gpt-3.5-turbo',2=>'gpt-3.5-turbo-16k'....];
         *          */

        public static function getExpertModels() {

            $expert_models = [];
            $gpt_models = unserialize(get_option('s2baia_chatgpt_expert_models', []));
            $i = 1;
            foreach ($gpt_models as $mkey => $mval) {
                if ($mval == 3 || $mval == 1) {
                    $expert_models[$i] = $mkey;
                    $i++;
                }
            }
            return $expert_models; // [1=>'gpt-3.5-turbo',2=>'gpt-3.5-turbo-16k'];
        }

        /*
          retrieves list of models from option table that are used in Edit tab of metabox
         * returns models in format ['gpt-3.5-turbo','gpt-3.5-turbo-16k'];;
         *          */

        public static function getEditModelTexts() {

            $edit_models = [];
            $gpt_models = unserialize(get_option('s2baia_chatgpt_models', []));
            foreach ($gpt_models as $mkey => $mval) {
                if ($mval == 3) {
                    $edit_models[] = sanitize_text_field($mkey);
                }
            }
            return $edit_models; //['gpt-3.5-turbo','gpt-3.5-turbo-16k'];
        }

        /*
          retrieves list of models from option table that are used in Expert tab of metabox
         * returns models in format ['gpt-3.5-turbo','gpt-3.5-turbo-16k'];;
         *          */

        public static function getExpertModelTexts() {

            $expert_models = [];
            $gpt_models = unserialize(get_option('s2baia_chatgpt_expert_models', []));
            foreach ($gpt_models as $mkey => $mval) {
                if ($mval == 3 || $mval == 1) {
                    $expert_models[] = sanitize_text_field($mkey);
                }
            }
            return $expert_models; //return ['gpt-3.5-turbo','gpt-3.5-turbo-16k'];
        }

        //checks if user has access to plugin

        public static function checkChatGPTAccess() {//TO-DO add capability and/or usermeta
            return self::checkEditAccess();
        }

        public static function getChatGptMaxTokens() {
            return get_option(S2BAIA_PREFIX_LOW . 'max_tokens', 2048);
        }

        public static function getUsername($user_id) {

            $created_by = get_userdata($user_id);
            if (is_object($created_by) && isset($created_by->ID)) {
                $author = $created_by->user_login;
            } else {
                $author = esc_html__('System', 's2b-ai-aiassistant');
            }
            return $author;
        }

        public static function checkDeleteInstructionAccess() {
            if (current_user_can('manage_options')) {
                //return true;
            }
            $user_role = get_option(S2BAIA_PREFIX_LOW . 'config_delete_instructions');
            return self::checkCaps($user_role);
        }

        public static function checkEditInstructionAccess() {

            $edrole = get_option(S2BAIA_PREFIX_LOW . 'config_edit_instructions', 'editor');

            return self::checkCaps($edrole);
        }

        public static function checkCaps($neccesary_role) {


            $curuser = wp_get_current_user();
            $curroles = (array) $curuser->roles;
            if (!is_array($curroles) || count($curroles) <= 0) {
                return false;
            }
            $curcaps = get_role($curroles[0])->capabilities;
            $caps = get_role($neccesary_role)->capabilities;

            foreach ($caps as $cap => $val) {
                if ($val == false) {
                    continue;
                }
                if (!array_key_exists($cap, $curcaps)) {
                    return false;
                }
                if ($curcaps[$cap] == false) {
                    return false;
                }
            }
            return true;
        }
        
        /*Returns roles allowed to do manipilations with instructions*/
        public static function getInstructionRoles() {
            $s2baia_users_with_caps = get_editable_roles();
            $s2baia_user_roles = [];
            foreach ($s2baia_users_with_caps as $role_n => $role_obj) {
                if (!get_role($role_n)->has_cap('edit_posts')) {
                    continue;
                }
                $s2baia_user_roles[] = $role_n;
            }
            return $s2baia_user_roles;
        }

        public static function checkEditAccess() {
            if (current_user_can('manage_options')) {
                //return true;
            }
            $mbuserrole = get_option(S2BAIA_PREFIX_LOW . 'config_meta_instructions', 'editor');
            return self::checkCaps($mbuserrole);
        }

        public static function sanitizeArrayModels(&$models_array = []) {
            
            $models_array  = array_map('sanitize_text_field',$models_array);
            foreach ($models_array as $key => $iv) {
                $models_array[$key] = (int) $iv;
            }
            
            return $models_array;
        }

        //used in such constructions wp_kses($data['instruction'], S2bAia_Utils::getInstructionAllowedTags());

        public static function getInstructionAllowedTags() {
            return [];
        }
        
        
        public static function getToken($length) {
            $token = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet .= "0123456789";
            $max = strlen($codeAlphabet); // edited

            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[self::cryptoRandSecure(0, $max - 1)];
            }

            return $token;
        }

        public static function cryptoRandSecure($min, $max) {
            $range = $max - $min;
            if ($range < 1){
                return $min; // not so random...
            }
            $log = ceil(log($range, 2));
            $bytes = (int) ($log / 8) + 1; // length in bytes
            $bits = (int) $log + 1; // length in bits
            $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ($rnd > $range);
            return $min + $rnd;
        }
        
        public static function storeFile( $targetDir) {
            if (!isset($_FILES) || !is_array($_FILES) || !isset($_FILES['s2baia_chatbot_config_database'])) {
                return '';
            }
            $jform = $_FILES['s2baia_chatbot_config_database'];
            if (!isset($jform['error']) || !isset($jform['name']) || !isset($jform['size']) || !isset($jform['tmp_name'])) {
                return '';
            }
            $chunk = isset($_REQUEST["chunk"]) ? (int)$_REQUEST["chunk"] : 0;
            //$chunks = isset($_REQUEST["chunks"]) ? (int)$_REQUEST["chunks"] : 0;
            //$error = $jform['error'];
            $name = sanitize_file_name($jform['name']);
            if (strlen($name) == 0) {
                return '';
            }
            $finfo = pathinfo($name);

            if (is_array($finfo)) {
                $fname = sanitize_file_name($finfo['filename']);
                $fext = $finfo['extension'];
                if(!self::checkAllowedFilesearchExtensions($fext)){
                    return '';
                }
                if (file_exists($targetDir . DIRECTORY_SEPARATOR . $name)) {
                    $timest = time();
                    $name = $fname . '_' . $timest . '_' . random_int(1000, 9999) . '.' . $fext;
                }
            }
            //$size = $jform['size'];
            $tmp_name = $jform['tmp_name'];


            $outfile = $targetDir . DIRECTORY_SEPARATOR . $name;

            $out = fopen($outfile, $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen($tmp_name, "rb");

                if ($in) {

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                } else {
                    return '';
                }
                fclose($in);
                fclose($out);
                @unlink($tmp_name);
            } else {
                return '';
            }
            return $targetDir . DIRECTORY_SEPARATOR . $name;
        }
        
        public static function checkAllowedFilesearchExtensions($ext){
            switch($ext){
                case 'c':
                case 'cs':
                case 'cpp':
                case 'doc':
                case 'docx':
                case 'html':
                case 'java':
                case 'json':
                case 'md':
                case 'pdf':
                case 'php':
                case 'pptx':
                case 'py':
                case 'rb':
                case 'tex':
                case 'txt':
                case 'css':
                case 'js':
                case 'sh':
                case 'ts':

                    return true;
                    
                default:
                    return false;
            }
            return false;
        }
        
        
        public static function getIpAddress( $params = null ) {
            
		$ip = '127.0.0.1';
		$headers = [
			'HTTP_TRUE_CLIENT_IP',
			'HTTP_CF_CONNECTING_IP',
			'HTTP_X_REAL_IP',
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];
	
		if ( isset( $params ) && isset( $params[ 'ip' ] ) ) {
			$ip = ( string )$params[ 'ip' ];
		} else {
			foreach ( $headers as $header ) {
				if ( array_key_exists( $header, $_SERVER ) && !empty( $_SERVER[ $header ] && $_SERVER[ $header ] != '::1' ) ) {
					$address_chain = explode( ',', wp_unslash( $_SERVER [ $header ] ) );
					$ip = filter_var( trim( $address_chain[ 0 ] ), FILTER_VALIDATE_IP );
					break;
				}
			}
		}
	
		return $ip;
  	}
        
        
    
    }

}