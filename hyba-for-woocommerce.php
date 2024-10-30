<?php
/**
 * Plugin Name: HyBa for WooCommerce
 * Description: Extends WooCommerce with HyBa Banklinks.
 * Version: 1.5.0
 * Author: HyBa OÜ
 * Author URI: http://uus.hyba.ee/
 * License: GPLv2 or later
 * Text Domain: wc-gateway-hyba
 * WC requires at least: 2.6
 * WC tested up to: 5.4.2
 */

// Security check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main file constant
 */
define( 'WC_HYBA_GATEWAYS_MAIN_FILE', __FILE__ );

define( 'WC_HYBA_LIVE', 'https://iseteenindus.hyba.ee/' );

define( 'WC_HYBA_PRELIVE', 'https://prelive.hyba.ee/' );

/**
 * Includes folder path
 */
define( 'WC_HYBA_GATEWAYS_INCLUDES_PATH', plugin_dir_path( WC_HYBA_GATEWAYS_MAIN_FILE ) . 'includes' );

/**
 * @class    HyBa_Gateways_For_WooCommerce
 * @category Plugin
 * @package  HyBa_Gateways_For_WooCommerce
 */
class HyBa_Gateways_For_WooCommerce {
	/**
	 * Instance
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Class constructor
	 */
	function __construct() {
		add_action( 'plugins_loaded',                   array( $this, 'plugins_loaded' ) );

		// Allow WC template file search in this plugin
		add_filter( 'woocommerce_locate_template',      array( $this, 'locate_template' ), 20, 3 );
		add_filter( 'woocommerce_locate_core_template', array( $this, 'locate_template' ), 20, 3 );
	}

	/**
	 * Initialize plugin
	 * @return void
	 */
	public function plugins_loaded() {
		// Check if payment gateways are available
		if ( $this->is_payment_gateway_class_available() ) {
			add_filter( 'woocommerce_payment_gateways', array( $this, 'register_gateways' ) );

			// Load functionality, translations
			$this->includes();
			$this->load_translations();
		}
	}

	/**
	 * Require functionality
	 *
	 * @return void
	 */
	public function includes() {
		// Compatibility helpers
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/compatibility-helpers.php';

		// Abstract classes
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/abstracts/class-wc-hyba.php';
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/abstracts/class-wc-hyba-ipizza.php';

		// IPizza
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/gateways/class-wc-hyba-pay-gateway.php';
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/gateways/class-wc-hyba-split-gateway.php';
		require_once WC_HYBA_GATEWAYS_INCLUDES_PATH . '/gateways/class-wc-hyba-plan-gateway.php';
	}

	/**
	 * Check if WooCommerce WC_Payment_Gateway class exists
	 *
	 * @return boolean True if it does
	 */
	function is_payment_gateway_class_available() {
		return class_exists( 'WC_Payment_Gateway' );
	}

