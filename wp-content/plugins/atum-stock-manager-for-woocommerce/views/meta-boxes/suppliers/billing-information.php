<?php
/**
 * View for the Supplier billing information meta box
 *
 * @since 1.2.9
 *
 * @var int   $supplier_id
 * @var array $countries
 */

defined( 'ABSPATH' ) || die;

?>

<div class="atum-meta-box supplier">

	<p class="description"><?php esc_html_e( 'Provide the billing information on this supplier.', ATUM_TEXT_DOMAIN ) ?></p>

	<div class="form-field form-field-wide">
		<label for="currency"><?php esc_html_e( 'Currency', ATUM_TEXT_DOMAIN ) ?></label>

		<select id="currency" name="billing_information[currency]" style="width:100%;" data-placeholder="<?php esc_attr_e( 'Choose a currency&hellip;', ATUM_TEXT_DOMAIN ); ?>" class="wc-enhanced-select atum-enhanced-select">
			<option value=""><?php esc_html_e( 'Choose a currency&hellip;', ATUM_TEXT_DOMAIN ); ?></option>
			<?php
			$currency = get_post_meta( $supplier_id, '_billing_information_currency', TRUE );
			foreach ( get_woocommerce_currencies() as $code => $name ) :
				/* translators: the fist one is the currency name and the second is the currency code */
				echo '<option value="' . esc_attr( $code ) . '" ' . selected( $currency, $code, FALSE ) . '>' . sprintf( esc_html__( '%1$s (%2$s)', ATUM_TEXT_DOMAIN ), $name, get_woocommerce_currency_symbol( $code ) ) . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endforeach;
			?>
		</select>

	</div>

	<div class="form-field form-field-wide">
		<label for="address"><?php esc_html_e( 'Address', ATUM_TEXT_DOMAIN ) ?></label>
		<input type="text" id="address" name="billing_information[address]" value="<?php echo esc_attr( get_post_meta( $supplier_id, '_billing_information_address', TRUE ) ) ?>">
	</div>

	<div class="form-field form-field-wide">
		<label for="city"><?php esc_html_e( 'City', ATUM_TEXT_DOMAIN ) ?></label>
		<input type="text" id="city" name="billing_information[city]" value="<?php echo esc_attr( get_post_meta( $supplier_id, '_billing_information_city', TRUE ) ) ?>">
	</div>

	<div class="form-field form-field-wide">
		<label for="country"><?php esc_html_e( 'Country', ATUM_TEXT_DOMAIN ) ?></label>

		<?php $country = get_post_meta( $supplier_id, '_billing_information_country', TRUE ); ?>
		<select id="country" name="billing_information[country]" style="width:100%;" data-placeholder="<?php esc_attr_e( 'Choose a country&hellip;', ATUM_TEXT_DOMAIN ); ?>" class="wc-enhanced-select atum-enhanced-select">
			<option value=""><?php esc_html_e( 'Choose a country&hellip;', ATUM_TEXT_DOMAIN ); ?></option>

			<?php foreach ( $countries as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ) ?>"<?php selected( $key, $country ) ?>><?php echo esc_html( $value ) ?></option>
			<?php endforeach; ?>
		</select>

	</div>

	<div class="form-field form-field-wide">
		<label for="state"><?php esc_html_e( 'State', ATUM_TEXT_DOMAIN ) ?></label>
		<input type="text" id="state" name="billing_information[state]" value="<?php echo esc_attr( get_post_meta( $supplier_id, '_billing_information_state', TRUE ) ) ?>">
	</div>

	<div class="form-field form-field-wide">
		<label for="zip_code"><?php esc_html_e( 'Zip Code', ATUM_TEXT_DOMAIN ) ?></label>
		<input type="text" id="zip_code" name="billing_information[zip_code]" value="<?php echo esc_attr( get_post_meta( $supplier_id, '_billing_information_zip_code', TRUE ) ) ?>">
	</div>

</div>
