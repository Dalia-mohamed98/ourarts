<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<!-- <div class="woocommerce-form-coupon-toggle">
	<?php //wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', __( 'هل لديك كوبون خصم؟', 'woocommerce' ) ), 'notice' ); ?>
</div> -->

<form class="checkout_coupon woocommerce-form-coupon" method="post">

	<!-- <p><?php //esc_html_e( 'اذا كان لديك رمز الكوبون, فيرجى استخدامه ادناه. ', 'woocommerce' ); ?></p> -->
	<div class="coupon">
		<div class="flex-row">
			<div class="flex-col flex-grow">
				<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( ' ادخل كوبون خصم', 'woocommerce' ); ?>" id="coupon_code" value="" />			
			</div>
			<div class="flex-col">
				<button type="submit" class="button expand" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( ' اضف', 'woocommerce' ); ?></button>			
			</div>
		</div><!-- row -->
	</div><!-- coupon -->
</form>
