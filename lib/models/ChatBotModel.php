<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('S2bAia_ChatBotModel')) {

    class S2bAia_ChatBotModel {
        public $exclude_default_assistant = true;
        
        public function getChatBotSettings($chatbot_hash = '') {
            global $wpdb;

            //get_row
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_chatbots WHERE hash_code LIKE %s  ", [$chatbot_hash]));
            if (is_object($row) && isset($row->bot_options) && strlen($row->bot_options) > 1 && $row->bot_options != null) {
                $row->bot_options = json_decode($row->bot_options);
                $bot_options = $row->bot_options;
                $new_bot_options = [];
                foreach ($bot_options as $idx => $b_opt) {
                    if ($idx == 'compliance_text') {
                        $allowed = array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                                'target' => array(),
                                'style' => array()   
                            )
                        );
                        $new_bot_options[sanitize_text_field($idx)] = wp_kses($b_opt,$allowed);
                        continue;
                    }
                    $new_bot_options[sanitize_text_field($idx)] = sanitize_text_field($b_opt);
                }
                $row->bot_options = $new_bot_options;
                
            } else {
                $row = new stdClass();
                $row->bot_options = [];
                $row->comment = '';
                $row->id_author = 0;
                $row->datetimecreated = 0;
                $row->id = 0;
                $row->hash_code = '';
            }
            $row->id_author = (int) $row->id_author;
            $row->comment = sanitize_text_field($row->comment);
            $row->datetimecreated = sanitize_text_field($row->datetimecreated);

            return $row;
        }
        
        public function getChatBotById($chatbot_id = 0) {
            global $wpdb;

            //get_row
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_chatbots WHERE id = %d  ", [$chatbot_id]));
            if (is_object($row) && isset($row->bot_options) && strlen($row->bot_options) > 1 && $row->bot_options != null) {
                $row->bot_options = json_decode($row->bot_options);
                $bot_options = $row->bot_options;
                $new_bot_options = [];
                foreach ($bot_options as $idx => $b_opt) {
                    if ($idx == 'compliance_text') {
                        $allowed = array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                                'target' => array(),
                                'style' => array()   
                            )
                        );
                        $new_bot_options[sanitize_text_field($idx)] = wp_kses($b_opt,$allowed);
                        continue;
                    }
                    $new_bot_options[sanitize_text_field($idx)] = sanitize_text_field($b_opt);
                }
                $row->bot_options = $new_bot_options;
                
            } else {
                $row = new stdClass();
                $row->bot_options = [];
                $row->comment = '';
                $row->id_author = 0;
                $row->datetimecreated = 0;
                $row->id = 0;
                $row->hash_code = '';
            }
            $row->id_author = (int) $row->id_author;
            $row->comment = sanitize_text_field($row->comment);
            $row->datetimecreated = sanitize_text_field($row->datetimecreated);

            return $row;
        }
        
        public function parseBotOptions($bot_options_str = '') {
            if (!class_exists('S2bAia_ChatBotUtils')) {
                require_once S2BAIA_PATH . '/lib/helpers/ChatBotUtils.php';
            }
            $bot_options = json_decode($bot_options_str);
            if (!is_object($bot_options)) {
                return S2bAia_ChatBotUtils::getDefaultAssistant();
            }
            return $bot_options;
        }

        public function parseAssistantFile($bot_options) {//TO DO delete
            $uploaded_f = '';
            if (isset($bot_options['assistant_file']) && strlen($bot_options['assistant_file']) > 0) {
                $uploaded_f = sanitize_text_field($bot_options['assistant_file']);
            }
            $uploaded_default_file = ['code' => 0, 'error_msg' => '', 'id' => '', 'filename' => '', 'assistant_file' => ''];
            if (is_string($uploaded_f) && strlen($uploaded_f) > 0) {
                $uploaded_f_arr = unserialize(base64_decode($uploaded_f));
                if (is_array($uploaded_f_arr)) {
                    $uploaded_file = $uploaded_f_arr;
                } else {
                    $uploaded_file = $uploaded_default_file;
                }
            } else {
                $uploaded_file = $uploaded_default_file;
            }
            if ($uploaded_f == FALSE) {
                $uploaded_file = $uploaded_default_file;
            }
            return $uploaded_file;
        }

        public function storeChatBotOptions($chatbot_hash = '', $data = []) {

            $current_chat_bot = $this->getChatBotSettings($chatbot_hash);
            if (is_object($current_chat_bot) && isset($current_chat_bot->id) && $current_chat_bot->id > 0) {
                $res = $this->updateChatBotOptions($chatbot_hash, $data, $current_chat_bot->bot_options);
            } else {
                $res = $this->insertChatBotSettingsOptions($chatbot_hash, $data);
            }
            return $res !== false;
        }

        public function insertChatBotSettingsOptions($chatbot_hash = '', $data = []) {

            global $wpdb;
            $botprovider = (int)$data['botprovider'];
            unset($data['botprovider']);
            $encoded = json_encode($data);
            $wpdb->insert($wpdb->prefix . 's2baia_chatbots',
                    array(
                        'hash_code' => $chatbot_hash,
                        'bot_options' => $encoded,
                        'type_of_chatbot' => $botprovider
                    ),
                    array('%s', '%s','%d')
            );

            return $wpdb->insert_id;
        }

        public function updateChatBotOptions($chatbot_hash = '', $data = [], $old_data = []) {

            global $wpdb;
            $donottouched = [];
            unset($data['botprovider']);
            foreach ($old_data as $key => $value) {
                if (!array_key_exists($key, $data)) {
                    $donottouched[$key] = $value;
                }
            }
            $new = array_merge($donottouched, $data);
            $encoded = json_encode($new);
            $res = $wpdb->update($wpdb->prefix . 's2baia_chatbots',
                            array(
                                'bot_options' => $encoded),
                            array('hash_code' => $chatbot_hash),
                            array('%s'),
                            array('%s'));
            return $res;
        }
        
        public function deleteChatBot($hash_code = '') {
            global $wpdb;
            $res =  $wpdb->delete($wpdb->prefix . 's2baia_chatbots', 
                    array(
                        'hash_code' => $hash_code
                    ),
                    array(
                        '%s'
                    ));
            return $res;
        }
        
        public function getLogRecords($load_disabled = false){
            global $wpdb;
            if (!$load_disabled) {
                return $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log WHERE selected = %d ",0));
            }
            
            return $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log");
        }
        
        
        
        public function searchBotRecords($type = 0, $search = '', $page = 1, $records_per_page = 20, $show_selected_only = false) {
            
            global $wpdb;
            $par_arr = [];
            $default_where_parameter = 1;
            $type_str = '';
            if ($type > 0) {
                $type_str = " AND type_of_chatbot = %d "  ;
                $par_arr[] = (int) $type;
            }
            $search_part = '';
            if (strlen($search) > 0) {
                $search_part = ' AND  bot_options LIKE %s';
                $par_arr[] = '%' . $search . '%';
            }
            $disabled_part = '';
            if ($show_selected_only) {
                $disabled_part = ' AND disabled = 1 ';
            }
            
            $excluded_part = '';
            if($this->exclude_default_assistant){
                $excluded_part = ' AND hash_code <> "assistant" ';
            }
            
            if (count($par_arr) > 0) {
                $cnt = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "s2baia_chatbots   WHERE 1  " . $type_str . $search_part . $disabled_part.$excluded_part, $par_arr));
            }else{
                $cnt = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "s2baia_chatbots     WHERE %d  " . $type_str . $search_part . $disabled_part.$excluded_part,[$default_where_parameter]));
            }
            
            $limit_part = ""  ;
            if($records_per_page > 0 && $page > 0){
                $limit_part = " LIMIT  %d,%d "  ;
                $par_arr[] = ($page - 1) * $records_per_page; 
                $par_arr[] =  $records_per_page;
            }
            
            if (count($par_arr) > 0) {
                $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_chatbots  WHERE 1  " . $type_str . $search_part . $disabled_part. $excluded_part . $limit_part, $par_arr));
            }else{
                $rows = $wpdb->get_results($wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "s2baia_chatbots  WHERE %d  " . $type_str . $search_part . $disabled_part . $excluded_part . $limit_part,[$default_where_parameter]));
            }
            $new_rows = [];
            foreach($rows as $row){
                
                $bot_options = json_decode($row->bot_options);
                $new_bot_opts = new stdClass();
                foreach($bot_options as $idx => $bot_opt){
                    $iidx = sanitize_text_field($idx);
                    if($idx == 'custom_css'){
                        $new_bot_opts->$iidx = strip_tags($bot_opt);
                    }else{
                        $new_bot_opts->$iidx = sanitize_text_field($bot_opt);
                    }
                }
                if(is_object($new_bot_opts) && !isset($new_bot_opts->html_id_closed_bot)){
                    $new_bot_opts->html_id_closed_bot = '';
                }
                if(is_object($new_bot_opts) && !isset($new_bot_opts->html_id_open_bot)){
                    $new_bot_opts->html_id_open_bot = '';
                }
                if(is_object($new_bot_opts) && !isset($new_bot_opts->custom_css)){
                    $new_bot_opts->custom_css = '';
                }
                $row->bot_options = $new_bot_opts;
                $row->id_author = (int)$row->id_author;
                $row->type_of_chatbot = (int)$row->type_of_chatbot;
                $row->hash_code = sanitize_text_field($row->hash_code);
                
                $new_rows[(int)$row->id] = $row;
            }
            return ['cnt' => $cnt, 'rows' => $new_rows];
        }
        
    }

}