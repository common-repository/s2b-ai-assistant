<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$display_pagination = true;
$chatbots_per_page = 10;
$search_string = '';
$current_page = 1;
$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_nonce');
$load_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_loadnonce');
$wp_del_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'bot_dellognonce');
$wp_toggle_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'bot_togglenonce');
$icon_positions = S2bAia_ChatBotUtils::getIconPositionOptions();
$chatbot_positions = S2bAia_ChatBotUtils::getPositionOptions();
$metrics = S2bAia_ChatBotUtils::getMetrics();
//var_dump($chat_bot_options);

$max_tokens = (int) get_option(S2BAIA_PREFIX_LOW . 'max_tokens', 1024);
$count_of_instructions = (int) get_option(S2BAIA_PREFIX_LOW . 'count_of_instructions', 10);
$models = S2bAia_ChatBotUtils::getModels();
$total_chatbots = $chat_bots['cnt'];
$chat_bots_rows = $chat_bots['rows'];
?>
<div id="s2baia-tabs-3" class="s2baia_tab_panel" data-s2baia="3">
    <div class="inside">
        <div class="s2baia_config_items_wrapper">
            <?php
            //var_dump($default_chat_bot);
            $need_key_enter = true;
            $api_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            if(strlen($api_key) > 0){
                $need_key_enter = false;
            }