	/**
	 * Load translations
	 *
	 * Allows overriding the offical translation by placing
	 * the translation files in wp-content/languages/hyba-for-woocommerce
	 *
	 * @return void
	 */
	function load_translations() {
		$domain = 'wc-gateway-hyba';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/hyba-for-woocommerce/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( WC_HYBA_GATEWAYS_MAIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Register gateways
	 *
	 * @param  array $gateways Gateways
	 * @return array           Gateways
	 */
	function register_gateways( $gateways ) {
		$gateways[] = 'WC_Hyba_Pay_Gateway';
		$gateways[] = 'WC_Hyba_Split_Gateway';
		// $gateways[] = 'WC_Hyba_Plan_Gateway';

		return $gateways;
	}


	/**
	 * Fetch instance of this plugin
	 *
	 * @return HyBa_Gateways_For_WooCommerce
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Locates the WooCommerce template files from this plugin directory
	 *
	 * @param  string $template      Already found template
	 * @param  string $template_name Searchable template name
	 * @param  string $template_path Template path
	 * @return string                Search result for the template
	 */
	function locate_template( $template, $template_name, $template_path ) {
		// Tmp holder
		$_template = $template;

		if ( ! $template_path ) {
			$template_path = WC_TEMPLATE_PATH;
		}

		// Set our base path
		$plugin_path = plugin_dir_path( WC_HYBA_GATEWAYS_MAIN_FILE ) . '/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template	= $plugin_path . $template_name;
		}

		// Use default template
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}
}


/**
 * Returns the main instance of HyBa_Gateways_For_WooCommerce to prevent the need to use globals.
 * @return HyBa_Gateways_For_WooCommerce
 */
function WC_HyBa_Gateways() {
	return HyBa_Gateways_For_WooCommerce::instance();
}

// Global for backwards compatibility.
$GLOBALS['wc_hyba_gateways'] = WC_HyBa_Gateways();


function display_product_teaser()  {
    global $product;

    $active_price = wc_get_price_to_display($product);
    $active_gateways = WC()->payment_gateways->get_available_payment_gateways();
    foreach($active_gateways  as $gateway){
        if(get_option('woocomerce_show_hyba_info') == 'on' && in_array($gateway->id, ['hybapay', 'hybasplit', 'hybaplan'])) {

            if ($gateway->enabled == 'yes' && $active_price <= $gateway->banner_maximum_amount && $active_price >= $gateway->banner_minimum_amount) {
                $icon = '<img src="' . WC_HTTPS::force_https_url($gateway->logo) . '" alt="' . $gateway->method_title . '" style="float:left; max-height:35px; vertical-align: baseline;"/>';
                echo '<p style="margin: 0.5rem 0 1.5rem 0;">' . sprintf( __($gateway->cart_banner . '&nbsp;', "woocommerce")) . ' <br> '. $icon . '</p>';
            }
        }

    }
}
add_action('woocommerce_single_product_summary', 'display_product_teaser', get_option('woocomerce_show_hyba_info_position') == 'yes' ? 36 : 12);

add_action('admin_init', 'addHybaAdsConfig');

$woocomerceHybaInfoBlock = 'woocomerce_show_hyba_info';
$woocomerceHybaInfoBlockPosition = 'woocomerce_show_hyba_info_position';

function addHybaAdsConfig(){
    global $woocomerceHybaInfoBlock;
    global $woocomerceHybaInfoBlockPosition;

    add_settings_section( 'hyba_settings', 'HyBa product page banner settings', '', 'general' );

    register_setting('general', $woocomerceHybaInfoBlock, 'esc_attr');
    register_setting('general', $woocomerceHybaInfoBlockPosition, 'esc_attr');

    add_settings_field(
        $woocomerceHybaInfoBlock,
        'Enable product page banner',
        function(){
            global $woocomerceHybaInfoBlock;
            $value = get_option($woocomerceHybaInfoBlock, '');
            echo '<input type="checkbox" id="' . $woocomerceHybaInfoBlock . '" name="' . $woocomerceHybaInfoBlock . '" / ' . ($value == 'on' ? 'checked="checked"' : ""). '">';
        },
        'general',
        'hyba_settings'
    );

    add_settings_field(
        $woocomerceHybaInfoBlockPosition,
        'Set banner location',
        function(){
            global $woocomerceHybaInfoBlockPosition;
            $value = get_option($woocomerceHybaInfoBlockPosition, '');

            echo '<input type="radio" name="' . $woocomerceHybaInfoBlockPosition . '" value="no" ' . ($value == 'no' ?  'checked="checked" ' : "") . '> Before add-to-cart &emsp;';
            echo '<input type="radio" name="' . $woocomerceHybaInfoBlockPosition . '" value="yes" ' . ($value == 'yes' ?  'checked="checked" ' : "") . '> After add-to-cart';
        }
        ,
        'general',
        'hyba_settings'
    );
}
function addHybaAdsConfigShowOpt(){
    global $woocomerceHybaInfoBlock;
    $value = get_option($woocomerceHybaInfoBlock, '');
    echo '<input type="checkbox" id="' . $woocomerceHybaInfoBlock . '" name="' . $woocomerceHybaInfoBlock . '" / ' . ($value == 'yes' ? 'checked="checked"' : ""). '">';
}