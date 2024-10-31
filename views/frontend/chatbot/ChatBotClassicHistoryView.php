<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_ChatBotClassicHistoryView')) {

    class S2bAia_ChatBotClassicHistoryView {
        /*
         * $data_par - string that represents array encoded to json
         * $data_parameters - 
         */
        public function render($data_par,$data_parameters){
            
                ob_start();
                //var_dump($data_parameters);
                $id_closed_bot = '';
                $id_open_bot = '';
                $custom_css = '';
                $display_custom_css = false;
                $display_closed_bot_id = false;
                $display_open_bot_id = false;
                if(isset($data_parameters['s2baia_chatbot_opt_html_id_closed_bot']) && strlen($data_parameters['s2baia_chatbot_opt_html_id_closed_bot']) > 0){
                    $id_closed_bot =  $data_parameters['s2baia_chatbot_opt_html_id_closed_bot'];
                    $display_closed_bot_id = true;
                }
                if(isset($data_parameters['s2baia_chatbot_opt_html_id_open_bot']) && strlen($data_parameters['s2baia_chatbot_opt_html_id_open_bot']) > 0){
                    $id_open_bot = $data_parameters['s2baia_chatbot_opt_html_id_open_bot'];
                    $display_open_bot_id = true;
                }
                if(isset($data_parameters['s2baia_chatbot_opt_custom_css']) && strlen($data_parameters['s2baia_chatbot_opt_custom_css']) > 0){
                    $custom_css = $data_parameters['s2baia_chatbot_opt_custom_css'];
                    $display_custom_css = true;
                }
                $view_mode = $data_parameters['view_mode'];
                $chatbot_picture_url = isset($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']) && strlen($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']) > 0?esc_html($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX.'chatbot_picture_url']): S2BAIA_URL."/views/resources/img/chatbot.svg";
		$s2baia_log_conversation = (int)get_option('s2baia_log_conversation',0);
                $s2baia_log_alert = sanitize_text_field(get_option('s2baia_chatbot_log_alert',''));
                $show_alert = false;
                if($s2baia_log_conversation == 1 && strlen($s2baia_log_alert) > 0){
                    $show_alert = true;
                }
                ?>
        <?php  
        $container_style = 'display: none;';
        $icon_style = '';
        $container_calss = '';
                if($view_mode == 1){
                    $container_style = '';
                    $container_calss = ' s2baia-bot-chatbot-main-container-maximized-view';
                    $icon_style = 'display: none;';
                }
        ?>
	<div class="s2baia-bot-chatbot" style="" data-parameters='<?php echo esc_attr($data_par);?>' >
        <?php
        if($display_closed_bot_id){
        ?>
        <div class="s2baia-bot-chatbot-closed-view" style="<?php echo esc_attr($icon_style); ?>" id="<?php echo esc_html($id_closed_bot);  ?>"   > 
        <?php
        }else{
        ?>
        <div class="s2baia-bot-chatbot-closed-view" style="<?php echo esc_attr($icon_style); ?>"    >     
        <?php
        }
        ?>    
            <div class="s2baia-bot-closed-ic-container">
                <img class="s2baia-bot-chatbot-logo-img" src="<?php echo esc_url($chatbot_picture_url); ?>" alt="Chat Assistant Icon">
            </div>
        </div>
            
        <div class="s2baia-bot-chatbot-maximized-bg" style="<?php echo esc_attr($container_style); ?>"></div>
        <?php
        if($display_open_bot_id){
        ?>
        <div class="s2baia-bot-chatbot-main-container <?php  echo esc_html($container_calss); ?>"  id="<?php echo esc_html($id_open_bot);  ?>" style="<?php echo esc_attr($container_style); ?>">
        <?php
        }else{
        ?>
          <div class="s2baia-bot-chatbot-main-container <?php  echo esc_html($container_calss); ?>"   style="<?php echo esc_attr($container_style); ?>">  
        <?php
        }
        ?>
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
                        <?php if(isset($data_parameters['hide_close']) && $data_parameters['hide_close'] != 1){ ?>
                        <img src="<?php echo esc_url(S2BAIA_URL); ?>/views/resources/img/end-button.svg" alt="End" class="s2baia-bot-chatbot-end-bttn">
                        <?php } ?>
                    </div>
                </div>
                <div class="s2baia-bot-chatbot-messages-box"> 
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
    <?php
    if($display_custom_css){
        echo '<style type="text/css">';
		echo strip_tags( $custom_css );
	echo '</style>';
    }
?>
    <script>
    let s2baia_button_config_general_send = '<?php echo esc_html($send_button_text); ?>';
    let s2baia_button_config_general_clear = '<?php echo esc_html($clear_button_text); ?>';
    let s2baia_bot_view = '<?php echo (int)$data_parameters['bot_view']; ?>';
    let s2baia_chat_id = '<?php echo esc_html($data_parameters['chat_id']); ?>';
    let s2baia_alert_log_msg_exist = <?php echo $show_alert?'true':'false'; ?>;
    <?php if($view_mode == 1){  ?>
        jQuery(document).ready(function () {
            
            jQuery('body').addClass('s2baia-bot-chatbot-disabled-scroll-body');

        });
    <?php }  ?>    
    </script>

				<?php
                echo  '<style>'.esc_html(S2bAia_ChatBotUtils::getChatBotStyles($data_parameters)).'</style>'  ;              
		$content = ob_get_clean();
                return $content;
        }
    }
    
}