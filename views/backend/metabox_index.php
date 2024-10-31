<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script>
    let s2baia_copy_clipboard_sucess = '<?php esc_html_e('Copying to clipboard was successful!', 's2b-ai-aiassistant'); ?>';
    let s2baia_copy_clipboard_fail = '<?php esc_html_e('Could not copy text', 's2b-ai-aiassistant'); ?>';
    
    jQuery(function () {
        jQuery("#s2baia_metatabs").tabs();
    });
</script>


<?php ?>

<div class="s2baia_container">
    <div id="s2baia_metatabs">
        <form action="" method="post" id="s2baia_post">
            <?php

            ?>
            <ul>
                <li><a href="#s2baia-tabs-1"><?php echo esc_html__('Edit & Extend', 's2b-ai-aiassistant') ?></a></li>

                <li><a href="#s2baia-tabs-3"><?php echo esc_html__('Expert', 's2b-ai-aiassistant') ?></a></li>
            </ul>
            <?php
            include S2BAIA_PATH . '/views/backend/correct.php';
            include S2BAIA_PATH . '/views/backend/generate.php';
            ?>

        </form>
    </div>
</div>
<script>
    const s2baajaxAction = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    let s2baia_jquery_is_not_installed = '<?php esc_html_e('Error. jQuery library is not installed!', 's2b-ai-aiassistant'); ?>';
    (function ($) {
    $(document).ready(function () {
        
        $('.s2baia_submit_meta').click(function (event) {
            console.log('Send GPT request ');
            event.preventDefault();
            console.log($("#s2baia_metatabs").tabs('option', 'selected'));
            console.log($("#s2baia_metatabs .s2baia_tab_panel:visible").attr("id"));
            console.log($("#s2baia_metatabs .s2baia_tab_panel:visible").attr("data-s2baia"));
            let s2b_active_panel = $("#s2baia_metatabs .s2baia_tab_panel:visible").attr("data-s2baia");
            if (s2b_active_panel <= 0) {
                console.log('error = can not find active panel ');
                return;
            }
            let s2bdata = {'s2b_gpt_nonce': "<?php echo esc_html(wp_create_nonce('s2b_gpt_nonce')); ?>"};
            switch (s2b_active_panel) {
                case '1':
                    s2bdata['action'] = 's2b_gpt_correct';
                    s2bdata['instructiontype'] = $('input[name="s2baia_instructiontype"]:checked').val();
                    s2bdata['model'] = $('#s2baia_model_c').find(":selected").val();
                    s2bdata['temperature'] = $('#s2baia_temperature_c').val();

                    s2bdata['max_tokens'] = $('#s2baia_max_tokens_c').val();
                    s2bdata['instruction'] = $('#s2baia_instruction').val();
                    s2bdata['text'] = $('#s2baia_text_c').val();
                    
                    $('#s2baia_result_c').val('');
                    s2b_performAjax.call(s2b_correct_result_dynamic, s2bdata);
                    break;
                case '2':
                    s2bdata['action'] = 's2b_gpt_generate';
                    s2bdata['model'] = $('#s2baia_model_e').find(":selected").val();
                    s2bdata['temperature'] = $('#s2baia_temperature_e').val();
                    s2bdata['max_tokens'] = $('#s2baia_max_tokens_e').val();
                    s2bdata['top_p'] = $('#s2baia_top_p_edit').val();
                    s2bdata['presence_penalty'] = $('#s2baia_presence_penalty_e').val();
                    s2bdata['frequency_penalty'] = $('#s2baia_frequency_penalty_e').val();
                    s2bdata['system'] = $('#s2baia_system_e').val();
                    let cnt_els = s2baia_radion_lst2.length;
                    let s2baia_actors = [];
                    let s2baia_msgs = [];

                    for (let i = 1; i < cnt_els; i++) {
                        if (s2baia_radion_lst2[i] === 'Deleted') {
                            continue;
                        }
                        s2baia_actors.push(s2baia_radion_lst2[i]);
                        let s2baia_textarea = document.querySelector("#s2baia_message_e_" + i);
                        if (s2baia_textarea) {
                            s2baia_msgs.push(s2baia_textarea.value);
                        } else {
                            s2baia_msgs.push('');
                        }
                        s2bdata['actors'] = s2baia_actors;
                        s2bdata['msgs'] = s2baia_msgs;
                    }
                    $('#s2baia_response_e').val('');
                    s2b_performAjax.call(s2b_generate_result_dynamic, s2bdata);

                    break;
                default:
                    console.log('Sorry, we are out of ' + s2b_active_panel);
                    return;
            }

        });
        
        let s2baia_temperature_c_prevent = document.getElementById("s2baia_temperature_c");
        s2baia_temperature_c_prevent.addEventListener("keypress", s2baia_preventDefault);
        
        let s2baia_max_tokens_c_prevent = document.getElementById("s2baia_max_tokens_c");
        s2baia_max_tokens_c_prevent.addEventListener("keypress", s2baia_preventDefault);
        
        let s2baia_search_prevent = document.getElementById("s2baia_search");
        s2baia_search_prevent.addEventListener("keypress", s2baia_preventDefault);
        
        let s2baia_temperature_e_prevent = document.getElementById("s2baia_temperature_e");
        s2baia_temperature_e_prevent.addEventListener("keypress", s2baia_preventDefault);
        
        let s2baia_max_tokens_e_prevent = document.getElementById("s2baia_max_tokens_e");
        s2baia_max_tokens_e_prevent.addEventListener("keypress", s2baia_preventDefault);

        let s2baia_top_p_edit_prevent = document.getElementById("s2baia_top_p_edit");
        s2baia_top_p_edit_prevent.addEventListener("keypress", s2baia_preventDefault);

        let s2baia_presence_penalty_e_prevent = document.getElementById("s2baia_presence_penalty_e");
        s2baia_presence_penalty_e_prevent.addEventListener("keypress", s2baia_preventDefault);

        let s2baia_frequency_penalty_e_prevent = document.getElementById("s2baia_frequency_penalty_e");
        s2baia_frequency_penalty_e_prevent.addEventListener("keypress", s2baia_preventDefault);
        
        if(!navigator || !navigator.clipboard){
            let s2baia_cp_clipb_links = document.querySelectorAll('.s2baia_copy_link');
            for (let ii in s2baia_cp_clipb_links){
				let s2baia_cp_clipb_link = s2baia_cp_clipb_links[ii];
				if(s2baia_cp_clipb_link.remove){
					s2baia_cp_clipb_link.remove();
				}
            }
        }
        
    });
})(jQuery);

    
    

</script>
