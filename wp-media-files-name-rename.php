<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             2.0.0
 * @package           Rename_WP_Media_Files_Name
 *
 * Plugin Name:       Rename WP Media Files Name
 * Plugin URI:        https://wordpress.org/plugins/wp-media-files-name-rename/
 * Description:       Change Media Attachments Files Name Easily.
 * Version:           2.0.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-media-files-name-rename
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

/**
 * Currently plugin version.
 */
define( 'RENAME_WP_MEDIA_FILES_NAME_VERSION', '2.0.0' );

/**
 * Define Plugin Folders Path
 */
define( 'RENAME_WP_MEDIA_FILES_NAME_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'RENAME_WP_MEDIA_FILES_NAME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'RENAME_WP_MEDIA_FILES_NAME_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-activator.php
 * 
 * @since    2.0.0
 */
function activate_rename_wp_media_files_name()
{
	require_once RENAME_WP_MEDIA_FILES_NAME_PLUGIN_PATH . 'includes/class-plugin-activator.php';
	
	Rename_WP_Media_Files_Name_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_rename_wp_media_files_name' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-deactivator.php
 * 
 * @since    2.0.0
 */
function deactivate_rename_wp_media_files_name()
{
	require_once RENAME_WP_MEDIA_FILES_NAME_PLUGIN_PATH . 'includes/class-plugin-deactivator.php';
	
	Rename_WP_Media_Files_Name_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_rename_wp_media_files_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 * 
 * @since    2.0.0
 */
require RENAME_WP_MEDIA_FILES_NAME_PLUGIN_PATH . 'includes/class-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_rename_wp_media_files_name()
{
	$plugin = new Rename_WP_Media_Files_Name();
	
	$plugin->run();
}

run_rename_wp_media_files_name();
