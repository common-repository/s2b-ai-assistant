<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_ChatBotController')) {

    class S2bAia_ChatBotController extends S2bAia_BaseController {

        public $global_bot_enabled = false;

        public $namespace = 's2baia/v1';
        public $bot_url = '/chat/submit';
        public $default_bot_id = 'default';
        public $chat_session_expired = 7200;
        public $log_conversation = false;
        public $log_model = false;
        public $id_bot = 0;
        public $bot = false;
        public $bot_model = 'n/a';
        private $nonce = null;
        

        public function __construct() {
            if (!class_exists('S2bAia_ChatBotUtils')) {
                require_once S2BAIA_PATH . '/lib/helpers/ChatBotUtils.php';
            }
            $this->load_model('ChatBotModel');
            $this->log_conversation = get_option( 's2baia_log_conversation', 0 ) > 0;
            
                if (!class_exists('S2bAia_ChatBotLogModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotLogModel.php';
                }
                $this->log_model = new S2bAia_ChatBotLogModel();
            
            $this->global_bot_enabled = get_option( 's2baia_global_bot_enabled' );
            add_shortcode( 's2baia_chatbot', array( $this, 'chatShortcode' ) );
            add_action( 'rest_api_init', array( $this, 'restApiInit' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'registerScripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'registerStyles' ) );
            
            if (  $this->global_bot_enabled ) {
			$this->enqueueScripts();
			add_action( 'wp_footer', array( $this, 'injectChatBot' ) );
		}
                
		
        }
        


        public function registerScripts(){

            wp_enqueue_script( 's2baia', S2BAIA_URL . '/views/frontend/resources/js/chatbot.js',  array( 'jquery' ), S2BAIA_VERSION, false );
        }
        
        
        public function registerStyles(){
            wp_enqueue_style(
                    's2baia',
                    S2BAIA_URL . '/views/frontend/resources/css/chatbot.css',
                    array(),
                    S2BAIA_VERSION
            );
        }

        public function injectChatBot(){
            
        }
        public function enqueueScripts(){
            
        }
        
        function helperGetUserData() {
		$user = wp_get_current_user();
		if ( empty( $user ) || empty( $user->ID ) ) {
			return null;
		}
		$placeholders = array(
			'FIRST_NAME' => get_user_meta( $user->ID, 'first_name', true ),
			'LAST_NAME' => get_user_meta( $user->ID, 'last_name', true ),
			'USER_LOGIN' => isset( $user ) && isset($user->data) && isset( $user->data->user_login ) ? 
				$user->data->user_login : null,
			'DISPLAY_NAME' => isset( $user ) && isset( $user->data ) && isset( $user->data->display_name ) ?
				$user->data->display_name : null,
			'AVATAR_URL' => get_avatar_url( get_current_user_id() ),
		);
		return $placeholders;
	}
        
        function helperGetSessionId() {
		if ( isset( $_COOKIE['s2baia_session_id'] ) ) {
			return $_COOKIE['s2baia_session_id'];
		}
		return "N/A";
	}
        
        public function getFrontParams( $bot_attributes ) {
		$front_params = [
			'bot_id' => $bot_attributes['id'],
			'custom_id' => $bot_attributes['custom'],
			'user_data' => $this->helperGetUserData(),
			'session_id' => $this->helperGetSessionId(),
			'rest_nonce' => $this->getNonce(),
			'context_id' => get_the_ID(),
			'plugin_url' => S2BAIA_URL,
			'rest_url' => untrailingslashit( get_rest_url().$this->namespace.$this->bot_url ),
		];
                if(is_object($this->bot) && isset($this->bot->id) && $this->bot->id > 0){
                    $chat_bot_options = $this->bot;
                    $chat_bot_options->bot_options = $this->prefixizeOptions($chat_bot_options->bot_options);
                }else{
                    $chat_bot_options = $this->model->getChatBotSettings($bot_attributes['id']);
                }
                $chat_bot_styles = $this->model->getChatBotSettings('default');//because we have only one page for styles [s2bprogress]
                $default_bot_styles = S2bAia_ChatBotUtils::getChatBotDefaultStyles();
                if(!is_object($chat_bot_styles) || !isset($chat_bot_styles->id) || $chat_bot_styles->id <= 0 ){
                    $chat_bot_styles = $default_bot_styles;
                }else{
                    $chat_bot_styles = $this->mergeOptions($this->prefixizeOptions($chat_bot_styles->bot_options), $default_bot_styles);
                }
                $res = $this->mergeOptions($chat_bot_options->bot_options,$chat_bot_styles); //array_merge($front_params,$chat_bot_options->bot_options,$chat_bot_styles);
		$res = array_merge($res,$front_params);
                return $res;
	}
        
        public function prefixizeOptions($chat_bot_options=[]){
            $new = [];
            foreach($chat_bot_options as $idx=>$val){
                $new[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.$idx] = $val;
            }
            return $new;
        }
        
        public function mergeOptions($opts1,$opts2){
            $donottouched = [];
            foreach($opts2 as $key=>$value){
                if(!array_key_exists($key, $opts1)){
                    $donottouched[$key] = $value;
                }
            }
            $new = array_merge($donottouched, $opts1);
            return $new;
        }
        
        public function getBotInfo( $bot_id ){// id of bot which starts from default is for default modes 
            //++++++++++ need to get info from database
            //$default_provider = get_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider','default');
            $bot = $this->getBotByHash($bot_id);
            $provider = $this->getBotProvider($bot);
            if($provider !== 'default'){//deprecated
                $bot_mode = sanitize_text_field($provider);
            }else{
                $bot_mode = 'classic';
            }
            //TO-DO 'view' => 'default',  'custom' => 0 custom related with view
            //TO-DO 'botmode' => $bot_mode, related with provider
            return apply_filters('s2baia_get_bot_info',['botmode' => $bot_mode, 
                    'provider' => $provider,
                    'view' => 'default', 
                    'id' => $bot_id, 
                    'custom' => 0,'text_input_max_length'=>1350,'stream' => false],$bot);
        }
        
        public function chatShortcode($atts){
            $atts = empty( $atts ) ? [] : $atts;
            $atts = apply_filters( 's2baia_chatbot_params', $atts );
            $attr_bot_id = isset($atts['bot_id'])?$atts['bot_id']:'';
            $configured_bot_id = get_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider', 'default');//[s2bprogress]
            if($attr_bot_id == ''){
                $bot_id = $configured_bot_id;
                
            }else{
                $bot_id = $attr_bot_id;
            }
            $resolved_bot = $this->getBotInfo( $bot_id );
            
            if ( isset( $resolved_bot['error'] ) ) {
              return $resolved_bot['error'];
            }
            $data_parameters = $this->getFrontParams($resolved_bot);
            $access_for_guest = isset($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'access_for_guests'])?(int)$data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'access_for_guests']:1;
            $user_id = get_current_user_id();
            $content = '';
            if($access_for_guest < 1 && $user_id < 1){
                return $content;
            }
            $data_par = htmlspecialchars( wp_json_encode( $data_parameters ), ENT_QUOTES, 'UTF-8' );
            $chat_id = $this->getChatId($bot_id);
            $data_parameters['chat_id'] = sanitize_text_field($chat_id);    
            $data_parameters['bot_id'] = !isset($data_parameters['bot_id'])?$bot_id:$data_parameters['bot_id'];
            $data_parameters['view_mode'] = $this->getViewMode($atts);
            $data_parameters['hide_close'] = isset($atts['hideclose']) && $atts['hideclose'] == 1?1:0;
            $view = $this->getView($resolved_bot, $atts);
            switch($resolved_bot['provider']){
                case 'chatgpt':
                        $data_parameters['bot_view'] = 1;
                        if($view !== 'default'){
                            $content = $this->showView($view, $data_par, $data_parameters);
                        }else{
                            $content = $this->showClassicChatGPTDefaultHistory($data_par,$data_parameters);
                        }
                    break;
                case 'assistant':
                            $data_parameters['bot_view'] = 2;
                            if (!class_exists('S2bAia_ChatBotConversationModel')) {
                                $classmodel_path = S2BAIA_PATH . "/lib/models/ChatBotConversationModel.php";
                                include_once $classmodel_path;
                            }
                            if($view !== 'default'){
                                $content = $this->showView($view, $data_par, $data_parameters);
                            }else{
                                $content = $this->showClassicChatGPTDefaultHistory($data_par,$data_parameters);
                            }
                            break;    
                default:
                    
                            $data_parameters['bot_view'] = 1;
                            $content = $this->showClassicChatGPTDefaultHistory($data_par,$data_parameters);

            }
		return $content;

        }
        
        public function getViewMode($atts){

           if(is_array($atts) && isset($atts['view_mode'])){
               if($atts['view_mode'] == 'fullscreen1'){
                   return 1;
               }
           }
           return 0;
        }
        
        public function getView($resolved_bot,$atts){

           if(is_array($atts) && isset($atts['view'])){
               $file_suffix = sanitize_text_field($atts['view']);
               $view_file =  S2BAIA_PATH . "/views/frontend/chatbot/ChatBot".ucfirst($file_suffix)."View.php";
               if(file_exists($view_file)){
                   return ucfirst($file_suffix);
               }
           }
           return 'default';
        }
        
        public function getChatId($chatbot_hash = '') {
            if (!class_exists('S2bAia_ChatBotConversationModel')) {
                $classmodel_path = S2BAIA_PATH . "/lib/models/ChatBotConversationModel.php";
                include_once $classmodel_path;
            }
            $chb_model = new S2bAia_ChatBotConversationModel();
            $create_new_chat = false;
            $exptime = 0;//time() + $this->chat_session_expired;
            $cdom = COOKIE_DOMAIN?COOKIE_DOMAIN:'';
            $cpath = COOKIEPATH?COOKIEPATH:'/'; 
            if (isset($_COOKIE) && is_array($_COOKIE) && isset($_COOKIE['s2baia_bothash']) && strlen($_COOKIE['s2baia_bothash']) > 0) {
                $cookie_b_h = sanitize_text_field($_COOKIE['s2baia_bothash']);
                if($chatbot_hash != $cookie_b_h){
                    $create_new_chat = true;
                    setcookie('s2baia_bothash', $chatbot_hash, $exptime, $cpath, $cdom);
                }
            }else{
                $create_new_chat = true;
                setcookie('s2baia_bothash', $chatbot_hash, $exptime, $cpath, $cdom);
            }
            $chat_id = '';
            if (isset($_COOKIE) && is_array($_COOKIE) && isset($_COOKIE['s2baia_chatid']) && strlen($_COOKIE['s2baia_chatid']) == 20 && !$create_new_chat) {
                $chat_id = sanitize_text_field($_COOKIE['s2baia_chatid']);
            } else {
                $chat_id = $chb_model->createChat('', ['chat_status' => 'none'], 'user', $this->chat_session_expired);
                $this->createLog('',['chat_status' => 'none'], 'user' ,$chatbot_hash ,$chat_id);
                       
                setcookie('s2baia_chatid', $chat_id, $exptime, $cpath,$cdom);
            }
            return $chat_id;
        }
                
        public function showClassicChatGPTDefaultHistory($data_par,$data_parameters){
            if (!class_exists('S2bAia_ChatBotClassicView')) {
                                $classview_path = S2BAIA_PATH . "/views/frontend/chatbot/ChatBotClassicHistoryView.php";
                                include_once $classview_path;
                            }
                            $this->view = new S2bAia_ChatBotClassicHistoryView();
                return    $this->view->render($data_par,$data_parameters);
        }
        
        public function showView($view,$data_par,$data_parameters){
            $view_class = 'S2bAia_ChatBot'.$view.'View';
            if (!class_exists($view_class)) {
                                $classview_path = S2BAIA_PATH . "/views/frontend/chatbot/ChatBot".$view."View.php";
                                include_once $classview_path;
                            }
                $this->view = new $view_class();
                return    $this->view->render($data_par,$data_parameters);
        }
        
        function getNonce() {

		if ( isset( $this->nonce ) ) {
			return $this->nonce;
		}
		$this->nonce = wp_create_nonce( 'wp_rest' );
		return $this->nonce;
	}
       
        

        
        public function restApiInit(){
            
            register_rest_route( $this->namespace, $this->bot_url, array(
			'methods' => 'POST',
			'callback' => [ $this, 'restChat' ],
			'permission_callback' => array( $this, 'checkRestNonce' )
		) );
            
        }
        
        public function restChat($request){
            
            $params = $request->get_json_params();
            $filtered_params = $this->filterParameters($params);    
            $new_message = $filtered_params['message'];
            if ( !$this->basicsSecurityCheck( $filtered_params['bot_id'],  $new_message )) {
			return new WP_REST_Response( [ 
				'success' => false, 
				'message' => apply_filters( 's2baia_exception', 'Sorry, your query has been rejected.' )
			], 403 );
            }

            
	try {
			
                $data = $this->chatSubmitRequest( $new_message,  $filtered_params);
		return new WP_REST_Response( [
				'success' => true,
				'reply' => $data['reply'],
				'images' => $data['images'],
				'usage' => $data['usage']
			], 200 );
		}
	catch ( Exception $e ) {
			$message = apply_filters( 's2baia_exception', $e->getMessage() );
			return new WP_REST_Response( [ 
				'success' => false, 
				'message' => $message
			], 500 );
		}
        }
        
        public function filterParameters($params) {
            $filtered_pars = [];
            
            if (isset($params['messages']) && is_array($params['messages'])) {
                    $filtered_pars['messages'] = $this->filterMessages($params['messages']);

            }
            $filtered_pars['message'] = isset($params['message'])?sanitize_text_field(trim($params['message'])):'';
            $filtered_pars['bot_id'] = isset($params['bot_id'])?sanitize_text_field($params['bot_id']):$this->default_bot_id;
            
            return $filtered_pars;
        }

        public function filterMessages($messages) {

            foreach ($messages as $row) {
                if ($row['role'] == 'user') {
                    $filtered_messages[] = ['role' => 'user', 'content' => sanitize_text_field($row['content'])];
                } elseif ($row['role'] == 'assistant') {
                    $filtered_messages[] = ['role' => 'assistant', 'content' => sanitize_text_field($row['content'])];
                }
            }

            return $filtered_messages;
        }

        public function basicsSecurityCheck( $botId,  $new_message ) {
                $cur_time = time();
		if ( empty( $new_message ) ) {
                        
			error_log("S2BAi Assistant: Message was empty. at timestamp:".$cur_time);
                        error_log($botId);
			return false;
		}
		
		$length = strlen( $new_message );
		if ( $length < 1 || $length > ( 4096 * 16 ) ) {
			error_log("S2BAi Assistant: Message was too short or too longat timestamp:".$cur_time);
			error_log($botId);
		}
		return true;
	}
    
        

        
        
    public function chatSubmitRequest( $new_message,  $params = [] ) {
		try {

                        $bot_id = $params['bot_id'];
                        $resolved_bot = $this->getBotInfo( $bot_id );
                        $provider = $resolved_bot['provider'];
                        if ( isset( $resolved_bot['error'] ) ) {
                          error_log( $resolved_bot['error']);
                          throw new Exception(  $resolved_bot['error'] );
                        }
                        $chatbotinfo = $this->getFrontParams($resolved_bot);
                        $access_for_guest = isset($chatbotinfo[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'access_for_guests'])?(int)$chatbotinfo[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'access_for_guests']:1;
                        $user_id = get_current_user_id();

                        if($access_for_guest < 1 && $user_id < 1){
                            error_log("S2baia: Access denied.");
                            return [
				'reply' => 'Access denied.',
				'images' =>  null,
				'usage' => '',
                                'code' => 403
                            ];

                        }
                        
			if ( !$chatbotinfo ) {
				error_log("S2baia: No chatbot was found for this query.");
				return [
                                    'reply' => 'S2baia: No chatbot was found for this query.',
                                    'images' =>  null,
                                    'usage' => '',
                                    'code' => 404
                                ];
			}

			
			$newParams = [];
                        $messages = [];
			foreach ( $chatbotinfo as $key => $value ) {
					$newParams[$key] = $value;
			}
			foreach ( $params as $key => $value ) {
                                if(isset($params['messages']) && is_array($params['messages'])){
                                    $messages = $params['messages'];
                                    continue;
                                }
					$newParams[$key] = $value;
			}

                        switch($provider){
                            case 'chatgpt':
                                $reply =  $this->classicChatGpt2Request($messages, $newParams,$bot_id);
                                break;
                            case 'assistant':
                                $reply =  $this->assistantChatGpt2RequestAsync($messages, $newParams,$bot_id); 
                                break;
                            default:
                                //$reply =  $this->classicChatGpt2Request($messages, $newParams,$bot_id);
                                $reply = apply_filters( 's2baia_chat_submit_request', ['msg'=>'','code'=>200], $messages, $newParams, $bot_id, $provider );
                        }
			$rawText = $reply['msg'];

			$restRes = [
				'reply' => $rawText,
				'images' =>  null,
				'usage' => '',
                                'code' => $reply['code']
			];

			
			return $restRes;

		}
		catch ( Exception $e ) {
			$message = $e->getMessage() ;
			throw $e;
			
		}
	}
        
        
        

        public function classicChatGpt2Request($inputmessages, $params, $bot_id) {

            if (!class_exists('S2bAia_AiRequest')) {
                require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
            }
            $data = [];
            $model = '';
            if(isset($params['model']) && strlen($params['model']) > 0){
                $model = sanitize_text_field($params['model']) ;
            }elseif(isset($params['s2baia_chatbot_opt_chat_model']) && strlen($params['s2baia_chatbot_opt_chat_model']) > 0){
                $model = sanitize_text_field($params['s2baia_chatbot_opt_chat_model']);
            }else{
                $model = 'gpt-4o';
            }
            $data['model'] = $model;
            $system = '';
            if(isset($params['context']) && strlen($params['context']) > 0){
                $system = sanitize_text_field($params['context']) ;
            }elseif(isset($params['s2baia_chatbot_opt_context']) && strlen($params['s2baia_chatbot_opt_context']) > 0){
                $system = sanitize_text_field($params['s2baia_chatbot_opt_context']);
            }else{
                $system = '';
            }
            $data['system'] = $system;
            $max_tokens = 1024;
            if(isset($params['max_tokens']) && is_numeric($params['max_tokens'])){
                $max_tokens = (int)$params['max_tokens'] ;
            }elseif(isset($params['s2baia_chatbot_opt_max_tokens']) && is_numeric($params['s2baia_chatbot_opt_max_tokens'])){
                $max_tokens = (int) $params['s2baia_chatbot_opt_max_tokens'];
            }
            $data['max_tokens'] = $max_tokens;
/*
chat_temperature
chat_top_p
presence_penalty
frequency_penalty
 *  */
            $temperature = 0.7;
            if(isset($params['chat_temperature']) && is_numeric($params['chat_temperature'])){
                $temperature = floatval($params['chat_temperature']) ;
            }elseif(isset($params['s2baia_chatbot_opt_chat_temperature']) && is_numeric($params['s2baia_chatbot_opt_chat_temperature'])){
                $temperature = floatval($params['s2baia_chatbot_opt_chat_temperature']);
            }
            $data['temperature'] = $temperature;
            
            $top_p = 1;
            if(isset($params['chat_top_p']) && is_numeric($params['chat_top_p'])){
                $top_p = floatval($params['chat_top_p']) ;
            }elseif(isset($params['s2baia_chatbot_opt_chat_top_p']) && is_numeric($params['s2baia_chatbot_opt_chat_top_p'])){
                $top_p = floatval($params['s2baia_chatbot_opt_chat_top_p']);
            }
            $data['top_p'] = $top_p;
            
            $presence_penalty = 0;
            if(isset($params['presence_penalty']) && is_numeric($params['presence_penalty'])){
                $presence_penalty = floatval($params['presence_penalty']) ;
            }elseif(isset($params['s2baia_chatbot_opt_presence_penalty']) && is_numeric($params['s2baia_chatbot_opt_presence_penalty'])){
                $presence_penalty = floatval($params['s2baia_chatbot_opt_presence_penalty']);
            }
            $data['presence_penalty'] = $presence_penalty;
            
            $frequency_penalty = 0;
            if(isset($params['frequency_penalty']) && is_numeric($params['frequency_penalty'])){
                $frequency_penalty = floatval($params['frequency_penalty']) ;
            }elseif(isset($params['s2baia_chatbot_opt_frequency_penalty']) && is_numeric($params['s2baia_chatbot_opt_frequency_penalty'])){
                $frequency_penalty = floatval($params['s2baia_chatbot_opt_frequency_penalty']);
            }
            $data['frequency_penalty'] = $frequency_penalty;
            
            $messages = [];

            foreach ($inputmessages as $row) {
                if ($row['role'] == 'user') {
                    $messages[] = ['role' => 'user', 'content' => sanitize_text_field($row['content'])];
                } elseif ($row['role'] == 'assistant') {
                    $messages[] = ['role' => 'assistant', 'content' => sanitize_text_field($row['content'])];
                }
            }

            $data['messages'] = $messages;
            $res = S2bAia_AiRequest::sendChatGptCompletion($data);
            if ($res[0] == 1) {
                $response = json_decode($res[1]);
                if (S2bAia_AiRequest::testChatGptResponse($response)) {
                    $msg = S2bAia_AiRequest::getChatGptResponseEditMessage($response);
                    $r['result'] = 200;
                    $r['msg'] = wp_kses($msg, S2bAia_Utils::getInstructionAllowedTags());
                    //$data['messages'][] = ['role'=>'assistant','content'=>$r['msg']];
                    $chat_id = $this->getChatId($bot_id);
                    $options = ['typeof_message' => 2];
                    $dt = $this->prepareLogData($r['msg'], $options, 'assistant', $bot_id, $chat_id);
                    $dt['messages'] = $data['messages'];
                    $dt['messages'][] = ['role' => 'assistant', 'content' => $r['msg']];
                    $this->log_model->updateLogRecordByChatId($chat_id, $dt,   0);
                    $r['code'] = 200;
                    return $r;
                }
            } else {
                if (is_array($res) && count($res) > 0 && is_string($res[1])) {
                    $response = $res[1];
                } else {
                    $response = is_array($res) && count($res) > 0 && is_array($res[1]) && count($res[1]) > 0 ? esc_html__('Error', 's2b-ai-aiassistant') . ' ' . $res[1][0] . ' ' . $res[1][1] : esc_html__('Unknown error', 's2b-ai-aiassistant');
                }
                $r['result'] = 404;
                $r['msg'] = wp_kses($response, S2bAia_Utils::getInstructionAllowedTags());
            }
            if (isset($response->error) && isset($response->error->message)) {
                $r['result'] = 404;
                $r['msg'] = wp_kses($response->error->message, S2bAia_Utils::getInstructionAllowedTags());
            }

            return $r;
        }

        public function checkRestNonce( $request ) {
            $nonce = $request->get_header( 'X-WP-Nonce' );
            $rest_nonce = wp_verify_nonce( $nonce, 'wp_rest' );
            return apply_filters( 's2baia_rest_authorized', $rest_nonce, $request );
          }
        
	public function assistantChatGpt2RequestAsync($messages, $newParams,$bot_id) {
            
            if (!class_exists('S2bAia_AiRequest')) {
                require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
            }
            if (!class_exists('S2bAia_ChatBotConversationModel')) {
                $classmodel_path = S2BAIA_PATH . "/lib/models/ChatBotConversationModel.php";
                include_once $classmodel_path;
            }
            $chb_model = new S2bAia_ChatBotConversationModel();
            $final_response = ['msg' => '', 'code' => 404];
            $last_msg = '';
            $last_role = '';
            foreach ($messages as $row) {
                if ($row['role'] == 'user') {
                    $last_msg = sanitize_text_field($row['content']);
                    $last_role = 'user';
                    $messages[] = ['role' => 'user', 'content' => $last_msg];
                } elseif ($row['role'] == 'assistant') {
                    $last_msg = sanitize_text_field($row['content']);
                    $last_role = 'assistant';
                    $messages[] = ['role' => 'assistant', 'content' => $last_msg];
                }
            }


            $chat_id = '';
            if (!isset($newParams['chat_id'])) {
                $chat_id = $this->getChatId($bot_id);
            } else {
                $chat_id = $newParams['chat_id'];
            }
            

            
            $assistant = $newParams;

            
            $assistant_id = isset($assistant['assistant_id']) ? sanitize_text_field($assistant['assistant_id']) : '';
            if($assistant_id == ''){
                $assistant_id = isset($assistant[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'assistant_id']) ? sanitize_text_field($assistant[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'assistant_id']) : '';
            }
            $timeout = isset($assistant['assistant_timeout']) ? (int)$assistant['assistant_timeout'] : 0;
            if($timeout == 0){
                $timeout = isset($assistant[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'assistant_timeout']) ? (int)$assistant[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'assistant_timeout'] : 1;
            }
            $count_loop = $timeout;
            $chat_info = $chb_model->getChat($chat_id);
            if (!isset($chat_info['chat_status'])) {
                $chat_info['chat_status'] = 'none';
            }
            if (!isset($chat_info['thread_id'])) {
                $thread = S2bAia_AiRequest::createThread(); //?add user message
                if (is_array($thread) && isset($thread['id'])) {
                    $thread_id = $thread['id'];
                    $chat_info['thread_id'] = $thread_id;
                    $chb_model->updateChat($chat_id, $last_msg, $chat_info, $last_role, $this->chat_session_expired,true);
                    $this->updateLog($last_msg, $chat_info, $last_role, $bot_id, $chat_id, 2);
                }else{
                    $final_response['msg'] = esc_html__('Network error happened. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'401';
                    $final_response['code'] = 401;
                    $chat_info['chat_status'] = 'fail';
                }
            } else {
                $thread_id = $chat_info['thread_id'];
            }

            
            $response = false;
            $answer_received = false;
            switch ($chat_info['chat_status']) {
                case 'none':
                    $response = S2bAia_AiRequest::addAssistantMessage($thread_id, $last_msg);
                    if (is_array($response) && isset($response['id'])) {
                        $message_id = $response['id'];
                        $chat_info['message_id'] = $message_id;
                        $chb_model->updateChat($chat_id, $last_msg, $chat_info, $last_role, $this->chat_session_expired,true);
                        $this->updateLog($last_msg, $chat_info, $last_role, $bot_id, $chat_id, 1);
                    }else{
                        //Process fail
                        $final_response['msg'] = esc_html__('Error happened during sending message. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'402';
                        $final_response['code'] = 402;
                    }
                    if (strlen($message_id) > 0) {
                        $response = S2bAia_AiRequest::runAssistant($thread_id, $assistant_id, '');
                        if (is_array($response) && isset($response['id']) && isset($response['status'])) {
                            $run_id = $response['id'];
                            $status = $response['status'];
                            $chat_info['run_id'] = $run_id;
                            $chat_info['run_status'] = $status;
                            $chb_model->updateChat($chat_id, $last_msg, $chat_info, $last_role, $this->chat_session_expired);
                            $this->updateLog($last_msg, $chat_info, $last_role, $bot_id, $chat_id, 2);
                            if ($status == 'queued') {
                                while ($status != 'completed' || $count_loop > 0) {
                                    $response = S2bAia_AiRequest::getRunStepsStatus($thread_id, $run_id);
                                    if (is_array($response) && isset($response['data'])) {
                                        $list = $response['data'];
                                        foreach ($list as $ls) {
                                            if ($ls['status'] == 'completed') {
                                                sleep(1);
                                                $response = S2bAia_AiRequest::listAssistantMessages($thread_id);
                                            }
                                        }
                                    } elseif ($response === true) {
                                        
                                        sleep(1);
                                        $response = S2bAia_AiRequest::listAssistantMessages($thread_id);
                                        //error_log(print_r($response,true));
                                        $parsed_response = $this->parseListResponse($response);
                                        if(strlen($parsed_response['text']) > 0 && $parsed_response['text'] !== '**empty'){
                                            $final_response['msg'] = $parsed_response['text'];
                                            $final_response['code'] = 200;
                                            $answer_received = true;
                                            $chat_info['run_status'] = 'none';
                                            $chb_model->updateChat($chat_id, $parsed_response['text'], $chat_info, 'assistant', $this->chat_session_expired);	
                                            $this->updateLog($parsed_response['text'], $chat_info, 'assistant', $bot_id, $chat_id, 1);
                                            break;
                                        }
                                        //$logvar = '**empty' ; 
                                        //error_log(print_r($logvar,true));
                                    }else{
                                        $final_response['msg'] = esc_html__('Can not establish connection to assistant. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'409';
                                        $final_response['code'] = 409;
                                    }
                                    $count_loop--;
                                    
                                }
                            }else{
                                $final_response['msg'] = esc_html__('Can not establish connection to assistant. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'406';
                                $final_response['code'] = 406;
                            }
                        }
                    }else{
                        $final_response['msg'] = esc_html__('Error happened during sending message. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'405';
                        $final_response['code'] = 405;
                    }
                    break;
                case 'queued':
                case 'in_progress':
                case 'requires_action':
                case 'cancelling':    
                    $final_response['msg'] = esc_html__('Your request in progress. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'407';
                    $final_response['code'] = 407;
                    break;

                case 'expired':
                case 'completed':
                case 'cancelled':  
                case 'failed':    
                    $final_response['msg'] = esc_html__('Your request failed. Please send your request again.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'408';
                    $final_response['code'] = 408;
                    break;
                case 'fail':
                    return $final_response;

                default:
                    $response = ['status' => 'need_wait'];
            }

            if($answer_received){
                return $final_response;
            }else{
                $final_response['msg'] = esc_html__('Could not receive answer.', 's2b-ai-genius').' '.esc_html__('Error code', 's2b-ai-genius').':'.'410';
                    $final_response['code'] = 410;
                    return $final_response;
            }
        }
        

        
        public  function parseListResponse($response = []) {
            $res = ['text' => '', 'annotations' => ''];
            if (is_array($response) && isset($response['data']) && is_array($response['data']) && count($response['data']) > 0) {
                $first = $response['data'][0];
                if (is_array($first) && count($first) > 0 && isset($first['content']) && is_array($first['content']) && count($first['content']) > 0) {
                    $arr = $first['content'][0];
                    if (is_array($arr) && isset($arr['text']) && is_array($arr['text']) && isset($arr['text']['value']) && isset($arr['text']['annotations'])) {
                        $annotations = $arr['text']['annotations'];
                        $value = $arr['text']['value'];
                        //error_log(print_r($annotations, true));
                        //error_log(print_r($value, true));
                        $res['text'] = $this->cleanAnswer($value, $annotations);
                        $res['annotations'] = $annotations;
                    }else{
                        $res['text'] = '**empty';
                        $res['annotations'] = '';
                    }
                }else{
                    $res['text'] = '**empty';
                    $res['annotations'] = '';
                }
            }
            return $res;
        }
        
        
        public function cleanAnswer($answer = '',$annotations = []){
            $cleaned_answer = $answer;
            foreach($annotations as $annot){
                if(is_array($annot) && isset($annot['type']) && isset($annot['text'])){
                    $cleaned_answer = str_replace($annot['text'], '', $cleaned_answer);
                }
            }
            return $cleaned_answer;
        }
        
        public function createLog($message = '',$options = [], $author = '',$chatbot_hash = '',$chat_id = ''){
            if(!$this->log_conversation){
                return;
            }
            $data = $this->prepareLogData($message, $options, $author, $chatbot_hash, $chat_id);
            $data['messages'] = [];
            $this->log_model->insertLogRecord($data);
        }
        
        public function updateLog($message = '',$options = [], $author = '',$chatbot_hash = '',$chat_id = '',$append_messages = 0){
            if(!$this->log_conversation){
                return;
            }
            $data = $this->prepareLogData($message, $options, $author, $chatbot_hash, $chat_id);
            $this->log_model->updateLogRecordByChatId($chat_id,$data,$append_messages);
        }
        
        public function prepareLogData($message = '',$options = [], $author = '',$chatbot_hash = '',$chat_id = ''){
            $data = [];
            $data['id_user'] = get_current_user_id();
            $data['typeof_message'] = is_array($options) && isset($options['typeof_message'])?(int)$options['typeof_message']:1;
            $data['id_assistant'] = $this->getBotIdByHash($chatbot_hash);
            $data['messages'] = ['role'=>sanitize_text_field($author),'content'=>sanitize_text_field($message)];
            $data['parameters'] = $options;
            $data['parameters']['model'] = $this->bot_model;
            $data['ipaddress'] = sanitize_text_field(S2bAia_Utils::getIpAddress());
            $data['chat_id'] = sanitize_text_field($chat_id);
            $data['comments'] = '';
            return $data;
        }
        
        public function getBotIdByHash($chatbot_hash = ''){
            if($this->id_bot == 0){
                if (!class_exists('S2bAia_ChatBotModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotModel.php';
                }
                $ch_model = new S2bAia_ChatBotModel();
                $chb = $ch_model->getChatBotSettings($chatbot_hash);
                if(is_object($chb) && isset($chb->id) && $chb->id > 0){
                    $this->id_bot = $chb->id;
                    $bot_options = $chb->bot_options;
                    $this->bot_model = isset($bot_options['model']) && strlen($bot_options['model']) > 0?$bot_options['model']:'';
                }
            }
            return $this->id_bot;
        }
        
        public function getBotByHash($chatbot_hash = ''){
            if($this->bot == false){
                if (!class_exists('S2bAia_ChatBotModel')) {
                    require_once S2BAIA_PATH . '/lib/models/ChatBotModel.php';
                }
                $ch_model = new S2bAia_ChatBotModel();
                $chb = $ch_model->getChatBotSettings($chatbot_hash);
                if(is_object($chb) && isset($chb->id) && $chb->id > 0){
                    $this->id_bot = (int)$chb->id;
                    $bot_options = $chb->bot_options;
                    $this->bot_model = isset($bot_options['model']) && strlen($bot_options['model']) > 0?$bot_options['model']:'';
                    $this->bot = $chb;
                    return $this->bot;
                }
                
            }
            return $this->bot;
        }
        
        public function getBotProvider($bot){
            if(is_object($bot) && isset($bot->type_of_chatbot) && $bot->type_of_chatbot > 0){
                $type_of_chatbot = $bot->type_of_chatbot;
                switch($type_of_chatbot){
                    case 2:
                        return 'assistant';
                    case 1:
                        return 'chatgpt';
                }
            }
            return apply_filters( 's2baia_get_bot_provider', 'chatgpt', $bot, $type_of_chatbot );
        }
          
    }

}
