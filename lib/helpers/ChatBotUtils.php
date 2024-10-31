<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('S2bAia_ChatBotUtils')) {

    class S2bAia_ChatBotUtils {

        //$data_parameters
        public static function getChatBotStyles($data_parameters) {
            $icon_size = $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_icon_size'];

            $styles = '';  		
            if ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'position'] == 'center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container {
							left: 50%;
							transform: translateX(-50%);
						}
			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'position'] == 'left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container {
							left: 0;
							right: unset;
						}
			';
            } else {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container {
							right: 0;
						}
			';
            }

            if ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'bottom-center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 50%;
						}




			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'bottom-left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 20px;
							right: unset;
						}


			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-right') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							right: 20px;
                            top: 20px;
						}



			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-center') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 50%;
                            top: 20px;
						}


			';
            } elseif ($data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position'] == 'top-left') {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							left: 20px;
							right: unset;
                            top: 20px;
						}


			';
            } else {
                $styles .= '
						div.s2baia-bot-chatbot div.s2baia-bot-chatbot-closed-view {
							right: 20px;
						}



			';
            }

            $styles .= '
                                                div.s2baia-bot-chatbot-main-container button.s2baia-bot-chatbot-send-button{
                                                    background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_color'] . ';
                                                    color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_text_color'] . ';    
                                                }
                                                div.s2baia-bot-chatbot-main-container button.s2baia-bot-chatbot-send-button:hover{
                                                    background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_hover_color'] . '; 
                                                }
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-header-row,
						div.s2baia-bot-chatbot-main-container button.s2baia-bot-chatbot-regenerate-response-button {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_color'] . ';
						}
						div.s2baia-bot-chatbot-main-container button.s2baia-bot-chatbot-send-button:disabled,
						div.s2baia-bot-chatbot-main-container button.s2baia-bot-chatbot-regenerate-response-button:disabled {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'color'] . '2b;
						}
						.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-main-chat-box,
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-main-chat-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'background_color'] . ';
							border-radius: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px;
						}
						.s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-input-box,
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-input-box {
							border-radius: 0 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius'] . 'px;
						}
                        .s2baia-bot-chatbot-shortcode div.s2baia-bot-chatbot-main-container p.s2baia-bot-chatbot-header-text,
                        .s2baia-bot-chatbot div.s2baia-bot-chatbot-main-container p.s2baia-bot-chatbot-header-text {
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_text_color'] . ' !important;
						}
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-loading-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_bg_color'] . ';
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_text_color'] . ';
						}
                        div.s2baia-bot-chatbot-main-container .s2baia-bot-chatbot-ai-message-buttons svg {
							fill: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_icons_color'] . ' !important;
						}
                        div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box .s2baia-bot-chatbot-ai-response-message {
							border-radius: 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px;
						}
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-user-message-box {
							background-color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_bg_color'] . ';
							color: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_text_color'] . ';
							border-radius: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px 0 ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius'] . 'px;
						}
                        
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-user-message-box,
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box div.s2baia-bot-chatbot-ai-message-box,
						div.s2baia-bot-chatbot-main-container div.s2baia-bot-chatbot-messages-box .s2baia-bot-chatbot-ai-response-message{
							font-size: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_font_size'] . 'px;
							margin-bottom: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_margin'] . 'px;
						}



                        div.s2baia-bot-chatbot .s2baia-bot-chatbot-closed-view {
                            width: ' . $icon_size . 'px !important;
                            height: ' . $icon_size . 'px !important;
                        }

                        .s2baia-bot-chatbot .s2baia-bot-chatbot-main-container {
                            width: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width'] . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width_metrics'] . ';
                            height: ' . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height'] . $data_parameters[S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height_metrics'] . ';
                        }



                        
			';

            return $styles;
        }

        public static function getChatBotDefaultStyles() {
            $styles = [
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_icon_size' => 70,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'position' => 'right',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'background_color' => '#ffffff',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'icon_position' => 'bottom-right',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_color' => '#0C476E',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_color' => '#0E5381',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_text_color' => '#ffffff',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'send_button_hover_color' => '#126AA5',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'color' => '#ffefea',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chatbot_border_radius' => 10,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'header_text_color' => '#ffffff',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_bg_color' => '#5AB2ED',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_text_color' => '#000000',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'response_icons_color' => '#000',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_border_radius' => 10,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_bg_color' => '#1476B8',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_text_color' => '#fff',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_font_size' => 16,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'message_margin' => 5,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width' => 25,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_width_metrics' => '%',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height' => 55,
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'chat_height_metrics' => '%',
                S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX . 'access_for_guests' => 1,
            ];
            return $styles;
        }

        public static function getPositionOptions() {
            return [
                'right' => esc_html('Right', 's2b-ai-aiassistant'),
                'left' => esc_html('Left', 's2b-ai-aiassistant'),
                'center' => esc_html('Center', 's2b-ai-aiassistant')
            ];
        }

        public static function getIconPositionOptions() {//bottom-center bottom-left top-right top-center top-left
            return [
                'bottom-right' => esc_html('Bottom right', 's2b-ai-aiassistant'),
                'bottom-left' => esc_html('Bottom left', 's2b-ai-aiassistant'),
                'bottom-center' => esc_html('Bottom center', 's2b-ai-aiassistant'),
                'top-right' => esc_html('Top right', 's2b-ai-aiassistant'),
                'top-left' => esc_html('Top left', 's2b-ai-aiassistant'),
                'top-center' => esc_html('Top center', 's2b-ai-aiassistant'),
            ];
        }

        public static function getMetrics() {
            return [
                'percent' => '%',
                'pixels' => 'px'
            ];
        }

        public static function generateBotHash() {

            return S2bAia_Utils::getToken(10);
        }

        public static function getModels() {
            return S2bAia_Utils::getEditModels();
        }

        public static function getProviders() {
            return ['default', 'assistant'];
        }

        public static function getDefaultAssistant() {
            $assistant_opts_default = ['code' => 0, 'error_msg' => '', 'id' => '', 'model' => ''
                , 'created_at' => '', 'instruction' => '', 'name' => '', 'description' => '', 'assistant_timeout' => 3];
            return $assistant_opts_default;
        }
    }

}