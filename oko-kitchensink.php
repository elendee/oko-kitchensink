<?php
/**
 * Plugin Name: OKO Kitchensink
 * Plugin URI: https://oko.nyc
 * Description: A starter boilerplate for WordPress plugins with hooks, admin page, and best practices.
 * Version: 1.0.0
 * Author: KO
 * Author URI: https://oko.nyc
 * License: GPL v2 or later
 * Text Domain: oko-kitchensink
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.6
 * Requires PHP: 8.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
define( 'MY_PLUGIN_VERSION', '1.0.0' );
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// --- Module Configuration ---
// Manually toggle these booleans to enable/disable modules.
define( 'OKO_KSK_ENABLE_ACCOUNT_FIELDS', true ); // Set to false to disable WooCommerce features.
// --- End Module Configuration ---


// Plugin class loader.
class OKO_Kitchensink {
    
    private static $instance = null;
    
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }
    
    public function init() {
        // Load text domain for translations.
        load_plugin_textdomain( 'oko-kitchensink', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        
        // Hooks.
        $this->hooks();

        if ( class_exists( 'WooCommerce' ) { 
        	if( OKO_KSK_ENABLE_ACCOUNT_FIELDS ) {
	            require_once MY_PLUGIN_PATH . 'modules/wc_account_fields.php';
	        }
	    }
	    
    }
    
    private function hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_shortcode( 'my_shortcode', array( $this, 'shortcode_output' ) );
    }
    
    public function admin_menu() {
        add_options_page(
            __( 'OKO Kitchensink Settings', 'oko-kitchensink' ),
            __( 'OKO Kitchensink', 'oko-kitchensink' ),
            'manage_options',
            'oko-kitchensink',
            array( $this, 'settings_page' )
        );
    }
    
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'OKO Kitchensink Settings', 'oko-kitchensink' ); ?></h1>
            <p>
            	This is a boilerplate repo only.  Edit freely, and all changes are intended to be local to each site only.
            </p>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'oko_kitchensink_options' );
                do_settings_sections( 'oko_kitchensink_options' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style( 'oko-kitchensink-style', MY_PLUGIN_URL . 'assets/style.css', array(), MY_PLUGIN_VERSION );
    }
    
    public function shortcode_output( $atts ) {
        $atts = shortcode_atts( array(
            'text' => 'Hello from OKO Kitchensink!',
        ), $atts );
        
        ob_start();
        ?>
        <div class="oko-kitchensink-shortcode">
            <?php echo esc_html( $atts['text'] ); ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Activation/deactivation hooks.
register_activation_hook( __FILE__, 'oko_kitchensink_activate' );
function oko_kitchensink_activate() {
    // Add default options or flush rewrite rules.
}

register_deactivation_hook( __FILE__, 'oko_kitchensink_deactivate' );
function oko_kitchensink_deactivate() {
    // Cleanup if needed.
}

// Initialize.
OKO_Kitchensink::get_instance();
