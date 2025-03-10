<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, other methods and
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rename_WP_Media_Files_Name
 * @subpackage Rename_WP_Media_Files_Name/admin
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Rename_WP_Media_Files_Name_Admin
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
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name 	= $plugin_name;
		
		$this->version 		= $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles()
	{
		global $pagenow, $typenow;
		
		/* Only show if in attachment edit page */
		if( $pagenow != 'post.php' || $typenow != 'attachment' ) return;
		
		wp_enqueue_style( $this->plugin_name, RENAME_WP_MEDIA_FILES_NAME_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts()
	{
		global $pagenow, $typenow;
		
		/* Only show if in attachment edit page */
		if( $pagenow != 'post.php' || $typenow != 'attachment' ) return;
		
		wp_enqueue_script( $this->plugin_name, RENAME_WP_MEDIA_FILES_NAME_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'RENAME_WP_MEDIA_FILES_NAME', array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'info_txt'	=> __( 'Everytime You Change File Name , Thumbnails of Edited File Are Also Regenerated!', 'wp-media-files-name-rename' ),
		) );
	}

	/**
	 * Show rename edit field.
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post )
	{
		/* Only show if not in Thickbox iframe */
		$screen 								= get_current_screen();
		
		if ( $screen->parent_base !== 'upload' ) return $form_fields;

		$form_fields['wpmfne_edit_file_input'] 	= array(
			'label' => __( 'Change File Name To :', 'wp-media-files-name-rename' )
		);

		return $form_fields;
	}

	/**
	 * Save and rename the media file.
	 *
	 * @return object
	 */
	public function attachment_fields_to_save( $post, $attachment )
	{
		/* Only proceed if filename changed and new filename input submitted */
		if ( isset( $attachment['wpmfne_edit_file_input'] ) && ! empty( $attachment['wpmfne_edit_file_input'] ) )
		{
			// media post id
			$id 					= $post['ID'];

			// get the media file dir path based on the media id (https://developer.wordpress.org/reference/functions/get_attached_file/)
			$original_file 			= get_attached_file( $id );

			$new_file_name 			= sanitize_text_field( $attachment['wpmfne_edit_file_input'] );

			// get media file name (https://www.php.net/manual/en/function.basename.php)
			$original_file_name 	= basename( $original_file );

			// get media file extension (https://php.net/manual/en/function.pathinfo.php)
			$original_file_ext 		= pathinfo( $original_file, PATHINFO_EXTENSION );
			
			// get media file full path excluded file name + ext (https://developer.wordpress.org/reference/functions/trailingslashit/)
			$original_file_path 	= trailingslashit( str_replace( "\\", "/" , pathinfo( $original_file, PATHINFO_DIRNAME ) ) );

			/* Make new a filename that is sanitized and unique */
			$new_filename 			= wp_unique_filename( $original_file_path, $new_file_name . "." . $original_file_ext );

			// combine file path + new file name
			$new_file 				= $original_file_path . $new_filename;
			
			/* Rename the media with new file (https://www.php.net/manual/en/function.rename.php)  */
			rename( $original_file_path . $original_file_name, $new_file );
			
			// get _wp_attached_file of this post
			$old_wp_attached_file 	= get_post_meta( $id, '_wp_attached_file', true );

			$new_wp_attached_file 	= str_replace( $original_file_name, $new_filename, $old_wp_attached_file );

			/* Update file location in database */
			update_attached_file( $id, $new_wp_attached_file );

			// get post object
			$post_for_guid 			= get_post( $id );

			/* Update guid for attachment */
			$guid 					= str_replace( $original_file_name, $new_filename, $post_for_guid->guid );

			// update the media post with new $guid (https://developer.wordpress.org/reference/functions/wp_update_post/)
			wp_update_post( array(
				'ID' => $id,
				'guid' => $guid
			) );

			// Update metadata for that attachment (https://developer.wordpress.org/reference/functions/wp_update_attachment_metadata/)
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $new_file ) );

			$this->clean_thumbnails( $original_file_path, $original_file, $original_file_ext );
		}

		return $post;
	}

	/**
	 * Returns the path of the upload directory.
	 *
	 * @return string
	 */
	public function get_cache_dir( $base_dir_only = false, $dir = 'basedir' )
	{
		$upload 	= wp_upload_dir();

		return realpath( $upload['basedir'] );
	}

	/**
	 * Get all the registered image sizes along with their dimensions
	 *
	 * @global array $_wp_additional_image_sizes
	 *
	 * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
	 *
	 * @return array $image_sizes The image sizes
	 */
	public function get_all_image_sizes()
	{
		global $_wp_additional_image_sizes;

		$default_image_sizes 					= get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size )
		{
			$image_sizes[ $size ][ 'width' ] 	= intval( get_option( "{$size}_size_w" ) );
			
			$image_sizes[ $size ][ 'height' ] 	= intval( get_option( "{$size}_size_h" ) );
			
			$image_sizes[ $size ][ 'crop' ] 	= get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) )
		{
			$image_sizes 						= array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
	}

	/**
	* Main function; deletes all thumbnails for specific post stored
	* in the uploads directory.
	*
	* @since 1.0.0
	*
	* @return int
	*/
	public function clean_thumbnails( $file_path, $file_name, $file_ext )
	{
		$all_thumbnailes_sizes 		= $this->get_all_image_sizes();

		$file_name_excluded_ext 	= basename( $file_name, '.' . $file_ext );

		foreach( $all_thumbnailes_sizes as $thumbnail )
		{
			// generate thumbnail file name
			$thumbnail_file_name 	= $file_name_excluded_ext . '-' . $thumbnail['width'] . 'x' . $thumbnail['height'] . '.' . $file_ext;

			$full_file_path 		= $file_path . $thumbnail_file_name;

			// check if file exists
			if ( file_exists( $full_file_path ) && file_is_valid_image( $full_file_path ) )
			{
				@unlink( $full_file_path );
			}
		}
	}
}
