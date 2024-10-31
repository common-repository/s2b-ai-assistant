<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$wp_nonce = wp_create_nonce(S2BAIA_PREFIX_SHORT.'imggen_nonce');
$menu_page = S2BAIA_PREFIX_LOW . 'settings';
$p_types = get_post_types();
$stored_selected_p_types = get_option(S2BAIA_PREFIX_LOW . 'selected_types');
$s2baia_image_save_media_text = get_option(S2BAIA_PREFIX_LOW.'img_save_text',esc_html__('Save to Media','gpt3-ai-content-generator'));
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





$models = [];
?>
<div id="s2baia-tabs-1" class="s2baia_tab_panel" data-s2baia="1">
    <div class="inside">
        <div class="s2baia_config_items_wrapper">
            <form action="" method="post" id="s2baia_img_gen_form">    
                <input type="hidden" name="<?php echo esc_html(S2BAIA_PREFIX_SHORT); ?>imggen_nonce" value="<?php echo esc_html($wp_nonce); ?>"/>
                <input type="hidden" name="action" value="s2baia_image_generate"/>

                <div class="s2baia_data_column_container">
                    <div class="s2baia_data_column s2baia_left_column">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Image Generation', 's2b-ai-aiassistant'); ?></h3>
                                </div>

                                <div class="s2baia_block_content" >
                                    <?php
                                    $prompt_examples = S2bAia_ImageUtils::getPromptExamples();
                                    $r_prompt = esc_html($prompt_examples[array_rand($prompt_examples)]);
                                    
                                    ?>
                                    <div  class="s2baia_row_content s2baia_pr">
                                        <div  style="position:relative;">
                                            <label for="s2baia_open_ai_gpt_key" class="s2baia_lbl"><?php esc_html_e('Prompt', 's2b-ai-aiassistant'); ?>:</label>
                                            <textarea id="s2baia_text_c" name="s2baia_text_c" rows="3" style="width: 100%;"><?php echo esc_textarea($r_prompt); ?></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="s2baia_block_content" >
                                    <div  class="s2baia_row_content s2baia_pr s2baia_mt10">
                                        <div  style="position:relative;">
                                            <label for="s2baia_open_ai_gpt_key" class="s2baia_lbl"><?php esc_html_e('The number of images that are being generated', 's2b-ai-aiassistant'); ?>:</label>
                                            
                                            <select id="<?php echo esc_html(S2BAIA_PREFIX_LOW); ?>images_count" name="<?php echo esc_html(S2BAIA_PREFIX_LOW); ?>images_count">
                                            <?php
                                            $s2baia_images_count = get_option(S2BAIA_PREFIX_LOW . 'image_generator_cnt', '1');
                                            $s2baia_images_variations = S2bAia_ImageUtils::getImageCountVars();
                                            
                                            foreach($s2baia_images_variations as $s2baia_images_variation){
                                                if($s2baia_images_variation == $s2baia_images_count){
                                                    $sel_opt = 'selected';
                                                }else{
                                                    $sel_opt = '';
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($s2baia_images_variation); ?>" <?php echo esc_html($sel_opt);  ?>> <?php echo esc_html($s2baia_images_variation); ?> </option>
                                                <?php
                                            }
                                            ?>
                                            </select>

                                        </div>

                                    </div>
                        </div>
                <div class="s2baia_block_content">

                    <div class="s2baia_row_content s2baia_pr">

                        <div class="s2baia_bloader s2baia_gbutton_container">
                            <div style="padding: 1em 1.4em;">
                                <input type="submit" value="<?php echo esc_html__('Surprise Me', 's2b-ai-aiassistant') ?>" name="s2baia_surpriseme" id="s2baia_surpriseme" class="button button-primary button-large" onclick="s2baiaGetRandPrompt(event);" >
                                <input type="submit" value="<?php echo esc_html__('Generate', 's2b-ai-aiassistant') ?>" name="s2baia_submit" id="s2baia_submit" class="button button-primary button-large"  >
                                <div class="s2baia-custom-loader  s2baia-img-loader"></div>
                            </div>

                            <div class="s2baia-custom-loader s2baia-general-loader" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                                
                <div class="image-generated">
                            <div class="image-generate-loading" id="image-generate-loading"><div class="lds-dual-ring"></div></div>
                            <div class="image-grid s2baia-mb-5" id="image-grid">
                            </div>
                            <div style="<?php echo is_user_logged_in()? '' : 'display:none'?>">
                            <br><br>
                            <div id="s2baia_message" class="s2baia_message" style="text-align: center;margin-top: 10px;"></div>
                            <div class="s2baia-convert-progress s2baia-convert-bar" id="s2baia-convert-bar" style="display:none;">
                                <span></span>
                                <small>0%</small>
                            </div>
                            <div class="s2baia_bloader">
                            <button type="button" id="image-generator-save" class="button button-primary s2baia-button image-generator-save" style="width: 100%;display: none"><?php echo esc_html($s2baia_image_save_media_text)?></button>
                            <div class="s2baia-custom-loader  s2baia-img-loader2"></div>
                            </div>
                            </div>
                </div>                

                           


                            </div>
                        </div>    
                    </div>
                    <div class="s2baia_data_column s2baia_right_column ">
                        <div class="s2baia_block ">
                            <div style="position:relative;">
                                 <?php
                                    include S2BAIA_PATH . '/views/backend/image/settings.php';
                                    ?>
                            </div>
                        </div> 
                    </div>

                </div>

            </form>
        </div>

    </div>
</div>

<script>
    let s2baiaImageNonce = '<?php echo esc_html(wp_create_nonce( 's2baia-imagelog' ))?>';
    let s2baiaImageSaveNonce = '<?php echo esc_html(wp_create_nonce('s2baia-ajax-nonce'))?>';
    let s2baiaSelectAllText = '<?php echo esc_html('Select All')?>';
    
    let s2baia_message_config_general_error = '<?php esc_html_e('There were errors during store configuration.', 's2b-ai-aiassistant'); ?>';
    let s2baia_message_config_general_succes1 = '<?php esc_html_e('Configuration stored successfully.', 's2b-ai-aiassistant'); ?>';
    
    jQuery(document).ready(function () {
        
        
            
    }
    
    );
    
    function s2baiaImageLoadingEffect(btn, loader_selector){
        console.log('btn.id='+btn.id);
        let l_selector = '.s2baia-img-loader';
        if(loader_selector){
            l_selector = loader_selector;
        }
        let loader = document.querySelector(l_selector);
        loader.style.left = '200' +  'px';
        loader.style.top =  '-30px';
        s2baiaShowLoader(l_selector);
        btn.setAttribute('disabled','disabled');
        btn.innerHTML += '<span class="s2baia-loader"></span>';
    }
    
    function s2baiaImageRmLoading(btn, hide_loader_selector){
        if(hide_loader_selector){
            s2baiaHideLoader(hide_loader_selector);
        }else{
            s2baiaHideLoader('.s2baia-img-loader');
        }
        btn.removeAttribute('disabled');
        if(s2baiaHasChildElement(btn)){
            btn.removeChild(btn.getElementsByTagName('span')[0]);
        }
        let mtop = window.getComputedStyle(document.querySelector('.s2baia_right_column')).marginTop;
        if(mtop === '300px'){
            let rcolumn = document.querySelector(".s2baia_right_column");
            rcolumn.classList.add("s2baia_right_column2");   
        }
        //console.log('margin-top:'+window.getComputedStyle(document.querySelector('.s2baia_right_column')).marginTop);
        
    }
  
    function s2baiaHasChildElement(elm) {
        
        let child, rv;

        if (elm.children) {

            rv = elm.children.length !== 0;
        } else {
            // The hard way...
            rv = false;
            for (child = element.firstChild; !rv && child; child = child.nextSibling) {
                if (child.nodeType == 1) { // 1 == Element
                    rv = true;
                }
            }
        }
        return rv;
    }
    function s2baiaGetRandPrompt(e){
        e.preventDefault();
        let randomIndex = Math.floor(Math.random() * <?php echo esc_html(count($prompt_examples)); ?>);
        document.getElementById("s2baia_text_c").value = <?php echo wp_json_encode($prompt_examples,JSON_HEX_TAG); ?> [randomIndex];
    }
    
    function s2baiaImageCloseModal() {
        document.querySelectorAll('.s2baia_modal_close')[0].addEventListener('click', event => {
            document.querySelectorAll('.s2baia_modal_content')[0].innerHTML = '';
            document.querySelectorAll('.s2baia-overlay')[0].style.display = 'none';
            document.querySelectorAll('.s2baia_modal')[0].style.display = 'none';
        })
    }
    
    function s2baiaSaveImageData(id){
        var item = document.getElementById('s2baia-image-item-'+id);
        item.querySelectorAll('.s2baia-image-item-alt')[0].value = document.querySelectorAll('.s2baia_edit_item_alt')[0].value;
        item.querySelectorAll('.s2baia-image-item-title')[0].value = document.querySelectorAll('.s2baia_edit_item_title')[0].value;
        item.querySelectorAll('.s2baia-image-item-caption')[0].value = document.querySelectorAll('.s2baia_edit_item_caption')[0].value;
        item.querySelectorAll('.s2baia-image-item-description')[0].value = document.querySelectorAll('.s2baia_edit_item_description')[0].value;
        let l_selector = '.s2baia-modal-loader';
        
        let loader = document.querySelector(l_selector);
        loader.style.left = '75%';
        loader.style.top =  '50%';
        
        s2baiaShowLoader('.s2baia-modal-loader');
        if(s2baiaImage){
            s2baiaImage.save_image([id],0);
        }
    }
    
</script>
<style>
.image-grid {
        grid-template-columns: repeat(3,1fr);
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        display: grid;
        grid-template-rows: auto auto;
    }
    
    .s2baia-image-item {
        background-size: cover;
        box-shadow: 0px 0px 10px #ccc;
        position: relative;
        cursor: pointer;
    }
    .s2baia-image-item img{
        width: 100%; /* instead of max-width */
        height: auto;
    }
    .s2baia-image-item label{
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .s2baia_left_column{
        min-width:45%;
    }
    /*s2baia_right_column*/
    @media only screen and (max-width: 768px) {
        .s2baia_right_column {
            margin-top: 150px; /* make it one column on small screens */
        }
    }
    
    
    
    
    @media only screen and (max-width: 600px) {
        .image-grid {
            grid-template-columns: 1fr; /* make it one column on small screens */
        }
    }
    
    @media only screen and (min-width: 601px) and (max-width: 900px) {
        .image-grid {
            grid-template-columns: repeat(2,1fr); /* make it two columns on medium screens */
        }
    }
    
    @media only screen and (max-width: 480px) {
        .s2baia_right_column {
            margin-top: 300px; /* make it one column on small screens */
        }
        
        .s2baia_right_column2 {
            margin-top: 650px; /* make it one column on small screens */
        }
    }
</style>