if(true){
?>
            <form action="" method="post" id="s2baia_chatbot_edit_form">    
                <input type="hidden" id="s2baia_randpar" name="s2baia_randpar" value="45"/>
                <input type="hidden" id="s2baia_idbot" name="s2baia_idbot" value=""/>
                <input type="hidden" name='s2b_chatbot_nonce' value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash"  id="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash" value="<?php echo esc_html($chatbot_hash); ?>"/>
                <input type="hidden" name="action" value="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>store_chatbot"/>
                
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">

                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" 
                                       name="s2baia_submit" 
                                       id="s2baia_submit" class="button button-primary button-large" 
                                       onclick="s2b_chatbot_list.saveChatbot(event);" >
                                
                                <button 
                                    value="<?php echo esc_html__('Clear & New', 's2b-ai-aiassistant') ?>" 
                                    name="s2baia_new_chatbot" 
                                    id="s2baia_new_chatbot" class="button button-primary button-large" style=""
                                    onclick="s2b_chatbot_list.newBot(event,s2baia_default_chatbot_options,'');">
                                        <?php echo esc_html__('Clear & New', 's2b-ai-aiassistant'); ?>
                                </button>

                            </div>

                            <div class="s2baia-custom-loader s2baia-general-loader" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <?php
                if($need_key_enter){
                
                ?>
                <h1 style="color:red;text-align: center;"><?php echo esc_html__('You need to enter Open I API Key before configurations start working', 's2b-ai-aiassistant'); ?>. <?php echo esc_html__('Please open', 's2b-ai-aiassistant'); ?>  <a href="<?php echo esc_url(admin_url()) . 'admin.php?page=s2baia_settings'; ?>"><?php echo esc_html__('this page', 's2b-ai-aiassistant'); ?></a> <?php esc_html__('and enter Open AI key', 's2b-ai-aiassistant'); ?>.</h1>
                <?php
                }
                
                ?>
                
                <h3 class="s2baia_instruction" style="text-align: center;">
                                                    <?php echo esc_html__('Just put', 's2b-ai-aiassistant'); ?> <span id="s2baia_shortcode" style="cursor: pointer;" onClick="s2b_utils.CopyToClipboardInnerHtml(event,'s2baia_shortcode');">[s2baia_chatbot]</span> <?php echo esc_html__('into any page or post to display chatbot.', 's2b-ai-aiassistant'); ?> 
                </h3>
                <h3 class="s2baia_instruction" style="text-align: center; min-height: 30px;">
                    <span  class="s2baia_selected_bot_info">

                    </span>
                </h3>    
                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Appearance', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_position"><?php esc_html_e('ChatBot Position', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <select id="s2baia_chatbot_position" name="s2baia_chatbot_position">
                                            <?php
                                            $position = isset($chat_bot_options['position'])?esc_html($chat_bot_options['position']):'right';
                                            foreach($chatbot_positions as $idx => $posit){
                                                if($position == $idx){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($idx); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($posit); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Position of chatbot on browser screen', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_icon_position"><?php esc_html_e('Icon Position', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <select id="s2baia_chatbot_icon_position" name="s2baia_chatbot_icon_position">
                                            <?php
                                            $iposition = isset($chat_bot_options['icon_position'])?esc_html($chat_bot_options['icon_position']):'bottom-right';
                                            
                                            foreach($icon_positions as $idx => $pos){
                                                if($iposition == $idx){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($idx); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($pos); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Position of icon on browser screen', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chat_icon_size">
                                            <?php esc_html_e('Chat icon size', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $icon_size = isset($chat_bot_options['chat_icon_size'])?(int)$chat_bot_options['chat_icon_size']:70; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_chat_icon_size"  
                                                   id="s2baia_chatbot_chat_icon_size" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   placeholder="<?php esc_html_e('Enter number pixels or percent', 's2b-ai-aiassistant'); ?>"
                                                   value="<?php echo (int)$icon_size; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Icon size in pixels.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chat_width">
                                            <?php esc_html_e('Bot window width', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_width = isset($chat_bot_options['chat_width'])?(int)$chat_bot_options['chat_width']:25; ?>
                                            <input class="s2baia_input s2baia_20pc"  
                                                   name="s2baia_chatbot_chat_width"  
                                                   id="s2baia_chatbot_chat_width" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   placeholder="<?php  ?>" value="<?php echo (int)$chat_width; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select width of Chatbot window.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_width_metrics = isset($chat_bot_options['chat_width_metrics'])?$chat_bot_options['chat_width_metrics']:'%'; ?>
                                        
                                            <select id="s2baia_chatbot_width_metrics" name="s2baia_chatbot_width_metrics">
                                                <?php

                                                foreach($metrics as $idx => $met_val){

                                                    if($chat_width_metrics == $met_val){
                                                        $sel_opt = 'selected';
                                                    }else{
                                                        $sel_opt = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo esc_html($met_val); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($met_val); ?> </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>                                        
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select units of measurement.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                    
                                    
                                    
                                    
                                </div>
                                
                                <div class="s2baia_block_content">
                                        <div class="s2baia_row_header">
                                            <label for="s2baia_chatbot_chat_height"><?php esc_html_e('Chat Height', 's2b-ai-aiassistant'); ?>:</label>
                                        </div>
                                        <div class="s2baia_row_content s2baia_pr">
                                            <div style="position: relative;">
                                                <?php $chat_height = isset($chat_bot_options['chat_height']) ? (int) $chat_bot_options['chat_height'] : 55; ?>
                                                <input class="s2baia_input s2baia_20pc" name="s2baia_chatbot_chat_height" 
                                                       id="s2baia_chatbot_chat_height" type="number" 
                                                       step="1" maxlength="4" autocomplete="off"  
                                                       placeholder="<?php ?>" value="<?php echo (int) $chat_height; ?>">
                                            </div>
                                            <p class="s2baia_input_description">
                                                <span style="display: inline;">
                                                    <?php esc_html_e('Select height of Chatbot window.', 's2b-ai-aiassistant'); ?>
                                                </span>
                                            </p>
                                        </div>
                                        
                                        <div class="s2baia_row_content s2baia_pr">
                                            <div style="position: relative;">
                                                <?php $chat_height_metrics = isset($chat_bot_options['chat_height_metrics']) ? $chat_bot_options['chat_height_metrics'] : '%'; ?>
                                                
                                                <select id="s2baia_chatbot_height_metrics" name="s2baia_chatbot_height_metrics">
                                                    <?php

                                                    foreach($metrics as $idx => $met_val){

                                                        if($chat_height_metrics == $met_val){
                                                            $sel_opt = 'selected';
                                                        }else{
                                                            $sel_opt = '';
                                                        }
                                                        ?>
                                                        <option value="<?php echo esc_html($met_val); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($met_val); ?> </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select> 
                                                
                                            </div>
                                            <p class="s2baia_input_description">
                                                <span style="display: inline;">
                                                    <?php esc_html_e('Select units of measurement for chatbot height.', 's2b-ai-aiassistant'); ?>
                                                </span>
                                            </p>
                                        </div>
                                    
                                </div>
                                
                                <div class="s2baia_block_content">
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chatbot_picture_url"><?php esc_html_e('Url of chatbot picture', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $chatbot_picture_url = isset($chat_bot_options['chatbot_picture_url']) ? $chat_bot_options['chatbot_picture_url'] : ''; ?>
                                            <input type="text" id="s2baia_chatbot_chatbot_picture_url" 
                                                   name="s2baia_chatbot_chatbot_picture_url" 
                                                   
                                                   value="<?php echo esc_html($chatbot_picture_url); ?>" />
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Caution!!! Leave this field empty for default picture or enter full url of picture with protocol and domain name. Otherwise error wil be generated.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content">
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_send_button_text"><?php esc_html_e('Send button text', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $send_button_text = isset($chat_bot_options['send_button_text']) ? $chat_bot_options['send_button_text'] : esc_html__('Send','s2b-ai-aiassistant'); ?>
                                            <input type="text" id="s2baia_chatbot_send_button_text" 
                                                   name="s2baia_chatbot_send_button_text" 
                                                   
                                                   value="<?php echo esc_html($send_button_text); ?>" />
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Text displayed on send button.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content">
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_clear_button_text"><?php esc_html_e('Clear button text', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $clear_button_text = isset($chat_bot_options['clear_button_text']) ? $chat_bot_options['clear_button_text'] : esc_html__('Clear','s2b-ai-aiassistant'); ?>
                                            <input type="text" id="s2baia_chatbot_clear_button_text" 
                                                   name="s2baia_chatbot_clear_button_text" 
                                                   
                                                   value="<?php echo esc_html($clear_button_text); ?>" />
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Text displayed on clear button.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="s2baia_block_content">
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_placeholder"><?php esc_html_e('Message Placeholder', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $message_placeholder = isset($chat_bot_options['message_placeholder']) ? $chat_bot_options['message_placeholder'] : 'Ctrl+Enter to send request'; ?>
                                            <input type="text" id="s2baia_chatbot_message_placeholder" 
                                                   name="s2baia_chatbot_message_placeholder" 
                                                   
                                                   value="<?php echo esc_html($message_placeholder); ?>" />
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Placeholder text for the message input field.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <?php
                                if(true){
                                ?>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_chatbot_name"><?php esc_html_e('Chatbot Name', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $chatbot_name = isset($chat_bot_options['chatbot_name']) ? $chat_bot_options['chatbot_name'] : 'GPT Assistant'; ?>
                                                    <input type="text" id="s2baia_chatbot_chatbot_name" 
                                                           name="s2baia_chatbot_chatbot_name" 
                                                           value="<?php echo esc_html($chatbot_name); ?>" />
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Name of the chatbot', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_compliance_text"><?php esc_html_e('Compliance Text', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php 
                                                            $allowed = array(
                                                                'a' => array(
                                                                    'href' => array(),
                                                                    'title' => array(),
                                                                    'target' => array(),
                                                                    'style' => array()   
                                                                )
                                                            );
                                                            $compliance_text = isset($chat_bot_options['compliance_text']) ? wp_kses($chat_bot_options['compliance_text'], $allowed): '';
                                                            ?>
                                                    <input type="text" id="s2baia_chatbot_compliance_text" 
                                                           name="s2baia_chatbot_compliance_text" 
                                                           value="<?php echo esc_html($compliance_text); ?>" />
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Compliance text for the chatbot. Text will not be displayed if you leave this field blank. Links with <a  html tags are allowed. For example <a href="http:example.com" target="blank" style="color:red;">link</a>', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                

                                
                                
                                
                                
                                
                            </div>
                        </div>   
                              
                    </div>
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Chatbot behavior', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_access_for_guests">
                                            <?php esc_html_e('Access for guests', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php 
                                            $checked = '';
                                            $access_for_guests = isset($chat_bot_options['access_for_guests'])?(int)$chat_bot_options['access_for_guests']:1; 
                                            if ($access_for_guests == 1) {
                                                    $checked = ' checked ';
                                                }
                                            ?>
                                            
                                            <input type="checkbox" id="s2baia_access_for_guests" 
                                                   name="s2baia_access_for_guests" 
                                                       <?php echo esc_html($checked); ?>  >

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Check box if you want to make chatbot accessible for anonimous visitors', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_context"><?php esc_html_e('Context', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $context = isset($chat_bot_options['context']) ? $chat_bot_options['context'] : ''; ?>
                                                    <textarea id="s2baia_chatbot_context" 
                                                              name="s2baia_chatbot_context"><?php echo esc_html($context); ?></textarea>
                                                    
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('The text that you will write in the Context field will be added to the beginning of the prompt. Note, in case you want to use the default message, you will need to leave the field blank.', 's2b-ai-aiassistant'); ?> 
                                                         <?php esc_html_e('Read this ', 's2b-ai-aiassistant'); ?> 
                                                        <a href="https://soft2business.com/how-to-create-content-aware-chat-bot/#content_aware_completion_api_use" target="blank" class="s2baia_instruction" ><?php esc_html_e('article', 's2b-ai-aiassistant'); ?></a> <?php esc_html_e(' to get idea how use this field. ', 's2b-ai-aiassistant'); ?> 
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                <?php 
                                if(false){
                                ?>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_greeting_message"><?php esc_html_e('Greeting message', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $greeting_message = isset($chat_bot_options['greeting_message'])?(int)$chat_bot_options['greeting_message']:1; 
                                            $checked = '';
                                                if ($greeting_message == 1) {
                                                    $checked = ' checked ';
                                                }
                                                
                                            ?>
                                           <input type="checkbox" id="s2baia_chatbot_greeting_message" 
                                                  name="s2baia_chatbot_greeting_message" 
                                                      <?php echo esc_html($checked); ?>  >
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select box if you want display greeting message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                    
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_greeting_message_text">
                                            <?php esc_html_e('Greeting message text', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php 
                                            $greeting_message_text = isset($chat_bot_options['greeting_message_text'])?$chat_bot_options['greeting_message_text']:''; 
                                              ?>
                                           <input type="text" name="s2baia_chatbot_greeting_message_text" 
                                                  id="s2baia_chatbot_greeting_message_text" 
                                                  value="<?php echo esc_html($greeting_message_text); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Enter greeting message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <?php 
                                }
                                ?>
                                
                                <?php
                                if(false){
                                ?>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_language"><?php esc_html_e('Language', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $language = isset($chat_bot_options['language']) ? $chat_bot_options['language'] : 'english'; ?>
                                                    <input type="text" id="s2baia_chatbot_language" 
                                                           name="s2baia_chatbot_language" 
                                                           value="<?php echo esc_html($language); ?>" />
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Language setting for the chatbot', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                <?php
                                }
                                ?>        
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_chat_model"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position:relative;">
                                                    <select id="s2baia_chatbot_chat_model" name="s2baia_chatbot_chat_model">
                                                        <?php
                                                        $model = isset($chat_bot_options['model']) ? esc_html($chat_bot_options['model']) : 'gpt-4o';

                                                        foreach ($models as $value) {
                                                            if ($model == $value) {
                                                                $sel_opt = 'selected';
                                                            } else {
                                                                $sel_opt = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo esc_html($value); ?>" <?php echo esc_html($sel_opt); ?>><?php echo esc_html($value); ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;"><?php esc_html_e('Select model', 's2b-ai-aiassistant'); ?></span>
                                                </p>
                                            </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chat_temperature">
                                            <?php esc_html_e('Temperature', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_temperature = isset($chat_bot_options['chat_temperature'])?$chat_bot_options['chat_temperature']:0.8; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_chat_temperature"  
                                                   id="s2baia_chatbot_chat_temperature" type="number" 
                                                   step="0.1" min="0" max="2" maxlength="4" autocomplete="off"  
                                                   placeholder="<?php esc_html_e('Enter number pixels or percent', 's2b-ai-aiassistant'); ?>"
                                                   value="<?php echo esc_html($chat_temperature); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Input temperature from 0 to 2.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                              
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chat_top_p">
                                            <?php esc_html_e('Top P', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_top_p = isset($chat_bot_options['chat_top_p'])?$chat_bot_options['chat_top_p']:1; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_chat_top_p"  
                                                   id="s2baia_chatbot_chat_top_p" type="number" 
                                                   step="0.1" min="0" max="1" maxlength="4" autocomplete="off"  
                                                   value="<?php echo esc_html($chat_top_p); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('With this option, the model considers only the most probable tokens, based on a specified probability threshold. For example, if the top_p value is set to 0.1, only the tokens with the highest probability mass that make up the top 10% of the distribution will be considered for output. This can help generate more focused and coherent responses, while still allowing for some level of randomness and creativity in the generated text.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_max_tokens">
                                            <?php esc_html_e('Maximum tokens', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $max_tokens = isset($chat_bot_options['max_tokens'])?$chat_bot_options['max_tokens']:2048; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_max_tokens"  
                                                   id="s2baia_chatbot_max_tokens" type="number" 
                                                   step="1" min="0" max="256000" maxlength="4" autocomplete="off"  
                                                   value="<?php echo esc_html($max_tokens); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Specifies the maximum number of tokens (words or word-like units) that the chatbot will generate in response to a prompt. This can be used to control the length of the generated text.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_frequency_penalty">
                                            <?php esc_html_e('Frequency penalty', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $frequency_penalty = isset($chat_bot_options['frequency_penalty'])?$chat_bot_options['frequency_penalty']:0; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_frequency_penalty"  
                                                   id="s2baia_chatbot_frequency_penalty" type="number" 
                                                   step="0.01" min="-2" max="2" maxlength="4" autocomplete="off"  
                                                   value="<?php echo esc_html($frequency_penalty); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Encourages the chatbot to generate text with a more diverse vocabulary. A higher frequency penalty value will reduce the likelihood of the chatbot repeating words that have already been used in the generated text. Number between -2.0 and 2.0.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_presence_penalty">
                                            <?php esc_html_e('Presence penalty', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $presence_penalty = isset($chat_bot_options['presence_penalty'])?$chat_bot_options['presence_penalty']:0; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_presence_penalty"  
                                                   id="s2baia_chatbot_presence_penalty" type="number" 
                                                   step="0.01" min="-2" max="2" maxlength="4" autocomplete="off"  
                                                   value="<?php echo esc_html($presence_penalty); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Encourages the chatbot to generate text that includes specific phrases or concepts. A higher presence penalty value will reduce the likelihood of the chatbot repeating the same phrases or concepts multiple times in the generated text. Number between -2.0 and 2.0.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div> 
                    </div>

                </div>
<?php
}
?>
            
                
            <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Colors', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                <?php
                                if(false){
                                ?>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_config_color"><?php esc_html_e('ChatBot Widget Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $color = isset($chat_bot_options['color'])?esc_html($chat_bot_options['color']):'#ffefea';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_color" 
                                                   id="s2baia_chatbot_config_color" 
                                                   value="<?php echo esc_html($color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php  ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_header_text_color"><?php esc_html_e('Header Text Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $header_text_color = isset($chat_bot_options['header_text_color'])?esc_html($chat_bot_options['header_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_header_text_color" 
                                                   id="s2baia_chatbot_header_text_color" 
                                                   value="<?php echo esc_html($header_text_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php echo esc_html('Specify the color of the chat widget header text.', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                        
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_header_color"><?php esc_html_e('Header Background Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $header_color = isset($chat_bot_options['header_color'])?esc_html($chat_bot_options['header_color']):'#0C476E';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_header_color" 
                                                   id="s2baia_chatbot_header_color" 
                                                   value="<?php echo esc_html($header_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php  echo esc_html('Color of background of Header', 's2b-ai-aiassistant');?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_send_button_color"><?php esc_html_e('Send Button Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $send_button_color = isset($chat_bot_options['send_button_color'])?esc_html($chat_bot_options['send_button_color']):'#0E5381';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_send_button_color" 
                                                   id="s2baia_chatbot_send_button_color" 
                                                   value="<?php echo esc_html($send_button_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of background of Send button', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_send_button_hover_color"><?php esc_html_e('Send Button Hover Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $send_button_hover_color = isset($chat_bot_options['send_button_hover_color'])?esc_html($chat_bot_options['send_button_hover_color']):'#126AA5';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_send_button_hover_color" 
                                                   id="s2baia_chatbot_send_button_hover_color" 
                                                   value="<?php echo esc_html($send_button_hover_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of background of Send button when cursor over it', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_send_text_color"><?php esc_html_e('Send Button Text Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $send_button_text_color = isset($chat_bot_options['send_button_text_color'])?esc_html($chat_bot_options['send_button_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_send_text_color" 
                                                   id="s2baia_chatbot_send_text_color" 
                                                   value="<?php echo esc_html($send_button_text_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of text on Send button', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_bg_color2"><?php esc_html_e('Message background color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $message_bg_color = isset($chat_bot_options['message_bg_color'])?esc_html($chat_bot_options['message_bg_color']):'#1476B8';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_message_bg_color2" 
                                                   id="s2baia_chatbot_message_bg_color2" 
                                                   value="<?php echo esc_html($message_bg_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of background of user message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_text_color2"><?php esc_html_e('Message text color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $message_text_color = isset($chat_bot_options['message_text_color'])?esc_html($chat_bot_options['message_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_message_text_color2" 
                                                   id="s2baia_chatbot_message_text_color2" 
                                                   value="<?php echo esc_html($message_text_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of text of user message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                          
                        
                        
                        <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_response_bg_color2"><?php esc_html_e('Message responce color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_bg_color = isset($chat_bot_options['response_bg_color'])?esc_html($chat_bot_options['response_bg_color']):'#5AB2ED';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_response_bg_color2" 
                                                   id="s2baia_chatbot_response_bg_color2" 
                                                   value="<?php echo esc_html($response_bg_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of background of response message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                        
                        <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_response_text_color2"><?php esc_html_e('Responce text color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_text_color = isset($chat_bot_options['response_text_color'])?esc_html($chat_bot_options['response_text_color']):'#000000';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_response_text_color2" 
                                                   id="s2baia_chatbot_response_text_color2" 
                                                   value="<?php echo esc_html($response_text_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of text of response message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                        </div>
                        
                        <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_response_icons_color2"><?php esc_html_e('Responce icon color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_icons_color = isset($chat_bot_options['response_icons_color'])?esc_html($chat_bot_options['response_icons_color']):'#000000';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_response_icons_color2" 
                                                   id="s2baia_chatbot_response_icons_color2" 
                                                   value="<?php echo esc_html($response_icons_color); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Color of icons in response message', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div> 
                    </div>
                    
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Geometry', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                
                            </div> 
                            
                            <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_font_size">
                                            <?php esc_html_e('Message font size', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_font_size = isset($chat_bot_options['message_font_size'])?(int)$chat_bot_options['message_font_size']:16; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_message_font_size"  
                                                   id="s2baia_chatbot_message_font_size" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   value="<?php echo (int)$message_font_size; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Message font size in pixels.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                            </div>
                            
                            <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_margin">
                                            <?php esc_html_e('Message margin', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_margin = isset($chat_bot_options['message_margin'])?(int)$chat_bot_options['message_margin']:7; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_message_margin"  
                                                   id="s2baia_chatbot_message_margin" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   value="<?php echo (int)$message_margin; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Bottom margin in pixels of user and response messages.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                            </div>
                            
                            
                            <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_message_border_radius">
                                            <?php esc_html_e('Message border radius', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_border_radius = isset($chat_bot_options['message_border_radius'])?(int)$chat_bot_options['message_border_radius']:10; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_message_border_radius"  
                                                   id="s2baia_chatbot_message_border_radius" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   value="<?php echo (int)$message_border_radius; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Radius of user and response messages in pixels.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                            </div>
                            
                            <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_chatbot_border_radius">
                                            <?php esc_html_e('Chatbot widget border radius', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chatbot_border_radius = isset($chat_bot_options['chatbot_border_radius'])?(int)$chat_bot_options['chatbot_border_radius']:10; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_chatbot_border_radius"  
                                                   id="s2baia_chatbot_chatbot_border_radius" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   value="<?php echo (int)$chatbot_border_radius; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Chatbot widget border radius in pixels.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                            </div>
                            
                            
                    </div>
                    
                    <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Deep customization', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                
                            </div> 
                            
                            <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_html_id_closed_bot"><?php esc_html_e('Closed bot html ID', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php 
                                                            
                                                            $html_id_closed_bot = isset($chat_bot_options['html_id_closed_bot']) ? esc_html($chat_bot_options['html_id_closed_bot']): '';
                                                            ?>
                                                    <input type="text" id="s2baia_chatbot_html_id_closed_bot" 
                                                           name="s2baia_chatbot_html_id_closed_bot" 
                                                           value="<?php echo esc_html($html_id_closed_bot); ?>" />
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Enter ID of html element of closed chatbot . It allows you to assign your own css styles.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                            </div>
                        
                            <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_html_id_open_bot"><?php esc_html_e('Open bot html ID', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php 
                                                            
                                                            $html_id_open_bot = isset($chat_bot_options['html_id_open_bot']) ? esc_html($chat_bot_options['html_id_open_bot']): '';
                                                            ?>
                                                    <input type="text" id="s2baia_chatbot_html_id_open_bot" 
                                                           name="s2baia_chatbot_html_id_open_bot" 
                                                           value="<?php echo esc_html($html_id_open_bot); ?>" />
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Enter ID of html element of open chatbot . It allows you to assign your own css styles.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                            </div>
                            
                        <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_custom_css"><?php esc_html_e('Custom CSS', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $custom_css = isset($chat_bot_options['custom_css']) ? $chat_bot_options['custom_css'] : ''; ?>
                                                    <textarea id="s2baia_chatbot_custom_css" 
                                                              name="s2baia_chatbot_custom_css"><?php echo strip_tags($custom_css); ?></textarea>
                                                    
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Enter custom CSS rules which will be applied to chatbot. You can use id of html elements pointed on above fields or other css selectors that are related with chatbot.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                        </div>
                        <input type="hidden" name="s2bfinishplug" value="dk(4ds4fd5">

                            
                            
                    </div>    
                    
                        </div> 

                    </div>    
                
                
            </form>
           
            <h3 class="s2baia_instruction" style="text-align: center; min-height: 30px;">
                <span  class="s2baia_selected_bot_info">
                    
                </span>
            </h3>            

        <div class="tablenav-pages">
            <?php
            if ($display_pagination) {
                ?>
                <div class="tablenav top">
                    <div class="alignleft ">
                        <label><?php esc_html_e('Items per page', 's2b-ai-aiassistant'); ?>:</label>
                        <select name="bots_per_page" id="bots_per_page" onchange="s2b_chatbot_list.changeRowPerPage(this);">
                            <option <?php echo $chatbots_per_page == 10 ? 'selected="selected"' : ''; ?> value="10">10</option>
                            <option  <?php echo $chatbots_per_page == 20 ? 'selected="selected"' : ''; ?>  value="20">20</option>
                            <option  <?php echo $chatbots_per_page == 50 ? 'selected="selected"' : ''; ?>  value="50">50</option>
                            <option  <?php echo $chatbots_per_page == 100 ? 'selected="selected"' : ''; ?>  value="100">100</option>
                        </select>
                        <input type="hidden" id="s2baia_page" name="s2baia_page" value="1"/>

                    </div>
                </div> 

                <div class="s2baia_pagination">
                    <?php
                    echo '<span class="s2baia_page_lbl" style=""> ' . esc_html__('Page', 's2b-ai-aiassistant') . ':</span>';

                    echo '<span aria-current="page" class="page-numbers2 current page-numbers2gpt" >' . esc_html($current_page) . '</span>';
                    echo '<a class="s2bprevious page-numbers2 page-numbers2gpt" href="#" onclick="s2b_chatbot_list.prevRowPage(event);" style="display:none;" >&lt;&lt;</a>';
                    if ($current_page * $chatbots_per_page < $total_chatbots) {
                        echo '<a class="s2bnext page-numbers2 page-numbers2gpt" href="#" style="" onclick="s2b_chatbot_list.nextRowPage(event);" >&gt;&gt;</a>';
                    }
                    echo '<span class="s2baia_total_rows s2baia_totals" style="padding-left:20px;"> ';
                    printf(esc_html__( 'Total: %s items', 's2b-ai-aiassistant' ),esc_html($total_chatbots));
                    echo '</span>   ';
                    echo '';
                    echo '';
                    ?>    
                </div>
                <div class="s2baia_load_container" style="position:relative;">
                    <div class="s2baia-custom-loader s2baia-instructions-loader" style="position:fixed;"></div>
                </div>
                <?php
            }
            ?>
            <p class="search-box">
                <span title="clear" id="s2baiaclear" class="dashicons dashicons-no" onclick="s2b_chatbot_list.clearSearch(event);"></span>
                <input type="search" id="s2baia_search_bots" name="s2baia_search" value="<?php echo esc_html($search_string); ?>" onkeyup="s2b_chatbot_list.searchRowKeyUp(event);" >
                <input type="submit" id="s2baia_search_submit" class="button" value="Search chatbots" onclick="s2b_chatbot_list.loadRowsE(event);">
            </p>
        </div>                    
        <?php
        if (true) {
            ?>
            <div id="s2baia_container2" class="  ">

                <table id="s2baia_bots" class="wp-list-table widefat fixed striped pages">
                    <thead>

                    <th class="manage-column id_column" style="width: 5%;"><?php esc_html_e('ID', 's2b-ai-aiassistant'); ?></th>

                    <th class="manage-column"  style="width: 15%;"><?php esc_html_e('Hash', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column mvertical"  style="width: 10%;"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column " style="width: 40%;"><?php esc_html_e('Chatbot Name', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column mvertical"  style="width: 20%;"><?php esc_html_e('Position', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column"  style="width: 10%;"><?php esc_html_e('Actions', 's2b-ai-aiassistant'); ?></th>

                    </thead>
                    <tbody id="s2baia-bots-list">
                        <?php
                        $js_bots = [];
                        $current_row = 0;
                        foreach ($chat_bots_rows as $row) {

                            //var_dump($row);
                            $bot_options = $row->bot_options;
                            //var_dump($bot_options);
                            $row->bot_options = $bot_options;
                            if(is_object($row->bot_options) && isset($row->bot_options->custom_css)){
                                $row->bot_options->custom_css = strip_tags($row->bot_options->custom_css);
                            }else{
                                $row->bot_options->custom_css = '';
                            }
                            $js_bots[(int) $row->id] = $row;
                            //s2baia_disabled_text
                            $s2baia_disabled_text = '';
                            if ($row->disabled) {
                                $s2baia_disabled_text = 's2baia_disabled_text';
                            }
                            ?>
                            <tr class="<?php echo esc_attr($s2baia_disabled_text); ?>">
                                <td class="id_column">
                                    <?php
                                    $displayed_id = (int) $row->id;
                                    ?>

                                    <?php
                                    echo esc_html($displayed_id);
                                    ?>

                                </td>
                                <?php ?> 
                                <td>
                                    <a href="<?php echo '#'; ?>" onclick="s2b_chatbot_list.editBot(event,<?php echo (int) $row->id; ?>,'')" id="s2baia_bot_href_<?php echo (int) $row->id; ?>">
                                        <?php
                                        echo esc_html($row->hash_code);//wp_kses($row->hash_code, S2bAia_Utils::getInstructionAllowedTags());
                                        ?>
                                    </a>


                                </td>
                                <td class="mvertical">
                                    <span id="s2baia_model_span_<?php echo (int) $row->id; ?>">

                                        <?php
                                        if(isset($bot_options->model)){
                                            echo esc_html($bot_options->model);
                                        }else{
                                            echo '';
                                        }
                                        ?>
                                    </span>              
                                </td>
                                <td class="mvertical">
                                    <span id="s2baia_chatbotname_span_<?php echo (int) $row->id; ?>">
                                        <?php
                                        if (isset($bot_options->chatbot_name)) {
                                            echo esc_html($bot_options->chatbot_name);
                                        } else {
                                            echo esc_html__('Unknown', 's2b-ai-aiassistant');
                                        }
                                        ?>
                                    </span>  
                                </td>
                                <td class="s2baia_user">
                                    <span id="s2baia_position_span_<?php echo (int) $row->id; ?>">
                                        <?php
                                        echo esc_html($bot_options->position);
                                        ?>
                                    </span>
                                </td>


                                <td class="s2baia_flags_td">
                                    <?php
                                    if ($row->disabled) {
                                        $dashiconsclass = 'dashicons-insert';
                                    } else {
                                        $dashiconsclass = 'dashicons-remove';
                                    }
                                    ?>
                                    <span title="edit" class="dashicons dashicons-edit"  onclick="s2b_chatbot_list.editBot(event,<?php echo (int) $row->id; ?>,'')" ></span>
                                    <span title="remove"  class="dashicons dashicons-trash" onclick="s2b_chatbot_list.removeRow(event,'<?php echo esc_html($row->id); ?>')"></span>

                                </td>


                            </tr>
                            <?php
                            $current_row++;
                            if($current_row >= $chatbots_per_page){
                                break;
                            }
                        }
                        ?>

                    </tbody>
                </table>


                <?php
            }
            ?>            
            <?php
            if ($display_pagination) {
                ?>                    
                <div class="s2baia_pagination">
                    <?php
                    echo '<span class="s2baia_page_lbl" style=""> ' . esc_html__('Page', 's2b-ai-aiassistant') . ':</span>';

                    echo '<span aria-current="page" class="page-numbers2 current page-numbers2gpt" >' . esc_attr($current_page) . '</span>';
                    echo '<a class="s2bprevious page-numbers2 page-numbers2gpt" href="#" onclick="s2b_chatbot_list.prevRowPage(event);" style="display:none;" >&lt;&lt;</a>';
                    if ($current_page * $chatbots_per_page < $total_chatbots) {
                        echo '<a class="s2bnext page-numbers2 page-numbers2gpt" href="#" style="" onclick="s2b_chatbot_list.nextRowPage(event);" >&gt;&gt;</a>';
                    }
                    echo '<span class="s2baia_total_rows s2baia_totals" style="padding-left:20px;"> '; 
                    printf(esc_html__( 'Total: %s items', 's2b-ai-aiassistant' ),esc_html($total_chatbots));
                    echo '</span>   ';
                    echo '';
                    echo '';
                    ?>   

                </div>
                <?php
            }
            ?>                    
        </div>

            
            
        </div>

    </div>
</div>

<script>
    let s2b_chatbot_list = null;
    let s2baia_edited_chatbot_id = '';
    
    
    let s2baia_bots = <?php echo wp_json_encode($js_bots,JSON_HEX_TAG); ?>;
    jQuery(document).ready(function () {
        s2baiaRowsOptions['bot_Togglenonce'] = '<?php echo esc_html($wp_toggle_nonce) ?>';
        s2baiaRowsOptions['bot_EdidtedBot'] = 0;
        s2baiaRowsOptions['ajax_Action'] = s2baajaxAction;
        s2baiaRowsOptions['delete_RowsAction'] = 's2b_remove_chatbot';
        s2baiaRowsOptions['row_DellogNonce'] = '<?php echo esc_html($wp_del_nonce) ?>';
        s2baiaRowsOptions['message_LogConfirmDelete'] = '<?php echo esc_html__('Do you want to delete bot with ID', 's2b-ai-aiassistant'); ?>';
        s2baiaRowsOptions['table_Row_Href_Prefix'] = 's2baia_bot_href_';
        s2baiaRowsOptions['row_Loadnonce'] = '<?php echo esc_html($load_nonce) ?>';
        s2baiaRowsOptions['message_Update_Success'] = '<?php echo esc_html__('Bot updated successfully', 's2b-ai-aiassistant'); ?>';
        s2baiaRowsOptions['message_New_Success'] = '<?php echo esc_html__('Bot created successfully', 's2b-ai-aiassistant'); ?>';
        s2baiaRowsOptions['load_RowsAction'] = 's2b_load_chatbots';
        s2baiaRowsOptions['rows_PerPageId'] = '#bots_per_page';
        s2baiaRowsOptions['row_PageId'] = '#s2baia_page';
        s2baiaRowsOptions['table_ElementId'] = '#s2baia_bots';
        s2baiaRowsOptions['search_InputId'] = '#s2baia_search_bots';
        s2baiaRowsOptions['table_Container'] = '#s2baia_container2';
        s2baiaRowsOptions['page_Numbers'] = '.page-numbers2gpt';
        s2baiaRowsOptions['row_items'] = s2baia_bots;
        s2baiaRowsOptions['app_suffix'] = '';
        s2baiaRowsOptions['total_Rows'] = '.s2baia_totals';
        
        s2b_chatbot_list = new S2baiaChatGptManager(s2baiaRowsOptions);
        
        console.log(s2baia_default_chatbot_options);
        });
</script>
