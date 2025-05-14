<?php
/**
 * This file contains the definition of the Wp_Media_Files_Name_Rename class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Wp_Media_Files_Name_Rename
 * @subpackage    Wp_Media_Files_Name_Rename/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Wp_Media_Files_Name_Rename {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Wp_Media_Files_Name_Rename_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		$this->version     = defined( 'WP_MEDIA_FILES_NAME_RENAME_PLUGIN_VERSION' ) ? WP_MEDIA_FILES_NAME_RENAME_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'wp-media-files-name-rename';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Media_Files_Name_Rename_Loader.  Orchestrates the hooks of the plugin.
	 * - Wp_Media_Files_Name_Rename_i18n.    Defines internationalization functionality.
	 * - Wp_Media_Files_Name_Rename_Admin.   Defines all hooks for the admin area.
	 * - Wp_Media_Files_Name_Rename_Public.  Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WP_MEDIA_FILES_NAME_RENAME_PLUGIN_PATH . 'includes/class-wp-media-files-name-rename-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WP_MEDIA_FILES_NAME_RENAME_PLUGIN_PATH . 'includes/class-wp-media-files-name-rename-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WP_MEDIA_FILES_NAME_RENAME_PLUGIN_PATH . 'admin/class-wp-media-files-name-rename-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WP_MEDIA_FILES_NAME_RENAME_PLUGIN_PATH . 'public/class-wp-media-files-name-rename-public.php';

		$this->loader = new Wp_Media_Files_Name_Rename_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Media_Files_Name_Rename_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function set_locale() {
		$plugin_i18n = new Wp_Media_Files_Name_Rename_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wp_Media_Files_Name_Rename_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'plugin_action_links_' . WP_MEDIA_FILES_NAME_RENAME_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links' );

		$this->loader->add_action( 'attachment_fields_to_edit', $plugin_admin, 'attachment_fields_to_edit', 11, 2 );
		$this->loader->add_action( 'attachment_fields_to_save', $plugin_admin, 'attachment_fields_to_save', 11, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Wp_Media_Files_Name_Rename_Public( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Wp_Media_Files_Name_Rename_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
