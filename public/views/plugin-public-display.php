<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since         1.0.0
 * @package       Tip_The_Helper
 * @subpackage    Tip_The_Helper/public/views
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$heading           = Tip_The_Helper::get_option( 'heading', 'tip_the_helper_basic_settings', __( 'Add a Tip?', 'tip-the-helper' ) );
$subheading        = Tip_The_Helper::get_option( 'subheading', 'tip_the_helper_basic_settings', __( 'Show your appreciation by adding a tip.', 'tip-the-helper' ) );
$fixed_preset      = explode( ',', Tip_The_Helper::get_option( 'fixed_preset', 'tip_the_helper_basic_settings', '5,10,15' ) );
$percentage_preset = explode( ',', Tip_The_Helper::get_option( 'percentage_preset', 'tip_the_helper_basic_settings', '10,15,20' ) );
$tipping_type      = Tip_The_Helper::get_option( 'tipping_type', 'tip_the_helper_basic_settings', 'fixed' );
$custom            = Tip_The_Helper::get_option( 'custom', 'tip_the_helper_basic_settings', 'off' );
$custom_btn_label  = Tip_The_Helper::get_option( 'custom_btn_label', 'tip_the_helper_basic_settings', __( 'Custom', 'tip-the-helper' ) );

?>
<div id="tip_the_helper" class="woocommerce-additional-fields">
	<h3 class="tip-the-helper-heading"><?php echo esc_html( $heading ); ?></h3>
	<p class="tip-the-helper-subheading"><?php echo esc_html( $subheading ); ?></p>

	<?php if ( 'fixed' === $tipping_type ) : ?>
		<div class="form-row form-row-wide">
			<?php foreach ( $fixed_preset as $key => $amount ) : ?>
				<p class="woocommerce-form__label woocommerce-form__label-for-radio">
					<input type="radio" class="input-radio tipping-input" id="tip_the_helper_<?php echo esc_attr( $key ); ?>" name="tip_the_helper_fixed" value="<?php printf( '%.2f', esc_attr( $amount ) ); ?>">
					<label for="tip_the_helper_<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( wc_price( $amount ) ); ?></label>
				</p>
			<?php endforeach; ?>

			<?php if ( 'on' === $custom ) : ?>
				<p class="woocommerce-form__label woocommerce-form__label-for-radio tip-the-helper-custom-input">
					<input type="radio" id="tip_the_helper_custom" class="input-radio tipping-input" name="tip_the_helper_fixed" value="custom">
					<label for="tip_the_helper_custom"><?php echo esc_html( $custom_btn_label ); ?></label>
					<span id="custom_tip_input_wrapper">
						<input type="number" class="input-text" step="0.5" min="0" name="tip_the_helper_custom" placeholder="9.99">
					</span>
				</p>
			<?php endif; ?>
		</div>

	<?php elseif ( 'percentage' === $tipping_type ) : ?>
		<div class="form-row form-row-wide">
			<?php foreach ( $percentage_preset as $key => $amount ) : ?>
				<p class="woocommerce-form__label woocommerce-form__label-for-radio">
					<input type="radio" class="input-radio tipping-input" id="tip_the_helper_<?php echo esc_attr( $key ); ?>" name="tip_the_helper_percentage" value="<?php printf( '%d', esc_attr( $amount ) ); ?>">
					<label for="tip_the_helper_<?php echo esc_attr( $key ); ?>"><?php printf( '%d %%', esc_attr( $amount ) ); ?></label>
				</p>
			<?php endforeach; ?>

			<?php if ( 'on' === $custom ) : ?>
				<p class="woocommerce-form__label woocommerce-form__label-for-radio tip-the-helper-custom-input">
					<input type="radio" id="tip_the_helper_custom" class="input-radio tipping-input" name="tip_the_helper_percentage" value="custom">
					<label for="tip_the_helper_custom"><?php echo esc_html( $custom_btn_label ); ?></label>
					<span id="custom_tip_input_wrapper">
						<input type="number" class="input-text tipping-input" step="0.5" min="0" name="tip_the_helper_custom" placeholder="5">
					</span>
				</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>