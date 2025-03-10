<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Rename_WP_Media_Files_Name
 * @subpackage Rename_WP_Media_Files_Name/includes
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Rename_WP_Media_Files_Name_i18n
{
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'wp-media-files-name-rename',
			false,
			dirname( RENAME_WP_MEDIA_FILES_NAME_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
