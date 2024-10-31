<?php

if ( ! defined( 'ABSPATH' ) ) exit;


if (!class_exists('S2bAia_FrontendDispatcher')) {

    class S2bAia_FrontendDispatcher{
        
        public $chatbot_controller;
        

        public function __construct() {
            if (!class_exists('S2bAia_ChatBotController')) {
                $contr_path = S2BAIA_PATH . "/lib/controllers/ChatBotController.php";
                include_once $contr_path;
            }
            $this->chatbot_controller = new S2bAia_ChatBotController();
        }
        
        

    }

}
