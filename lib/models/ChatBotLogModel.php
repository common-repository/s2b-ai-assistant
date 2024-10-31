<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia_ChatBotLogModel')) {

    class S2bAia_ChatBotLogModel {
        
        public function insertLogRecord($data = []) {

            global $wpdb;
            $wpdb->insert($wpdb->prefix . 's2baia_messages_log',
                    array(
                        'id_user' => (int)$data['id_user'],
                        'typeof_message' => (int)$data['typeof_message'],
                        'id_assistant' => (int)$data['id_assistant'],
                        'messages' => json_encode($data['messages']),
                        'parameters' => json_encode($data['parameters']),
                        'ipaddress' => $data['ipaddress'],
                        'chat_id' => $data['chat_id'],
                        'comments' => $data['comments'],
                        'created' => date( 'Y-m-d H:i:s' ),
                        'updated' => date( 'Y-m-d H:i:s' )
                    ),
                    array('%d', '%d','%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
           
            return $wpdb->insert_id;
        }
        
        public function updateLogRecord($id = 0, $data = [], $append_messages = false){
            global $wpdb;
            $old_msgs = [];
            if($append_messages){
                $log_record = $this->getLogRecord($id);
                if(is_object($log_record) && isset($log_record->id) && $log_record->id > 0){
                    $old_msgs = $log_record->messages;
                    $new_msgs = $data['messages'];
                    $data['messages'] = $old_msgs;
                    foreach($new_msgs as $nm){
                        $data['messages'][] = $mn;
                    }
                }
            }
            
            return $wpdb->update($wpdb->prefix . 's2baia_messages_log', 
                    array(
                        'id_user' => (int)$data['id_user'],
                        'typeof_message' => (int)$data['typeof_message'],
                        'id_assistant' => (int)$data['id_assistant'],
                        'messages' => json_encode($data['messages']),
                        'parameters' => json_encode($data['parameters']),
                        'ipaddress' => $data['ipaddress'],
                        'chat_id' => $data['chat_id'],
                        'comments' => $data['comments'],
                        'updated' => date( 'Y-m-d H:i:s' )
                        ), 
                    array('id' => (int)$id),
                    array('%d','%d','%d','%s','%s','%s','%s','%s','%s'),
                    array('%d'));
        }
        /*append_mode == 0 - overwrite messages by values stored in  $data including stored in $data['messages']
         * 1 - append stored in $data['messages'] to old data ,
           2 - do not store values from  $data['messages'], but store other info from $data
         *          */
        public function updateLogRecordByChatId($chat_id = '', $data = [], $append_mode = 0){
            global $wpdb;
            $old_msgs = [];
            if($append_mode > 0){
                $log_record = $this->getLogRecordByChatId($chat_id);
                if(is_object($log_record) && isset($log_record->id) && $log_record->id > 0){
                    $old_msgs = $log_record->messages;
                    if($append_mode == 1){
                        $new_msgs = $data['messages'];
                        $data['messages'] = $old_msgs;
                        $data['messages'][] = $new_msgs;
                        /*foreach($new_msgs as $nm){
                            $data['messages'][] = [$nm];
                        }*/
                    }elseif($append_mode == 2){
                        $data['messages'] = $old_msgs;
                    }
                }
            }
            
            return $wpdb->update($wpdb->prefix . 's2baia_messages_log', 
                    array(
                        'id_user' => (int)$data['id_user'],
                        'typeof_message' => (int)$data['typeof_message'],
                        'id_assistant' => (int)$data['id_assistant'],
                        'messages' => json_encode($data['messages']),
                        'parameters' => json_encode($data['parameters']),
                        'ipaddress' => $data['ipaddress'],
                        'comments' => $data['comments'],
                        'updated' => date( 'Y-m-d H:i:s' )
                        ), 
                    array('chat_id' => $chat_id),
                    array('%d','%d','%d','%s','%s','%s','%s','%s'),
                    array('%s'));

        }
        
        public function getLogRecord($id = 0){
            global $wpdb;
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log WHERE id = %d  ", [(int)$id]));
            $this->filterLogFields($row);
            return $row;
        }
        
        public function getLogRecordByChatId($chat_id = ''){
            global $wpdb;
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log WHERE chat_id LIKE %s  ", [$chat_id]));
            $this->filterLogFields($row);
            return $row;
        }
        
        public function getLogRecords($load_disabled = false){
            global $wpdb;
            if (!$load_disabled) {
                return $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log WHERE selected = %d ",0));
            }
            
            return $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "s2baia_messages_log");
        }
        
        
        
        public function searchLogRecords($type = 0, $search = '', $page = 1, $records_per_page = 20, $show_selected_only = false) {
            
            global $wpdb;
            $par_arr = [];
            $default_where_parameter = 1;
            $type_str = '';
            if ($type > 0) {
                $type_str = " AND typeof_message = %d "  ;
                $par_arr[] = (int) $type;
            }
            $search_part = '';
            if (strlen($search) > 0) {
                $search_part = ' AND messages LIKE %s';
                $par_arr[] = '%' . $search . '%';
            }
            $disabled_part = '';
            if ($show_selected_only) {
                $disabled_part = ' AND selected = 1 ';
            }

            
            if (count($par_arr) > 0) {
                $cnt = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "s2baia_messages_log as a LEFT  JOIN ". $wpdb->prefix ."s2baia_chatbots as b ON a.id_assistant = b.id   WHERE 1  " . $type_str . $search_part . $disabled_part, $par_arr));
            }else{
                $cnt = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "s2baia_messages_log as a LEFT  JOIN ". $wpdb->prefix ."s2baia_chatbots as b ON a.id_assistant = b.id   WHERE %d  " . $type_str . $search_part . $disabled_part,[$default_where_parameter]));
            }
            
            $limit_part = ""  ;
            if($records_per_page > 0 && $page > 0){
                $limit_part = " LIMIT  %d,%d "  ;
                $par_arr[] = ($page - 1) * $records_per_page; 
                $par_arr[] =  $records_per_page;
            }
            
            if (count($par_arr) > 0) {
                $rows = $wpdb->get_results($wpdb->prepare("SELECT a.*, b.hash_code FROM " . $wpdb->prefix . "s2baia_messages_log as a LEFT  JOIN ". $wpdb->prefix ."s2baia_chatbots as b ON a.id_assistant = b.id  WHERE 1  " . $type_str . $search_part . $disabled_part . $limit_part, $par_arr));
            }else{
                $rows = $wpdb->get_results($wpdb->prepare("SELECT a.*, b.hash_code  FROM " . $wpdb->prefix . "s2baia_messages_log as a LEFT  JOIN ". $wpdb->prefix ."s2baia_chatbots as b ON a.id_assistant = b.id   WHERE %d  " . $type_str . $search_part . $disabled_part . $limit_part,[$default_where_parameter]));
            }

            return ['cnt' => $cnt, 'rows' => $rows];
            
        }
        
        public function filterLogFields(&$row){
            if (strlen($row->messages) > 1 && $row->messages != null) {
                $row->messages = json_decode($row->messages,true);
                $messages = $row->messages;
                $new_messages = [];
                foreach ($messages as $idx => $b_msg) {
                    if(is_array($b_msg)){
                        $keys = array_map('sanitize_text_field',array_keys($b_msg));
                        $vals = $b_msg;
                        $b_msg = [];
                        foreach($keys as $i => $key){
                            $b_msg[$key] = sanitize_text_field($vals[$key]);
                        }
                        $new_messages[sanitize_text_field($idx)] = $b_msg;
                    }else{
                        $new_messages[sanitize_text_field($idx)] = sanitize_text_field($b_msg);
                    }
                }
                $row->messages = $new_messages;
            } else {
                $row->messages = [];
            }
            if (strlen($row->parameters) > 1 && $row->parameters != null) {
                $row->parameters = json_decode($row->parameters,true);
                $parameters = $row->parameters;
                $new_parameters = [];
                foreach ($parameters as $idx => $b_opt) {
                    $new_parameters[sanitize_text_field($idx)] = sanitize_text_field($b_opt);
                }
                $row->parameters = $new_parameters;
            } else {
                $row->parameters = [];
            }
            $row->typeof_message = (int) $row->typeof_message;
            $row->id_assistant = (int) $row->id_assistant;
            $row->id_user = (int) $row->id_user;
            $row->ipaddress = sanitize_text_field($row->ipaddress);
            $row->chat_id = sanitize_text_field($row->chat_id);
            $row->comments = sanitize_text_field($row->comments);
            $row->created = sanitize_text_field($row->created);
            $row->updated = sanitize_text_field($row->updated);
            $row->selected =  $row->selected == 1? $row->selected:0;
            return $row;
        }
        
        
        public function toggleLogRecord($id, $selected){
            global $wpdb;
            return $wpdb->update($wpdb->prefix . 's2baia_messages_log', 
                    array(
                        'selected' => (int) $selected
                    ), 
                    array('id' => (int) $id),
                    array('%d'),
                    array('%d')
                    );
        }
        
        public function toggleLogRecordByChatId($chat_id,  $selected){
            global $wpdb;
            return $wpdb->update($wpdb->prefix . 's2baia_messages_log', 
                    array(
                        'selected' => (int) $selected
                    ), 
                    array('chat_id' => $chat_id),
                    array('%d'),
                    array('%s')
                    );
        }
        
        public function deleteLogRecord($id = 0){
            global $wpdb;
            return $wpdb->delete($wpdb->prefix . 's2baia_messages_log', 
                    array(
                        'id' => (int) $id
                    ),
                    array(
                        '%d'
                    ));
            
        }
        
        public function parseMessages($typeof_message,$messages_str){
            
            if($typeof_message == 1){
                $messages = json_decode($messages_str,true);
                $new_messages = [];
                foreach ($messages as $idx => $b_msg) {
                    if(is_array($b_msg)){
                        $keys = array_map('sanitize_text_field',array_keys($b_msg));
                        $vals = $b_msg;
                        $b_msg = [];
                        foreach($keys as $i => $key){
                            $b_msg[$key] = sanitize_text_field($vals[$key]);
                        }
                        $new_messages[sanitize_text_field($idx)] = $b_msg;
                    }else{
                        $new_messages[sanitize_text_field($idx)] = sanitize_text_field($b_msg);
                    }
                }
                return $new_messages;
            }
            return json_decode($messages_str, true);
        }
        
        public function parseParameters($row){
            $pars = json_decode($row->parameters,true);
            if(isset($row->hash_code) && $row->hash_code != null){
                $pars['bot_id'] = sanitize_text_field($row->hash_code);
            }else{
                $pars['bot_id'] = '';
            }
            if(isset($row->chat_id) && $row->chat_id != null){
                $pars['chat_id'] = sanitize_text_field($row->chat_id);
            }else{
                $pars['chat_id'] = '';
            }
            if(isset($row->created) && $row->created != null){
                $pars['created'] = sanitize_text_field($row->created);
            }else{
                $pars['created'] = '';
            }
            if(isset($row->updated) && $row->updated != null){
                $pars['updated'] = sanitize_text_field($row->updated);
            }else{
                $pars['updated'] = '';
            }
            return $pars;
        }
        
    }

}
