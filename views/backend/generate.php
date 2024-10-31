<?php
if ( ! defined( 'ABSPATH' ) ) exit;

?>
<div id="s2baia-tabs-3" class="s2baia_tab_panel"  data-s2baia="2">
    <h3><?php esc_html_e('Expert', 's2b-ai-aiassistant'); ?></h3>
    <div class="s2baia_form_row">
<?php
?>

<div class="s2baiawrap">     
            
        <table  class="s2baia_edit_tbl">
            <tbody >
                <tr>
                    <td id="s2baia_model_td_expert" class="s2baia_td" style="">
                        <label for="s2baia_model" class="s2baia_lbl s2baia_block"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?></label>
                        <select id="s2baia_model_e" name="s2baia_model_e" class="s2baia_selection">
<?php
foreach ($expert_models as $ei => $em) {
    ?>
                                <option value="<?php echo wp_kses($em, S2bAia_Utils::getInstructionAllowedTags()); ?>"><?php echo wp_kses($em, S2bAia_Utils::getInstructionAllowedTags()); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_temperature_e" class="s2baia_lbl s2baia_block "><?php esc_html_e('Temperature', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_temperature_e" name="s2baia_temperature_e" type="number" step="0.1" min="0" max="2" maxlength="3" autocomplete="off"  placeholder="temperature" value="0.8">

                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_max_tokens_e" class="s2baia_lbl s2baia_block"><?php esc_html_e('Maximum length', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_max_tokens_e" name="s2baia_max_tokens_e" type="number" step="1"  value="<?php echo (int)$mx_tokens; ?>" maxlength="3" autocomplete="off"  >

                    </td>

                </tr>
</tbody>
        </table>
    </div>
<div class="s2baiawrap">     
            <table  class="s2baia_edit_tbl">
            <tbody>   
                <tr>
                    <td id="s2baia_model_td_expert" class="s2baia_td" style="">
                        <label for="s2baia_top_p_edit" class="s2baia_lbl s2baia_block"><?php esc_html_e('Top P', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_top_p_edit" name="s2baia_top_p_edit" type="number" step="0.1" min="0" max="1" maxlength="3" autocomplete="off"  placeholder="temperature" value="1">

                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_presence_penalty_e" class="s2baia_lbl s2baia_block"><?php esc_html_e('Presence penalty (from -2 to 2)', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_presence_penalty_e" name="s2baia_presence_penalty_e" type="number" step="0.1" min="-2" max="2" maxlength="3" autocomplete="off"  placeholder="temperature" value="0">

                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_frequency_penalty_e" class="s2baia_lbl s2baia_block"><?php esc_html_e('Frequency penalty (from -2 to 2)', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_frequency_penalty_e" name="s2baia_frequency_penalty_e" type="number" step="0.1" min="-2" max="2" maxlength="3" autocomplete="off"  placeholder="temperature" value="0">
                    </td>

                </tr> 
                </tbody>
        </table>
    </div>
<div class="s2baiawrap">     
            <table class="s2baia_edit_tbl">
            <tbody>    
                <tr>
                    <td colspan="3" class="">
                        <label for="s2baia_system_e" class="s2baia_lbl"><?php esc_html_e('System', 's2b-ai-aiassistant'); ?></label>

                        <textarea id="s2baia_system_e" name="s2baia_system_e" rows="2" cols="25"  class=""></textarea>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
            <div class="s2baiawrap">     
            <table id="s2baia_expert_tbl"  class="s2baia_edit_tbl">
            <tbody id="s2baia_expert_tbody">    
                <tr class="s2baia_field">
                    <td colspan="3" class="">
                        <div class="s2baia_halfscreen">
                            <input type="radio" id="s2baia_actor_ae_1" name="s2baia_actor[]" value="<?php esc_html_e('Assistant', 's2b-ai-aiassistant'); ?>"
                                   onchange="s2baiaRadioChange(1, 'ae');" >
                            <label for="s2baia_message_e_1"><?php esc_html_e('Assistant', 's2b-ai-aiassistant'); ?></label>
                        </div>
                        <div class="s2baia_halfscreen">
                            <input type="radio" id="s2baia_actor_ue_1" name="s2baia_actor[]" value="<?php esc_html_e('User', 's2b-ai-aiassistant'); ?>"
                                   checked onchange="s2baiaRadioChange(1, 'ue');">
                            <label for="s2baia_message_ue_1"><?php esc_html_e('User', 's2b-ai-aiassistant'); ?></label>
                        </div>

                        <div class="s2baia_2actor">
                            <textarea id="s2baia_message_e_1" name="s2baia_message_e_1" rows="2" cols="55"  class=""></textarea>
                        </div>
                        <div class="s2baia_actor">
                            <span onclick="s2baiaAddField(this)">+</span>
                            <span onclick="s2baiaRemoveField(this)">-</span>
                        </div>

                    </td>
                </tr>
                
                <tr id="s2baia_response_td">
                    <td colspan="3">
                        <label for="s2baia_response_e" class="s2baia_lbl"><?php esc_html_e('Response', 's2b-ai-aiassistant'); ?></label>
                        <textarea id="s2baia_response_e" name="s2baia_response_e" rows="10" cols="25"></textarea>
                        <a href="#" onclick="s2baiaMetaCopyToClipboard(event,'s2baia_response_e');"  class="s2baia_copy_link"><?php esc_html_e('Copy to clipboard', 's2b-ai-aiassistant'); ?></a>
                        <a href="#" onclick="s2baiaMetaClearText(event,'s2baia_response_e');" class="s2baia_clear_link"><?php esc_html_e('Clear text', 's2b-ai-aiassistant'); ?></a>
                    </td>    
                </tr>
            </tbody>
        </table>
</div>    
        <div class="s2baia_bloader">
            <div style="padding: 1em 1.4em;"><button   name="s2baia_submit" id="s2baia_submit" class="s2baia_submit_meta button button-primary button-large"><?php echo esc_html__('Send', 's2b-ai-aiassistant') ?></button></div>

            <div class="s2baia-custom-loader"></div>
        </div>
    </div>

</div>



<script>
    let s2baia_generate_assistant = '<?php esc_html_e('Assistant', 's2b-ai-aiassistant'); ?>';
    let s2baia_generate_user = '<?php esc_html_e('User', 's2b-ai-aiassistant'); ?>';
    

</script>
