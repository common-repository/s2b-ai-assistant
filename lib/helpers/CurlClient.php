<?php

if (!class_exists('S2bAia_CurlClient')) {

    class S2bAia_CurlClient {

        public static $stream_answer = '';

        public static function sendCurlRequest($url, $options) {

            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($options) {
                $stream_options = stream_context_get_options($options);
                if (isset($stream_options['http']['method'])) {
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $stream_options['http']['method']);
                }
                if (isset($stream_options['http']['header'])) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $stream_options['http']['header']);
                }
                if (isset($stream_options['http']['content'])) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $stream_options['http']['content']);
                }
            }

            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        }

        public static function uploadFile($file_path,$url,$api_key) {

            $res = ['code' => 404, 'error_msg' => '', 'id' => '', 'filename' => $file_path];
            $request_url = $url;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $api_key));

            $post_fields = array(
                'purpose' => 'assistants',
                'file' => new CURLFile($file_path)
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            if (curl_errno($ch)) {
                $res = ['code' => $http_status,
                    'error_msg' => 'Error:' . curl_error($ch),
                    'id' => '',
                    'filename' => $file_path];
            } else {
                $response = json_decode($response, true);
                if ($http_status != 200 || isset($response['error'])) {
                    $error_message = $response['error']['message'] ?? 'Unknown error.';
                    $res = array(
                        'error_msg' => $error_message,
                        'code' => $http_status,
                        'id' => '',
                        'filename' => $file_path
                    );
                } else {


                    $res = array(
                        'code' => $http_status,
                        'filename' => $file_path,
                        'id' => $response['id'],
                        'error_msg' => ""
                    );
                }
            }

            curl_close($ch);
            unlink($file_path);
            return $res;
        }
    }

}