<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('S2bAia_AiRequest')) {

    class S2bAia_AiRequest {

        public static $gpt_key = '';
        public static $model = 'gpt-4o-mini';
        public static $files_api_url = 'https://api.openai.com/v1/files';
        public static $chat_completion_endpoint = 'https://api.openai.com/v1/chat/completions';
        public static $assistant_api = 'https://api.openai.com/v1/assistants';
        public static $thread_url = "https://api.openai.com/v1/threads";
        public static $http_client = 'curl';

        /* prepares chat completion chatGPT API request and calls :sendChatGptRequest
          @param $data     array  received from Edit&Extend metabox form
         * see https://platform.openai.com/docs/api-reference/chat/create 
          Returns the array in format  [error_code,response] see sendChatGptRequest
         *          */

        public static function sendChatGptEdit($data) {

            $model = $data['model'];
            $temperature = $data['temperature'];
            $instruction = $data['instruction'];
            $max_tokens = $data['max_tokens'];
            $body = ["model" => $model, "temperature" => $temperature, "max_tokens" => $max_tokens,
                "messages" => [
                    ["role" => "system", "content" => "Help to change user\'s text according to such instruction:" . $instruction],
                    ["role" => "user", "content" => $data['text']]
                ]
            ];
            $body_str = json_encode($body);
            $result = self::sendChatGptRequest($body_str);
            return $result;
        }

        /* prepares chat completion chatGPT API request and calls :sendChatGptRequest
          @param $data     array  received from client side form
         * see https://platform.openai.com/docs/api-reference/chat/create 
          Returns the array in format  [error_code,response] see sendChatGptRequest
         *          */

        public static function sendChatGptCompletion($data) {

            $model = $data['model'];
            $temperature = $data['temperature'];

            $max_tokens = $data['max_tokens'];
            $msgs = [["role" => "system", "content" => $data['system']]];
            if (isset($data["messages"])) {
                foreach ($data["messages"] as $msg) {
                    $msgs[] = ['role' => $msg['role'], 'content' => $msg['content']];
                }
            }
            $body = ["model" => $model, "temperature" => $temperature, "max_tokens" => $max_tokens,
                "top_p" => $data['top_p'], "presence_penalty" => $data['presence_penalty'],
                "frequency_penalty" => $data['frequency_penalty'],
                "messages" => $msgs
            ];
            $body_str = json_encode($body);
            $result = self::sendChatGptRequest($body_str);
            //var_dump($result);
            return $result;
        }

        /* sends request to chatGPT API and returns response in format:       [error_code,response]
          @param $body_str     string  that is json encoded array in format
         * defined https://platform.openai.com/docs/api-reference/chat/create */

        public static function sendChatGptRequest($body_str = '', $method = 'POST', $url = '') {

            $r_url = is_string($url) && strlen($url) > 0 ? $url : self::$chat_completion_endpoint;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', ''); //s2baia_open_ai_gpt_key
            }
            $headers = [
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . self::$gpt_key
            ];
            global $wp_version;
            $response_timeout = (int) get_option(S2BAIA_PREFIX_LOW . 'response_timeout', 120);

            $request_options = array(
                'method' => $method,
                'body' => $body_str,
                'headers' => $headers,
                'user-agent' => $wp_version . '; ' . home_url(),
                'httpversion' => '1.1',
                'timeout' => $response_timeout,
                'sslverify' => false,
                'stream' => false);
            $response = wp_remote_request($r_url, $request_options);
            if (is_wp_error($response)) {
                return array(0, [$response->get_error_code(), $response->get_error_message()]);
            } else {
                if (is_array($response) && array_key_exists('response', $response) && is_array($response['response']) && array_key_exists('code', $response['response'])) {
                    $code = $response['response']['code'];
                    if ($code == 200) {
                        return [1, wp_remote_retrieve_body($response)];
                    } else {
                        if (array_key_exists('body', $response) && is_string($response['body'])) {
                            $resp_body = json_decode($response['body']);
                            if (isset($resp_body->error) && isset($resp_body->error->message)) {
                                return [0, $resp_body->error->message];
                            }
                        }
                        return [0, $response['response']['message']];
                    }
                } else {
                    return [0, wp_remote_retrieve_body($response)];
                }
            }

            return array(0, [0, 'unknown error']);
        }

        /* sends GET request to chatGPT API . It is used for example when getting all list of models
          @param $url     string  that is url of API Endpoint
         */

        public static function getFromUrl($url) {

            return self::sendChatGptRequest('', 'GET', $url);
        }

        /* tests if response from ChatGPT API has correct fromat and contains all respected fields
         * $response - json decoded response from ChatGPT API
         *  */

        public static function testChatGptResponse($response) {
            if (is_object($response) && isset($response->choices) && is_array($response->choices) && count($response->choices) > 0) {
                $choice = $response->choices[0];
                if (is_object($choice) && isset($choice->message) && is_object($choice->message) && isset($choice->message->content)) {
                    return true;
                }
            }
            return false;
        }

        /*
          method parses response from ChatGPT chat completion API and gets message
         *  $response - json decoded response from ChatGPT API
         *          */

        public static function getChatGptResponseEditMessage($response) {

            if (is_object($response) && isset($response->choices) && is_array($response->choices) && count($response->choices) > 0) {
                $choice = $response->choices[0];
                if (is_object($choice) && isset($choice->message) && is_object($choice->message) && isset($choice->message->content)) {
                    $resp_text = $choice->message->content;
                    return $resp_text;
                }
            }
            return '';
        }

        public static function createAssistantRetrievalV2($options) {

            $res = ['code' => 404, 'body' => '', 'error_msg' => '', 'id' => '', 'model' => ''
                , 'created_at' => '', 'instruction' => '', 'name' => ''];
            if (isset($options['assistant_id']) && strlen($options['assistant_id']) > 0) {//update
                $assistant_id = $options['assistant_id'];
                $request_url = self::$assistant_api . '/' . $assistant_id;
                $post_data = array(
                    'instructions' => $options['instructions'],
                    'tools' => array(
                        array(
                            'type' => 'retrieval'
                        )
                    ),
                    'model' => $options['model']
                );
            } else {//create
                $request_url = self::$assistant_api;
                $post_data = array(
                    'instructions' => $options['instructions'],
                    'name' => $options['assistant_name'],
                    'tools' => array(
                        array(
                            'type' => 'file_search'
                        )
                    ),
                    'model' => $options['model'],
                    'tool_resources' => [
                        'file_search' => [
                            'vector_stores' => [[
                            'file_ids' => array($options['file_id'])
                                ]]
                        ]
                    ],
                );
            }
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $request_url,
                    array(
                        'method' => 'POST',
                        'headers' => $headers,
                        'body' => wp_json_encode($post_data),
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id'])) {
                        $res['id'] = $res_obj['id'];
                        $res['created_at'] = $res_obj['created_at'];
                        $res['model'] = $options['model'];
                        $res['instruction'] = $options['instructions'];
                        $res['name'] = $options['assistant_name'];
                        $res['file_id'] = $options['file_id'];
                        $res['vector_store_ids'] = '';
                        if (is_array($res_obj['tool_resources']) && count($res_obj['tool_resources']) > 0) {
                            $t_r = $res_obj['tool_resources'];
                            if (is_array($t_r['file_search']) && count($t_r['file_search']) > 0) {
                                $f_s = $t_r['file_search'];
                                if (is_array($f_s['vector_store_ids']) && count($f_s['vector_store_ids']) > 0) {
                                    $res['vector_store_ids'] = $f_s['vector_store_ids'][0];
                                }
                            }
                        }
                    } else {
                        if (isset($res_obj['error']) && is_array($res_obj['error'])) {
                            $error = $res_obj['error'];
                            if (is_array($error) && isset($error['message'])) {
                                $res['error_msg'] = $error['message'];
                            }
                        }
                    }
                }
            }
            return $res;
        }

        public static function updateAssistantRetrievalV2($options) {

            $res = ['code' => 404, 'body' => '', 'error_msg' => '', 'id' => '', 'model' => ''
                , 'created_at' => '', 'instruction' => '', 'name' => ''];
            if (isset($options['assistant_id']) && strlen($options['assistant_id']) > 0) {//update
                $assistant_id = $options['assistant_id'];
                $request_url = self::$assistant_api . '/' . $assistant_id;
                $post_data = array(
                    'instructions' => $options['instructions'],
                    'tools' => array(
                        array(
                            'type' => 'file_search'
                        )
                    ),
                    'model' => $options['model']
                );
            } else {//create
                $request_url = self::$assistant_api;
                $post_data = array(
                    'instructions' => $options['instructions'],
                    'name' => $options['assistant_name'],
                    'tools' => array(
                        array(
                            'type' => 'file_search'
                        )
                    ),
                    'model' => $options['model'],
                    'tool_resources' => [
                        'file_search' => [
                            'vector_stores' => [[
                            'file_ids' => array($options['file_id'])
                                ]]
                        ]
                    ],
                );
            }
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $request_url,
                    array(
                        'method' => 'POST',
                        'headers' => $headers,
                        'body' => wp_json_encode($post_data),
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id'])) {
                        $res['id'] = $res_obj['id'];
                        $res['created_at'] = $res_obj['created_at'];
                        $res['model'] = $options['model'];
                        $res['instruction'] = $options['instructions'];
                        $res['name'] = $options['assistant_name'];
                        $res['file_id'] = $options['file_id'];
                    }
                }
            }
            return $res;
        }

        public static function updateAssistantRetrievalFileV2($options) {

            $res = ['code' => 404, 'body' => '', 'error_msg' => '', 'id' => '', 'model' => ''
                , 'created_at' => '', 'instruction' => '', 'name' => ''];
            if (isset($options['assistant_id']) && strlen($options['assistant_id']) > 0) {//update
                $assistant_id = $options['assistant_id'];
                $request_url = self::$assistant_api . '/' . $assistant_id;
                $post_data = array(
                    'instructions' => $options['instructions'],
                    'tools' => array(
                        array(
                            'type' => 'file_search'
                        )
                    ),
                    'model' => $options['model'],
                    'tool_resources' => [
                        'file_search' => [
                            'vector_stores' => [[
                            'file_ids' => array($options['file_id'])
                                ]]
                        ]
                    ],
                );
            }
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $request_url,
                    array(
                        'method' => 'POST',
                        'headers' => $headers,
                        'body' => wp_json_encode($post_data),
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id'])) {
                        $res['id'] = $res_obj['id'];
                        $res['created_at'] = $res_obj['created_at'];
                        $res['model'] = $options['model'];
                        $res['instruction'] = $options['instructions'];
                        $res['name'] = $options['assistant_name'];
                        $res['file_id'] = $options['file_id'];
                    }
                }
            }
            return $res;
        }

        public static function removeAssistantV2($assistant_id = '') {


            $request_url = self::$assistant_api . '/' . $assistant_id;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $request_url,
                    array(
                        'method' => 'DELETE',
                        'headers' => $headers,
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id']) && isset($res_obj['deleted']) && $res_obj['deleted'] == true) {
                        return true;
                    }
                }
                if($res['code'] == 404){
                    return true;
                }
            }
            return false;
        }

        public static function createThread($user_msg = '') {

            $url = self::$thread_url;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }


            $headers = array(
                "Content-Type" => "application/json",
                "OpenAI-Beta" => "assistants=v2",
                "Authorization" => "Bearer " . self::$gpt_key
            );
            if (strlen($user_msg) > 0) {
                $data = array(
                    "role" => "user",
                    "content" => $user_msg
                );
                $response = wp_remote_request(
                        $url,
                        array(
                            'method' => 'POST',
                            'headers' => $headers,
                            'body' => wp_json_encode($data),
                            'timeout' => 120
                        )
                );
            } else {
                $response = wp_remote_request(
                        $url,
                        array(
                            'method' => 'POST',
                            'headers' => $headers,
                            'timeout' => 120
                        )
                );
            }
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id'])) {
                        $res['id'] = $res_obj['id'];
                        $res['created_at'] = isset($res_obj['created_at'])?$res_obj['created_at']:0;

                        $res['vector_store_ids'] = '';
                        if (is_array($res_obj['tool_resources']) && count($res_obj['tool_resources']) > 0) {
                            $t_r = $res_obj['tool_resources'];
                            if (is_array($t_r['file_search']) && count($t_r['file_search']) > 0) {
                                $f_s = $t_r['file_search'];
                                if (is_array($f_s['vector_store_ids']) && count($f_s['vector_store_ids']) > 0) {
                                    $res['vector_store_ids'] = $f_s['vector_store_ids'][0];
                                }
                            }
                        }
                    } else {
                        if (isset($res_obj['error']) && is_array($res_obj['error'])) {
                            $error = $res_obj['error'];
                            if (is_array($error) && isset($error['message'])) {
                                $res['error_msg'] = $error['message'];
                            }
                        }
                    }
                }
            }
            return $res;
        }

        public static function addAssistantMessage($thread_id, $user_msg) {

            $url = self::$thread_url . "/" . $thread_id . "/messages";
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }
            $headers = array(
                "Content-Type" => "application/json",
                "OpenAI-Beta" => "assistants=v2",
                "Authorization" => "Bearer " . self::$gpt_key
            );
            $data = array(
                "role" => "user",
                "content" => $user_msg
            );

            $response = wp_safe_remote_request(
                    $url,
                    array(
                        'method' => 'POST',
                        'headers' => $headers,
                        'body' => wp_json_encode($data),
                        'timeout' => 120
                    )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    return $res_obj;
                }
            }
            return $res;
        }

        public static function runAssistant($thread_id, $assistant_id, $instruction) {

            $url = self::$thread_url . "/" . $thread_id . "/runs";
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            if (strlen($instruction) > 1) {
                $data = array(
                    "assistant_id" => $assistant_id,
                    "instructions" => $instruction
                );
            } else {
                $data = array(
                    "assistant_id" => $assistant_id
                );
            }

            $headers = array(
                "Content-Type" => "application/json",
                "OpenAI-Beta" => "assistants=v2",
                "Authorization" => "Bearer " . self::$gpt_key
            );

            $response = wp_remote_request(
                    $url,
                    array(
                        'method' => 'POST',
                        'headers' => $headers,
                        'body' => wp_json_encode($data),
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    return $res_obj;
                }
            }

            return $res;
        }

        public static function uploadFile($file_path) {
            $http_client = self::$http_client;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }
            switch ($http_client) {
                case 'curl':
                    if (!class_exists('S2bAia_CurlClient')) {
                        require_once S2BAIA_PATH . '/lib/helpers/CurlClient.php';
                    }
                    return S2bAia_CurlClient::uploadFile($file_path,self::$files_api_url,self::$gpt_key);
                    break;

                default:
                    if (!class_exists('S2bAia_CurlClient')) {
                        require_once S2BAIA_PATH . '/lib/helpers/CurlClient.php';
                    }
                    return S2bAia_CurlClient::uploadFile($file_path,self::$files_api_url,self::$gpt_key);
            }
        }

        public static function removeFileV2($id_file) {

            $request_url = self::$files_api_url . '/' . $id_file;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $request_url,
                    array(
                        'method' => 'DELETE',
                        'headers' => $headers,
                        'timeout' => 120
                    )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $res_obj = json_decode($res['body'], true);
                    if (is_array($res_obj) && isset($res_obj['id']) && isset($res_obj['deleted']) && $res_obj['deleted'] == true) {
                        return true;
                    }
                }
            }
            return false;
        }

        public static function listAssistantMessages($thread_id) {

            $url = self::$thread_url . "/" . $thread_id . "/messages";
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $url,
                    array(
                        'method' => 'GET',
                        'headers' => $headers,
                        'timeout' => 120
                    )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
                return $res;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $response = json_decode($res['body'], true);
                }
            }
            return $response;
        }

        public static function getRunStepsStatus($thread_id, $run_id) {
            $status = false;
            if (strlen(self::$gpt_key) == 0) {
                self::$gpt_key = get_option(S2BAIA_PREFIX_LOW . 'open_ai_gpt_key', '');
            }

            $url = self::$thread_url . '/' . $thread_id . '/runs/' . $run_id . '/steps';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$gpt_key,
                'OpenAI-Beta' => 'assistants=v2'
            );

            $response = wp_remote_request(
                    $url,
                    array(
                        'method' => 'GET',
                        'headers' => $headers,
                        'timeout' => 120
                    )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $res['error_msg'] = $error_message;
                $res['code'] = 500;
                return $status;
            } else {
                $res['code'] = wp_remote_retrieve_response_code($response);
                $res['body'] = wp_remote_retrieve_body($response);
                if (strlen($res['body']) > 0) {
                    $responseArray = json_decode($res['body'], true);
                    if (array_key_exists("data", $responseArray) && !is_null($responseArray["data"])) {
                        $data = $responseArray["data"];
                    } else {

                        $status = "failed";
                        $data = [];
                    }

                    foreach ($data as $item) {
                        if ($item["status"] == "completed") {
                            $status = true;
                            break;
                        }
                    }

                    return $status;
                }
            }


            return $status;
        }
    }

}