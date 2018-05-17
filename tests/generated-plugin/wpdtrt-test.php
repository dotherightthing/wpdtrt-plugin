<?php
/**
 * Plugin Name:  DTRT Test
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-plugin
 * Description:  Test plugin using the wpdtrt-plugin base classes.
 * Version:      1.4.15
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-test
 * Domain Path:  /languages
 */

/**
 * Constants
 * WordPress makes use of the following constants when determining the path to the content and plugin directories.
 * These should not be used directly by plugins or themes, but are listed here for completeness.
 * WP_CONTENT_DIR  // no trailing slash, full paths only
 * WP_CONTENT_URL  // full url
 * WP_PLUGIN_DIR  // full path, no trailing slash
 * WP_PLUGIN_URL  // full url, no trailing slash
 *
 * WordPress provides several functions for easily determining where a given file or directory lives.
 * Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 * or using the WordPress internal constants.
 * plugins_url()
 * plugin_dir_url()
 * plugin_dir_path()
 * plugin_basename()
 *
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

if ( ! defined( 'WPDTRT_TEST_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * WP provides get_plugin_data(), but it only works within WP Admin,
	 * so we define a constant instead.
	 *
	 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
	 * @link https://wordpress.stackexchange.com /questions/18268/i-want-to-get-a-plugin-version-number-dynamically
	 *
	 * @since     1.0.0
	 * @version   1.0.0
	 */
	define( 'WPDTRT_TEST_VERSION', '1.4.15' );
}

