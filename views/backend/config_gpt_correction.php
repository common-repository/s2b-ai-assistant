<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$display_pagination = true;

$instructions_per_page = 10;
$current_page = 1;
$search_string = '';
$arr_instructions = S2bAia_InstructionsModel::searchInstructions(0, $search_string, $current_page, $instructions_per_page, false);
$instructions = $arr_instructions['rows'];
$total_instructions = $arr_instructions['cnt'];
//var_dump($models);
?>



<div id="s2baia-tabs-3" class="s2baia_tab_panel" data-s2baia="3">
    <div class="inside">
        <div class="s2baia_edit_panel s2baia_form_row ">
            <table  class="s2baia_editconf_tbl">
                <tbody>

                    <tr id="s2baia_response_td">
                        <td colspan="2">
                            <label for="s2baia_instruction_type" class="s2baia_lbl"><?php esc_html_e('Type', 's2b-ai-aiassistant'); ?></label>
                            <select id="s2baia_instruction_type" name="s2baia_instruction_type" class="s2baia_selection " >

                                <option value="1"><?php esc_html_e('text correction', 's2b-ai-aiassistant'); ?></option>
                                <option value="2"><?php esc_html_e('code correction', 's2b-ai-aiassistant'); ?></option>

                            </select>
                        </td>   
                        <td >
                            <label id="s2baia_id_instruction_lbl"  class="s2baia_lbl"><?php esc_html_e('ID', 's2b-ai-aiassistant'); ?>:</label>

                        </td>   
                    </tr>
                    <tr id="s2baia_instruction_td">
                        <td colspan="3">
                            <label for="s2baia_response_e" class="s2baia_lbl"><?php esc_html_e('Instruction', 's2b-ai-aiassistant'); ?></label>
                            <textarea id="s2baia_instruction" class="" name="s2baia_instruction" rows="10" cols="75"  onkeyup="s2baiaCountInstructionChar(this);" ></textarea>
                        </td>    
                    </tr>
                    <tr>
                        <td class="s2baia_button_container s2baia_bloader">
                            <!--<div class="s2baia-custom-loader s2baia-instructions-loader"></div>-->
                            <input type="hidden" id="s2baia_idinstruction" name="s2baia_idinstruction" value=""/>
                            <button  
                                name="s2baia_submit_edit_instruction" 
                                id="s2baia_submit_edit_instruction" class="button button-primary button-large"
                                onclick="s2baiaStoreInstruction(event);" disabled="disabled">
                                    <?php echo esc_html__('Add', 's2b-ai-aiassistant') ?>
                            </button>
                            <button 
                                value="<?php echo esc_html__('Save as new', 's2b-ai-aiassistant') ?>" 
                                name="s2baia_saveasnew_instruction" 
                                id="s2baia_saveasnew_instruction" class="button button-primary button-large" style="display:none;"
                                onclick="s2baiaStoreNewInstruction(event);"
                                >
                                    <?php echo esc_html__('Save as new', 's2b-ai-aiassistant') ?>
                            </button>


                            <button 
                                value="<?php echo esc_html__('Remove', 's2b-ai-aiassistant') ?>" 
                                name="s2baia_remove_instruction" 
                                id="s2baia_remove_instruction" class="button button-danger button-large" style="display:none;"
                                onclick="s2baiaRemoveInstruction(event);">
                                    <?php echo esc_html__('Remove', 's2b-ai-aiassistant') ?>
                            </button>
                            <button 
                                value="<?php echo esc_html__('Clear & New', 's2b-ai-aiassistant') ?>" 
                                name="s2baia_new_instruction" 
                                id="s2baia_new_instruction" class="button button-primary button-large" style="display:none;"
                                onclick="s2baiaNewInstruction(event);">
                                    <?php echo esc_html__('Clear & New', 's2b-ai-aiassistant'); ?>
                            </button>

                        </td>    
                        <td>

                        </td>
                        <td>
                            <input type="checkbox" id="s2baia_disabled" name="s2baia_disabled" class="" />
                            <label for="s2baia_disabled"><?php esc_html_e('Disabled', 's2b-ai-aiassistant'); ?></label>
                        </td>  
                    </tr>


                </tbody>
            </table>
        </div>

        <h3><?php echo esc_html__('Instructions', 's2b-ai-aiassistant'); ?></h3>
        <p class="s2baia_instruction"><?php echo esc_html__('More instruction ideas you can find', 's2b-ai-aiassistant'); ?> <a href="https://soft2business.com/chatgpt-instructions/" target="blank"  class="s2baia_instruction"><?php echo esc_html__('here', 's2b-ai-aiassistant'); ?></a></p>

        <input type="hidden" name="s2baia_instructions_nonce" value="<?php echo esc_html(wp_create_nonce('s2baia_instructions_nonce')); ?>"/>

        <div class="tablenav-pages">
            <?php
            if ($display_pagination) {
                ?>
                <div class="tablenav top">
                    <div class="alignleft ">
                        <label><?php esc_html_e('Items per page', 's2b-ai-aiassistant'); ?>:</label>
                        <select name="instructions_per_page" id="instructions_per_page" onchange="s2baiaChangePerPage(this);">
                            <option <?php echo $instructions_per_page == 10 ? 'selected="selected"' : ''; ?> value="10">10</option>
                            <option  <?php echo $instructions_per_page == 20 ? 'selected="selected"' : ''; ?>  value="20">20</option>
                            <option  <?php echo $instructions_per_page == 50 ? 'selected="selected"' : ''; ?>  value="50">50</option>
                            <option  <?php echo $instructions_per_page == 100 ? 'selected="selected"' : ''; ?>  value="100">100</option>
                        </select>
                        <input type="hidden" id="s2baia_page" name="s2baia_page" value="1"/>

                    </div>
                </div> 

                <div class="s2baia_pagination">
                    <?php
                    echo '<span class="s2baia_page_lbl" style=""> ' . esc_html__('Page', 's2b-ai-aiassistant') . ':</span>';

                    echo '<span aria-current="page" class="page-numbers current" >' . esc_html($current_page) . '</span>';
                    echo '<a class="s2bprevious page-numbers" href="#" onclick="s2baiaPrevPage(event);" style="display:none;" >&lt;&lt;</a>';
                    if ($current_page * $instructions_per_page < $total_instructions) {
                        echo '<a class="s2bnext page-numbers" href="#" style="" onclick="s2baiaNextPage(event);" >&gt;&gt;</a>';
                    }
                    echo '<span class="s2baia_total_instructions" style="padding-left:20px;"> ';
                    printf(esc_html__( 'Total: %s items', 's2b-ai-aiassistant' ),esc_html($total_instructions));
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
                <span title="clear" id="s2baiaclear" class="dashicons dashicons-no" onclick="s2baiaClearSearch(event);"></span>
                <input type="search" id="s2baia_search" name="s2baia_search" value="<?php echo esc_html($search_string); ?>" onkeyup="s2baiaSearchKeyUp(event);" >
                <input type="submit" id="s2baia_search_submit" class="button" value="Search instructions" onclick="s2baiaLoadInstructionsE(event);">
            </p>
        </div>                    
        <?php
        if (true) {
            ?>
            <div id="s2baia_container" class="  ">

                <table id="s2baia_instructions" class="wp-list-table widefat fixed striped pages">
                    <thead>

                    <th class="manage-column id_column" style="width: 5%;"><?php esc_html_e('ID', 's2b-ai-aiassistant'); ?></th>

                    <th class="manage-column"  style="width: 55%;"><?php esc_html_e('Instruction', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column mvertical"  style="width: 10%;"><?php esc_html_e('Type', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column " style="width: 10%;"><?php esc_html_e('Disabled', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column mvertical"  style="width: 10%;"><?php esc_html_e('User', 's2b-ai-aiassistant'); ?></th>
                    <th class="manage-column"  style="width: 10%;"><?php esc_html_e('Actions', 's2b-ai-aiassistant'); ?></th>

                    </thead>
                    <tbody id="s2baia-the-list">
                        <?php
                        $js_instructions = [];

                        foreach ($instructions as $row) {

                            $author = S2bAia_Utils::getUsername($row->user_id);
                            $row->user_id = $author;
                            $js_instructions[(int) $row->id] = $row;
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
                                    <a href="<?php echo '#'; ?>" onclick="s2baiaEditInstruction(event,<?php echo (int) $row->id; ?>)" id="s2baia_instr_href_<?php echo (int) $row->id; ?>">
                                        <?php
                                        echo wp_kses($row->instruction, S2bAia_Utils::getInstructionAllowedTags());
                                        ?>
                                    </a>


                                </td>
                                <td class="mvertical">
                                    <span id="s2baia_type_instr_span_<?php echo (int) $row->id; ?>">

                                        <?php
                                        switch ($row->typeof_instruction) {
                                            case 2:
                                                echo esc_html__('code-edit', 's2b-ai-aiassistant');
                                                break;
                                            default:
                                                echo esc_html__('text-edit', 's2b-ai-aiassistant');
                                        }
                                        ?>
                                    </span>              
                                </td>
                                <td class="mvertical">
                                    <span id="s2baia_enabled_span_<?php echo (int) $row->id; ?>">
                                        <?php
                                        if ($row->disabled) {
                                            echo esc_html__('disabled', 's2b-ai-aiassistant');
                                        } else {
                                            echo esc_html__('enabled', 's2b-ai-aiassistant');
                                        }
                                        ?>
                                    </span>  
                                </td>
                                <td class="s2baia_user">
                                    <span>
                                        <?php
                                        echo esc_html($row->user_id);
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
                                    <span title="edit" class="dashicons dashicons-edit"  onclick="s2baiaEditInstruction(event,<?php echo (int) $row->id; ?>)" ></span>
                                    <span title="disable"  class="dashicons <?php echo esc_attr($dashiconsclass); ?>" onclick="s2baiaToggleInstruction(event,<?php echo (int) $row->id; ?>)"></span>
                                    <span title="remove"  class="dashicons dashicons-trash" onclick="s2baiaRemoveRow(event,<?php echo (int) $row->id; ?>)"></span>

                                </td>


                            </tr>
                            <?php
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

                    echo '<span aria-current="page" class="page-numbers current" >' . esc_attr($current_page) . '</span>';
                    echo '<a class="s2bprevious page-numbers" href="#" onclick="s2baiaPrevPage(event);" style="display:none;" >&lt;&lt;</a>';
                    if ($current_page * $instructions_per_page < $total_instructions) {
                        echo '<a class="s2bnext page-numbers" href="#" style="" onclick="s2baiaNextPage(event);" >&gt;&gt;</a>';
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
        </div>

    </div>
</div>


<script>
    let s2baia_edited_instruction_id = 0;
    let s2baia_instructions = <?php echo wp_json_encode($js_instructions,JSON_HEX_TAG); ?>;
    let s2baia_text_edit_label = '<?php esc_html_e('text-edit', 's2b-ai-aiassistant'); ?>';
    let s2baia_code_edit_label = '<?php esc_html_e('code-edit', 's2b-ai-aiassistant'); ?>';
    let s2baia_disabled_label = '<?php esc_html_e('disabled', 's2b-ai-aiassistant'); ?>';
    let s2baia_enabled_label = '<?php esc_html_e('enabled', 's2b-ai-aiassistant'); ?>';
    let s2baia_toggleinstructionnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_toggleinstructnonce')); ?>";
    let s2b_gpt_delinstructnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_delinstructnonce')); ?>";
    let s2b_gpt_loadnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_loadnonce')); ?>";
    let s2b_gpt_confinstructnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_confinstructnonce')); ?>"
    let s2b_message_instruction_store_error = '<?php esc_html_e('Some issues happened during instruction store.', 's2b-ai-aiassistant'); ?>';
    let s2b_message_instruction_store_success = '<?php esc_html_e('Instruction added with ID', 's2b-ai-aiassistant'); ?>';
    let s2b_message_instruction_confirm_delete = '<?php esc_html_e('Are you sure you want to remove instruction with ID', 's2b-ai-aiassistant'); ?>';
    let s2b_message_instruction_access_denied = '<?php esc_html_e('only admin has acces to this operation', 's2b-ai-aiassistant'); ?>';
    
    

</script>

