<?php
/**
 * This file contains the definition of the Wp_Media_Files_Name_Rename_Deactivator class, which
 * is used during plugin deactivation.
 *
 * @package       Wp_Media_Files_Name_Rename
 * @subpackage    Wp_Media_Files_Name_Rename/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin deactivation.
 *
 * @since    2.0.0
 */
class Wp_Media_Files_Name_Rename_Deactivator {
	/**
	 * Deactivation hook.
	 *
	 * This function is called when the plugin is deactivated. It can be used to
	 * perform tasks such as cleaning up temporary data, unscheduling cron jobs,
	 * or removing options.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public static function on_deactivate() {}
}
