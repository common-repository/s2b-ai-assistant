<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$models_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'models_nonce');
$models = unserialize(get_option('s2baia_chatgpt_models', ''));
$expert_models = unserialize(get_option('s2baia_chatgpt_expert_models', ''));
$cnt_em = is_array($expert_models) ? count($expert_models) : 0;
$cnt_m = is_array($models) ? count($models) : 0;
if (!is_array($expert_models) || $cnt_em < $cnt_m) {
    update_option('s2baia_chatgpt_expert_models', serialize($models));
    $expert_models = unserialize(get_option('s2baia_chatgpt_expert_models', ''));
}
?>
<div id="s2baia-tabs-2" class="s2baia_tab_panel" data-s2baia="2">
    <div class="inside">
        <div class="s2baia_config_items_wrapper">
            <form action="" method="post" id="s2baia_models_form">  
                <input type="hidden" name="action" value="s2b_store_models_tab"/>
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>models_nonce" value="<?php echo esc_html($models_nonce); ?>"/>
                <div class="s2baia_block_content" >

                    <div  class="s2baia_row_content s2baia_pr">
                        <div  style="position:relative;">
                        </div>
                        <div class="s2baia_bloader">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" name="s2baia_submit_models" id="s2baia_submit_models" class="button button-primary button-large" onclick="s2baiaSaveModels(event);" >

                                <button 
                                    name="s2baia_refresh_models" 
                                    id="s2baia_refresh_models" class="button button-primary button-large" onclick="s2baiaGetModels(event);" >
                                        <?php echo esc_html__('Refresh from OpenAI', 's2b-ai-aiassistant') ?>
                                </button>
                            </div>

                            <div class="s2baia-custom-loader s2baia-models-loader"></div>
                        </div>
                    </div>
                </div>  


                <div class="s2baia_block ">
                    <div style="position:relative;">
                        <div class="s2baia_block_header">
                            <h3><?php esc_html_e('Models', 's2b-ai-aiassistant'); ?>:</h3>
                        </div>

                        <div class="s2baia_block_content" >

                            <div  class="s2baia_row_content ">
                                <div  class="s2baia_block_header">
                                    <h4><?php esc_html_e('Select models which you want to be in models selection box in AI assistant. Models marked by * - are not type of text. Check edit to show model in correct tab of meta box or expert on expert tab.', 's2b-ai-aiassistant'); ?></h4>
                                </div> 
                                <div class="s2baia_data_column_container s2baia_pl20" id="s2baia_models_container">
                                    <?php
                                    foreach ($models as $mdl => $mdlstatus) {
                                        $striped_mdl = wp_kses($mdl, []);
                                        $checked = '';
                                        if ($mdlstatus == 1 || $mdlstatus == 3) {
                                            $checked = ' checked ';
                                        }
                                        $echo_lbl = $striped_mdl;
                                        if ($mdlstatus < 2) {
                                            $echo_lbl = $striped_mdl . ' *';
                                        }
                                        if ($cnt_em) {
                                            $mdlstatus2 = $expert_models[$striped_mdl];
                                            $checked2 = '';
                                            if ($mdlstatus2 == 1 || $mdlstatus2 == 3) {
                                                $checked2 = ' checked ';
                                            }
                                            $echo_lbl2 = '';
                                            if ($mdlstatus2 < 2) {
                                                $echo_lbl2 = ' *';
                                            }
                                        }
                                        ?>
                                        <div class="s2baia_c_opt">
                                            <label><?php echo esc_html($echo_lbl); ?></label>
                                            <input type="checkbox" id="<?php echo esc_html($striped_mdl); ?>" name="<?php echo esc_html(S2BAIA_PREFIX_LOW) . "models[" . esc_html($striped_mdl) . "]"; ?>" <?php echo esc_html($checked); ?>  >
                                            <label for="<?php echo esc_html($striped_mdl); ?>"><?php echo esc_html__('edit', 's2b-ai-aiassistant'); ?></label>
                                            <?php if ($cnt_em > 0) { ?>
                                                <input type="checkbox" id="<?php echo "expert" . esc_html($striped_mdl); ?>" name="<?php echo esc_html(S2BAIA_PREFIX_LOW) . "expert_models[" . esc_html($striped_mdl) . "]"; ?>" <?php echo esc_html($checked2); ?>  >
                                                <label for="<?php echo "expert" . esc_html($striped_mdl); ?>"><?php echo esc_html__('expert', 's2b-ai-aiassistant') . esc_html($echo_lbl2); ?></label>
                                            <?php } ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
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
    let s2baia_message_config_models_error1 = '<?php esc_html_e('There were errors during store models.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_models_succes1 = '<?php esc_html_e('Models stored successfully.', 's2b-ai-aiassistant'); ?>';

    let s2baia_message_config_models_error2 = '<?php esc_html_e('There were errors during update models.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_models_succes2 = '<?php esc_html_e('Models updated successfully.', 's2b-ai-aiassistant'); ?>';

</script>


