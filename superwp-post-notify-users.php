<?php
/**
 * Superwp Post Notify Users
 *
 * @package       SUPERWPPOS
 * @author        Thiarara
 * @license       gplv3-or-later
 * @version       1.0.7
 *
 * @wordpress-plugin
 * Plugin Name:   Superwp Post Notify Users
 * Plugin URI:    https://github.com/Thiararapeter/Superwp-Post-Notify-Users
 * Description:   Notifies users with specified roles when a new or edited post is published. You can specify user roles, post types, customize email content, subject, and schedule notifications.
 * Version:       1.0.7
 * Author:        Thiarara
 * Author URI:    https://profiles.wordpress.org/thiarara/
 * Text Domain:   superwp-post-notify-users
 * Domain Path:   /languages
 * License:       GPLv3 or later
 * License URI:   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Superwp Post Notify Users. If not, see <https://www.gnu.org/licenses/gpl-3.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function SUPERWPPOS() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'SUPERWPPOS_NAME',			'Superwp Post Notify Users' );

// Plugin version
define( 'SUPERWPPOS_VERSION',		'1.0.7' );

// Plugin Root File
define( 'SUPERWPPOS_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'SUPERWPPOS_PLUGIN_BASE',	plugin_basename( SUPERWPPOS_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'SUPERWPPOS_PLUGIN_DIR',	plugin_dir_path( SUPERWPPOS_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'SUPERWPPOS_PLUGIN_URL',	plugin_dir_url( SUPERWPPOS_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once SUPERWPPOS_PLUGIN_DIR . 'core/class-superwp-post-notify-users.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Thiarara
 * @since   1.0.7
 * @return  object|Superwp_Post_Notify_Users
 */
function SUPERWPPOS() {
	return Superwp_Post_Notify_Users::instance();
}

require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Thiararapeter/Superwp-Post-Notify-Users',
	__FILE__,
	' Superwp-Post-Notify-Users '
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

SUPERWPPOS();