if ( ! defined( 'WPDTRT_TEST_PATH' ) ) {
	/**
	 * Plugin directory filesystem path.
	 *
	 * @param string $file
	 * @return The filesystem directory path (with trailing slash)
	 *
	 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 *
	 * @since     1.0.0
	 * @version   1.0.0
	 */
	define( 'WPDTRT_TEST_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPDTRT_TEST_URL' ) ) {
	/**
	 * Plugin directory URL path.
	 *
	 * @param string $file
	 * @return The URL (with trailing slash)
	 *
	 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
	 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 *
	 * @since     1.0.0
	 * @version   1.0.0
	 */
	define( 'WPDTRT_TEST_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * ===== Dependencies =====
 */

if ( defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
	$project_root_path = realpath( __DIR__ . '/../../..' ) . '/';
} else {
	// Note: path changed for wpdtrt-test.php only.
	$project_root_path = realpath( __DIR__ . '/../..' ) . '/';
}

// wpdtrt-plugin's root file loads Composer's autoloader.
require_once $project_root_path . 'index.php';

// sub classes, not loaded via PSR-4.
// comment out the ones you don't need, edit the ones you do.
require_once WPDTRT_TEST_PATH . 'src/class-wpdtrt-test-plugin.php';
require_once WPDTRT_TEST_PATH . 'src/class-wpdtrt-test-rewrite.php';
require_once WPDTRT_TEST_PATH . 'src/class-wpdtrt-test-shortcode.php';
require_once WPDTRT_TEST_PATH . 'src/class-wpdtrt-test-taxonomy.php';
require_once WPDTRT_TEST_PATH . 'src/class-wpdtrt-test-widget.php';

// log & trace helpers.
global $debug;
$debug = new DoTheRightThing\WPDebug\Debug;

/**
 * ===== WordPress Integration =====
 */
add_action( 'init', 'wpdtrt_test_plugin_init', 0 );
add_action( 'init', 'wpdtrt_test_shortcode_init', 100 );
add_action( 'init', 'wpdtrt_test_taxonomy_init', 100 );
add_action( 'widgets_init', 'wpdtrt_test_widget_init' );

/**
 * ===== Plugin config =====
 */

/**
 * Plugin initialisaton
 *
 * We call init before widget_init so that the plugin object properties are available to it.
 * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
 * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
 * widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
 *
 * @see https://wp-mix.com/wordpress-widget_init-not-working/
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 * @todo Add a constructor function to WPDTRT_Blocks_Plugin, to explain the options array
 */
function wpdtrt_test_plugin_init() {
	// pass object reference between classes via global
	// because the object does not exist until the WordPress init action has fired
	global $wpdtrt_test_plugin;

	/**
	 * Admin settings.
	 *  Changed to $taxonomy_options and retained for legacy support - may not be reqd
	 */
	$plugin_options = array();

	/**
	 * All options available to Widgets and Shortcodes
	 */
	$instance_options = array();

	$wpdtrt_test_plugin = new WPDTRT_Test_Plugin(
		array(
			'url'              => WPDTRT_TEST_URL,
			'prefix'           => 'wpdtrt_test',
			'slug'             => 'wpdtrt-test',
			'menu_title'       => __( 'Test', 'wpdtrt-test' ),
			'settings_title'   => __( 'Settings', 'wpdtrt-test' ),
			'developer_prefix' => 'DTRT',
			'path'             => WPDTRT_TEST_PATH,
			'messages'         => array(
				'loading'                     => __( 'Loading latest data...', 'wpdtrt-test' ),
				'success'                     => __( 'settings successfully updated', 'wpdtrt-test' ),
				'insufficient_permissions'    => __( 'Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-test' ),
				'options_form_title'          => __( 'General Settings', 'wpdtrt-test' ),
				'options_form_description'    => __( 'Please enter your preferences.', 'wpdtrt-test' ),
				'no_options_form_description' => __( 'There aren\'t currently any options.', 'wpdtrt-test' ),
				'options_form_submit'         => __( 'Save Changes', 'wpdtrt-test' ),
				'noscript_warning'            => __( 'Please enable JavaScript', 'wpdtrt-test' ),
				'demo_sample_title'           => __( 'Demo sample', 'wpdtrt-test' ),
				'demo_data_title'             => __( 'Demo data', 'wpdtrt-test' ),
				'demo_shortcode_title'        => __( 'Demo shortcode', 'wpdtrt-test' ),
				'demo_data_description'       => __( 'This demo was generated from the following data', 'wpdtrt-test' ),
				'demo_date_last_updated'      => __( 'Data last updated', 'wpdtrt-test' ),
				'demo_data_length'            => __( 'results', 'wpdtrt-test' ),
				'demo_data_displayed_length'  => __( 'results displayed', 'wpdtrt-test' ),
			),
			'plugin_options'   => $plugin_options,
			'instance_options' => $instance_options,
			'version'          => WPDTRT_TEST_VERSION,
		)
	);
}

/**
 * ===== Rewrite config =====
 */

/**
 * Register Rewrite
 */
function wpdtrt_test_rewrite_init() {

	global $wpdtrt_test_plugin;

	$wpdtrt_test_rewrite = new WPDTRT_Test_Rewrite();
}

/**
 * ===== Shortcode config =====
 */

/**
 * Register Shortcode
 */
function wpdtrt_test_shortcode_init() {

	global $wpdtrt_test_plugin;

	$wpdtrt_test_shortcode = new WPDTRT_Test_Shortcode(
		array(
			'name'                      => 'wpdtrt_test_shortcode',
			'plugin'                    => $wpdtrt_test_plugin,
			'template'                  => 'test',
			'selected_instance_options' => array(),
		)
	);
}

/**
 * ===== Taxonomy config =====
 */

/**
 * Register Taxonomy
 *
 * @return object Taxonomy/
 */
function wpdtrt_test_taxonomy_init() {

	global $wpdtrt_test_plugin;

	$wpdtrt_test_taxonomy = new WPDTRT_Test_Taxonomy(
		array(
			'name'                      => 'wpdtrt_test_taxonomy',
			'plugin'                    => $wpdtrt_test_plugin,
			'selected_instance_options' => array(),
			'taxonomy_options'          => array(
				'option1' => array(
					'type'              => 'text',
					'label'             => esc_html__( 'Option 1', 'wpdtrt-test' ),
					'admin_table'       => true,
					'admin_table_label' => esc_html__( 'Opt 1', 'wpdtrt-test ' ),
					'admin_table_sort'  => true,
					'tip'               => 'Enter something',
					'todo_condition'    => 'foo !== "bar"',
				),
			),
			'labels'                    => array(
				'slug'                       => 'tours',
				'description'                => __( 'Multiday rides', 'wpdtrt-test ' ),
				'posttype'                   => 'tourdiaries',
				'name'                       => __( 'Tours', 'taxonomy general name' ),
				'singular_name'              => _x( 'Tour', 'taxonomy singular name' ),
				'menu_name'                  => __( 'Tours', 'wpdtrt-test' ),
				'all_items'                  => __( 'All Tours', 'wpdtrt-test' ),
				'add_new_item'               => __( 'Add New Tour', 'wpdtrt-test' ),
				'edit_item'                  => __( 'Edit Tour', 'wpdtrt-test' ),
				'view_item'                  => __( 'View Tour', 'wpdtrt-test' ),
				'update_item'                => __( 'Update Tour', 'wpdtrt-test' ),
				'new_item_name'              => __( 'New Tour Name', 'wpdtrt-test' ),
				'parent_item'                => __( 'Parent Tour', 'wpdtrt-test' ),
				'parent_item_colon'          => __( 'Parent Tour:', 'wpdtrt-test' ),
				'search_items'               => __( 'Search Tours', 'wpdtrt-test' ),
				'popular_items'              => __( 'Populars', 'wpdtrt-test' ),
				'separate_items_with_commas' => __( 'Separate Tours with commas', 'wpdtrt-test' ),
				'add_or_remove_items'        => __( 'Add or remove Tours', 'wpdtrt-test' ),
				'choose_from_most_used'      => __( 'Choose from most used Tours', 'wpdtrt-test' ),
				'not_found'                  => __( 'No Tours found', 'wpdtrt-test' ),
				'separate_items_with_commas' => __( 'Separate Tours with commas', 'wpdtrt-test' ),
			),
		)
	);

	// return a reference for unit testing.
	return $wpdtrt_test_taxonomy;
}

/**
 * ===== Widget config =====
 */

/**
 * Register a WordPress widget, passing in an instance of our custom widget class
 * The plugin does not require registration, but widgets and shortcodes do.
 * Note: widget_init fires before init, unless init has a priority of 0
 *
 * @since       0.7.6
 * @version     0.0.1
 * @uses        ../../../../wp-includes/widgets.php
 * @see         https://codex.wordpress.org/Function_Reference/register_widget#Example
 * @see         https://wp-mix.com/wordpress-widget_init-not-working/
 * @see         https://codex.wordpress.org/Plugin_API/Action_Reference
 * @uses        https://github.com/dotherightthing/wpdtrt/tree/master/library/sidebars.php
 * @todo        Add form field parameters to the options array
 * @todo        Investigate the 'classname' option
 */
function wpdtrt_test_widget_init() {

	global $wpdtrt_test_plugin;

	$wpdtrt_test_widget = new WPDTRT_Test_Widget(
		array(
			'name'                      => 'wpdtrt_test_widget',
			'title'                     => __( 'Test Widget', 'wpdtrt-test' ),
			'description'               => __( 'Demo plugin which uses wpdtrt-plugin.', 'wpdtrt-test' ),
			'plugin'                    => $wpdtrt_test_plugin,
			'template'                  => 'test',
			'selected_instance_options' => array(),
		)
	);

	register_widget( $wpdtrt_test_widget );
}