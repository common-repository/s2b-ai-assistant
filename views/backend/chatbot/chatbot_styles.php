<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_styles_nonce');

?>
<div id="s2baia-tabs-2" class="s2baia_tab_panel" data-s2baia="2">
<div class="inside">
        <div class="s2baia_config_items_wrapper">
            <?php
            //var_dump($default_chat_bot);
if(true){
?>
            <form action="" method="post" id="s2baia_styles_form">    
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_styles_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash" value="<?php echo esc_html($chatbot_hash); ?>"/>
                <input type="hidden" name="action" value="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>store_chatbot_styles_tab"/>
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">

                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" name="s2baia_submit" id="s2baia_submit" class="button button-primary button-large" onclick="s2baiaSaveChatbotStyles(event);" >

                            </div>

                            <div class="s2baia-custom-loader s2baia-general-loader" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <h3 class="s2baia_instruction" style="text-align: center;">
                                                    <?php echo esc_html__('These options are used for default chatbot', 's2b-ai-aiassistant'); ?>  <?php echo esc_html__('which will be used for creating new chatbots. Go to Chatbots tab to create new.', 's2b-ai-aiassistant'); ?> 
                </h3>
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
                                        <label for="s2baia_chatbot_config_header_text_color"><?php esc_html_e('Header Text Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $header_text_color = isset($chat_bot_options['header_text_color'])?esc_html($chat_bot_options['header_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_header_text_color" 
                                                   id="s2baia_chatbot_config_header_text_color" 
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
                                        <label for="s2baia_chatbot_config_header_color"><?php esc_html_e('Header Background Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $header_color = isset($chat_bot_options['header_color'])?esc_html($chat_bot_options['header_color']):'#0C476E';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_header_color" 
                                                   id="s2baia_chatbot_config_header_color" 
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
                                                   name="s2baia_chatbot_config_send_button_color" 
                                                   id="s2baia_chatbot_config_send_button_color" 
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
                                                   name="s2baia_chatbot_config_send_button_hover_color" 
                                                   id="s2baia_chatbot_config_send_button_hover_color" 
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
                                        <label for="s2baia_chatbot_send_button_text_color"><?php esc_html_e('Send Button Text Color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $send_button_text_color = isset($chat_bot_options['send_button_text_color'])?esc_html($chat_bot_options['send_button_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_send_button_text_color" 
                                                   id="s2baia_chatbot_config_send_button_text_color" 
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
                                        <label for="s2baia_chatbot_message_bg_color"><?php esc_html_e('Message background color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $message_bg_color = isset($chat_bot_options['message_bg_color'])?esc_html($chat_bot_options['message_bg_color']):'#1476B8';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_message_bg_color" 
                                                   id="s2baia_chatbot_config_message_bg_color" 
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
                                        <label for="s2baia_chatbot_message_text_color"><?php esc_html_e('Message text color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $message_text_color = isset($chat_bot_options['message_text_color'])?esc_html($chat_bot_options['message_text_color']):'#ffffff';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_message_text_color" 
                                                   id="s2baia_chatbot_config_message_text_color" 
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
                                        <label for="s2baia_chatbot_response_bg_color"><?php esc_html_e('Message responce color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_bg_color = isset($chat_bot_options['response_bg_color'])?esc_html($chat_bot_options['response_bg_color']):'#5AB2ED';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_response_bg_color" 
                                                   id="s2baia_chatbot_config_response_bg_color" 
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
                                        <label for="s2baia_chatbot_response_text_color"><?php esc_html_e('Responce text color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_text_color = isset($chat_bot_options['response_text_color'])?esc_html($chat_bot_options['response_text_color']):'#000000';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_response_text_color" 
                                                   id="s2baia_chatbot_config_response_text_color" 
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
                                        <label for="s2baia_chatbot_response_text_color"><?php esc_html_e('Responce icon color', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            $response_icons_color = isset($chat_bot_options['response_icons_color'])?esc_html($chat_bot_options['response_icons_color']):'#000000';

                                            ?>
                                            <input type="color" 
                                                   name="s2baia_chatbot_config_response_icons_color" 
                                                   id="s2baia_chatbot_config_response_icons_color" 
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
                                        <label for="s2baia_chatbot_config_message_font_size">
                                            <?php esc_html_e('Message font size', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_font_size = isset($chat_bot_options['message_font_size'])?(int)$chat_bot_options['message_font_size']:16; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_message_font_size"  
                                                   id="s2baia_chatbot_config_message_font_size" type="number" 
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
                                        <label for="s2baia_chatbot_config_message_margin">
                                            <?php esc_html_e('Message margin', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_margin = isset($chat_bot_options['message_margin'])?(int)$chat_bot_options['message_margin']:7; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_message_margin"  
                                                   id="s2baia_chatbot_config_message_margin" type="number" 
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
                                        <label for="s2baia_chatbot_config_message_border_radius">
                                            <?php esc_html_e('Message border radius', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $message_border_radius = isset($chat_bot_options['message_border_radius'])?(int)$chat_bot_options['message_border_radius']:10; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_message_border_radius"  
                                                   id="s2baia_chatbot_config_message_border_radius" type="number" 
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
                                        <label for="s2baia_chatbot_config_chatbot_border_radius">
                                            <?php esc_html_e('Chatbot widget border radius', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php $chatbot_border_radius = isset($chat_bot_options['chatbot_border_radius'])?(int)$chat_bot_options['chatbot_border_radius']:10; ?>
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_chatbot_config_chatbot_border_radius"  
                                                   id="s2baia_chatbot_config_chatbot_border_radius" type="number" 
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
                    
                    
                        </div> 
                
                    </div>
                 </form>   

                </div>
<?php
}
?>
            
        </div>
</div>
<script>
    let s2baia_message_config_styles_error = '<?php esc_html_e('There were errors during store configuration.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_styles_succes1 = '<?php esc_html_e('Configuration stored successfully.', 's2b-ai-aiassistant'); ?>';

</script>