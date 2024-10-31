<?php
if ( ! defined( 'ABSPATH' ) ) exit;




if (!class_exists('S2bAia_AdminChatBotController')) {

    class S2bAia_AdminChatBotController extends S2bAia_BaseController {


        
        public function __construct() {
            
            if (!class_exists('S2bAia_ChatBotUtils')) {
                require_once S2BAIA_PATH . '/lib/helpers/ChatBotUtils.php';
            }
            $this->load_model('ChatBotModel');
            
            add_action('wp_ajax_s2b_store_chatbot_general_tab', [$this, 'processGeneralSubmit']);
            add_action('wp_ajax_s2b_store_chatbot', [$this, 'processChatbotSubmit']);
            add_action('wp_ajax_s2b_remove_chatbot', [$this, 'processChatbotRemove']);
            add_action('wp_ajax_s2b_load_chatbots', [$this, 'searchBots']);
            add_action('wp_ajax_s2b_store_chatbot_styles_tab', [$this, 'processStylesSubmit']);
            add_action('admin_post_s2b_store_chatbot_upload', [$this, 'processAssistantUpload']);
            add_action('admin_post_s2b_create_assistant', [$this, 'processAssistantSubmit']);
            add_action('admin_post_s2b_remove_assistant', [$this, 'processAssistantRemove']);
            //chatbot_provider
            add_action('admin_post_s2b_chatbot_provider', [$this, 'processAssistantProvider']);
            add_action('admin_post_s2b_remove_file', [$this, 'processFileRemove']);
            add_action('wp_ajax_s2b_gpt_toggle_selectionlog', [$this, 'toggleSelectionLog']);
            add_action('wp_ajax_s2b_gpt_load_log', [$this, 'searchLogs']);
            add_action('wp_ajax_s2b_bot_switchlogmode', [$this, 'turnOnnOffLogs']);
            add_action('wp_ajax_s2b_bot_logtext', [$this, 'storeLogText']);
            add_action('wp_ajax_s2b_gpt_delete_log', [$this, 'deleteLog']);
            
            
        }

        public function registerAdminMenu() {

            
        }
        
        
        public function processChatbotRemove(){
            
            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = 's2b_bot_dellognonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            if (!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
                $r['result'] = 4;
                $r['msg'] = __('Chatbot not specified','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $bot_id = sanitize_text_field($_POST['id']);
            $model = $this->model;
            $bot = $model->getChatBotById($bot_id);
            
            if (!$bot || !is_object($bot) || $bot->id == 0) {//verify id instruction
                $r['result'] = 4;
                $r['msg'] = __('Wrong bot','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            //check if user is allowed to delete instruction
            if (!S2bAia_Utils::checkDeleteInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $bot_hash = sanitize_text_field($bot->hash_code);
            if($bot_hash == 'default' || $bot_hash == 'assistant'){
                $r['result'] = 11;
                $r['msg'] = __('It is not allowed to delete default bots','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $model->deleteChatBot($bot_hash);
            $r['result'] = 200;
            $r['msg'] = 'OK';
            $r['del_row'] = $bot->id;
            wp_send_json($r);
            exit;
            
        }
        
        public function processChatbotSubmit(){
            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                return;
            }

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant'),'bot' => [],'chat_bots'=> []];
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            
            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            
            $data = [];
            if (isset($_POST['s2baia_access_for_guests'])) {//++
                if($_POST['s2baia_access_for_guests'] == 'on'){
                   $data['access_for_guests']  = 1;
                }else{
                   $data['access_for_guests']  = 0;
                }
            }else{
                $data['access_for_guests']  = 0;
            }
            
            if (isset($_POST['s2baia_chatbot_chat_height'])) {//++
                $data['chat_height']  = (int)$_POST['s2baia_chatbot_chat_height'];
            }
            
            if (isset($_POST['s2baia_chatbot_height_metrics'])) {//++
                $data['chat_height_metrics']  = $_POST['s2baia_chatbot_height_metrics'] == '%'?'%':'px';
            }
            
            if (isset($_POST['s2baia_chatbot_chat_width'])) {//++
                $data['chat_width']  = (int)$_POST['s2baia_chatbot_chat_width'];
            }
            
            if (isset($_POST['s2baia_chatbot_width_metrics'])) {//++
                $data['chat_width_metrics']  = $_POST['s2baia_chatbot_width_metrics'] == '%'?'%':'px';
            }
            
            if (isset($_POST['s2baia_chatbot_chat_icon_size'])) {//++
                $data['chat_icon_size']  = (int)$_POST['s2baia_chatbot_chat_icon_size'];
            }
            
            if (isset($_POST['s2baia_chatbot_chatbot_picture_url'])) {//++
                $data['chatbot_picture_url']  = sanitize_url($_POST['s2baia_chatbot_chatbot_picture_url']);
            }
            
            
            if (isset($_POST[ 's2baia_chatbot_send_button_text'])) {//++
                $data['send_button_text']  = sanitize_text_field($_POST[ 's2baia_chatbot_send_button_text']);
            }
            
            if (isset($_POST[ 's2baia_chatbot_clear_button_text'])) {//++
                $data['clear_button_text']  = sanitize_text_field($_POST[ 's2baia_chatbot_clear_button_text']);
            }
            
            
            // ++
            $data['chatbot_name'] = isset($_POST['s2baia_chatbot_chatbot_name']) ? sanitize_text_field($_POST['s2baia_chatbot_chatbot_name']) : '';
            
            $allowed = array(  
                                'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array(),
                 'style' => array()                   
                )
                            );  
            // ++
            $data['compliance_text'] = isset($_POST['s2baia_chatbot_compliance_text']) ? wp_kses($_POST['s2baia_chatbot_compliance_text'],$allowed) : '';
            
            // ++
            $data['greeting_message_text'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_greeting_message_text']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_greeting_message_text']) : '';

            // ++
            $data['icon_position'] = isset($_POST['s2baia_chatbot_icon_position']) ? sanitize_text_field($_POST['s2baia_chatbot_icon_position']) : 'bottom-right';

            // ++Transforming [s2baia_chatbot_config_language]
            $data['language'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_language']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_language']) : 'english';

            
            //++
            $data['message_placeholder'] = isset($_POST['s2baia_chatbot_message_placeholder']) ? sanitize_text_field($_POST['s2baia_chatbot_message_placeholder']) : '';

            //++ Transforming [s2baia_chatbot_config_position]
            $data['position'] = isset($_POST['s2baia_chatbot_position']) ? sanitize_text_field($_POST['s2baia_chatbot_position']) : 'right';

            
            
            
            $sent_new_bot = true;
            if(isset($_POST['s2b_chatbot_hash'])){
                if($_POST['s2b_chatbot_hash'] == 'generatenew'){
                    $chat_bot_hash = S2bAia_ChatBotUtils::generateBotHash();
                }else{
                    $chat_bot_hash =  sanitize_text_field($_POST['s2b_chatbot_hash']) ;
                    $sent_new_bot = false;
                }
            
            }
            //++
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_header_text_color'])) {
                $data['header_text_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_header_text_color']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_header_color'])) {
                $data['header_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_header_color']);
            }
            
            if (isset($_POST[ 's2baia_chatbot_send_button_color'])) {
                $data['send_button_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_send_button_color']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_send_text_color'])) {
                $data['send_button_text_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_send_text_color']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_send_button_hover_color'])) {
                $data['send_button_hover_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_send_button_hover_color']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_message_bg_color2'])) {
                $data['message_bg_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_message_bg_color2']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_message_text_color2'])) {
                $data['message_text_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_message_text_color2']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_response_bg_color2'])) {
                $data['response_bg_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_response_bg_color2']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_response_text_color2'])) {
                $data['response_text_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_response_text_color2']);
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_response_icons_color2'])) {
                $data['response_icons_color']  = sanitize_text_field($_POST[ 's2baia_chatbot_response_icons_color2']);
            }

            //++
            if (isset($_POST[ 's2baia_chatbot_message_font_size'])) {
                $data['message_font_size']  = (int)$_POST[ 's2baia_chatbot_message_font_size'];
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_message_margin'])) {
                $data['message_margin']  = (int)$_POST[ 's2baia_chatbot_message_margin'];
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_message_border_radius'])) {
                $data['message_border_radius']  = (int)$_POST[ 's2baia_chatbot_message_border_radius'];
            }
            //++
            if (isset($_POST[ 's2baia_chatbot_chatbot_border_radius'])) {
                $data['chatbot_border_radius']  = (int)$_POST[ 's2baia_chatbot_chatbot_border_radius'];
            }
            
            if (isset($_POST[ 's2baia_chatbot_html_id_closed_bot'])) {
                $data['html_id_closed_bot']  = sanitize_text_field($_POST[ 's2baia_chatbot_html_id_closed_bot']);
            }
            if (isset($_POST[ 's2baia_chatbot_html_id_open_bot'])) {
                $data['html_id_open_bot']  = sanitize_text_field($_POST[ 's2baia_chatbot_html_id_open_bot']);
            }
            
            if (isset($_POST[ 's2baia_chatbot_custom_css'])) {
                $data['custom_css']  = strip_tags($_POST[ 's2baia_chatbot_custom_css']);
            }
            $s2baia_botprovider = 1;
            if (isset($_POST[ 's2baia_botprovider'])) {
                $s2baia_botprovider  = (int)$_POST[ 's2baia_botprovider'];
            }
            
            if($s2baia_botprovider == 1)
            {    
                $data['botprovider'] = 1;
                // --
                $data['presence_penalty'] = isset($_POST['s2baia_chatbot_presence_penalty']) ? (float)$_POST['s2baia_chatbot_presence_penalty'] : 0;
                
                // --
                $data['frequency_penalty'] = isset($_POST['s2baia_chatbot_frequency_penalty']) ? sanitize_text_field($_POST['s2baia_chatbot_frequency_penalty']) : 0;
                //--
                $data['max_tokens'] = isset($_POST['s2baia_chatbot_max_tokens']) ? (int)$_POST['s2baia_chatbot_max_tokens'] : 1024;
                if($data['max_tokens'] <= 0){
                        $data['max_tokens'] = 1024;
                }

                //s2baia_chatbot_config_chat_model
                if (isset($_POST[ 's2baia_chatbot_chat_model'])) {//--
                    $data['model']  = sanitize_text_field($_POST[ 's2baia_chatbot_chat_model']);;
                }
                //--
                $data['chat_temperature'] = isset($_POST['s2baia_chatbot_chat_temperature']) && is_numeric($_POST['s2baia_chatbot_chat_temperature']) ? floatval($_POST['s2baia_chatbot_chat_temperature']) : 1;

                // --
                $data['chat_top_p'] = isset($_POST['s2baia_chatbot_chat_top_p']) ? (float)$_POST['s2baia_chatbot_chat_top_p'] : 1;

                //--
                $data['context'] = isset($_POST['s2baia_chatbot_context']) ? sanitize_text_field($_POST['s2baia_chatbot_context']) : '';
            }else{
                $data['botprovider'] = 2;
                if (isset($_POST[ 's2baia_assistant_id'])) {//--
                    $data['assistant_id']  = sanitize_text_field($_POST[ 's2baia_assistant_id']);
                    $data['id'] = $data['assistant_id'];
                }else{
                    $data['assistant_id']  = '';
                    $data['id'] = '';
                }
                $data['assistant_timeout'] = (int)$_POST['s2baia_assistant_timeout'];
                
            }
            

            $res = $this->model->storeChatBotOptions($chat_bot_hash,$data);
            
            $stored_bot = $this->model->getChatBotSettings($chat_bot_hash);
            $r['bot'] = $stored_bot;
            if($sent_new_bot){
                $r['chat_bots'] =  $this->model->searchBotRecords(1,  '',  1, 20);
            }
            if($res){
                
                $r['result'] = 200;
                $r['msg'] = __('OK','s2b-ai-aiassistant');
                
            }else{
                $r['result'] = 500;
                $r['msg'] = __('Error','s2b-ai-aiassistant');
            }
            wp_send_json($r);
            exit;
            
        }
        
        public function processGeneralSubmit() {
            
            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                return;
            }

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_config_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            
            $data = [];
            if (isset($_POST['s2baia_chatbot_access_for_guests'])) {
                if($_POST['s2baia_chatbot_access_for_guests'] == 'on'){
                   $data['access_for_guests']  = 1;
                }else{
                   $data['access_for_guests']  = 0;
                }
            }else{
                $data['access_for_guests']  = 0;
            }
            
            if (isset($_POST['s2baia_chatbot_config_chat_height'])) {
                $data['chat_height']  = (int)$_POST['s2baia_chatbot_config_chat_height'];
            }
            
            if (isset($_POST['s2baia_chatbot_config_height_metrics'])) {
                $data['chat_height_metrics']  = $_POST['s2baia_chatbot_config_height_metrics'] == '%'?'%':'px';
            }
            
            if (isset($_POST['s2baia_chatbot_config_chat_width'])) {
                $data['chat_width']  = (int)$_POST['s2baia_chatbot_config_chat_width'];
            }
            
            if (isset($_POST['s2baia_chatbot_config_width_metrics'])) {
                $data['chat_width_metrics']  = $_POST['s2baia_chatbot_config_width_metrics'] == '%'?'%':'px';
            }
            
            if (isset($_POST['s2baia_chatbot_config_chat_icon_size'])) {
                $data['chat_icon_size']  = (int)$_POST['s2baia_chatbot_config_chat_icon_size'];
            }
            
            if (isset($_POST['s2baia_chatbot_config_chatbot_picture_url'])) {
                $data['chatbot_picture_url']  = sanitize_url($_POST['s2baia_chatbot_config_chatbot_picture_url']);
            }
            
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_text'])) {
                $data['send_button_text']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_text']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_clear_button_text'])) {
                $data['clear_button_text']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_clear_button_text']);
            }
            
            //s2baia_chatbot_config_chat_model
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_chat_model'])) {
                $data['model']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_chat_model']);;
            }
            
            $data['chat_temperature'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chat_temperature']) && is_numeric($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chat_temperature']) ? floatval($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chat_temperature']) : 1;
            
            // Transforming [s2baia_chatbot_config_chat_top_p]
            $data['chat_top_p'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chat_top_p']) ? (float)$_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chat_top_p'] : 1;

            // Transforming [s2baia_chatbot_config_chatbot_name]
            $data['chatbot_name'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chatbot_name']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_chatbot_name']) : '';
            
            $allowed = array(  
                                'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array(),
                 'style' => array()                   
                )
                            );  
            // Transforming [s2baia_chatbot_config_compliance_text]
            $data['compliance_text'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_compliance_text']) ? wp_kses($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_compliance_text'],$allowed) : '';

            // Transforming [s2baia_chatbot_config_context]
            $data['context'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_context']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_context']) : '';

            // Transforming [s2baia_chatbot_config_greeting_message_text]
            $data['greeting_message_text'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_greeting_message_text']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_greeting_message_text']) : '';

            // Transforming [s2baia_chatbot_config_icon_position]
            $data['icon_position'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_icon_position']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_icon_position']) : 'bottom-right';

            // Transforming [s2baia_chatbot_config_language]
            $data['language'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_language']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_language']) : 'english';

            // Transforming [s2baia_chatbot_config_max_tokens]
            $data['max_tokens'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_max_tokens']) ? (int)$_POST[S2BAIA_PREFIX_LOW .'chatbot_config_max_tokens'] : 1024;
            if($data['max_tokens'] <= 0){
                    $data['max_tokens'] = 1024;
            }
            // Transforming [s2baia_chatbot_config_message_placeholder]
            $data['message_placeholder'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_message_placeholder']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_message_placeholder']) : '';

            // Transforming [s2baia_chatbot_config_position]
            $data['position'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_position']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_position']) : 'right';

            // Transforming [s2baia_chatbot_config_presence_penalty]
            $data['presence_penalty'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_presence_penalty']) ? (float)$_POST[S2BAIA_PREFIX_LOW .'chatbot_config_presence_penalty'] : 0;

            // Transforming [s2baia_chatbot_config_frequency_penalty]
            $data['frequency_penalty'] = isset($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_frequency_penalty']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'chatbot_config_frequency_penalty']) : 0;
            
            $chat_bot_hash = isset($_POST[S2BAIA_PREFIX_LOW .'s2b_chatbot_hash']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'s2b_chatbot_hash']) : 'default';
            
            $res = $this->model->storeChatBotOptions($chat_bot_hash,$data);

            
            
            
            if($res){
                $r['result'] = 200;
                $r['msg'] = __('OK','s2b-ai-aiassistant');
            }else{
                $r['result'] = 500;
                $r['msg'] = __('Error','s2b-ai-aiassistant');
            }
            wp_send_json($r);
            exit;
            
        }
        
        
        public function processStylesSubmit() {
            
            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                return;
            }

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_styles_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $data = [];
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_header_text_color'])) {
                $data['header_text_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_header_text_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_header_color'])) {
                $data['header_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_header_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_color'])) {
                $data['send_button_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_text_color'])) {
                $data['send_button_text_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_text_color']);
            }
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_hover_color'])) {
                $data['send_button_hover_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_send_button_hover_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_bg_color'])) {
                $data['message_bg_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_bg_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_text_color'])) {
                $data['message_text_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_text_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_bg_color'])) {
                $data['response_bg_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_bg_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_text_color'])) {
                $data['response_text_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_text_color']);
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_icons_color'])) {
                $data['response_icons_color']  = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_response_icons_color']);
            }
            
            
            
            
            
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_font_size'])) {
                $data['message_font_size']  = (int)$_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_font_size'];
            }
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_margin'])) {
                $data['message_margin']  = (int)$_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_margin'];
            }
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_border_radius'])) {
                $data['message_border_radius']  = (int)$_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_message_border_radius'];
            }
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_chatbot_border_radius'])) {
                $data['chatbot_border_radius']  = (int)$_POST[S2BAIA_PREFIX_LOW . 'chatbot_config_chatbot_border_radius'];
            }
            
            $chat_bot_hash = isset($_POST[S2BAIA_PREFIX_LOW .'s2b_chatbot_hash']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_LOW .'s2b_chatbot_hash']) : 'default';
            
            $res = $this->model->storeChatBotOptions($chat_bot_hash,$data);
            
            if($res){
                $r['result'] = 200;
                $r['msg'] = __('OK','s2b-ai-aiassistant');
            }else{
                $r['result'] = 500;
                $r['msg'] = __('Error','s2b-ai-aiassistant');
            }
            wp_send_json($r);
            exit;
            
        }
        

        function showMainView(){
            $this->showChatbotSettings();
        }
        
        function showChatbotSettings() {
            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                return;
            }
            if (!class_exists('S2bAia_ChatBotLogModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotLogModel.php';
                }
            $log_model = new S2bAia_ChatBotLogModel();
            $records_per_page = 10;
            $current_page = 1;
            $search_string = '';
            $arr_logrecords = $log_model->searchLogRecords(0, $search_string, $current_page, $records_per_page, false);
            $default_bot = $this->model->getChatBotSettings('default');
            $current_assistant = $this->model->getChatBotSettings('assistant');
            $chat_bots = $this->model->searchBotRecords(1,  '',  1, 20);
            $assistants = $this->model->searchBotRecords(2,  '',  1, 20);
            $conf_contr = $this;
            
            $conf_contr->load_view('backend/chatbot/chatbot', ['default_bot' => $default_bot,
                'current_assistant'=>$current_assistant,
                'arr_logrecords'=>$arr_logrecords,'records_per_page'=>$records_per_page,
                'curr_page'=>$current_page,'log_model'=>$log_model, 'chat_bots' =>$chat_bots,
                'assistants'=>$assistants]);
            
            $conf_contr->render();
        }

        

        public function checkAssistantApiChanged($old_assistant_options,$new_assistant_options){
            //TO-DO add checking get https://api.openai.com/v1/assistants/{assistant_id},
            
            if(!isset($old_assistant_options['instruction']) || $old_assistant_options['instruction'] != $new_assistant_options['instructions'] ){
                return true;
            }
            
            if(!isset($old_assistant_options['model']) || $old_assistant_options['model'] != $new_assistant_options['model'] ){
                return true;
            }
            if(!isset($old_assistant_options['name']) || $old_assistant_options['name'] != $new_assistant_options['assistant_name'] ){
                return true;
            }
            if(!isset($old_assistant_options['file_id']) || $old_assistant_options['file_id'] != $new_assistant_options['file_id'] ){
                return true;
            }
            //file_id
            return false;
        }
        
        
        public function processAssistantProvider() {
            $redirect_url = admin_url('admin.php?page=s2baia_chatbot');
            $r = ['code' => 404, 'error_msg' => '', 'id' => '', 'model' => ''
                , 'created_at' => '', 'instruction' => '', 'name' => '', 'description' => ''];

            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce';

            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                $r['code'] = 403;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                update_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider', $resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }


            $provider = sanitize_text_field($_POST['s2baia_chatbot_config_chatbot_provider']);
            $chat_bot_providers = S2bAia_ChatBotUtils::getProviders();
            $found = false;
            foreach ($chat_bot_providers as $provideropt) {
                if ($provider === $provideropt) {
                    $found = true;
                }
            }
            if (!$found) {
                $r['code'] = 404;
                $r['error_msg'] = 'Provider not found';
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            update_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider', $provider);

            wp_redirect(esc_url($redirect_url));
            exit;
            
        }

        public function processAssistantSubmit() {
            
            $redirect_url = admin_url('admin.php?page=s2baia_chatbot');
            $r = ['code'=>404,'error_msg'=>'','id'=>'','model' =>''
            ,'created_at' =>'','instruction' =>'','name' =>'','description'=>''];
            
            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            
            
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce';

            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                $r['code'] = 403;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            
            
    
                $data = [];
                $assistant_hash = isset($_POST) && isset($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash'])?sanitize_text_field($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash']):'assistant';
                $model = $this->model;
                $assistant_info = $model->getChatBotSettings($assistant_hash); 
                if(!is_object($assistant_info)){
                $r['code'] = 405;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                //update_option(S2BAIA_PREFIX_LOW . 'assistant_file', $resp);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            $bot_options = $assistant_info->bot_options;//it is array
            //$assistant_file = $model->parseAssistantFile($bot_options);
            
                $mod_id = sanitize_text_field($_POST['s2baia_chatbot_config_chat_model2']);
                $models_allowed = S2bAia_Utils::getEditModelTexts();
                if (!in_array($mod_id, $models_allowed)) {
                    $r['code'] = 403;
                    $r['msg'] = __('Model is not allowed','s2b-ai-aiassistant');
                    $resp = serialize($r);
                    $this->updateAssistantInfoDb($resp);
                    wp_redirect(esc_url($redirect_url));
                    exit;
                }
                $data['model'] = $mod_id;

                $data['assistant_name']  = isset($_POST['s2baia_assistant_name'])?sanitize_text_field($_POST['s2baia_assistant_name']):'assistant';
                $data['instructions']  = isset($_POST['s2baia_assistant_instructions'])?sanitize_text_field($_POST['s2baia_assistant_instructions']):'';
                $data['assistant_timeout'] = isset($_POST['s2baia_assistant_timeout']) && ((int)$_POST['s2baia_assistant_timeout']) > 0?(int)$_POST['s2baia_assistant_timeout']:1;
                
                
                $file_id = isset($bot_options['assistant_file_id']) && strlen($bot_options['assistant_file_id']) > 0?$bot_options['assistant_file_id']:'';
                if(strlen($file_id) <= 0){
                    $r['code'] = 403;
                    $r['msg'] = __('File is not uploaded','s2b-ai-aiassistant');
                    $resp = serialize($r);
                    $this->updateAssistantInfoDb($resp);
                    wp_redirect(esc_url($redirect_url));
                    exit;
                }
                $data['file_id'] = $file_id;
                if (!class_exists('S2bAia_AiRequest')) {
                    require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
                }
                //$old_option = isset($bot_options['assistant_opts'])?$bot_options['assistant_opts']:'';
                if(!isset($bot_options['assistant_id']) || strlen($bot_options['assistant_id']) <= 0){//insert
                    $response = S2bAia_AiRequest::createAssistantRetrievalV2($data);
                    if(is_array($response) && isset($response['id']) && strlen($response['id']) > 0){
                        $assistant_opts = base64_encode(serialize($response));
                        $response['assistant_id'] = $response['id'];
                        $response['assistant_opts'] = $assistant_opts;
                        $response['assistant_timeout'] = $data['assistant_timeout'];
                        $model->updateChatBotOptions($assistant_hash ,$response,$assistant_info->bot_options);
                    }else{
                        //if(isset($response['code']) && $response['code'] !== 0 && isset($response['error_msg']) ){
                        $response['assistant_timeout'] = $data['assistant_timeout'];
                            $model->updateChatBotOptions($assistant_hash ,$response,$assistant_info->bot_options);
                        //}
                    }
                }else{//update
                    //check if api fields changed

                    $api_changed = $this->checkAssistantApiChanged($assistant_info->bot_options, $data);
                    $this->updateAssistant($assistant_info->bot_options, $data, $api_changed,$assistant_hash);
                    
                }
                

            wp_redirect(esc_url($redirect_url));
            exit;
            
        }
        
        public function processAssistantRemove(){
            $redirect_url = admin_url('admin.php?page=s2baia_chatbot');

            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            $r = ['code'=>404,'error_msg'=>'','id'=>''];
            
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce';

            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                $r['code'] = 403;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                update_option(S2BAIA_PREFIX_LOW . 'assistant_options', $resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
                
            $assistant_hash = isset($_POST) && isset($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash'])?sanitize_text_field($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash']):'assistant';
            $model = $this->model;
            $assistant_info = $model->getChatBotSettings($assistant_hash); 
            if(!is_object($assistant_info)){
                $r['code'] = 405;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                //update_option(S2BAIA_PREFIX_LOW . 'assistant_file', $resp);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            $bot_options = $assistant_info->bot_options;//it is array
            
                if (!class_exists('S2bAia_AiRequest')) {
                    require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
                }
                if(isset($bot_options['assistant_id']) && strlen($bot_options['assistant_id']) > 0){//delete
                        $assistant_id = $bot_options['assistant_id'];
                        $response = S2bAia_AiRequest::removeAssistantV2($assistant_id);
                        if($response){
                            $resp = ['assistant_id' => ''];
                            $resp['assistant_opts'] = '';
                            $resp['error_msg'] = '';
                            $resp['success'] ='true';
                        }else{
                            $resp = ['error_msg' => 'Assistant delete error'];
                            $resp['success'] = 'false';
                        }
                        $model->updateChatBotOptions($assistant_hash ,$resp,$bot_options);
                    
                }
                

            wp_redirect(esc_url($redirect_url));
            exit;
        }
        
        
        public function processFileRemove(){
            $redirect_url = admin_url('admin.php?page=s2baia_chatbot');

            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            $r = ['code'=>404,'error_msg'=>'','id'=>''];

            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce';

            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                $r['code'] = 403;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                update_option(S2BAIA_PREFIX_LOW . 'assistant_options', $resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            $assistant_hash = isset($_POST) && isset($_POST[S2BAIA_PREFIX_SHORT . 'chatbot_hash']) ? sanitize_text_field($_POST[S2BAIA_PREFIX_SHORT . 'chatbot_hash']) : 'assistant';
            $model = $this->model;
            $assistant_info = $model->getChatBotSettings($assistant_hash);
            if (!is_object($assistant_info)) {
                $r['code'] = 405;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                //update_option(S2BAIA_PREFIX_LOW . 'assistant_file', $resp);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            $bot_options = $assistant_info->bot_options; //it is array

            if (!class_exists('S2bAia_AiRequest')) {
                require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
            }

            if (!isset($bot_options['assistant_id']) || strlen($bot_options['assistant_id']) == 0) {//delete
                if (isset($bot_options['assistant_file_id']) && strlen($bot_options['assistant_file_id']) > 0) {
                    $assistant_file_id = $bot_options['assistant_file_id'];
                    $response = S2bAia_AiRequest::removeFileV2($assistant_file_id);
                    if ($response) {
                        $resp = ['assistant_file_id' => ''];
                        $resp['assistant_file_path'] = '';
                        $resp['assistant_file'] = '';
                        $resp['error_msg'] = '';
                        $resp['success'] = 'true';
                    } else {
                        $resp = ['error_msg' => 'File delete error'];
                        $resp['success'] = 'false';
                    }
                    $model->updateChatBotOptions($assistant_hash, $resp, $bot_options);
                }
            }


            wp_redirect(esc_url($redirect_url));
            exit;
        }
        
        public function updateAssistant($old_assistant_options,$new_assistant_options,$api_changed,$assistant_hash){//TO-DO implement $api_changed
            $res = ['code'=>99,'body'=>'','msg'=>''];
            if($api_changed){
                $new_assistant_options['assistant_id'] = $old_assistant_options['assistant_id'];
                $res = S2bAia_AiRequest::updateAssistantRetrievalV2($new_assistant_options);
                
            }
            if($res['code'] === 200){
            $response = ['code'=>200,
                'body'=>$res['body'],'error_msg' => '','success'=>'true',
                'model' =>$new_assistant_options['model']
            ,'created_at' =>$old_assistant_options['created_at'],
                'instruction' =>$new_assistant_options['instructions'],
                'name' =>$old_assistant_options['name'],'file_id' =>$old_assistant_options['file_id']];
            $response['assistant_timeout'] = (int)$new_assistant_options['assistant_timeout'];
            $this->model->updateChatBotOptions($assistant_hash ,$response,$old_assistant_options);
            return $res;
            }elseif($res['code'] === 99){//if API not changed
                $response = ['error_msg' => '','success'=>'true'];
                $response['assistant_timeout'] = (int)$new_assistant_options['assistant_timeout'];
                $this->model->updateChatBotOptions($assistant_hash ,$response,$old_assistant_options);
            }
            else{
                $body = json_decode($res['body']);
                $error_msg = 'Some error happened';
                if(is_object($body) && isset($body->message) && isset($body->code)){
                    $error_msg = $body->code.':'.$body->message;
                }elseif(is_array($body) && isset($body['code']) && isset($body['message'])){
                    $error_msg = $body['code'].':'.$body['message'];
                }elseif(is_object($body) && isset($body->error) && is_object($body->error) && isset($body->error->message)){
                    $error_msg = $body->error->message;
                }elseif(isset($res['error_msg'])){
                    $error_msg = $res['error_msg'];
                }
                $response = ['error_msg' => sanitize_text_field($error_msg),'success'=>'false'];
                $response['assistant_timeout'] = (int)$new_assistant_options['assistant_timeout'];
                $this->model->updateChatBotOptions($assistant_hash ,$response,$old_assistant_options);
            }
            return $res;
        }
        
        function processAssistantUpload(){
            
            $redirect_url = admin_url('admin.php?page=s2baia_chatbot');
            $r = ['code'=>404,'error_msg'=>'','id'=>'','filename' =>'','msg'];

            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                wp_redirect(esc_url($redirect_url));
                exit;
            }

            
            $nonce = S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                $r['code'] = 403;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            
            $model = $this->model;
            $assistant_hash = isset($_POST) && isset($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash'])?sanitize_text_field($_POST[S2BAIA_PREFIX_SHORT.'chatbot_hash']):'assistant';
            $assistant_info = $model->getChatBotSettings($assistant_hash);
            if(!is_object($assistant_info)){
                $r['code'] = 405;
                $r['error_msg'] = 'Access denied';
                $resp = serialize($r);
                //update_option(S2BAIA_PREFIX_LOW . 'assistant_file', $resp);
                $this->updateAssistantInfoDb($resp);
                wp_redirect(esc_url($redirect_url));
                exit;
            }
            $bot_options = $assistant_info->bot_options;//it is array
            $old_file_id = isset($bot_options['assistant_file_id']) && strlen($bot_options['assistant_file_id']) > 0?$bot_options['assistant_file_id']:'';
            if(strlen($old_file_id) > 0){
                //delete previous
            }
            
            $upload_dir = wp_upload_dir();
            if(is_array($upload_dir) && isset($upload_dir['path'])){
                $filepath = S2bAia_Utils::storeFile($upload_dir['path']);
                if($filepath === '' || $filepath == 'wrong_file_format.s2baia'){
                    $r['result'] = 403;
                    $r['msg'] = 'Access denied';
                    $r['filename'] = $filepath;
                    $resp = serialize($r);
                    $this->updateAssistantInfoDb($resp);
                    wp_redirect(esc_url($redirect_url));
                    exit;
                }
                if (!class_exists('S2bAia_AiRequest')) {
                    require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
                }
                
                $new_file_id = '';
                $response = S2bAia_AiRequest::uploadFile($filepath);
                if(is_array($response) && isset($response['id']) && strlen($response['id']) > 0){
                    $resp = base64_encode(serialize($response));
                    $assistant_info->bot_options['assistant_file'] =  $resp;
                    $new_file_id = $response['id'];
                    $assistant_info->bot_options['assistant_file_id'] = $new_file_id;
                    $assistant_info->bot_options['assistant_file_path'] = $response['filename'];
                    
                }
                $model->updateChatBotOptions($assistant_hash ,$assistant_info->bot_options, []);

                
            }
            

            wp_redirect(esc_url($redirect_url));
            exit;
        }
        
        public function updateAssistantInfoDb($assistant){
            
        }
        
        function toggleSelectionLog() {

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = 's2b_gpt_toggleselectionnonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!isset($_POST['id']) || $_POST['id'] == 0) {
                $r['result'] = 4;
                $r['msg'] = __('Log record is not specified','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            
            $id_log = (int) $_POST['id'];
            if (!class_exists('S2bAia_ChatBotLogModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotLogModel.php';
                }
            $log_model = new S2bAia_ChatBotLogModel();
            $log_record = $log_model->getLogRecord($id_log);
            if (!$log_record) {//verify id instruction
                $r['result'] = 4;
                $r['msg'] = __('Wrong log record','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            $selected = $log_record->selected;
            if ($selected == 1) {
                $toggle_val = 0;
            } else {
                $toggle_val = 1;
            }
            
            $upd_res = $log_model->toggleLogRecord($id_log, $toggle_val);
            if ($upd_res !== false) {
                $r['result'] = 200;
                $r['msg'] = 'OK';
                $r['new_selection'] = ['id' => $id_log, 'selected' => $toggle_val];
            }

            wp_send_json($r);
            exit;
        }

        function turnOnnOffLogs(){
            $r = ['result' => 0, 'msg' => __('Unknow problem', 's2b-ai-aiassistant')];
            $nonce = 's2b_changemode_lognonce';
            $r = $this->verifyPostRequest($r, $nonce, 's2b_changemode_lognonce');
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            $turnon = isset($_POST['allow']) && $_POST['allow'] == 'true' ? 1 : 0;
            update_option('s2baia_log_conversation', $turnon);
            $r['result'] = 1;
            $r['cd'] = (int)get_option('s2baia_log_conversation', 0);$_POST['allow'];
            $r['msg'] =  __('Success', 's2b-ai-aiassistant');
            wp_send_json($r);
            exit;
        }
        
        function storeLogText(){
            
            $r = ['result' => 0, 'msg' => __('Unknow problem', 's2b-ai-aiassistant')];
            $nonce = 's2b_changemode_lognonce';
            $r = $this->verifyPostRequest($r, $nonce, 's2b_changemode_lognonce');
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            $alert_text = isset($_POST['s2b_text']) ? sanitize_text_field($_POST['s2b_text']) :'';
            update_option('s2baia_chatbot_log_alert', $alert_text);
            $r['result'] = 1;
            $r['msg'] =  __('Success', 's2b-ai-aiassistant');
            wp_send_json($r);
            exit;
            
        }
        
        function deleteLog(){
            
            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = 's2b_bot_dellognonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            if (!isset($_POST['id']) || $_POST['id'] == 0) {
                $r['result'] = 4;
                $r['msg'] = __('Log record not specified','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $id_log = (int) $_POST['id'];
            if (!class_exists('S2bAia_ChatBotLogModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotLogModel.php';
                }
            $log_model = new S2bAia_ChatBotLogModel();
            $log_record = $log_model->getLogRecord($id_log);
            if (!$log_record) {//verify id instruction
                $r['result'] = 4;
                $r['msg'] = __('Wrong instruction','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            //check if user is allowed to delete instruction
            if (!S2bAia_Utils::checkDeleteInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            
            
            $del_res = $log_model->deleteLogRecord($id_log);

            if ($del_res > 0) {
                $r['result'] = 200;
                $r['msg'] = 'OK';
                $r['del_log'] = $id_log;
            }

            wp_send_json($r);
            exit;
        }
        
        function searchBots(){
            
            $r = ['result' => 0, 'msg' => __('Unknow problem', 's2b-ai-aiassistant')];
            $nonce = 's2b_gpt_loadnonce';
            $r = $this->verifyPostRequest($r, $nonce, 's2b_chatbot_loadnonce');
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            $rows_per_page = (int)$_POST['rows_per_page'];
            $search = sanitize_text_field($_POST['search']);
            $page = isset($_POST['page']) && ((int) $_POST['page']) > 0 ? (int) $_POST['page'] : 1;
            $provider = isset($_POST['provider']) && ((int) $_POST['provider']) > 0 ? (int) $_POST['provider'] : 1;
            $chat_bots =  $this->model->searchBotRecords($provider,  $search,  $page, $rows_per_page);
            //'cnt' => $cnt, 'rows' => $rows
            if(is_array($chat_bots) && isset($chat_bots['rows']) && isset($chat_bots['cnt'])){
                $r['chat_bots'] = $chat_bots['rows'];
                $r['cnt'] = (int)$chat_bots['cnt'];
                $r['result'] = 200;
                $r['msg'] = __('OK','s2b-ai-aiassistant');
                $r['total'] = $r['cnt'];
                $r['page'] = $page;
                $r['rows_per_page'] = $rows_per_page;
                
            }else{
                $r['result'] = 500;
                $r['msg'] = __('Error','s2b-ai-aiassistant');
            }
            
            wp_send_json($r);
            exit;
        }
        
        function searchLogs() {

            $r = ['result' => 0, 'msg' => __('Unknow problem', 's2b-ai-aiassistant')];
            $nonce = 's2b_gpt_loadnonce';
            $r = $this->verifyPostRequest($r, $nonce, 's2b_gpt_loadnonce');
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!class_exists('S2bAia_ChatBotLogModel')) {
                require_once S2BAIA_PATH . '/lib/models/ChatBotLogModel.php';
            }
            $log_model = new S2bAia_ChatBotLogModel();
            $records_per_page = (int) $_POST['logs_per_page'];
            $search = isset($_POST['search'])? sanitize_text_field($_POST['search']):'';
            $page = isset($_POST['page']) && ((int) $_POST['page']) > 0 ? (int) $_POST['page'] : 1;
            $show_selected_only = isset($_POST['show_selected_only']) && (int) $_POST['show_selected_only'] > 1 ? true : false;

            $arr_logrecords = $log_model->searchLogRecords(0, $search, $page, $records_per_page, $show_selected_only);
            $log_records = $arr_logrecords['rows'];
            $total_rows = $arr_logrecords['cnt'];
            

            $js_logmessages = [];
            $js_loginfos = [];
            $result_records = [];
            foreach ($log_records as $row) {
                $created_by = get_userdata($row->id_user);
                if (is_object($created_by) && isset($created_by->ID)) {
                    $visitor = esc_html($created_by->user_login);
                } else {
                    $visitor = esc_html__('Guest', 's2b-ai-aiassistant');
                }
                $row->visitor_info = esc_html($visitor) . '</br>' . esc_html($row->ipaddress);

                $messages = json_decode($row->messages, true);
                $preview = __('Click to see details', 's2b-ai-aiassistant');
                $js_logmessages[(int) $row->id] = $log_model->parseMessages($row->typeof_message, $row->messages);
                $js_loginfos[(int) $row->id] = $log_model->parseParameters($row);
                if (is_array($messages) && count($messages) > 0) {
                    $firo = isset($messages[0]) ? $messages[0] : [];
                    if (is_array($firo) && isset($firo['content'])) {
                        $preview = substr($firo['content'], 0, 100);
                    }
                }
                $row->preview = esc_html($preview);
                $bot_h = '';
                if (strlen($row->hash_code) > 0) {
                    $bot_h = '<b>bot:</b>' . $row->hash_code;
                }
                $ch_id = '';
                if (strlen($row->chat_id) > 0) {
                    $ch_id = '<b>chat:</b>' . $row->chat_id;
                }
                $row->chat_info = wp_kses($bot_h, ['b' => []]) . '<br> ' . wp_kses($ch_id, ['b' => []]);
                $row->created = esc_html($row->created);
                if ($row->selected) {
                    $row->dashiconsclass = 'dashicons-remove';
                } else {
                    $row->dashiconsclass = 'dashicons-insert';
                }
                $result_records[] = $row;
            }

            
            $res = ['js_logmessages' => $js_logmessages, 
                'log_records' => $result_records,
                'js_loginfos' => $js_loginfos,
                'result' => 200, 'total' => $total_rows, 'page' => $page,
                'logs_per_page' => $records_per_page];
            wp_send_json($res);
            exit;
        }
    }

}