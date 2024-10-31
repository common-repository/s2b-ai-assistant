<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('S2bAia')) {

    class S2bAia {
        public static $database_version = 2;
        public $admin_controller;
        public $frontend_dispatcher;

        
        public function __construct() {
            $this->admin_controller = new S2bAia_AdminController();
            $this->frontend_dispatcher = new S2bAia_FrontendDispatcher();
            add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
            add_filter('plugin_action_links', [$this, 'actionLinks'], 10, 2);
        }

        public function enqueueScripts() {
            wp_enqueue_script('s2baia_backend', S2BAIA_URL . '/views/resources/js/s2baia-admin.js', [], '2.7', true);
            wp_enqueue_script('s2baia_backendv2', S2BAIA_URL . '/views/resources/js/s2baia-admin-v2.js', [], '2.03', true);
        }

        public function actionLinks($links, $file) {
            $fl2 = plugin_basename(dirname(dirname(__FILE__))) . '/s2b-ai-assistant.php';
            if ($file == $fl2) {
                $mylinks[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=s2baia_settings')) . '">' . __('Settings', 's2b-ai-aiassistant') . '</a>';

                return $mylinks + $links;
            }
            return $links;
        }

        public static function install() {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . 's2baia_instructions';
            $wpdb->query(
                    'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `typeof_instruction` int(11) NOT NULL DEFAULT "0" COMMENT "1-instruction for text-edit , 2 - for code-edit,  other values are reserved for funture needs"  ,
                `instruction`  MEDIUMTEXT  ,
                `disabled`  SMALLINT NOT NULL DEFAULT "0",
                `user_id`  int(11) NOT NULL DEFAULT "0" 
                ) ENGINE = INNODB
            ' . $charset_collate
            );

            $wpdb->insert($wpdb->prefix . 's2baia_instructions', array(
                'typeof_instruction' => 1,
                'instruction' => 'Correct any grammatical errors in the document.',
                    ),
                    array('%d', '%s'));

            $wpdb->insert($wpdb->prefix . 's2baia_instructions', array(
                'typeof_instruction' => 1,
                'instruction' => 'Improve the clarity and readability of this passage.',
                    ),
                    array('%d', '%s'));

            $wpdb->insert($wpdb->prefix . 's2baia_instructions', array(
                'typeof_instruction' => 1,
                'instruction' => 'Paraphrase the highlighted sentences without changing the meaning.',
                    ),
                    array('%d', '%s'));

            $models = ['gpt-4o' => 3, 'gpt-4o-mini' => 3, 'gpt-4' => 3]; //1-not text selected,2-text not selected, 3-text and selected
            update_option('s2baia_chatgpt_models', serialize($models));
            update_option('s2baia_chatgpt_expert_models', serialize($models));
        }

        public static function deactivate() {
            
        }

        public static function uninstall() {
            
        }

        public function bootstrap() {
            
        }

        
    }

}
