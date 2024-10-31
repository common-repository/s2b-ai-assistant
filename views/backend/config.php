<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>



<div class="s2baia_container">
    <div id="s2baia_configtabs">


        <ul>
            <li><a href="#s2baia-tabs-1"><?php echo esc_html__('ChatGPT', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-2"><?php echo esc_html__('Models', 's2b-ai-aiassistant') ?></a></li>
            <li><a href="#s2baia-tabs-3"><?php echo esc_html__('Instructions', 's2b-ai-aiassistant') ?></a></li>
        </ul>
        <?php
        include S2BAIA_PATH . '/views/backend/config_gpt_general.php';
        include S2BAIA_PATH . '/views/backend/config_gpt_models.php';
        include S2BAIA_PATH . '/views/backend/config_gpt_correction.php';
        ?>
        <div class="s2baia_bloader">
            <div style="padding: 1em 1.4em;"></div>


        </div>

    </div>
</div>



<script>
    const s2baajaxAction = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';

    let s2b_gpt_confnonce = "<?php echo esc_html(wp_create_nonce('s2b_gpt_confnonce')); ?>";
    let s2baia_instruction_table_height = 0;

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
        let s2baia_instruction = document.querySelector("#s2baia_instruction");
        s2baia_instruction.addEventListener("paste", (event) => {
            //event.preventDefault();
            let paste = (event.clipboardData || window.clipboardData).getData("text");
            let len = event.target.value.length;
            if (len > 0 || paste.length > 0) {
                document.querySelector('#s2baia_submit_edit_instruction').disabled = false;//s2baia_submit_edit_instruction
            } else {
                document.querySelector('#s2baia_submit_edit_instruction').disabled = true;
            }
        });


    });





</script>