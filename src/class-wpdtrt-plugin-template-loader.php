<?php
/**
 * Template loader.
 *
 * @package     WPDTRT_Attachment_Map
 * @subpackage  WPDTRT_Attachment_Map/app
 * @since       0.6.0
 * @version 	1.0.0
 */

/**
 * Template loader sub class
 *
 * Extends the base class to inherit functionality.
 * Displays templates in the Templates dropdown in the page edit screen.
 * Allows the author to override these from the templates folder in their own theme.
 *
 * @uses 		https://github.com/wpexplorer/page-templater
 * @see 		http://www.wpexplorer.com/wordpress-page-templates-plugin/
 *
 * @since       0.6.0
 * @version 	1.0.0
 *
 * @todo 		See wpdtrt-responsive-nav-shortcodes.php for usage
 */
class WPDTRT_Plugin_Template_Loader extends Gamajo_Template_Loader {

	public function __construct( $options ) {

      // define variables
      $filter_prefix = null;
      $plugin_template_directory = null;
      $theme_template_directory = null;
      $plugin_directory = null;

      $this->filter_prefix = $filter_prefix;
      $this->plugin_template_directory = $plugin_template_directory;
      $this->theme_template_directory = $theme_template_directory;
      $this->plugin_directory = $plugin_directory;

      // extract variables
      extract( $options, EXTR_IF_EXISTS );

	}

	/**
	 * Prefix for filter names.
	 */
	//protected $filter_prefix = 'wpdtrt-attachment-map';

	/**
	 * Directory name where custom templates for this plugin should be found in the plugin.
	 */
	//protected $plugin_template_directory = 'template-parts/wpdtrt-attachment-map';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 */
	//protected $theme_template_directory = 'template-parts/wpdtrt-attachment-map';

	/**
	 * Reference to the root directory path of this plugin.
	 */
	//protected $plugin_directory = WPDTRT_ATTACHMENT_MAP_PATH;

}

?>