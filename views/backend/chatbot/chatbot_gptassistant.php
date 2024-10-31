<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//var_dump($current_assistant);
$bot_options = [];
//$uploaded_f = '';
$assistant_opts = '';
$chatbot_hash = '';
$err_msg = '';
if(is_object($current_assistant) && isset($current_assistant->bot_options) && is_array($current_assistant->bot_options)){
    $bot_options = $current_assistant->bot_options;
    //var_dump($bot_options['error_msg']);
    if(isset($bot_options['error_msg'])){
        $err_msg = $bot_options['error_msg'];
    }

}
if(is_object($current_assistant) && isset($current_assistant->hash_code) && strlen($current_assistant->hash_code) > 0){
    $chatbot_hash = sanitize_text_field($current_assistant->hash_code);
}

$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_assistant_nonce');

$uploaded_default_file = ['code'=>0,'error_msg'=>'','id'=>'','filename' =>''];

$file_id = isset($bot_options['assistant_file_id'])?$bot_options['assistant_file_id']:'';
$file_path = isset($bot_options['assistant_file_path'])?$bot_options['assistant_file_path']:'';
$models = S2bAia_ChatBotUtils::getModels();
$default_provider = get_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider');
$chat_bot_providers = S2bAia_ChatBotUtils::getProviders();


$assistant_opts_default = S2bAia_ChatBotUtils::getDefaultAssistant();


$assistant_id = isset($bot_options['assistant_id']) && strlen($bot_options['assistant_id']) > 0?$bot_options['assistant_id']:'';

