<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$display_pagination = true;

$instructions_per_page = (int) get_option(S2BAIA_PREFIX_LOW . 'count_of_instructions', 10);
$current_page = 1;
$search_string = '';
$arr_instructions = S2bAia_InstructionsModel::searchInstructions(0, $search_string, $current_page, $instructions_per_page, true);
$instructions = $arr_instructions['rows'];
$total_instructions = $arr_instructions['cnt'];
?>
<div class="s2baiawrap"> 
<div id="s2baia-tabs-1" class="s2baia_tab_panel" data-s2baia="1">
    <h3><?php esc_html_e('Edit & Extend', 's2b-ai-aiassistant'); ?></h3>
    <p class="s2baia_instruction_title">
        <?php esc_html_e('How to use:', 's2b-ai-aiassistant'); ?>
    </p>
    <p class="s2baia_instruction"><b>1.</b><?php esc_html_e('Input text into "Text to be changed" field.', 's2b-ai-aiassistant'); ?>
        <b>2.</b><?php esc_html_e('Enter instruction with description of what ChatGPT needs to do with the text, or select stored instruction from list below.', 's2b-ai-aiassistant'); ?>
        <b>3.</b><?php esc_html_e('Select other parameters (optionally).', 's2b-ai-aiassistant'); ?>
        <b>4.</b><?php esc_html_e('Click Send button.', 's2b-ai-aiassistant'); ?>
    </p>
    <div class="s2baia_form_row">
        <?php
        $first_instruction_text = '';
        $first_instruction = is_array($edit_instructions) && count($edit_instructions) > 0 ? $edit_instructions[0] : false;
        if (is_object($first_instruction) && $first_instruction !== false && isset($first_instruction->instruction)) {
            $first_instruction_text = $first_instruction->instruction;
        }
        //var_dump($first_instruction);
        ?>

        
                       <div class="s2baiawrap"> 
                        <table class="s2baia_edit_tbl">
                            <tbody>
                            <tr>
                    <td id="s2baia_model_td" class="s2baia_td" style="">
                        <label for="s2baia_model" class="s2baia_lbl s2baia_block"><?php esc_html_e('Model', 's2b-ai-aiassistant'); ?></label>
                        <select id="s2baia_model_c" name="s2baia_model_c" class="s2baia_selection">
                            <?php
                            foreach ($edit_models as $ei => $em) {
                                ?>
                                <option value="<?php echo wp_kses($em, S2bAia_Utils::getInstructionAllowedTags()); ?>"><?php echo wp_kses($em, S2bAia_Utils::getInstructionAllowedTags()); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_temperature_c" class="s2baia_lbl s2baia_block"><?php esc_html_e('Temperature', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_temperature_c" name="s2baia_temperature_c" type="number" step="0.1" min="0" max="2" maxlength="3" autocomplete="off"  placeholder="temperature" value="0.8">

                    </td>
                    <td id="newmetaleft" class="s2baia_td">
                        <label for="s2baia_max_tokens_c" class="s2baia_lbl s2baia_block"><?php esc_html_e('Maximum length', 's2b-ai-aiassistant'); ?></label>
                        <input class="s2baia_input s2baia_select_ch" id="s2baia_max_tokens_c" name="s2baia_max_tokens_c" type="number" step="1"  value="<?php echo (int)$mx_tokens; ?>" maxlength="3" autocomplete="off" >

                    </td>
                            </tr>
                            </tbody>
                    </table>
                       </div>  
        <div class="s2baiawrap"> 
            <table class="s2baia_edit_tbl">
            <tbody>

                <tr>
                    <td colspan="3">
                        <label for="s2baia_text_c" class="s2baia_lbl"><?php esc_html_e('Text to be changed', 's2b-ai-aiassistant'); ?></label>
                        <textarea id="s2baia_text_c" name="s2baia_text_c" rows="3" cols="25"></textarea>
                        <a href="#" onclick="s2baiaMetaClearText(event,'s2baia_text_c');" class="s2baia_clear_link"><?php esc_html_e('Clear text', 's2b-ai-aiassistant'); ?></a>
                    </td>    
                </tr>
            </tbody>
            </table>
        </div>   
        <div class="s2baiawrap"> 
            <table class="s2baia_edit_tbl">
            <tbody>
                <tr>
                    <td colspan="3">
                        <label for="s2baia_result_c" class="s2baia_lbl"><?php esc_html_e('Result', 's2b-ai-aiassistant'); ?></label>
                        
                        <textarea id="s2baia_result_c" name="s2baia_result_c" rows="3" cols="25"></textarea>
                        <a href="#" onclick="s2baiaMetaCopyToClipboard(event,'s2baia_result_c');" class="s2baia_copy_link"><?php esc_html_e('Copy to clipboard', 's2b-ai-aiassistant'); ?></a>
                        <a href="#" onclick="s2baiaMetaClearText(event,'s2baia_result_c');" class="s2baia_clear_link"><?php esc_html_e('Clear text', 's2b-ai-aiassistant'); ?></a>
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
                        <label for="s2baia_instruction" class="s2baia_lbl"><?php esc_html_e('Instruction', 's2b-ai-aiassistant'); ?></label>
                        <textarea id="s2baia_instruction" name="s2baia_instruction" rows="3" cols="55" class="s2bmt30"><?php
                            echo esc_textarea($first_instruction_text);
                            ?>
                        </textarea>

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
                        <div class="s2baia_bloader">
                            <div style="padding: 1em 1.4em;"><button   name="s2baia_submit2" id="s2baia_submit2" class="s2baia_submit_meta button button-primary button-large"><?php echo esc_html__('Send', 's2b-ai-aiassistant') ?></button></div>

                            <div class="s2baia-custom-loader"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h4 class="s2baia_block_header">
                            <?php echo esc_html__('Click on any instruction from the list to use in the prompt.', 's2b-ai-aiassistant') ?>
                        </h4>
                        <p class="s2baia_instruction">
                            <?php echo esc_html__('You can add or edit instructions on this', 's2b-ai-aiassistant') ?> <a class="s2baia_instruction" href="<?php echo esc_url(admin_url()) . 'admin.php?page=s2baia_settings'; ?>"><?php echo esc_html__('page', 's2b-ai-aiassistant') ?></a>
                        </p>
                    </td>
                </tr>    
                <tr>
                    <td colspan="3">

                        <div class="tablenav-pages">
                            <?php
                            if ($display_pagination) {
                                ?>

                                <input type="hidden" id="s2baia_page" name="s2baia_page" value="1"/>
                                <input type="hidden" id="instructions_per_page" name="instructions_per_page" value="<?php echo (int)$instructions_per_page ?>"/>
                                <div class="s2baia_pagination">
                                    <?php
                                    echo '<span class="s2baia_page_lbl" style=""> ' . esc_html__('Page', 's2b-ai-aiassistant') . ':</span> ';

                                    echo '<span aria-current="page" class="page-numbers current" style="padding-left:10px;">' . (int)$current_page . '</span>';
                                    echo '<a class="s2bprevious page-numbers" href="#" onclick="s2baiaMetaPrevPageIn(event);" style="display:none;" >&lt;&lt;</a>';
                                    if ($current_page * $instructions_per_page < $total_instructions) {
                                        echo '<a class="s2bnext page-numbers" href="#" style="" onclick="s2baiaMetaNextPageIn(event);" >&gt;&gt;</a>';
                                    }
                                    echo '<span class="s2baia_total_instructions" style="padding-left:20px;"> ';
                                    printf(esc_html__( 'Total: %s items', 's2b-ai-aiassistant' ),esc_html($total_instructions));
                                    echo '</span>   ';
                                    echo '';
                                    echo '';
                                    ?>    
                                </div>
                                <?php
                            }
                            ?>
                            <p class="search-box">

                                <span title="clear" id="s2baiaclear" class="dashicons dashicons-no" onclick="s2baiaMetaClearSearch(event);"></span>
                                <input type="text" id="s2baia_search" name="s2baia_search" value="<?php echo esc_html($search_string); ?>" onkeyup="s2baiaMetaSearchKeyUp(event);" >
                                <input type="submit" id="s2baia_search_submit" class="button" value="<?php echo esc_html__('Search instructions', 's2b-ai-aiassistant') ?>" onclick="s2baiaMetaLoadInstructionsSearch(event);"></p>
                        </div>   

                        <table id="s2baia_instructions" class="wp-list-table widefat fixed striped pages">
                            <thead>

                            <th class="manage-column id_column" style="width: 5%;"><?php echo esc_html__('ID', 's2b-ai-aiassistant'); ?></th>

                            <th class="manage-column"  style="width: 55%;"><?php echo esc_html__('Instruction', 's2b-ai-aiassistant'); ?></th>
                            <th class="manage-column mvertical"  style="width: 10%;"><?php echo esc_html__('Type', 's2b-ai-aiassistant'); ?></th>


                            </thead>
                            <tbody id="s2baia-the-list">
                                <?php
                                $js_instructions = [];

                                foreach ($instructions as $row) {
                                    $row_id = (int) $row->id;
                                    $author = (int) S2bAia_Utils::getUsername($row->user_id);
                                    $row->user_id = $author;
                                    $row_instruction = wp_kses($row->instruction, S2bAia_Utils::getInstructionAllowedTags());
                                    $js_instructions[$row_id] = $row;
                                    $js_instructions[$row_id]->instruction = $row_instruction;
                                    $s2baia_disabled_text = '';
                                    if ($row->disabled) {
                                        $s2baia_disabled_text = 's2baia_disabled_text';
                                    }
                                    ?>
                                    <tr class="<?php echo esc_attr($s2baia_disabled_text); ?>">
                                        <td class="id_column">
                                            <?php
                                            $displayed_id = $row_id;
                                            ?>
                                            <a href="<?php echo '#'; ?>" onclick="s2baiaMetaSelectInstruction(event,<?php echo (int)$row_id; ?>)" >
                                                <?php
                                                echo esc_html($displayed_id);
                                                ?>
                                            </a>
                                        </td>
                                        <?php ?> 
                                        <td>
                                            <a href="<?php echo '#'; ?>" onclick="s2baiaMetaSelectInstruction(event,<?php echo esc_html($row_id); ?>)" id="s2baia_instr_href_<?php echo esc_html($row_id); ?>">
                                                <?php
                                                echo esc_html($row_instruction);
                                                ?>
                                            </a>


                                        </td>
                                        <td class="mvertical">
                                            <a href="<?php echo '#'; ?>" onclick="s2baiaMetaSelectInstruction(event,<?php echo esc_html($row_id); ?>)" >

                                                <?php
                                                switch ($row->typeof_instruction) {
                                                    case 2:
                                                        echo esc_html__('code-edit', 's2b-ai-aiassistant');
                                                        break;
                                                    default:
                                                        echo esc_html__('text-edit', 's2b-ai-aiassistant');
                                                }
                                                ?>
                                            </a>              
                                        </td>



                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>


                    </td>    
                </tr>


            </tbody>
        </table>
    </div>

</div>
</div>
    <script>
    let s2baia_instructions = <?php echo wp_json_encode($js_instructions,JSON_HEX_TAG); ?>;
    let s2baia_edited_instruction_id = 0;
    let s2b_gpt_loadnoncec = "<?php echo esc_html(wp_create_nonce('s2b_gpt_loadnoncec')); ?>";
    let s2baia_typeofinstr_text = '<?php esc_html_e('text-edit', 's2b-ai-aiassistant'); ?>';
    let s2baia_typeofinstr_code = '<?php esc_html_e('code-edit', 's2b-ai-aiassistant'); ?>';
    




</script>


