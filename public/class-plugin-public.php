<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, other methods and
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rename_WP_Media_Files_Name
 * @subpackage Rename_WP_Media_Files_Name/public
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Rename_WP_Media_Files_Name_Public
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param    string    $plugin_name   The name of the plugin.
	 * @param    string    $version   The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name 	= $plugin_name;
		
		$this->version 		= $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style( $this->plugin_name, RENAME_WP_MEDIA_FILES_NAME_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script( $this->plugin_name, RENAME_WP_MEDIA_FILES_NAME_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'RENAME_WP_MEDIA_FILES_NAME', array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
		) );
	}
}
