<?php
/**
 * This file contains the definition of the Tip_The_Helper_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Tip_The_Helper
 * @subpackage    Tip_The_Helper/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    1.0.0
 */
class Tip_The_Helper_Admin {
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
	 * The plugin options api wrapper object.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       array $settings_api Holds the plugin options api wrapper class object.
	 */
	private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Sajjad_Dev_Settings_API();
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=tip-the-helper' ) ), __( 'Settings', 'tip-the-helper' ) );

		return $links;
	}

	/**
	 * Adds the plugin settings page to the WordPress dashboard menu.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Tip the Helper', 'tip-the-helper' ),
			__( 'Tip the Helper', 'tip-the-helper' ),
			'manage_options',
			'tip-the-helper',
			array( $this, 'menu_page' ),
			'dashicons-money-alt'
		);
	}

	/**
	 * Renders the plugin menu page content.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function menu_page() {
		$this->settings_api->show_forms();
	}

	/**
	 * Initializes admin-specific functionality.
	 *
	 * This function is hooked to the 'admin_init' action and is used to perform
	 * various administrative tasks, such as registering settings, enqueuing scripts,
	 * or adding admin notices.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function admin_init() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns the settings sections for the plugin settings page.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    array An array of settings sections, where each section is an array
	 *                  with 'id' and 'title' keys.
	 */
	public function get_settings_sections() {
		$settings_sections = array(
			array(
				'id'    => 'tip_the_helper_basic_settings',
				'title' => __( 'Tipping Settings', 'tip-the-helper' ),
			),
		);

		/**
		 * Filters the plugin settings sections.
		 *
		 * This filter allows you to modify the plugin settings sections.
		 * You can use this filter to add/remove/edit any settings sections.
		 *
		 * @since     1.0.0
		 * @param     array $settings_sections Default settings sections.
		 * @return    array $settings_sections Modified settings sections.
		 */
		return apply_filters( 'tip_the_helper_settings_sections', $settings_sections );
	}

	/**
	 * Returns all the settings fields for the plugin settings page.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    array An array of settings fields, organized by section ID.  Each
	 *                  section ID is a key in the array, and the value is an array
	 *                  of settings fields for that section. Each settings field is
	 *                  an array with 'name', 'label', 'type', 'desc', and other keys
	 *                  depending on the field type.
	 */
	public function get_settings_fields() {
		$settings_fields = array(
			'tip_the_helper_basic_settings' => array(
				array(
					'name'  => 'enable',
					'label' => __( 'Enable Tipping', 'tip-the-helper' ),
					'desc'  => __( 'Turn on/off the tipping feature for your store.', 'tip-the-helper' ),
					'type'  => 'checkbox',
				),
				array(
					'name'        => 'heading',
					'label'       => __( 'Main Heading Text', 'tip-the-helper' ),
					'desc'        => __( 'The primary title displayed above tipping options (e.g., "Want to say thanks?").', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => __( 'Add a Tip?', 'tip-the-helper' ),
					'placeholder' => __( 'Add a Tip?', 'tip-the-helper' ),
				),
				array(
					'name'        => 'subheading',
					'label'       => __( 'Description Text', 'tip-the-helper' ),
					'desc'        => __( 'Optional explanatory text below the heading (e.g., "Your tip supports our team").', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => __( 'Show your appreciation by adding a tip.', 'tip-the-helper' ),
					'placeholder' => __( 'Show your appreciation by adding a tip.', 'tip-the-helper' ),
				),
				array(
					'name'        => 'fee_label',
					'label'       => __( 'Tip Line Item Label', 'tip-the-helper' ),
					'desc'        => __( 'How the tip will appear in cart/order summaries (e.g., "Service Tip").', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => __( 'Tip Amount', 'tip-the-helper' ),
					'placeholder' => __( 'Tip Amount', 'tip-the-helper' ),
				),
				array(
					'name'        => 'fixed_preset',
					'label'       => __( 'Fixed Amount Options', 'tip-the-helper' ),
					'desc'        => __( 'Predefined tip amounts (comma-separated, no currency symbols). Example: 5,10,15', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => '5,10,15',
					'placeholder' => '5,10,15',
				),
				array(
					'name'        => 'percentage_preset',
					'label'       => __( 'Percentage Options', 'tip-the-helper' ),
					'desc'        => __( 'Predefined percentage tips (comma-separated, no % signs). Example: 5,10,15', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => '10,15,20',
					'placeholder' => '10,15,20',
				),
				array(
					'name'    => 'tipping_type',
					'label'   => __( 'Tipping Style', 'tip-the-helper' ),
					'desc'    => __( 'Choose how customers can tip.', 'tip-the-helper' ),
					'type'    => 'select',
					'default' => 'fixed',
					'options' => array(
						'fixed'      => __( 'Fixed Amounts Only', 'tip-the-helper' ),
						'percentage' => __( 'Percentage Amounts Only', 'tip-the-helper' ),
					),
				),
				array(
					'name'  => 'custom',
					'label' => __( 'Allow Custom Tip Amounts', 'tip-the-helper' ),
					'desc'  => __( 'When enabled, customers can enter any tip amount instead of selecting presets.', 'tip-the-helper' ),
					'type'  => 'checkbox',
				),
				array(
					'name'        => 'custom_btn_label',
					'label'       => __( 'Custom Button Label', 'tip-the-helper' ),
					'desc'        => __( 'The button label for selecting custom tip amount.', 'tip-the-helper' ),
					'type'        => 'text',
					'default'     => __( 'Custom', 'tip-the-helper' ),
					'placeholder' => __( 'Custom', 'tip-the-helper' ),
				),
			),
		);

		/**
		 * Filters the plugin settings fields.
		 *
		 * This filter allows you to modify the plugin settings fields.
		 * You can use this filter to add/remove/edit any settings field.
		 *
		 * @since     1.0.0
		 * @param     array $settings_fields Default settings fields.
		 * @return    array $settings_fields Modified settings fields.
		 */
		return apply_filters( 'tip_the_helper_settings_fields', $settings_fields );
	}

	/**
	 * Displays admin notices in the admin area.
	 *
	 * This function checks if the required plugin is active.
	 * If not, it displays a warning notice and deactivates the current plugin.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function admin_notices() {
		// Check if required plugin is active.
		if ( ! class_exists( 'WooCommerce', false ) ) {
			sprintf(
				'<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a> %s</p></div>',
				__( 'Tip the Helper requires', 'tip-the-helper' ),
				esc_url( 'https://wordpress.org/plugins/woocommerce/' ),
				__( 'WooCommerce', 'tip-the-helper' ),
				__( 'plugin to be active!', 'tip-the-helper' ),
			);

			// Deactivate the plugin.
			deactivate_plugins( TIP_THE_HELPER_PLUGIN_BASENAME );
		}
	}

	/**
	 * Declares compatibility with WooCommerce's custom order tables feature.
	 *
	 * This function is hooked into the `before_woocommerce_init` action and checks
	 * if the `FeaturesUtil` class exists in the `Automattic\WooCommerce\Utilities`
	 * namespace. If it does, it declares compatibility with the 'custom_order_tables'
	 * feature. This is important for ensuring the plugin works correctly with
	 * WooCommerce versions that support this feature.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function declare_compatibility_with_wc_custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}
