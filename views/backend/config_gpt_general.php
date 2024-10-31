<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'config_nonce');
$menu_page = S2BAIA_PREFIX_LOW . 'settings';
$p_types = get_post_types();
$stored_selected_p_types = get_option(S2BAIA_PREFIX_LOW . 'selected_types');

if(is_string($stored_selected_p_types)){
	$stored_selected_p_types_arr = unserialize($stored_selected_p_types);
	if(is_array($stored_selected_p_types_arr)){
		$selected_p_types = array_map('wp_kses', $stored_selected_p_types_arr, []);
	}
	else{
		$selected_p_types = [];
	}
}else{
	$selected_p_types = [];
}
if ($stored_selected_p_types == FALSE) {
    $selected_p_types = ['post', 'page'];
}

$response_timeout = (int) get_option(S2BAIA_PREFIX_LOW . 'response_timeout', 120);
$max_tokens = (int) get_option(S2BAIA_PREFIX_LOW . 'max_tokens', 1024);
$count_of_instructions = (int) get_option(S2BAIA_PREFIX_LOW . 'count_of_instructions', 10);
$models = [];
?>
<div id="s2baia-tabs-1" class="s2baia_tab_panel" data-s2baia="1">
    <div class="inside">
        <div class="s2baia_config_items_wrapper">
            <form action="" method="post" id="s2baia_gen_form">    
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>config_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="action" value="s2b_store_general_tab"/>
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">

                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" name="s2baia_submit" id="s2baia_submit" class="button button-primary button-large" onclick="s2baiaSaveGeneral(event);" >

                            </div>

                            <div class="s2baia-custom-loader s2baia-general-loader" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('ChatGPT general', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_open_ai_gpt_key"><?php esc_html_e('Open AI Key', 's2b-ai-aiassistant'); ?>:</label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <input type="text"  name="s2baia_open_ai_gpt_key"   id="s2baia_open_ai_gpt_key"  value="<?php echo esc_html($s2baia_open_ai_gpt_key); ?>">
                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('You can get your API Keys in your', 's2b-ai-aiassistant'); ?> <a href="https://beta.openai.com/account/api-keys" target="_blank"><?php esc_html_e('OpenAI Account', 's2b-ai-aiassistant'); ?></a>.
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_response_timeout">
                                            <?php esc_html_e('Response Timeout (sec)', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_response_timeout"  id="s2baia_response_timeout" type="number" 
                                                   step="1"  max="200" maxlength="3" autocomplete="off"  
                                                   placeholder="<?php esc_html_e('Response Timeout', 's2b-ai-aiassistant'); ?>" value="<?php echo (int)$response_timeout; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Make this value higher for bad internet connection.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_response_timeout">
                                            <?php esc_html_e('Default request text length (tokens)', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_max_tokens"  id="s2baia_max_tokens" type="number" 
                                                   step="1" maxlength="4" autocomplete="off"  
                                                   placeholder="<?php esc_html_e('Max tokens', 's2b-ai-aiassistant'); ?>" value="<?php echo esc_html($max_tokens); ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Make this value higher for larger text.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="s2baia_block_content" >
                                    <div class="s2baia_row_header">
                                        <label for="s2baia_response_timeout">
                                            <?php esc_html_e('Count of instructions per portion', 's2b-ai-aiassistant'); ?>:
                                        </label>
                                    </div>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <input class="s2baia_input s2baia_20pc"  name="s2baia_count_of_instructions"  
                                                   id="s2baia_count_of_instructions" type="number" 
                                                   step="1" min="5" maxlength="3" autocomplete="off"  
                                                   placeholder="<?php esc_html_e('Max tokens', 's2b-ai-aiassistant'); ?>" value="<?php echo (int)$count_of_instructions; ?>">

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Count of instruction displayed in correction tab in meta box.', 's2b-ai-aiassistant'); ?>"
                                            </span>
                                        </p>
                                    </div>

                                </div>


                            </div>
                        </div>   
                       <?php if (current_user_can('manage_options')) {

                           $s2baia_user_roles = S2bAia_Utils::getInstructionRoles();
                           
                           ?> 
                        <div class="s2baia_block ">
                    <div style="position:relative;">
                        <div class="s2baia_block_header">
                            <h3><?php esc_html_e('User roles', 's2b-ai-aiassistant'); ?>:</h3>
                        </div>

                        <div class="s2baia_block_content" >

                            <div  class="s2baia_row_content ">
                                <div  class="s2baia_block_header">
                                    <h4><?php esc_html_e('Select minimal user roles you want to allow access to different functions of plugin.', 's2b-ai-aiassistant'); ?></h4>
                                </div> 
                                

                            </div>
                        </div>
                        <div class="s2baia_block_content" >
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <select id="s2baia_config_delete_instructions" name="s2baia_config_delete_instructions">
                                            <?php
                                            $s2baia_config_delete_instructions = get_option(S2BAIA_PREFIX_LOW . 'config_delete_instructions', 'administrator');

                                            foreach($s2baia_user_roles as $u_role){
                                                if($s2baia_config_delete_instructions == $u_role){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($u_role); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($u_role); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select lowest user role which will be available to delete instructions and full access to configure chatbots. For example if you select author then such roles as administrator and editor will have access to delete instructions.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                        </div>
                        <div class="s2baia_block_content" >
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <select id="s2baia_config_edit_instructions" name="s2baia_config_edit_instructions">
                                            <?php
                                            $s2baia_config_edit_instructions = get_option(S2BAIA_PREFIX_LOW . 'config_edit_instructions', 'editor');

                                            foreach($s2baia_user_roles as $u_role){
                                                if($s2baia_config_edit_instructions == $u_role){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($u_role); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($u_role); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select lowest user role which has access to plugin configurations. For example if you select author then such roles as administrator and editor will have access to plugin configurations.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                            <span style="display: inline;" class="s2baia_instruction">
                                                <?php esc_html_e('This role is the lowest one that allows access to Image generation and configure chatbot functions!!!', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                        </div>
                        <div class="s2baia_block_content" >
                            <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">

                                            <select id="s2baia_config_meta_instructions" name="s2baia_config_meta_instructions">
                                            <?php
                                            $s2baia_config_meta_instructions = get_option(S2BAIA_PREFIX_LOW . 'config_meta_instructions', 'editor');
                                            foreach($s2baia_user_roles as $u_role){
                                                if($s2baia_config_meta_instructions == $u_role){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($u_role); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($u_role); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>

                                        </div>
                                        <p class="s2baia_input_description">
                                            <span style="display: inline;">
                                                <?php esc_html_e('Select lowest user role which has access to plugin metabox. For example if you select author then such roles as administrator and editor will have access to plugin metabox.', 's2b-ai-aiassistant'); ?>
                                            </span>
                                        </p>
                                    </div>
                            
                            
                            </div>
                    </div>
                </div> 
                <?php }  ?>        
                    </div>
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Post Types', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                <div class="s2baia_block_content" >

                                    <div  class="s2baia_row_content ">
                                        <div  class="s2baia_block_header">
                                            <h4><?php esc_html_e('Select post type where you want to display AI assistant meta box.', 's2b-ai-aiassistant'); ?></h4>
                                        </div> 
                                        <div class="s2baia_data_column_container s2baia_pl20">
                                            <?php
                                            foreach ($p_types as $p_t) {
                                                //var_dump($p_t);
                                                $checked = '';
                                                if (in_array($p_t, $selected_p_types)) {
                                                    $checked = ' checked ';
                                                }
                                                ?>
                                                <div class="s2baia_c_opt">
                                                    <input type="checkbox" id="<?php echo esc_html($p_t); ?>" name="<?php echo "s2baia_ptypes[" . esc_html($p_t); ?>]" <?php echo esc_html($checked); ?>  >
                                                    <label for="<?php echo esc_html($p_t); ?>"><?php echo esc_html($p_t); ?></label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>

                </div>

            </form>
        </div>

    </div>
</div>

<script>
    let s2baia_message_config_general_error = '<?php esc_html_e('There were errors during store configuration.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_general_succes1 = '<?php esc_html_e('Configuration stored successfully.', 's2b-ai-aiassistant'); ?>';

</script>
