<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT . 'chatbot_styles_nonce');

?>
<div id="s2baia-tabs-7" class="s2baia_tab_panel" data-s2baia="7">
<div class="inside">
        <div class="s2baia_config_items_wrapper">
            <div style="padding: 1em 1.4em;">
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Privacy and User Notification', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('Our dedication to safeguarding your and your visitors\' privacy is of utmost importance when using our chatbot. Here are the main points regarding how we handle privacy issues:', 's2b-ai-aiassistant'); ?> 
                </p>
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Transparent Communication', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('Visitors should be made aware that their conversations with the chatbot are being recorded. This information should be displayed before they start using the chatbot. You can use Compliance Text on the General TAB for this purpose.', 's2b-ai-aiassistant'); ?> 
                </p>
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Purpose of Data Collection', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('The data gathered might be utilized to enhance user experience and chatbot performance. It is important to make sure that all information is managed securely and in accordance with applicable privacy laws.', 's2b-ai-aiassistant'); ?> 
                </p>
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Data Storage and Use', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('Details regarding the storage and utilization of the collected data are given, and they should comply with privacy regulations such as GDPR and CCPA.', 's2b-ai-aiassistant'); ?> 
                </p>
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Conversation Log Deletion', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('You can delete logs by click delete icon near each log record.', 's2b-ai-aiassistant'); ?> 
                </p>                 
                
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Privacy Policy and Link', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('We recommend adding a link to your privacy policy within the chatbot interface. This policy needs to outline how chatbot data is handled. A link to your site\'s privacy policy should be included in the Example Message below, which describes the details of chatbot data management. Please seek advice from qualified legal counsel and professionals to ensure your privacy policy adheres to all relevant laws and regulations.', 's2b-ai-aiassistant'); ?> 
                </p>
                
                
                <h4 class="s2baia_instruction" id="">
                    <?php echo esc_html__('Example Message', 's2b-ai-aiassistant'); ?> 
                </h4>
                <p class="s2b-notification">
                    <?php echo esc_html__('"Please be aware that we record your interactions with our chatbot to enhance our services and offer superior support. We prioritize your privacy, and all information is managed following our privacy policy, accessible <a href=\'https//...url of your privacy page ...\'>here</a>.. By using the chatbot, you agree to these practices."', 's2b-ai-aiassistant'); ?> 
                </p>
            </div>
            
        </div>
</div>
</div>
