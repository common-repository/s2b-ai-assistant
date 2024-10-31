<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_InstructionsModel')) {
    require_once S2BAIA_PATH . '/lib/models/InstructionsModel.php';
}


if (!class_exists('S2bAia_AdminConfigController')) {

    class S2bAia_AdminConfigController extends S2bAia_BaseController {

        public $security_mode = 1;

        
        public function __construct() {
            add_action('wp_ajax_s2b_gpt_conf_models', [$this, 'gptGetModels']);
            add_action('wp_ajax_s2b_gpt_conf_store_instruction', [$this, 'gptConfStoreInstruction']);
            add_action('wp_ajax_s2b_gpt_toggle_instruction', [$this, 'gptToggleInstruction']);
            add_action('wp_ajax_s2b_gpt_load_instruction', [$this, 'gptLoadInstructions']);
            add_action('wp_ajax_s2b_gpt_delete_instruction', [$this, 'gptDeleteInstruction']);

            add_action('wp_ajax_s2b_store_general_tab', [$this, 'processSettingsSubmit']);
            add_action('wp_ajax_s2b_store_models_tab', [$this, 'processModelsSubmit']);
        }

        public function registerAdminMenu() {

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                return false;
            }

            $settings_hook = add_submenu_page(S2BAIA_PREFIX_LOW . 'image', __('Settings', 's2b-ai-aiassistant'), __('Settings', 's2b-ai-aiassistant'), 'edit_posts', S2BAIA_PREFIX_LOW . 'settings', [$this, 'showSettings']);

            add_action('load-' . $settings_hook, [$this, 'processSettingsSubmit']);
        }

        public function processSettingsSubmit() {

            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                return;
            }

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = S2BAIA_PREFIX_SHORT . 'config_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            if (isset($_POST['s2baia_open_ai_gpt_key'])) {
                $api_key = sanitize_text_field($_POST['s2baia_open_ai_gpt_key']);
                update_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', $api_key);
            }

            
            $p_types = get_post_types();
            $selected_post_types = isset($_POST[S2BAIA_PREFIX_LOW . 'ptypes']) ? array_map('sanitize_key',array_keys($_POST[S2BAIA_PREFIX_LOW . 'ptypes'])) : [];
            $ptres = [];
            foreach ($selected_post_types as $pt) {
                if (in_array($pt, $p_types)) {
                    $ptres[] = $pt;
                }
            }

            $selected_ptypes = serialize($ptres);
            update_option(S2BAIA_PREFIX_LOW . 'selected_types', $selected_ptypes);


            if (isset($_POST[S2BAIA_PREFIX_LOW . 'response_timeout'])) {
                $response_timeout = (int) $_POST[S2BAIA_PREFIX_LOW . 'response_timeout'];
                if($response_timeout <= 0){
                    $response_timeout = 50;
                }
                if($response_timeout >300){
                    $response_timeout = 200;
                }
                update_option(S2BAIA_PREFIX_LOW . 'response_timeout', $response_timeout);
            }

            if (isset($_POST[S2BAIA_PREFIX_LOW . 'max_tokens'])) {
                $max_tokens = (int) $_POST[S2BAIA_PREFIX_LOW . 'max_tokens'];
                if($max_tokens <= 0){
                    $max_tokens = 1024;
                }
                update_option(S2BAIA_PREFIX_LOW . 'max_tokens', $max_tokens);
            }

            //count_of_instructions that are displayed in correction text meta box
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'count_of_instructions'])) {
                $count_of_instructions = (int) $_POST[S2BAIA_PREFIX_LOW . 'count_of_instructions'];
                if($count_of_instructions <= 0){
                    $count_of_instructions = 1;
                }
                update_option(S2BAIA_PREFIX_LOW . 'count_of_instructions', $count_of_instructions);
            }
            
            $instructions_roles = S2bAia_Utils::getInstructionRoles();
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'config_delete_instructions'])) {
                $config_delete_instructions =  sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'config_delete_instructions']);
                if(in_array($config_delete_instructions, $instructions_roles) ){
                    update_option(S2BAIA_PREFIX_LOW . 'config_delete_instructions', $config_delete_instructions);
                }
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'config_edit_instructions'])) {
                $s2baia_config_edit_instructions =  sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'config_edit_instructions']);
                if(in_array($s2baia_config_edit_instructions, $instructions_roles) ){
                    update_option(S2BAIA_PREFIX_LOW . 'config_edit_instructions', $s2baia_config_edit_instructions);
                }
            }
            
            if (isset($_POST[S2BAIA_PREFIX_LOW . 'config_meta_instructions'])) {
                $s2baia_config_meta_instructions =  sanitize_text_field($_POST[S2BAIA_PREFIX_LOW . 'config_meta_instructions']);
                if(in_array($s2baia_config_meta_instructions, $instructions_roles) ){
                    update_option(S2BAIA_PREFIX_LOW . 'config_meta_instructions', $s2baia_config_meta_instructions);
                }
            }
            
            $r['result'] = 200;
            $r['msg'] = __('OK','s2b-ai-aiassistant');
            wp_send_json($r);
            exit;
        }

        function processModelsSubmit() {
            if (('POST' !== $_SERVER['REQUEST_METHOD'])) {
                return;
            }

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = S2BAIA_PREFIX_SHORT . 'models_nonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }


            if (!isset($_POST[S2BAIA_PREFIX_LOW . 'models'])) {
                $r['result'] = 404;
                $r['msg'] = __('Models not sent','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $umodels = unserialize(get_option('s2baia_chatgpt_models', []));
            $local_models = S2bAia_Utils::sanitizeArrayModels($umodels);
            $sent_models = array_map('sanitize_text_field',array_keys($_POST[S2BAIA_PREFIX_LOW . 'models']));
            $new_models = [];
            foreach ($local_models as $lmkey => $lmval) {
                if (in_array($lmkey, $sent_models)) {

                    if ($lmval == 0 || $lmval == 2) {
                        $new_models[$lmkey] = ($lmval + 1);
                    } else {
                        $new_models[$lmkey] = (int) $lmval;
                    }
                } else {

                    if ($lmval == 1 || $lmval == 3) {
                        $new_models[$lmkey] = ($lmval - 1);
                    } else {
                        $new_models[$lmkey] = (int) $lmval;
                    }
                }
            }
            if (count($new_models)) {
                update_option('s2baia_chatgpt_models', serialize($new_models));
            }

            //update models that are displayed in Expert tab

            $local_models_e = unserialize(get_option('s2baia_chatgpt_expert_models', []));
            $sent_models_e = array_map('sanitize_text_field',array_keys($_POST[S2BAIA_PREFIX_LOW . 'expert_models']));
            $new_models_e = [];
            foreach ($local_models_e as $lmkey => $lmval) {
                if (in_array($lmkey, $sent_models_e)) {

                    if ($lmval == 0 || $lmval == 2) {
                        $new_models_e[$lmkey] = ($lmval + 1);
                    } else {
                        $new_models_e[$lmkey] = (int) $lmval;
                    }
                } else {

                    if ($lmval == 1 || $lmval == 3) {
                        $new_models_e[$lmkey] = ($lmval - 1);
                    } else {
                        $new_models_e[$lmkey] = (int) $lmval;
                    }
                }
            }
            if (count($new_models_e)) {
                update_option('s2baia_chatgpt_expert_models', serialize($new_models_e));
            }

            $r['result'] = 200;
            $r['msg'] = __('OK','s2b-ai-aiassistant');
            $r['new_models'] = $new_models;
            $r['new_models_expert'] = $new_models_e;
            wp_send_json($r);
            exit;
        }

        public function showSettings() {

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                return;
            }
            $s2baia_open_ai_gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            $conf_contr = $this;
            $conf_contr->load_view('backend/config', ['s2baia_open_ai_gpt_key' => $s2baia_open_ai_gpt_key]);
            $conf_contr->render();
        }

        public function gptGetModels() {

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];

            $nonce = 's2b_gpt_confnonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 1) {
                wp_send_json($r);
                exit;
            }

            $user_can_chat_gpt = S2bAia_Utils::checkEditInstructionAccess();
            if (!$user_can_chat_gpt) {
                $r['result'] = 4;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
            }

            if (!class_exists('S2bAia_AiRequest')) {
                require_once S2BAIA_PATH . '/lib/helpers/AiRequest.php';
            }
            $response = S2bAia_AiRequest::getFromUrl('https://api.openai.com/v1/models');
            if ($response[0] != 1) {
                $r['result'] = 401;
                $r['msg'] = __('Network error','s2b-ai-aiassistant');

            } else {
                $resp = json_decode($response[1]);
                $umodels = unserialize(get_option('s2baia_chatgpt_models', []));
                $local_models = S2bAia_Utils::sanitizeArrayModels($umodels);
                $uemodels = unserialize(get_option('s2baia_chatgpt_expert_models', []));
                $local_expert_models = S2bAia_Utils::sanitizeArrayModels($uemodels);

                if (isset($resp->data) && is_array($resp->data)) {
                    $models = $resp->data;

                    foreach ($models as $model) {
                        $parsed_model = $this->parseModelResponseObject($model);

                        if (!array_key_exists($parsed_model['id'], $local_models)) {
                            if ($parsed_model['textmodel']) {
                                $local_models[$parsed_model['id']] = 2;
                            } else {
                                $local_models[$parsed_model['id']] = 0;
                            }
                        }

                        if (!array_key_exists($parsed_model['id'], $local_expert_models)) {
                            if ($parsed_model['textmodel']) {
                                $local_expert_models[$parsed_model['id']] = 2;
                            } else {
                                $local_expert_models[$parsed_model['id']] = 0;
                            }
                        }
                    }
                }

                $r['result'] = 200;
                $r['msg'] = 'OK';
                $r['models'] = $local_models;
                $r['expert_models'] = $local_expert_models;
                //update_option('s2baia_chatgpt_models', serialize([]));
                //update_option('s2baia_chatgpt_expert_models', serialize([]));
                update_option('s2baia_chatgpt_models', serialize($local_models));
                update_option('s2baia_chatgpt_expert_models', serialize($local_expert_models));
            }

            wp_send_json($r);
            exit;
        }

        public function gptConfStoreInstruction() {

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];

            $nonce = 's2b_gpt_confinstructnonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $data = [];

            
            $data['instruction'] = sanitize_textarea_field(stripslashes($_POST['instruction']));
            

            $data['instruction_type'] = (int) $_POST['instruction_type'];
            $data['disabled'] = (int) $_POST['disabled'] == 1 ? (int) $_POST['disabled'] : 0;
            $id_instruction = $data['id'] = (int) $_POST['id_instruction'];
            $instruction_types = S2bAia_InstructionsModel::getInstructionTypes();
            if (!in_array($data['instruction_type'], $instruction_types)) {
                $r['result'] = 5;
                $r['msg'] = __('Wrong instruction type','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_InstructionsModel::getInstruction($data['id']) && $id_instruction > 0) {//verify if instruction is stored
                $r['result'] = 4;
                $r['msg'] = __('Wrong instruction','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }


            //verify idinstruction
            //instruction,disabled,instruction_type,id_instruction
            if ($id_instruction > 0) {
                $upd_res = S2bAia_InstructionsModel::updateInstruction($data);
                if ($upd_res > 0) {
                    $r['result'] = 200;
                    $r['msg'] = 'OK';
                    $r['new_instruction'] = $data;
                }
            } else {
                $new_id_instruction = S2bAia_InstructionsModel::insertInstruction($data);
                if ($new_id_instruction > 0) {
                    $data['id'] = $new_id_instruction;
                    $r['result'] = 200;
                    $r['msg'] = 'OK';
                    $data['instruction'] = wp_kses($data['instruction'], S2bAia_Utils::getInstructionAllowedTags());
                    $r['new_instruction'] = $data;
                }
            }


            wp_send_json($r);
            exit;
        }

        function gptLoadInstructionsSimple() {

            $instructions = S2bAia_InstructionsModel::getInstructions();
            $js_instructions = [];
            $i = 0;
            foreach ($instructions as $row) {
                $author = S2bAia_Utils::getUsername($row->user_id);
                $row->user_id = $author;
                $instructions[$i]->user_id = $author;
                $js_instructions[$row->id] = $row;
                $i++;
            }
            $res = ['js_instructions' => $js_instructions, 'instructions' => $instructions,
                'result' => 200];
            wp_send_json($res);
            exit;
        }

        function gptToggleInstruction() {

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = 's2b_gpt_toggleinstructnonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }

            if (!isset($_POST['id']) || $_POST['id'] == 0) {
                $r['result'] = 4;
                $r['msg'] = __('Instruction not specified','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            $id_instruction = (int) $_POST['id'];
            $instruction = S2bAia_InstructionsModel::getInstruction($id_instruction);
            if (!$instruction) {//verify id instruction
                $r['result'] = 4;
                $r['msg'] = __('Wrong instruction','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            if (!S2bAia_Utils::checkEditInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }

            $disabled = $instruction->disabled;
            if ($disabled == 1) {
                $toggle_val = 0;
            } else {
                $toggle_val = 1;
            }
            $upd_res = S2bAia_InstructionsModel::toggleInstruction($id_instruction, $toggle_val);
            if ($upd_res > 0) {
                $r['result'] = 200;
                $r['msg'] = 'OK';
                $r['new_instruction'] = ['id' => $id_instruction, 'disabled' => $toggle_val];
            }

            wp_send_json($r);
            exit;
        }

        function gptDeleteInstruction() {

            $r = ['result' => 0, 'msg' => __('Unknow problem','s2b-ai-aiassistant')];
            $nonce = 's2b_gpt_delinstructnonce';
            $r = $this->verifyPostRequest($r, $nonce, $nonce);
            if ($r['result'] > 0) {
                wp_send_json($r);
                exit;
            }
            if (!isset($_POST['id']) || $_POST['id'] == 0) {
                $r['result'] = 4;
                $r['msg'] = __('Instruction not specified','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            $id_instruction = (int) $_POST['id'];
            $instruction = S2bAia_InstructionsModel::getInstruction($id_instruction);
            if (!$instruction) {//verify id instruction
                $r['result'] = 4;
                $r['msg'] = __('Wrong instruction','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }
            //check if user is allowed to delete instruction
            if (!S2bAia_Utils::checkDeleteInstructionAccess()) {
                $r['result'] = 10;
                $r['msg'] = __('Access denied','s2b-ai-aiassistant');
                wp_send_json($r);
                exit;
            }


            $del_res = S2bAia_InstructionsModel::deleteInstruction($id_instruction);

            if ($del_res > 0) {
                $r['result'] = 200;
                $r['msg'] = 'OK';
                $r['del_instruction'] = $id_instruction;
            }

            wp_send_json($r);
            exit;
        }

        function parseModelResponseObject($model) {
            
            $model_id = isset($model->id)?sanitize_text_field($model->id):'';
            
            return ['id' => $model_id, 'textmodel' => strlen($model->id) > 0];
            
        }
    }

}