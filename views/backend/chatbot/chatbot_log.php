<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$display_pagination = true;

$log_records_per_page = $records_per_page;
$current_page = $curr_page;
$search_string = '';
$log_records = $arr_logrecords['rows'];
$total_rows = $arr_logrecords['cnt'];
$wp_nonce = esc_html(wp_create_nonce('s2b_gpt_loadnonce'));
//var_dump($log_records);
?>



<div id="s2baia-tabs-6" class="s2baia_tab_panel" data-s2baia="6">
    <div class="inside">
    <div class="s2baia_config_items_wrapper">
            <form action="" method="post" id="s2baia_gen_form">    
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>gpt_loadnonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="action" value="s2b_gpt_load_log"/>
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">
                        
                        <?php 
                                if(true){
                                    $s2baia_log_conversation = (int)get_option('s2baia_log_conversation',0);
                                    $checked = '';
                                    if ($s2baia_log_conversation == 1) {
                                            $checked = ' checked ';
                                    }
                                ?>
                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="checkbox" id="s2baia_log_conversation" name="s2baia_log_conversation" <?php echo esc_html($checked); ?> onchange="s2bChangeLogConversation(this)" >
                                <label for="s2baia_log_conversation"><?php echo esc_html__('Log discussion', 's2b-ai-aiassistant'); ?>  <b><?php echo esc_html__('This chatbot records interactions with users to gain insights and improve user experience. By default, the conversation logging feature is disabled. See Support TAB where you can find table structure and its purpose can be found.', 's2b-ai-aiassistant'); ?></b></label>
                                 
                            </div>
                            <?php 
                            $hidden_log_text = '';
                                if ($s2baia_log_conversation != 1) {
                                    $hidden_log_text = 'display:none;';        
                                ?>
                            <div style="padding: 1em 1.4em;">
                                <h4 class="s2baia_instruction" id="s2baia_turn_log">
                                    <?php echo esc_html__('You have turned OFF logging! New records will NOT be added to log.', 's2b-ai-aiassistant'); ?> 
                                </h4>
                                 
                            </div>
                            <?php 
                                }else{
                                ?>
                            <div style="padding: 1em 1.4em;">
                                <h4 class="s2baia_instruction" id="s2baia_turn_log">
                                    <?php echo esc_html__('You have turned ON logging! New records will  be added to log.', 's2b-ai-aiassistant'); ?> 
                                </h4>
                                
                        
                            </div>
                            <?php 
                                }
                                ?>
                            <div style="min-height:130px;">
                            <div style="padding: 1em 1.4em;<?php echo $hidden_log_text; ?>" id="s2baia_turn_log_container">
                                
                                <?php 
                                $s2baia_log_alert = sanitize_text_field(get_option('s2baia_chatbot_log_alert',''));
                                ?>
                                <div>
                                    <textarea id="s2baia_chatbot_log_alert" 
                                              name="s2baia_chatbot_log_alert" style="width:50%;"><?php  echo esc_html($s2baia_log_alert);  ?></textarea>
                                    <button 
                                        value="<?php echo esc_html__('Save', 's2b-ai-aiassistant') ?>" 
                                        name="s2baia_store_log_alert" 
                                        id="s2baia_store_log_alert" class="button button-primary button-large" style=""
                                        onclick="s2baiaStoreLogAlert(event);">
                                            <?php echo esc_html__('Save', 's2b-ai-aiassistant'); ?>
                                    </button>
                                </div>
                                <p><?php echo esc_html__('Enter text that will be displayed users to inform them that your conversation is logged. When empty then no alerts will be displayed users.', 's2b-ai-aiassistant'); ?> </p>
                                
                        
                            </div>
                            </div>
                        </div>
                        <?php
                                }
                                ?>
                    </div>
                </div>

                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column" style="flex:3;">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Discussion Log', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                

                                <div class="s2baia_block_content" >
                                    <div  class="s2baia_row_content s2baia_pr">
                                        

        <div class="tablenav-pages">
            <?php
            if ($display_pagination) {
                ?>
                <div class="tablenav top">
                    <div class="alignleft ">
                        <label><?php esc_html_e('Items per page', 's2b-ai-aiassistant'); ?>:</label>
                        <select name="logs_per_page" id="logs_per_page" onchange="s2baiaChangeLogPerPage(this);">
                            <option <?php echo $log_records_per_page == 10 ? 'selected="selected"' : ''; ?> value="10">10</option>
                            <option  <?php echo $log_records_per_page == 20 ? 'selected="selected"' : ''; ?>  value="20">20</option>
                            <option  <?php echo $log_records_per_page == 50 ? 'selected="selected"' : ''; ?>  value="50">50</option>
                            <option  <?php echo $log_records_per_page == 100 ? 'selected="selected"' : ''; ?>  value="100">100</option>
                        </select>
                        <input type="hidden" id="s2baia_page_log" name="s2baia_page_log" value="1"/>

                    </div>
                </div> 

                <div class="s2baia_pagination">
                    <?php
                    echo '<span class="s2baia_page_lbl" style=""> ' . esc_html__('Page', 's2b-ai-aiassistant') . ':</span>';

                    echo '<span aria-current="page" class="page-numbers current" >' . esc_html($current_page) . '</span>';
                    echo '<a class="s2bprevious page-numbers" href="#" onclick="s2baiaPrevLogPage(event);" style="display:none;" >&lt;&lt;</a>';
                    if ($current_page * $log_records_per_page < $total_rows) {
                        echo '<a class="s2bnext page-numbers" href="#" style="" onclick="s2baiaNextLogPage(event);" >&gt;&gt;</a>';
                    }
                    echo '<span class="s2baia_total_instructions" style="padding-left:20px;"> ';
                    printf(esc_html__( 'Total: %s items', 's2b-ai-aiassistant' ),esc_html($total_rows));
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
                <span title="clear" id="s2baiaclear" class="dashicons dashicons-no" onclick="s2baiaLogsClearSearch(event);"></span>
                <input type="search" id="s2baia_search" name="s2baia_search" value="<?php echo esc_html($search_string); ?>" onkeyup="s2baiaSearchLogKeyUp(event);" >
                <input type="submit" id="s2baia_search_submit" class="button" value="Search records" onclick="s2baiaLoadLogsE(event);">
            </p>
        </div>                    
        <?php
        if (true) {
            ?>
                                        <div id="s2baia_container" class="  ">

                                                <table id="s2baia_instructions" class="wp-list-table widefat fixed striped pages">
                                                    <thead>

                                                    <th class="manage-column id_column" style="width: 5%;"><?php esc_html_e('ID', 's2b-ai-aiassistant'); ?></th>

                                                    <th class="manage-column"  style="width: 55%;"><?php esc_html_e('Preview', 's2b-ai-aiassistant'); ?></th>
                                                    <th class="manage-column mvertical"  style="width: 10%;"><?php esc_html_e('User', 's2b-ai-aiassistant'); ?></th>
                                                    <th class="manage-column mvertical " style="width: 10%;"><?php esc_html_e('Chat', 's2b-ai-aiassistant'); ?></th>
                                                    <th class="manage-column"  style="width: 10%;"><?php esc_html_e('Time', 's2b-ai-aiassistant'); ?></th>
                                                    <th class="manage-column"  style="width: 10%;"><?php esc_html_e('Actions', 's2b-ai-aiassistant'); ?></th>

                                                    </thead>
                                                    <tbody id="s2baia-the-list">
                                                        <?php
                                                        $js_logmessages = [];
                                                        $js_loginfos = [];
                                                        foreach ($log_records as $row) {
                                                            $created_by = get_userdata($row->id_user);
                                                                if (is_object($created_by) && isset($created_by->ID)) {
                                                                    $visitor = $created_by->user_login;
                                                                } else {
                                                                    $visitor = esc_html__('Guest', 's2b-ai-aiassistant');
                                                                }
                                                            
                                                            
                                                            $s2baia_disabled_text = '';
                                                            if ($row->selected == 1) {
                                                                $s2baia_disabled_text = 's2baia_selected_text';
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
                                                                <?php 
                                                                $messages = json_decode($row->messages,true);
                                                                $preview = __('Click to see details', 's2b-ai-aiassistant');
                                                                $js_logmessages[(int) $row->id] = $log_model->parseMessages($row->typeof_message,$row->messages);
                                                                $js_loginfos[(int) $row->id] = $log_model->parseParameters($row);
                                                                if(is_array($messages) && count($messages) > 0){
                                                                    $firo = isset($messages[0])?$messages[0]:[];
                                                                    if(is_array($firo) && isset($firo['content'])){
                                                                        $preview = substr($firo['content'],0,100);
                                                                    }
                                                                }
                                                                ?> 
                                                                <td>
                                                                    <a href="<?php echo '#'; ?>" onclick="s2baiaShowRecord(event,<?php echo (int) $row->id; ?>)" id="s2baia_selected_href_<?php echo (int) $row->id; ?>">
                                                                        <?php
                                                                        echo esc_html($preview);
                                                                        ?>
                                                                    </a>


                                                                </td>
                                                                <td class="mvertical">
                                                                    <span id="s2baia_type_instr_span_<?php echo (int) $row->id; ?>">

                                                                        <?php
                                                                        echo esc_html($visitor).'</br>'.esc_html($row->ipaddress);
                                                                        ?>
                                                                    </span>              
                                                                </td>
                                                                <td class="mvertical">
                                                                    <span id="s2baia_selected_span_<?php echo (int) $row->id; ?>">
                                                                        <?php
                                                                        $bot_h = '';
                                                                        if(strlen($row->hash_code) > 0){
                                                                            $bot_h = '<b>bot:</b>'.$row->hash_code;
                                                                        }
                                                                        $ch_id = '';
                                                                        if(strlen($row->chat_id) > 0){
                                                                            $ch_id = '<b>chat:</b>'.$row->chat_id;
                                                                        }
                                                                        echo wp_kses($bot_h,['b'=>[]]).'<br> '.wp_kses($ch_id,['b'=>[]]);
                                                                        ?>
                                                                    </span>  
                                                                </td>
                                                                <td class="s2baia_timecreated">
                                                                    <span>
                                                                        <?php
                                                                        $s2btime = $row->created;
                                                                        $s2bmatches = [];
                                                                        
                                                                        echo esc_html($s2btime);
                                                                        ?>
                                                                    </span>
                                                                </td>


                                                                <td class="s2baia_flags_td">
                                                                    <?php
                                                                    if ($row->selected) {
                                                                        $dashiconsclass = 'dashicons-remove';
                                                                    } else {
                                                                        $dashiconsclass = 'dashicons-insert';
                                                                    }
                                                                    ?>
                                                                    <span title="select"  class="dashicons <?php echo esc_attr($dashiconsclass); ?>" onclick="s2baiaLogSelectRow(event,<?php echo (int) $row->id; ?>)"></span>
                                                                    <span title="remove"  class="dashicons dashicons-trash" onclick="s2baiaLogsRemoveRow(event,<?php echo (int) $row->id; ?>)"></span>

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
                                                    echo '<a class="s2bprevious page-numbers" href="#" onclick="s2baiaPrevLogPage(event);" style="display:none;" >&lt;&lt;</a>';
                                                    if ($current_page * $log_records_per_page < $total_rows) {
                                                        echo '<a class="s2bnext page-numbers" href="#" style="" onclick="s2baiaNextLogPage(event);" >&gt;&gt;</a>';
                                                    }
                                                    echo '<span class="s2baia_total_instructions" style="padding-left:20px;"> ';
                                                    printf(esc_html__('Total: %s items', 's2b-ai-aiassistant'), esc_html($total_rows));
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
                        </div>   
                           
                    </div>
                    <div class="s2baia_data_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header" id="s2b_bot_thread_info_header"style="display: none;">
                                    <h3><?php esc_html_e('Information', 's2b-ai-aiassistant'); ?></h3>
                                </div>
                                <div class="s2baia_block_content2" id="s2b_bot_thread_info">

                                    
                                </div>
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Selected Discussion', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                <div class="s2baia_block_content2" id="s2b_bot_thread_history">

                                    <div class="s2b-history" style="display: flex; flex-direction: column; margin-bottom: 5px;">
                                        <span><?php esc_html_e('No discussion selected', 's2b-ai-aiassistant'); ?></span>
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
    let s2baia_edited_instruction_id = 0;
    let s2baia_log_messages = <?php echo wp_json_encode($js_logmessages,JSON_HEX_TAG); ?>;
    let s2baia_log_infos = <?php echo wp_json_encode($js_loginfos,JSON_HEX_TAG); ?>;
    
    
    let s2baia_toggleselectionnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_toggleselectionnonce')); ?>";
    let s2b_bot_dellognonce = "<?php echo esc_html(wp_create_nonce('s2b_bot_dellognonce')); ?>";
    let s2b_changemode_lognonce = "<?php echo esc_html(wp_create_nonce('s2b_changemode_lognonce')); ?>";
    
    let s2b_gpt_loadnonce = "<?php echo esc_html($wp_nonce); ?>";
    let s2b_bot_turnoff_msg = "<?php echo esc_html('You have turned off logging! New records will NOT be added to log.','s2b-ai-aiassistant')  ?>";
    let s2b_bot_turnon_msg = "<?php echo esc_html('You have turned ON logging! New records will be added to log.','s2b-ai-aiassistant')  ?>";
    let s2b_message_log_confirm_delete = '<?php esc_html_e('Are you sure you want to remove log record with ID', 's2b-ai-aiassistant'); ?>';
    
    
    let s2b_gpt_confinstructnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_confinstructnonce')); ?>"
    let s2b_message_instruction_store_error = '<?php esc_html_e('Some issues happened during instruction store.', 's2b-ai-aiassistant'); ?>';
    let s2b_message_instruction_store_success = '<?php esc_html_e('Instruction added with ID', 's2b-ai-aiassistant'); ?>';
    
    let s2b_message_instruction_access_denied = '<?php esc_html_e('only admin has acces to this operation', 's2b-ai-aiassistant'); ?>';
    

    

    
    function s2baiaShowRecord(e,id_row){
        e.preventDefault();
        let row = s2baia_log_messages[id_row];
        let info_row = s2baia_log_infos[id_row];
        let s2b_thread_info = document.querySelector('#s2b_bot_thread_info');
        let s2b_thread_history = document.querySelector('#s2b_bot_thread_history');
        let s2b_bot_thread_info_header  = document.querySelector('#s2b_bot_thread_info_header');
        if(!s2b_thread_info || !s2b_thread_history || !s2b_bot_thread_info_header){
            return;
        }
        s2b_thread_info.innerHTML = '<div class="s2b-history" style="display: flex; flex-direction: column; margin-bottom: 5px;"><span>No data</span></div>';
        s2b_thread_history.innerHTML = '<div class="s2b-history" style="display: flex; flex-direction: column; margin-bottom: 5px;"><span>No data</span></div>';
        s2b_bot_thread_info_header.style.display = 'block';
        if(Array.isArray(row) && row !== null){
            let divv = '';
            let role = '';
            let content = '';
            
            for(let ii2 in row){
                let subsub = row[ii2];
                divv = divv + '<div class="s2b-history_block" style=" margin-bottom: 5px;">';
                role = '';
                content = '';
                for(let ii3 in subsub){

                    console.log(`${ii3}: ${subsub[ii3]}`);
                    if (ii3 === 'role'){
                        role = '<div class="s2b_bot_history_row_header"><span>'+subsub[ii3]+'</span></div>';
                    }
                    if (ii3 === 'content'){
                        content = '<div class="s2b_bot_history_row"><span>'+subsub[ii3]+'</span></div>';
                    }
                }
                divv = divv + role + content + '</div>';
                
            }
            s2b_thread_history.innerHTML = divv;
        }
        
        divv = '';
        
        if(typeof info_row === 'object' && !Array.isArray(info_row) && info_row !== null){
            let part_div = '<div style="font-weight: bold;">';
            
            let model = part_div + 'Model:</div>';
            let botid = part_div + 'Bot ID:</div>';
            let chatid = part_div + 'Chat ID:</div>';
            let created = part_div + 'Created:</div>';
            let updated = part_div + 'Last update:</div>';
            for (ii in info_row){
                    switch(ii){
                        case 'model':
                            model = model + '<div>' + info_row[ii] + '</div>';
                            break;
                        case 'bot_id':
                            botid = botid + '<div>' + info_row[ii] + '</div>';
                            break;
                        case 'chat_id':
                            chatid = chatid + '<div>' + info_row[ii] + '</div>';
                            break;
                        case 'created':
                            created = created + info_row[ii] + '</div>';
                            break;
                        case 'updated':
                            updated = updated + '<div>' + info_row[ii] + '</div>';
                            break;    
                    }

            }
            divv = model + botid + chatid + created + updated;
        }
        s2b_thread_info.innerHTML = divv;
    }
    
    function s2bChangeLogConversation(checkboxElem) {
        
        s2baiaLogConversation(checkboxElem.checked);
        if(checkboxElem.checked === true){
            document.querySelector('#s2baia_turn_log_container').style.display = 'block';
        }else{
            document.querySelector('#s2baia_turn_log_container').style.display = 'none';
        }
    }
    
    function s2baiaStoreLogAlert(e){
        e.preventDefault();
        let text = document.querySelector('#s2baia_chatbot_log_alert').value;
        s2baiaLogText(text);
    } 
       
    
  jQuery(document).ready(function () {
      
        


    });  

</script>