$instruction = isset($bot_options['instruction']) && strlen($bot_options['instruction']) > 0?$bot_options['instruction']:'';
$model = isset($bot_options['model']) && strlen($bot_options['model']) > 0?$bot_options['model']:'';
$assistant_name = isset($bot_options['name']) && strlen($bot_options['name']) > 0?$bot_options['name']:'';
$assistant_timeout = isset($bot_options['assistant_timeout'])?(int)$bot_options['assistant_timeout']:8;
?>
<div id="s2baia-tabs-4" class="s2baia_tab_panel" data-s2baia="4">
<div class="inside">
    <div class="s2baia_config_items_wrapper">
        <?php
                                    //var_dump($bot_options);
                                    if(strlen($err_msg) > 0){
                                    ?>
                                    <h4  style='color:red;text-align: center;'><?php echo 'ERROR:'.esc_html($err_msg); ?></h4>
                                    <?php
                                    }
                                    ?>
                                    <h4 class="s2baia_instruction" style="text-align: center;">
                                        <?php esc_html_e('Read this ', 's2b-ai-aiassistant');  ?>
                                        <a href="https://soft2business.com/how-to-create-content-aware-chat-bot/" target="blank"  class="s2baia_instruction"><?php echo esc_html__('article', 's2b-ai-aiassistant'); ?></a>
                                        <?php esc_html_e(' about how to configure AI Assistant chat bot.', 's2b-ai-aiassistant');  ?>
                                    </h4>
                                    <h4 class="s2baia_instruction" style="text-align: center;">
                                        <?php esc_html_e('If you want to change style, appearance and other parameters, please do this in General and Styles tabs. ', 's2b-ai-aiassistant');  ?>
                                        
                                    </h4>
                
                
                <?php
                $adm_url = admin_url( 'admin-post.php' );
                ?>
                <form action="<?php echo esc_url($adm_url); ?>" method="post" enctype="multipart/form-data" id="s2baia_chatbot_upload_form"> 
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_assistant_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash" value="<?php echo esc_html($chatbot_hash); ?>"/>
                <input type="hidden" name="action" value="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>store_chatbot_upload"/>
                    
                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Step 1. Upload file', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_chatbot_config_position"><?php esc_html_e('Uploaded file', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <?php
                                            if(strlen($file_id) > 0){
                                            ?>
                                            <p id="s2baia_uploaded_file"><?php echo esc_html($file_id); ?></p>
                                            <p id="s2baia_uploaded_filepath"><?php echo esc_html($file_path); ?></p>
                                            <?php
                                            if(strlen($assistant_id) > 0){
                                            ?>
                                            <p class="s2baia_instruction" ><?php echo esc_html__('If you want to remove or change file then first you need to remove assistant', 's2b-ai-aiassistant'); ?></p>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            }else{
                                            ?>
                                            
                                            <input type="file" id="s2baia_chatbot_config_database" 
                                                   name="s2baia_chatbot_config_database"  />
                                            <?php
                                            }
                                            ?>
                                            
                                            <p class="s2baia_instruction" ><?php echo esc_html__('File types that are supported by Assistant API are listed ', 's2b-ai-aiassistant'); ?>
                                                <a href="https://platform.openai.com/docs/assistants/tools/file-search/supported-files" target="blank"  class="s2baia_instruction"><?php echo esc_html__('here', 's2b-ai-aiassistant'); ?></a> 
                                                or 
                                                <a href="https://soft2business.com/how-to-create-content-aware-chat-bot/" target="blank"  class="s2baia_instruction"><?php echo esc_html__('here', 's2b-ai-aiassistant'); ?></a>
                                                
                                                
                                            </p>
                                        </div>
                                        <?php
                                        if(strlen($file_id) <= 0){
                                        ?>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e(' Select file as database for assistant.', 's2b-ai-aiassistant'); ?> 
                                            </span>
                                        </p>
                                        <p class="s2baia_input_description">
                                            <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" 
                                       name="s2baia_submit" 
                                       id="s2baia_submit" class="button button-primary button-large" 
                                       " >
                                        </p>
                                        <?php
                                        }elseif(strlen($assistant_id) == 0){
                                        ?>
                                        <p class="s2baia_input_description">
                                                    <input type="button" class="button button-danger button-large" value="<?php echo esc_html__('Remove', 's2b-ai-aiassistant') ?>" 
                                                        name="s2baia_remove" 
                                                        id="s2baia_remove" class="button button-primary button-large" 
                                                        onclick="s2baia_removeFile(event);"
                                                        " >
                                        </p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>                 
        <?php
        //var_dump($file_id);
        if(strlen($file_id) > 0){
        
        ?>
        
        <form action="<?php echo esc_url($adm_url); ?>" method="post" enctype="multipart/form-data" id="s2baia_assistant_manage_form"> 
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_assistant_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>chatbot_hash" value="<?php echo esc_html($chatbot_hash); ?>"/>
                <input type="hidden" id="s2baia_assistant_manage_action" name="action" value="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>create_assistant"/>
                    
                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <?php
                                    //var_dump($bot_options);
                                    if(strlen($assistant_id) > 0){
                                    ?>
                                    
                                    <h3><?php esc_html_e('Step 2. Update Assistant', 's2b-ai-aiassistant'); ?></h3>
                                    <?php
                                    }else{
                                    ?>
                                        <h3><?php esc_html_e('Step 2. Create Assistant', 's2b-ai-aiassistant'); ?></h3>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                                if(strlen($assistant_id) > 1){
                                                ?>
                                                <p class="s2baia_input_description">
                                                    <input type="submit" class="button button-danger button-large" value="<?php echo esc_html__('Remove', 's2b-ai-aiassistant') ?>" 
                                                        name="s2baia_submit" 
                                                        id="s2baia_submit" class="button button-primary button-large" 
                                                        onclick="s2baia_removeAssistant(event);"
                                                        " >
                                                </p>
                                                <?php
                                                }
                                                ?>
                                <div class="s2baia_block_content">
                                    
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_assistant_id"><?php esc_html_e('Assistant ID', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    
                                                    
                                                
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php 
                                                        if(strlen($assistant_id) > 0){
                                                            echo esc_html($assistant_id); 
                                                        }else{
                                                            echo '<span class="s2baia_instruction">'. esc_html__('Assistant is not created yet','s2b-ai-aiassistant').'</span>';
                                                        }
                                                        ?>
                                                    </span>
                                                </p>
                                                </div>
                                                
                                            </div>
                                </div>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_chatbot_config_chat_model2"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position:relative;">
                                                    <select id="s2baia_chatbot_config_chat_model2" name="s2baia_chatbot_config_chat_model2">
                                                        <?php
                                                        
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
                                                <p class="s2baia_instruction">
                                                    <span style="display: inline;"><?php esc_html_e('Select model. You can add more models into list visiting ', 's2b-ai-aiassistant'); ?> <a class="s2baia_instruction" href="<?php echo esc_url(admin_url()) . 'admin.php?page=s2baia_settings'; ?>" target="blank">page</a>. Then select Models tab and check edit checkbox  near selected model.</span>
                                                </p>
                                            </div>
                                </div>
                                
                                <?php
                                $disabled = '';
                                if( strlen($assistant_id) > 0){
                                    $disabled = 'disabled="disabled"';
                                }
                                
                                ?>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_assistant_name"><?php esc_html_e('Assistant name', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    
                                                    <input type="text"  name="s2baia_assistant_name"   id="s2baia_assistant_name"  value="<?php echo esc_html($assistant_name); ?>" <?php echo esc_html($disabled); ?> >
                                                    
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Enter name of assistant.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                <label for="s2baia_assistant_instructions"><?php esc_html_e('Instructions', 's2b-ai-aiassistant'); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position: relative;">
                                                    
                                                    <input type="text"  name="s2baia_assistant_instructions"   id="s2baia_assistant_instructions"  value="<?php echo esc_html($instruction); ?>">
                                                    
                                                </div>
                                                <p class="s2baia_input_description">
                                                    <span style="display: inline;">
                                                        <?php esc_html_e('Enter instructions.', 's2b-ai-aiassistant'); ?>
                                                    </span>
                                                </p>
                                            </div>
                                </div>
                                
                                
                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_">
                                            <?php esc_html_e('Timeout', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            
                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_assistant_timeout"  
                                                   id="s2baia_assistant_timeout" type="number" 
                                                   step="1" min="1" max="1000" maxlength="4" autocomplete="off"  
                                                   value="<?php echo (int)$assistant_timeout; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Timeout in seconds is time which bot will wait for generating answer by  Assistant. Its value depends on many factors including your internet connection. Try to play with this value to find best results. Minimal value = 1', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                        <p class="s2baia_input_description">
                                            <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" 
                                                name="s2baia_submit" 
                                                id="s2baia_submit" class="button button-primary button-large" 
                                                " >
                                        </p>
                                    </div>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                </form>
    <?php
    
        }
        
    ?>    
        <?php
        //var_dump($file_id);
        if(strlen($file_id) > 0 && strlen($assistant_id) > 0){//show selection if only assistant is created and file is uploaded
        
        ?>

                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Step 3. Put shortcode ', 's2b-ai-aiassistant'); ?> [s2baia_chatbot bot_id=assistant] <?php esc_html_e(' to any page, to display Assistant.', 's2b-ai-aiassistant'); ?> </h3>
                                </div>
                                <div class="s2baia_block_content">
                                            <div class="s2baia_row_header">
                                                
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position:relative;">
                                                    
                                                </div>
                                                
                                                
                                            </div>
                                </div>
                                
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
                

        <?php
    
        }else{
            update_option(S2BAIA_PREFIX_LOW . 'chat_bot_provider', 'default');//if assistant is not created then set default provider
        }
        
        ?>  
    </div>
</div>
</div>
<script>
    //onclick="s2baiaSaveChatbotAssistantUploadFile(event);
    function s2baiaSaveChatbotAssistantUploadFile(e){
        e.preventDefault;
        //store_chatbot_assistant_upload
        document.getElementById("s2baia_chatbot_upload_form").submit();//s2baia_chatbot_upload_form
   

    }
    
    function s2baia_removeAssistant(e){
        e.preventDefault;
        let form = document.querySelector('#s2baia_assistant_manage_form');
        let action = document.querySelector('#s2baia_assistant_manage_action');
        action.value = 's2b_remove_assistant';
        form.submit();
    }
    
    function s2baia_removeFile(e){
        e.preventDefault;
        let form = document.querySelector('#s2baia_assistant_manage_form');
        let action = document.querySelector('#s2baia_assistant_manage_action');
        action.value = 's2b_remove_file';
        form.submit();
    }
</script>
