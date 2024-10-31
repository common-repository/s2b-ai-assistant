<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$default_chat_bot = $this->view_vars['default_bot'];
$chat_bot_options = isset($default_chat_bot->bot_options) && is_array($default_chat_bot->bot_options) && count($default_chat_bot->bot_options) > 0 ? $default_chat_bot->bot_options : [];
$chatbot_hash = isset($default_chat_bot->hash_code)  && strlen($default_chat_bot->hash_code) > 0 ? $default_chat_bot->hash_code : 'default';//hash_code
?>



<div class="s2baia_container">
    <div id="s2baia_configtabs">


        <ul>
            <li><a href="#s2baia-tabs-1"><?php echo esc_html__('General', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-2"><?php echo esc_html__('Styles', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-3"><?php echo esc_html__('Chatbots', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-4"><?php echo esc_html__('Assistant  API', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-5"><?php echo esc_html__('Assistants', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-6"><?php echo esc_html__('Logs', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-7"><?php echo esc_html__('Support', 's2b-ai-aiassistant') ?></a></li>
        </ul>
        <?php
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_general.php';
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_styles.php';
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_gptassistant.php';        
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_chatbots.php';
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_assistants.php';
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_log.php';
        include S2BAIA_PATH . '/views/backend/chatbot/chatbot_support.php';
        ?>
        <div class="s2baia_bloader" style="display: none;">
            <div style="padding: 1em 1.4em;"></div>


        </div>

    </div>
</div>



<script>
    const s2baajaxAction = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    let s2b_utils = null;
    let s2b_gpt_confnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_confnonce')); ?>";
    let s2baia_instruction_table_height = 0;
    let s2baia_default_chatbot_options = <?php echo wp_json_encode($chat_bot_options,JSON_HEX_TAG); ?>;
    let s2baia_copy_clipboard_sucess = '<?php echo esc_html__('Shortcode successfully copied','s2b-ai-aiassistant'); ?>';
    let s2baia_copy_clipboard_fail = '<?php echo esc_html__('Can not copy shortcode','s2b-ai-aiassistant'); ?>';
    jQuery(function () {
        jQuery("#s2baia_configtabs").tabs({activate: function (event, ui) {
                let s2baia_active_tab = jQuery("#s2baia_configtabs .s2baia_tab_panel:visible").attr("id");
                if (s2baia_active_tab === 's2baia-tabs-3') {
                    s2baia_instruction_table_height = s2baiaSetTableContainerHeight();
                    let  tbl_div = document.querySelector('#s2baia_container');
                    if (tbl_div) {
                        tbl_div.style.height = s2baia_instruction_table_height + 'px';
                    }


                }
            }});
    });
    
    
    jQuery(document).ready(function () {

        s2b_utils = new S2baiaUtils();
    });





</script>
