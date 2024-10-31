<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$dropdown_images = S2bAia_ImageUtils::getSettings();
?>
                    <div class="s2baia_block_header">
                                    <h3><?php esc_html_e('Settings', 's2b-ai-aiassistant'); ?></h3>
                    </div>


				<?php
				foreach ( $dropdown_images as $label => $image_options ) :
					?>
				<div class="s2baia_block_content s2baia_mt10">

                                            <div class="s2baia_row_header">
						<label for="<?php echo esc_attr( $image_options['id'] ); ?>" class="s2baia_lbl s2baia_block"><?php echo esc_html( $label ); ?>:</label>
                                            </div>
                                            <div class="s2baia_row_content s2baia_pr">
                                                <div style="position:relative;">
                                                <select class="s2baia_selection" id="<?php echo esc_attr( $image_options['id'] ); ?>" name="<?php echo esc_attr( $image_options['id'] ); ?>">
						<?php
						foreach ( $image_options['option'] as $option_key => $option ) :
							$selected = '';
							if ( $option_key === $image_options['option_selected'] ) {
								$selected = ' selected';
							}
							?>
							<option value="<?php echo esc_html( $option ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
						<?php endforeach; ?>
						</select>
                                            </div>
                                            </div>
                                        
                                            </div>
					<?php
				endforeach;
				?>

			<div class="s2baia_block_content">
                                    
                                    <div class="s2baia_row_content s2baia_pr">
                                        <div style="position:relative;">
				<button type="button" id="s2baia_set_default_setting" class="button button-primary button-large"><?php echo esc_html__( 'Save Settings', 's2b-ai-aiassistant' ); ?></button>
			</div>
                                        </div>
                            </div>
			

<script>
<?php
    if(is_admin()):
    ?>
    let s2baiaSetDefault = document.getElementById('s2baia_set_default_setting');
    let s2baiaImageForm = document.getElementById('s2baia_img_gen_form');
    if(s2baiaSetDefault) {
        s2baiaSetDefault.addEventListener('click', function () {

            s2baiaImageLoadingEffect(s2baiaSetDefault);
            let queryString = new URLSearchParams(new FormData(s2baiaImageForm)).toString();
            queryString += '&action=s2baia_img_default_settings';
            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', s2baiaParams.ajax_url);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(queryString);
            xhttp.onreadystatechange = function (ev) {
                if (xhttp.readyState === 4) {
                    if (xhttp.status === 200) {
                        let s2baiaParentCol = s2baiaSetDefault.parentElement;
                        let successMessage = document.createElement('div');
                        successMessage.style.color = '#AE1F00';
                        successMessage.style.fontWeight = 'bold';
                        successMessage.innerHTML = '<?php echo esc_html__('Settings updated successfully','gpt3-ai-content-generator')?>';
                        s2baiaParentCol.appendChild(successMessage);
                        setTimeout(function (){
                            successMessage.remove();
                        },4000);
                        s2baiaImageRmLoading(s2baiaSetDefault);
                    }
                }
            }
        });
    }
    <?php
    endif;
    ?>
</script>
