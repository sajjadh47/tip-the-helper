<?php
/**
 * This file contains the definition of the Tip_The_Helper_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Tip_The_Helper
 * @subpackage    Tip_The_Helper/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    1.0.0
 */
class Tip_The_Helper_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		$enabled = Tip_The_Helper::get_option( 'enable', 'tip_the_helper_basic_settings', 'off' );

		if ( 'on' !== $enabled ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, TIP_THE_HELPER_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$enabled = Tip_The_Helper::get_option( 'enable', 'tip_the_helper_basic_settings', 'off' );

		if ( 'on' !== $enabled ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name, TIP_THE_HELPER_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'TipTheHelper',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Displays the tip input form on the checkout page if enabled.
	 *
	 * This method checks if the tipping feature is enabled via plugin options.
	 * If it's enabled, it includes the public view file responsible for rendering
	 * the tipping form on the WooCommerce checkout page.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function display_tipping_form() {
		$enabled = Tip_The_Helper::get_option( 'enable', 'tip_the_helper_basic_settings', 'off' );

		if ( 'on' !== $enabled ) {
			return;
		}

		require_once TIP_THE_HELPER_PLUGIN_PATH . '/public/views/plugin-public-display.php';
	}

	/**
	 * Validates the custom tip amount submitted during checkout.
	 *
	 * This method checks both fixed and percentage-based custom tip inputs.
	 * It ensures that if 'custom' is selected, a valid numeric amount is provided.
	 * Error notices are added to WooCommerce if validation fails.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function validate_tipping_data() {
		// Get & sanitize POST data for security.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_data = array_map( 'sanitize_text_field', wp_unslash( $_POST ) );

		// Validate custom fixed tip.
		if ( isset( $post_data['tip_the_helper_fixed'] ) && 'custom' === $post_data['tip_the_helper_fixed'] ) {
			if ( empty( $post_data['tip_the_helper_custom'] ) || ! is_numeric( $post_data['tip_the_helper_custom'] ) ) {
				wc_add_notice( __( 'Please enter a valid custom tip amount.', 'tip-the-helper' ), 'error' );
			}
		}

		// Validate custom percentage tip.
		if ( isset( $post_data['tip_the_helper_percentage'] ) && 'custom' === $post_data['tip_the_helper_percentage'] ) {
			if ( empty( $post_data['tip_the_helper_custom'] ) || ! is_numeric( $post_data['tip_the_helper_custom'] ) ) {
				wc_add_notice( __( 'Please enter a valid custom tip amount.', 'tip-the-helper' ), 'error' );
			}
		}
	}

	/**
	 * Applies the selected tip amount as a fee to the WooCommerce cart.
	 *
	 * This method determines the tip amount based on fixed or percentage selection,
	 * including handling custom amounts. It then adds this amount as a custom fee
	 * to the WooCommerce cart. It also includes checks to prevent execution in admin
	 * and non-AJAX contexts.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @param     WC_Cart $cart The WooCommerce cart object. Passed by reference implicitly by WooCommerce hooks.
	 */
	public function apply_tipping_to_cart( $cart ) {
		// Prevent execution in the admin area unless it's an AJAX request related to the frontend.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['post_data'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			parse_str( sanitize_text_field( wp_unslash( $_POST['post_data'] ) ), $post_data );
		} else {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$post_data = array_map( 'sanitize_text_field', wp_unslash( $_POST ) );
		}

		// Handle fixed tip.
		if ( isset( $post_data['tip_the_helper_fixed'] ) ) {
			$this->add_tip_to_cart( $cart, 'fixed', $post_data['tip_the_helper_fixed'], $post_data['tip_the_helper_custom'] ?? '' );
		}

		// Handle percentage tip.
		if ( isset( $post_data['tip_the_helper_percentage'] ) ) {
			$this->add_tip_to_cart( $cart, 'percentage', $post_data['tip_the_helper_percentage'], $post_data['tip_the_helper_custom'] ?? '' );
		}
	}

	/**
	 * Adds a tip as a fee to the WooCommerce cart based on the selected tip value.
	 *
	 * This is a private helper method to avoid code duplication in `apply_tipping_to_cart`.
	 * It calculates the final tip amount and adds it as a fee.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @param     WC_Cart $cart              The WooCommerce cart object.
	 * @param     string  $tip_type          The selected tipping type (e.g., 'fixed', 'percentage').
	 * @param     string  $selected_tip      The value of the selected tip option (e.g., '5', '10', 'custom').
	 * @param     string  $custom_tip_amount The value of the custom tip input, if 'custom' was selected.
	 */
	private function add_tip_to_cart( $cart, $tip_type, $selected_tip, $custom_tip_amount ) {
		$tip_value = $selected_tip;

		// If 'custom' is selected and a custom amount is provided, use that.
		if ( 'custom' === $selected_tip && ! empty( $custom_tip_amount ) ) {
			$tip_value = $custom_tip_amount;
		}

		$tip_amount = floatval( $tip_value );
		$fee_label  = Tip_The_Helper::get_option( 'fee_label', 'tip_the_helper_basic_settings', __( 'Tip Amount', 'tip-the-helper' ) );

		$final_tip_amount = 0.0; // Initialize final tip amount.

		// Determine the actual tip amount based on type.
		if ( 'percentage' === $tip_type ) {
			$percentage = floatval( $tip_value );

			if ( $percentage > 0 ) {
				// Get the cart subtotal. This is the sum of prices of all items in the cart.
				$cart_subtotal = floatval( $cart->get_subtotal() );

				// Calculate the tip amount as a percentage of the subtotal.
				$final_tip_amount = ( $cart_subtotal * $percentage ) / 100;
			}
		} else { // Selected 'fixed'.
			$final_tip_amount = floatval( $tip_value );
		}

		// Add the fee to the cart only if the final tip amount is positive.
		if ( $final_tip_amount > 0 ) {
			// The third parameter `false` means the fee is not taxable. Adjust if needed.
			$cart->add_fee( $fee_label, $final_tip_amount, false );
		}
	}
}
