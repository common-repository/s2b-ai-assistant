<?php
/*
  Plugin Name: S2B AI Assistant
  Plugin URI:
  Description: AI power for Wordpress. You can edit, generate texts, write programming codes using GPT. Plugin allows to create a database of instructions, which you can easily refer back to whenever needed. 
  Author: Oleh Chorn...
  Author URI: https://soft2business.com/
  Text Domain: s2b-ai-aiassistant
  Domain Path: /lang
  Version: 1.6.3
  License:  GPL-2.0+
  License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if (version_compare('5.3', phpversion(), '>')) {
    die(sprintf(__('We are sorry, but you need to have at least PHP 5.3 to run this plugin (currently installed version: %s) - please upgrade or contact your system administrator.'), phpversion()));
}

//Define constants

define('S2BAIA_PREFIX', 'S2BAIA_');
define('S2BAIA_PREFIX_LOW', 's2baia_');
define('S2BAIA_PREFIX_SHORT', 's2b_');
define('S2BAIA_CLASS_PREFIX', 'S2bAia_');
define('S2BAIA_PATH', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)));
define('S2BAIA_URL', plugins_url('', __FILE__));
define('S2BAIA_PLUGIN_FILE', __FILE__);
define('S2BAIA_TEXT_DOMAIN', 's2b-ai-aiassistant');
define( 'S2BAIA_CHATGPT_BOT_PREFIX', 's2baia_chatbot_' );
define( 'S2BAIA_CHATGPT_BOT_OPTIONS_PREFIX', 's2baia_chatbot_opt_' );
define('S2BAIA_VERSION', '1.5.0');
//Init the plugin
require_once S2BAIA_PATH . '/lib/helpers/Utils.php';
require_once S2BAIA_PATH . '/lib/S2bAia.php';
require_once S2BAIA_PATH . '/lib/controllers/BaseController.php';
require_once S2BAIA_PATH . '/lib/controllers/AdminController.php';
require_once S2BAIA_PATH . '/lib/dispatchers/FrontendDispatcher.php';

register_activation_hook(__FILE__, array('S2bAia', 'install'));
register_deactivation_hook(__FILE__, array('S2bAia', 'deactivate'));
register_uninstall_hook(__FILE__, array('S2bAia', 'uninstall'));
new S2bAia();
