<?php
/**
 * This file contains the definition of the Wp_Media_Files_Name_Rename_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Wp_Media_Files_Name_Rename
 * @subpackage    Wp_Media_Files_Name_Rename/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Wp_Media_Files_Name_Rename_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-media-files-name-rename',
			false,
			dirname( WP_MEDIA_FILES_NAME_RENAME_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
