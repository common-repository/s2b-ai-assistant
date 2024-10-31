<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(true){
$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_config_nonce');
$icon_positions = S2bAia_ChatBotUtils::getIconPositionOptions();
$chatbot_positions = S2bAia_ChatBotUtils::getPositionOptions();
$metrics = S2bAia_ChatBotUtils::getMetrics();
//var_dump($chat_bot_options);

$max_tokens = (int) get_option(S2BAIA_PREFIX_LOW . 'max_tokens', 1024);
$count_of_instructions = (int) get_option(S2BAIA_PREFIX_LOW . 'count_of_instructions', 10);
$models = S2bAia_ChatBotUtils::getModels();


?>
<div id="s2baia-tabs-1" class="s2baia_tab_panel" data-s2baia="1">
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
            <form action="" method="post" id="s2baia_chatbot_gen_form">    
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_config_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash" value="<?php echo esc_html($chatbot_hash); ?>"/>
                <input type="hidden" name="action" value="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>store_chatbot_general_tab"/>
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">

                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" 
                                       name="s2baia_submit" 
                                       id="s2baia_submit" class="button button-primary button-large" 
                                       onclick="s2baiaSaveChatbotGeneral(event);" >

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
                                                    <?php echo esc_html__('These options are used for default chatbot', 's2b-ai-aiassistant'); ?>  <?php echo esc_html__('which will be used for creating new chatbots. Go to Chatbots or Assistants tab to create new.', 's2b-ai-aiassistant'); ?> 
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
                                        <label for="s2baia_chatbot_config_position"><?php esc_html_e('ChatBot Position', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <select id="s2baia_chatbot_config_position" name="s2baia_chatbot_config_position">
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
                                        <label for="s2baia_chatbot_config_icon_position"><?php esc_html_e('Icon Position', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <select id="s2baia_chatbot_config_icon_position" name="s2baia_chatbot_config_icon_position">
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
                                        <label for="s2baia_chatbot_config_chat_icon_size">
                                            <?php esc_html_e('Chat icon size', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $icon_size = isset($chat_bot_options['chat_icon_size'])?(int)$chat_bot_options['chat_icon_size']:70; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_chat_icon_size"  
                                                   id="s2baia_chatbot_config_chat_icon_size" type="number" 
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
                                        <label for="s2baia_chatbot_config_chat_width">
                                            <?php esc_html_e('Bot window width', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_width = isset($chat_bot_options['chat_width'])?(int)$chat_bot_options['chat_width']:25; ?>
                                            <input class="s2baia_input s2baia_20pc"  
                                                   name="s2baia_chatbot_config_chat_width"  
                                                   id="s2baia_chatbot_config_chat_width" type="number" 
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
                                        
                                            <select id="s2baia_chatbot_config_width_metrics" name="s2baia_chatbot_config_width_metrics">
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
                                            <label for="s2baia_chatbot_config_chat_height"><?php esc_html_e('Chat Height', 's2b-ai-aiassistant'); ?>:</label>
                                        </div>
                                        <div class="s2baia_row_content s2baia_pr">
                                            <div style="position: relative;">
                                                <?php $chat_height = isset($chat_bot_options['chat_height']) ? (int) $chat_bot_options['chat_height'] : 55; ?>
                                                <input class="s2baia_input s2baia_20pc" name="s2baia_chatbot_config_chat_height" 
                                                       id="s2baia_chatbot_config_chat_height" type="number" 
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
                                                
                                                <select id="s2baia_chatbot_config_height_metrics" name="s2baia_chatbot_config_height_metrics">
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
                                        <label for="s2baia_chatbot_config_chatbot_picture_url"><?php esc_html_e('Url of chatbot picture', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $chatbot_picture_url = isset($chat_bot_options['chatbot_picture_url']) ? $chat_bot_options['chatbot_picture_url'] : ''; ?>
                                            <input type="text" id="s2baia_chatbot_config_chatbot_picture_url" 
                                                   name="s2baia_chatbot_config_chatbot_picture_url" 
                                                   
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
                                        <label for="s2baia_chatbot_config_send_button_text"><?php esc_html_e('Send button text', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $send_button_text = isset($chat_bot_options['send_button_text']) ? $chat_bot_options['send_button_text'] : esc_html__('Send','s2b-ai-aiassistant'); ?>
                                            <input type="text" id="s2baia_chatbot_config_send_button_text" 
                                                   name="s2baia_chatbot_config_send_button_text" 
                                                   
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
                                        <label for="s2baia_chatbot_config_clear_button_text"><?php esc_html_e('Clear button text', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $clear_button_text = isset($chat_bot_options['clear_button_text']) ? $chat_bot_options['clear_button_text'] : esc_html__('Clear','s2b-ai-aiassistant'); ?>
                                            <input type="text" id="s2baia_chatbot_config_clear_button_text" 
                                                   name="s2baia_chatbot_config_clear_button_text" 
                                                   
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
                                        <label for="s2baia_chatbot_config_message_placeholder"><?php esc_html_e('Message Placeholder', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position: relative;">
                                            <?php $message_placeholder = isset($chat_bot_options['message_placeholder']) ? $chat_bot_options['message_placeholder'] : 'Ctrl+Enter to send request'; ?>
                                            <input type="text" id="s2baia_chatbot_config_message_placeholder" 
                                                   name="s2baia_chatbot_config_message_placeholder" 
                                                   
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
                                                <label for="s2baia_chatbot_config_chatbot_name"><?php esc_html_e('Chatbot Name', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $chatbot_name = isset($chat_bot_options['chatbot_name']) ? $chat_bot_options['chatbot_name'] : 'GPT Assistant'; ?>
                                                    <input type="text" id="s2baia_chatbot_config_chatbot_name" 
                                                           name="s2baia_chatbot_config_chatbot_name" 
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
                                                <label for="s2baia_chatbot_config_compliance_text"><?php esc_html_e('Compliance Text', 's2b-ai-aiassistant'); ?>:</label>
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
                                                    <input type="text" id="s2baia_chatbot_config_compliance_text" 
                                                           name="s2baia_chatbot_config_compliance_text" 
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
                                    <h3><?php esc_html_e('Default Chatbot behavior', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_config_access_for_guests">
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
                                            
                                            <input type="checkbox" id="s2baia_chatbot_access_for_guests" 
                                                   name="s2baia_chatbot_access_for_guests" 
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
                                                <label for="s2baia_chatbot_config_context"><?php esc_html_e('Context', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $context = isset($chat_bot_options['context']) ? $chat_bot_options['context'] : ''; ?>
                                                    <textarea id="s2baia_chatbot_config_context" 
                                                              name="s2baia_chatbot_config_context"><?php echo esc_html($context); ?></textarea>
                                                    
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('The text that you will write in the Context field will be added to the beginning of the prompt. Note, in case you want to use the default message, you will need to leave the field blank.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                <?php 
                                if(false){
                                ?>
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_config_greeting_message"><?php esc_html_e('Greeting message', 's2b-ai-aiassistant'); ?>:</label>
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
                                           <input type="checkbox" id="s2baia_chatbot_config_greeting_message" 
                                                  name="s2baia_chatbot_config_greeting_message" 
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
                                        <label for="s2baia_chatbot_config_greeting_message_text">
                                            <?php esc_html_e('Greetin message text', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php 
                                            $greeting_message_text = isset($chat_bot_options['greeting_message_text'])?$chat_bot_options['greeting_message_text']:''; 
                                              ?>
                                           <input type="text" name="s2baia_chatbot_config_greeting_message_text" 
                                                  id="s2baia_chatbot_config_greeting_message_text" 
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
                                                <label for="s2baia_chatbot_config_language"><?php esc_html_e('Language', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    <?php $language = isset($chat_bot_options['language']) ? $chat_bot_options['language'] : 'english'; ?>
                                                    <input type="text" id="s2baia_chatbot_config_language" 
                                                           name="s2baia_chatbot_config_language" 
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
                                                <label for="s2baia_chatbot_config_chat_model"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position:relative;">
                                                    <select id="s2baia_chatbot_config_chat_model" name="s2baia_chatbot_config_chat_model">
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
                                        <label for="s2baia_chatbot_config_chat_temperature">
                                            <?php esc_html_e('Temperature', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_temperature = isset($chat_bot_options['chat_temperature'])?$chat_bot_options['chat_temperature']:0.8; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_chat_temperature"  
                                                   id="s2baia_chatbot_config_chat_temperature" type="number" 
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
                                        <label for="s2baia_chatbot_config_chat_top_p">
                                            <?php esc_html_e('Top P', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chat_top_p = isset($chat_bot_options['chat_top_p'])?$chat_bot_options['chat_top_p']:1; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_chat_top_p"  
                                                   id="s2baia_chatbot_config_chat_top_p" type="number" 
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
                                        <label for="s2baia_chatbot_config_max_tokens">
                                            <?php esc_html_e('Maximum tokens', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $max_tokens = isset($chat_bot_options['max_tokens'])?$chat_bot_options['max_tokens']:2048; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_max_tokens"  
                                                   id="s2baia_chatbot_config_max_tokens" type="number" 
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
                                        <label for="s2baia_chatbot_config_frequency_penalty">
                                            <?php esc_html_e('Frequency penalty', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $frequency_penalty = isset($chat_bot_options['frequency_penalty'])?$chat_bot_options['frequency_penalty']:0; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_frequency_penalty"  
                                                   id="s2baia_chatbot_config_frequency_penalty" type="number" 
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
                                        <label for="s2baia_chatbot_config_presence_penalty">
                                            <?php esc_html_e('Presence penalty', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $presence_penalty = isset($chat_bot_options['presence_penalty'])?$chat_bot_options['presence_penalty']:0; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_presence_penalty"  
                                                   id="s2baia_chatbot_config_presence_penalty" type="number" 
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
            </form>
        </div>

    </div>
</div>

<script>
    let s2baia_message_config_general_error = '<?php esc_html_e('There were errors during store configuration.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_general_succes1 = '<?php esc_html_e('Configuration stored successfully.', 's2b-ai-aiassistant'); ?>';

</script>
<?php
}
if(false){
?>

<div id="s2baia-tabs-1" class="s2baia_tab_panel" data-s2baia="1">
<h2>Tab 1</h2>
</div>

<?php
}
?>