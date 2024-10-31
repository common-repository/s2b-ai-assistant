<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_ChatBotEmbeddedView')) {

    class S2bAia_ChatBotEmbeddedView {
        /*
         * $data_par - string that represents array encoded to json
         * $data_parameters - 
         */
        public function render($data_par,$data_parameters){
            
                ob_start();
                //var_dump($data_parameters);
                $chatbot_picture_url = isset($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']) && strlen($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']) > 0?esc_html($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']): S2BAIA_URL."/views/resources/img/chatbot.svg";
		$s2baia_log_conversation = (int)get_option('s2baia_log_conversation',0);
                $s2baia_log_alert = sanitize_text_field(get_option('s2baia_chatbot_log_alert',''));
                $show_alert = false;
                if($s2baia_log_conversation == 1 && strlen($s2baia_log_alert) > 0){
                    $show_alert = true;
                }
                ?>
        <?php  

                    $container_style = '';
                    $container_calss = ' s2baia-bot-chatbot-main-container-maximized-view';
                    $icon_style = 'display: none;';
        
        ?>
	<div class="s2baia-bot-chatbot" style="" data-parameters='<?php echo esc_attr($data_par);?>' >
        <div class="s2baia-bot-chatbot-closed-view" style="<?php echo esc_attr($icon_style); ?>"> 
            <div class="s2baia-bot-closed-ic-container">
                <img class="s2baia-bot-chatbot-logo-img" src="<?php echo esc_url($chatbot_picture_url); ?>" alt="Chat Assistant Icon">
            </div>
        </div>
            
        <div class="s2baia-bot-chatbot-maximized-bg2" style="<?php echo esc_attr($container_style); ?>"></div>
        
        <div class="s2baia-bot-chatbot-main-container-embedd <?php  echo esc_html($container_calss); ?>" style="margin:auto;<?php echo esc_attr($container_style); ?>">
            <div class="s2baia-bot-chatbot-main-chat-modal" style="display: none;">
                
            </div>
            <div class="s2baia-bot-chatbot-main-chat-box">
                <div class="s2baia-bot-chatbot-header-row"> 

                    <div class="s2baia-bot-header-row-logo-row">
                        <div class="s2baia-bot-header-row-logo">
                            <img class="s2baia-bot-header-row-logo-image" src="<?php echo esc_url($chatbot_picture_url); ?>" alt="Chat Assistant Icon">
                        </div>
                        <p class="s2baia-bot-chatbot-header-text"><?php echo isset($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_name'])?esc_html($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_name']):esc_html__('AI Assistant','s2b-ai-aiassistant'); ?></p>
                    </div>
                    <div class="s2baia-bot-chatbot-logo">
                        <?php ?>
                        <img src="<?php echo esc_url(S2BAIA_URL); ?>/views/resources/img/maximize.svg" alt="Maximize" class="s2baia-bot-chatbot-resize-bttn" style="<?php echo esc_attr($icon_style); ?>">
                        <?php ?>

                    </div>
                </div>
                <div class="s2baia-bot-chatbot-messages-box" style=" min-height:150px;<?php echo  'height: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height'] . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height_metrics'] . ';'; ?>"> 
                    <div class="s2baia-bot-chatbot-loading-box" style=" display: none;"> 
                        <div class="s2baia-bot-chatbot-loader-ball-2">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <?php
                    if($show_alert){
                    ?>
                    <div class="s2baia-bot-chatbot-ai-message-box"><span class="s2baia-bot-chatbot-ai-response-message"><?php
                            echo esc_html($s2baia_log_alert);
                            ?>
                        </span><div class="s2baia-bot-chatbot-ai-message-buttons"></div></div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                $send_btn_key = S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'send_button_text';
                $clear_btn_key = S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'clear_button_text';
                if(isset($data_parameters[$send_btn_key])){
                    $send_button_text = $data_parameters[$send_btn_key];
                }else{
                    $send_button_text = __('Send','s2b-ai-aiassistant');
                }
                if(isset($data_parameters[$send_btn_key])){
                    $clear_button_text = $data_parameters[$clear_btn_key];
                }else{
                    $clear_button_text = __('Clear','s2b-ai-aiassistant');
                }
                
                
                
                ?>
                <div class="s2baia-bot-chatbot-input-box"> 
                    <textarea style="overflow: hidden scroll; overflow-wrap: break-word; height: 60px;" rows="1" id="s2baiabotchatbotpromptinput" class="s2baia-bot-chatbot-prompt-input s2baia-bot-chatbot-prompt-inputs-all" name="s2baia_bot_chatbot_prompt" id="s2baia-bot-chatbot-prompt" placeholder="<?php echo esc_html($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'message_placeholder']); ?>"></textarea>
                    <button class="s2baia-bot-chatbot-send-button"  onclick="s2baiaSendMessage(event);">
                        <span><?php echo esc_html($send_button_text);  ?></span>	
                    </button>
                </div>
                <?php
                $compliance_text = isset($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'compliance_text'])?$data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'compliance_text']:'';
                if(strlen($compliance_text) > 0){
                ?>
                    <div class="s2baia-bot-chatbot-compliance-text" style="padding:4px 20px">
                                                                    <span><?php 
                $allowed = array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                                'target' => array(),
                                'style' => array()   
                            )
                        );                                                    
                                                                    echo wp_kses($compliance_text,$allowed); ?></span>
                    </div>
                <?php
                }
                ?>
                <input type="hidden" id="s2baiaidbot" value="<?php echo esc_html($data_parameters['bot_id']); ?>"/>
                <input type="hidden" id="oc3daigchatid" value="<?php echo isset($data_parameters['chat_id'])?esc_html($data_parameters['chat_id']):''; ?>"/>
                <input type="hidden" id="oc3daigbotview" value="<?php echo esc_html($data_parameters['bot_view']); ?>"/>
            </div>
        </div>
    </div>
    <script>
    let s2baia_button_config_general_send = '<?php echo esc_html($send_button_text); ?>';
    let s2baia_button_config_general_clear = '<?php echo esc_html($clear_button_text); ?>';
    let s2baia_bot_view = '<?php echo (int)$data_parameters['bot_view']; ?>';
    let s2baia_chat_id = '<?php echo esc_html($data_parameters['chat_id']); ?>';
    let s2baia_alert_log_msg_exist = <?php echo $show_alert?'true':'false'; ?>;

    </script>

				<?php
                echo  '<style>'.esc_html($this->getChatBotStyles($data_parameters)).'</style>'  ;              
		$content = ob_get_clean();
                return $content;
        }
        
        function getChatBotStyles($data_parameters) {
            $icon_size = $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_icon_size'];

            $styles = '';  		
            if ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'position'] == 'center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd {
							left: 50%;
							transform: translateX(-50%);
						}
			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'position'] == 'left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd {
							left: 0;
							right: unset;
						}
			';
            } else {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd {
							right: 0;
						}
			';
            }

            if ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'bottom-center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 50%;
						}




			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'bottom-left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 20px;
							right: unset;
						}


			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-right') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							right: 20px;
                            top: 20px;
						}



			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 50%;
                            top: 20px;
						}


			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 20px;
							right: unset;
                            top: 20px;
						}


			';
            } else {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							right: 20px;
						}



			';
            }

            $styles .= '
                                                div.s2baia-bot-chatbot-main-container-embedd button.s2baia-bot-chatbot-send-button{
                                                    background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_color'] . ';
                                                    color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_text_color'] . ';    
                                                }
                                                div.s2baia-bot-chatbot-main-container-embedd button.s2baia-bot-chatbot-send-button:hover{
                                                    background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_hover_color'] . '; 
                                                }
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-header-row,
						div.s2baia-bot-chatbot-main-container-embedd button.s2baia-bot-chatbot-regenerate-response-button {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_color'] . ';
						}
						div.s2baia-bot-chatbot-main-container-embedd button.s2baia-bot-chatbot-send-button:disabled,
						div.s2baia-bot-chatbot-main-container-embedd button.s2baia-bot-chatbot-regenerate-response-button:disabled {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'color'] . '2b;
						}
						.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-main-chat-box,
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-main-chat-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'background_color'] . ';
							border-radius: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px;
						}
						.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-input-box,
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-input-box {
							border-radius: 0 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px;
						}
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container-embedd p.s2baia-bot-chatbot-header-text,
                        .s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container-embedd p.s2baia-bot-chatbot-header-text {
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_text_color'] . ' !important;
						}
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-loading-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_bg_color'] . ';
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_text_color'] . ';
						}
                        div.s2baia-bot-chatbot-main-container-embedd .s2baia-bot-chatbot-ai-message-buttons svg {
							fill: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_icons_color'] . ' !important;
						}
                        div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box .s2baia-bot-chatbot-ai-response-message {
							border-radius: 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px;
						}
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-user-message-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_bg_color'] . ';
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_text_color'] . ';
							border-radius: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px;
						}
                        
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-user-message-box,
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container-embedd div.s2baia-bot-chatbot-messages-box .s2baia-bot-chatbot-ai-response-message{
							font-size: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_font_size'] . 'px;
							margin-bottom: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_margin'] . 'px;
						}



                        div.s2baia-bot-chatbot .s2baia-bot-chatbot-closed-view {
                            width: ' . $icon_size . 'px !important;
                            height: ' . $icon_size . 'px !important;
                        }

                        .s2baia-bot-chatbot .s2baia-bot-chatbot-main-container-embedd {
                            width: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width'] . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width_metrics'] . ';
                            height: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height'] . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height_metrics'] . ';
                        }



                        
			';

            return $styles;
        }
        
    }
    
}
