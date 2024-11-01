<?php
/**
 * Plugin Name: SocialBugCRM
 * Plugin URI: https://socialbug.io/
 * Description: Integration plugin to connect with SocialBugCRM platform. 
 * Version: 1.0.5
 * Author: SocialBug
 * Author URI: https://socialbug.io
 * Requires at least: 5.2.3
 * Tested up to: 6.5.4
 *
 * @package SocialBugCRM
 */

define('SOCIALBUGCRM_PAGE', __FILE__);
define('SOCIALBUGCRM_DIR', plugin_dir_path(__FILE__));
define('SOCIALBUGCRM_URL', plugin_dir_url(__FILE__));

require_once(SOCIALBUGCRM_DIR . 'includes/db.php');
require_once(SOCIALBUGCRM_DIR . 'includes/sb.php');
require_once(SOCIALBUGCRM_DIR . 'includes/optionsBuilder.php');

$options_builder = new \SocialbugCRM\Includes\OptionsBuilder();
add_action('admin_menu', array($options_builder, 'Init'));