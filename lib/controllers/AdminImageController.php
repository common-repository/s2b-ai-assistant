<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_AdminImageController')) {

    class S2bAia_AdminImageController extends S2bAia_BaseController {

        public $security_mode = 1;
		public $headers = [];
        const ORIGIN = 'https://api.openai.com';
        const API_VERSION = 'v1';
        const OPEN_AI_URL = self::ORIGIN . "/" . self::API_VERSION;

        public function __construct() {
            if (!class_exists('S2bAia_ImageUtils')) {
                require_once S2BAIA_PATH . '/lib/helpers/ImageUtils.php';
            }                       
            add_action('wp_ajax_s2baia_image_generate', [$this, 'imageGenerate']);
            add_action('wp_ajax_nopriv_s2baia_image_generate', [$this, 'imageGenerate']);
            add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
            add_action('wp_ajax_nopriv_s2baia_save_image_media', [$this, 'saveImageToMedia']);
            add_action('wp_ajax_s2baia_save_image_media', [$this, 'saveImageToMedia']);
            add_action('wp_ajax_s2baia_img_default_settings', [$this,'setDefaultSettings']);
            $this->headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', ''),
            ];
        }
        
        function showMainView(){
            $this->showImage();
        }
        
        function showImage() {
            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                return;
            }
            $s2baia_open_ai_gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            $conf_contr = $this;
            $conf_contr->load_view('backend/image', ['s2baia_open_ai_gpt_key' => $s2baia_open_ai_gpt_key]);
            $conf_contr->render();
            $this->addModalWindow();
        }
        
        function addModalWindow(){
            ?>
            <div class="s2baia-overlay" style="display: none">
                <div class="s2baia_modal">
                    <div class="s2baia_modal_head">
                        <span class="s2baia_modal_title"><?php echo esc_html__('GPT3 Modal','s2b-ai-aiassistant')?></span>
                        <span class="s2baia_modal_close">&times;</span>
                    </div>
                    <div class="s2baia_modal_content"></div>
                </div>
            </div>
            <div class="s2baia-overlay-second" style="display: none">
                <div class="s2baia_modal_second">
                    <div class="s2baia_modal_head_second">
                        <span class="s2baia_modal_title_second"><?php echo esc_html__('GPT3 Modal','s2b-ai-aiassistant')?></span>
                        <span class="s2baia_modal_close_second">&times;</span>
                    </div>
                    <div class="s2baia_modal_content_second"></div>
                </div>
            </div>
            <div class="wpcgai_lds-ellipsis" style="display: none">
                <div class="s2baia-generating-title"><?php echo esc_html__('Generating content..','s2b-ai-aiassistant')?></div>
                <div class="s2baia-generating-process"></div>
                <div class="s2baia-timer"></div>
            </div>
            <script>
                let s2baia_ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php'))?>';
            </script>
            <?php
        }

        public function enqueueScripts() {
            $screen = get_current_screen();
            if (strpos($screen->id, 's2baia_image') !== false) {
                wp_enqueue_script('s2baia-init', S2BAIA_URL . '/views/resources/js/s2baia-image.js', array(), null, true);
                wp_localize_script('s2baia-init', 's2baiaParams', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'search_nonce' => wp_create_nonce('s2baia-chatbox'),
                    'logged_in' => is_user_logged_in() ? 1 : 0,
                    'languages' => array(
                        'source' => esc_html__('Sources', 's2b-ai-aiassistant'),
                        'no_result' => esc_html__('No result found', 's2b-ai-aiassistant'),
                        'wrong' => esc_html__('Something went wrong', 's2b-ai-aiassistant'),
                        'error_image' => esc_html__('Please select least one image for generate', 's2b-ai-aiassistant'),
                        'save_image_success' => esc_html__('Save images to media successfully', 's2b-ai-aiassistant'),
                        'select_all' => esc_html__('Select All', 's2b-ai-aiassistant'),
                        'unselect' => esc_html__('Unselect', 's2b-ai-aiassistant'),
                        'select_save_error' => esc_html__('Please select least one image to save', 's2b-ai-aiassistant'),
                        'alternative' => esc_html__('Alternative Text', 's2b-ai-aiassistant'),
                        'title' => esc_html__('Title', 's2b-ai-aiassistant'),
                        'edit_image' => esc_html__('Edit Image', 's2b-ai-aiassistant'),
                        'caption' => esc_html__('Caption', 's2b-ai-aiassistant'),
                        'description' => esc_html__('Description', 's2b-ai-aiassistant'),
                        'save' => esc_html__('Save', 's2b-ai-aiassistant')
                        
                    )
                ));
            }
        }

        public function imageGenerate() {
            $s2baia_result = array('status' => 'error', 'msg' => esc_html__('Something went wrong', 's2b-ai-aiassistant'));

            $s2baia_nonce = sanitize_text_field($_REQUEST['s2b_imggen_nonce']);
            if (!wp_verify_nonce($s2baia_nonce, 's2b_imggen_nonce')) {
                $s2baia_result['msg'] = esc_html__('Nonce verification failed', 's2b-ai-aiassistant');
            } else {


                $prompt = sanitize_text_field($_POST['s2baia_text_c']);
                $prompt_title = sanitize_text_field($_POST['s2baia_text_c']);
                $img_size = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'size_opt']);
                $img_model = sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'models_opt']);
                // Initialize the quality variable.
                $quality = '';

                if ($img_model === 'dall-e-3-hd') {
                    $img_model = 'dall-e-3'; // Remove '-hd' part
                    $quality = 'hd'; // Set quality to 'hd'
                }
                $num_images = (int) sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'images_count']);
                // Set the number of images to 1 if the model is 'dall-e-3' or 'dall-e-3-hd'.
                // Set the number of images to 1 if the model is 'dall-e-3' or 'dall-e-3-hd'.
                if ($img_model === 'dall-e-3' || $img_model === 'dall-e-3-hd') {
                    $num_images = 1;

                    // If the image size is either '256x256' or '512x512', set it to '1024x1024'.
                    if (in_array($img_size, ['256x256', '512x512'])) {
                        $img_size = '1024x1024';
                    }
                }

                $prompt_elements = array(
                    S2BAIA_PREFIX_LOW . 'artist_opt' => esc_html__('Painter', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'style_opt' => esc_html__('Style', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'photography_opt' => esc_html__('Photography Style', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'composition_opt' => esc_html__('Composition', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'resolution_opt' => esc_html__('Resolution', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'color_opt' => esc_html__('Color', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'special-effects_opt' => esc_html__('Special Effects', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'lighting_opt' => esc_html__('Lighting', 's2b-ai-aiassistant'),
                    S2BAIA_PREFIX_LOW . 'camera_opt' => esc_html__('Camera Settings', 's2b-ai-aiassistant'),
                );
                foreach ($prompt_elements as $key => $value) {
                    if (isset($_POST[$key]) &&  $_POST[$key] != "None") {
                        $prompt = $prompt . ". " . $value . ": " . sanitize_text_field($_POST[$key]);
                    }
                }
                //$imgresult = '';//remove

                $imgresult = $this->imageRequest([
                    "model" => $img_model,
                    "prompt" => $prompt,
                    "n" => $num_images,
                    "size" => $img_size,
                    "response_format" => "url",
                ]);
                // If quality is set to 'hd', add it to the request array.
                if ($quality === 'hd') {
                    $image_request_array['quality'] = $quality;
                }
                $img_result = json_decode($imgresult);
                if (isset($img_result->error)) {
                    $s2baia_result['msg'] = trim($img_result->error->message);
                    if (strpos($s2baia_result['msg'], 'limit has been reached') !== false) {
                        $s2baia_result['msg'] .= ' ' . esc_html__('Please note that this message is coming from OpenAI and it is not related to our plugin. It means that you do not have enough credit from OpenAI. You can check your usage here: https://platform.openai.com/account/usage', 's2b-ai-aiassistant');
                    }
                } else {
                    $s2baia_result['imgs'] = array();
                    //$num_images = 9;//remove
                    //sleep(20);//remove
                    for ($i = 0; $i < $num_images; $i++) {
                        $s2baia_result['imgs'][] = $img_result->data[$i]->url;//$this->getTestImage();//remove//$img_result->data[$i]->url;//
                    }
                    $s2baia_result['title'] = $prompt_title;
                    $s2baia_result['status'] = 'success';
                    
                }
            }
            wp_send_json($s2baia_result);
        }

        function getTestImage(){
            
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => - 1,
            );

            $query_images = new WP_Query( $query_images_args );

            $images = array();
            foreach ( $query_images->posts as $image ) {
                $images[] = wp_get_attachment_url( $image->ID );
            }
            $idx = (int)random_int(0, count($images) -1);
            return $images[$idx];
        }
        
        public function saveImageToMedia() {
            $s2baia_result = array('status' => 'error', 'msg' => esc_html__('Something went wrong', 's2b-ai-aiassistant'));
            if (!wp_verify_nonce($_POST['nonce'], 's2baia-ajax-nonce')) {
                $s2baia_result['status'] = 'error';
                $s2baia_result['msg'] = esc_html__('Nonce verification failed', 's2b-ai-aiassistant');
                wp_send_json($s2baia_result);
            }
            //sleep(7);//remove
            if (
                    isset($_POST['image_url']) && !empty($_POST['image_url'])
            ) {
                $url = sanitize_url($_POST['image_url']);
                $image_title = isset($_POST['image_title']) && !empty($_POST['image_title']) ? sanitize_text_field($_POST['image_title']) : '';
                $image_alt = isset($_POST['image_alt']) && !empty($_POST['image_alt']) ? sanitize_text_field($_POST['image_alt']) : '';
                $image_caption = isset($_POST['image_caption']) && !empty($_POST['image_caption']) ? sanitize_text_field($_POST['image_caption']) : '';
                $image_description = isset($_POST['image_description']) && !empty($_POST['image_description']) ? sanitize_text_field($_POST['image_description']) : '';
                $s2baia_image_attachment_id = $this->saveImage($url, $image_title);
                if ($s2baia_image_attachment_id['status'] == 'success') {
                    wp_update_post(array(
                        'ID' => $s2baia_image_attachment_id['id'],
                        'post_content' => $image_description,
                        'post_excerpt' => $image_caption
                    ));
                    update_post_meta($s2baia_image_attachment_id['id'], '_wp_attachment_image_alt', $image_alt);
                    $s2baia_result['status'] = 'success';
                } else {
                    $s2baia_result['msg'] = $s2baia_image_attachment_id['msg'];
                }
            }
            wp_send_json($s2baia_result);
        }

        public function saveImage($imageurl, $image_title = '') {
            global $wpdb;
            $result = array('status' => 'error', 'msg' => esc_html__('Can not save image to media', 's2b-ai-aiassistant'));
            if (!function_exists('wp_generate_attachment_metadata')) {
                include_once( ABSPATH . 'wp-admin/includes/image.php' );
            }
            if (!function_exists('download_url')) {
                include_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            if (!function_exists('media_handle_sideload')) {
                include_once( ABSPATH . 'wp-admin/includes/media.php' );
            }
            try {
                $array = explode('/', getimagesize($imageurl)['mime']);
                $imagetype = end($array);
                $uniq_name = md5($imageurl);
                $filename = $uniq_name . '.' . $imagetype;
                $checkExist = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->postmeta} WHERE meta_value LIKE %s", '%/' . $wpdb->esc_like($filename)));
                if ($checkExist) {
                    $result['status'] = 'success';
                    $result['id'] = $checkExist->post_id;
                } else {
                    $tmp = download_url($imageurl);
                    if (is_wp_error($tmp)) {
                        $result['msg'] = $tmp->get_error_message();
                        return $result;
                    }
                    $args = array(
                        'name' => $filename,
                        'tmp_name' => $tmp,
                    );
                    $attachment_id = media_handle_sideload($args, 0, '', array(
                        'post_title' => $image_title,
                        'post_content' => $image_title,
                        'post_excerpt' => $image_title
                    ));
                    if (!is_wp_error($attachment_id)) {
                        update_post_meta($attachment_id, '_wp_attachment_image_alt', $image_title);
                        $imagenew = get_post($attachment_id);
                        $fullsizepath = get_attached_file($imagenew->ID);
                        $attach_data = wp_generate_attachment_metadata($attachment_id, $fullsizepath);
                        wp_update_attachment_metadata($attachment_id, $attach_data);
                        $result['status'] = 'success';
                        $result['id'] = $attachment_id;
                    } else {
                        $result['msg'] = $attachment_id->get_error_message();
                        return $result;
                    }
                }
            } catch (\Exception $exception) {
                $result['msg'] = $exception->getMessage();
            }
            return $result;
        }

        public function registerAdminMenu() {
            
        }

        public function imageRequest($opts) {
            $url = self::imageUrl() . "/generations";

            return $this->sendRequest($url, 'POST', $opts);
        }

        public static function imageUrl(): string {
            return self::OPEN_AI_URL . "/images";
        }

        private function sendRequest(string $url, string $method, array $opts = []) {

            $post_fields = json_encode($opts);
            if (array_key_exists('file', $opts)) {
                $boundary = wp_generate_password(24, false);
                $this->headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
                $post_fields = $this->create_body_for_file($opts['file'], $boundary);
            } elseif (array_key_exists('audio', $opts)) {
                $boundary = wp_generate_password(24, false);
                $this->headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
                $post_fields = $this->create_body_for_audio($opts['audio'], $boundary, $opts);
            } else {
                $this->headers['Content-Type'] = 'application/json';
            }
            $stream = false;
            if (array_key_exists('stream', $opts) && $opts['stream']) {
                $stream = true;
            }
            $timeout = get_option(S2BAIA_PREFIX_LOW . 'response_timeout', 50);
            $request_options = array(
                'timeout' => $timeout,
                'headers' => $this->headers,
                'method' => $method,
                'body' => $post_fields,
                'stream' => $stream
            );
            if ($post_fields == '[]') {
                unset($request_options['body']);
            }
            $response = wp_remote_request($url, $request_options);
            if (is_wp_error($response)) {
                return json_encode(array('error' => array('message' => $response->get_error_message())));
            } else {
                if ($stream) {
                    return $this->response;
                } else {
                    return wp_remote_retrieve_body($response);
                }
            }
        }


        
        public function setDefaultSettings()
        {
            if ( ! wp_verify_nonce( $_POST['s2b_imggen_nonce'], 's2b_imggen_nonce' ) ) {
                $s2baia_result['status'] = 'error';
                $s2baia_result['msg'] = esc_html__('Nonce verification failed','s2b-ai-aiassistant');
                wp_send_json($s2baia_result);
            }
                
                $keys = S2bAia_ImageUtils::getSettingsKeys();
                $result = array();
                foreach($keys as $key){
                    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
                        $result[$key] = sanitize_text_field($_REQUEST[$key]);
                    }
                }
                $images_count = (int)$_REQUEST['s2baia_images_count'];
                
                update_option(S2BAIA_PREFIX_LOW . 'image_generator_cnt',$images_count);
                update_option(S2BAIA_PREFIX_LOW . 'image_generator',$result);
                
            wp_send_json(array('status' => 'success'));
        }
        

    }

}
