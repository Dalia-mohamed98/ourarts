<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Check stock status.
$out_of_stock = get_post_meta( $post->ID, '_stock_status', true ) == 'outofstock';

// Extra post classes.
$classes   = array();
$classes[] = 'product-small';
$classes[] = 'col';
$classes[] = 'has-hover';

$has_multivendor = get_post_meta( $post->ID, '_has_multi_vendor', true );

//check if duplicate products exist
if ( !empty( $has_multivendor ) ) {
	$sql     = "SELECT product_id FROM {$wpdb->prefix}dokan_product_map WHERE map_id= $has_multivendor AND is_trash = 0";
	$results = $wpdb->get_results( $sql );
	// var_dump($results);
	$multi_products = array();
	foreach ($results as $key => $result){
		 array_push($multi_products, wc_get_product($result->product_id));
	}
	// var_dump($multi_products);
	$in_stock_multi = 0;
	foreach ($multi_products as $multi_product){
		if($multi_product->is_in_stock())
			$in_stock_multi +=1 ;
	}
	//if more than one product in stock or all out of stock, then select least price
	if(($in_stock_multi > 1 && $product->is_in_stock()) || $in_stock_multi == 0){
		$min_price = $product->get_price();
		$least_product = $product;
		foreach($multi_products as $multi_product)
		{
			if($min_price > $multi_product->get_price())
				{
					$min_price = $multi_product->get_price();
					$least_product = $multi_product;
				}
		}
		// hide product from shop if not selected 
		if($post->ID != $least_product->get_ID())
			{
				$terms = array( 'exclude-from-catalog' ); 
				wp_set_post_terms( $post->ID, $terms, 'product_visibility', false );

				if(!$least_product->is_visible()){
					$product->set_props(
						array(
							'featured'           => array(),
							'catalog_visibility' => 'visible',
						)
					);
				}
				return;
			}
		else{
			$terms = array( 'exclude-from-catalog' ); 
			wp_set_post_terms( $least_product->get_ID(), $terms, 'product_visibility', false );

		}
	}
	//if only one in stock but not this product, hide
	else if(!$product->is_in_stock()){
		$terms = array( 'exclude-from-catalog' ); 
		wp_set_post_terms( $post->ID, $terms, 'product_visibility', false );
		return;

	}

}

//check out of stock
if ( $out_of_stock ) 
	$classes[] = 'out-of-stock';


?>

<div <?php fl_woocommerce_version_check( '3.4.0' ) ? wc_product_class( $classes, $product ) : post_class( $classes ); ?>>
	<div class="col-inner">
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="product-small box <?php echo flatsome_product_box_class(); ?>">
		<div class="box-image">
			<div class="<?php echo flatsome_product_box_image_class(); ?>">
				<a href="<?php echo get_the_permalink(); ?>">
					<?php
						/**
						 *
						 * @hooked woocommerce_get_alt_product_thumbnail - 11
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'flatsome_woocommerce_shop_loop_images' );
					?>
				</a>
			</div>
			<div class="image-tools is-small top right show-on-hover">
				<?php do_action( 'flatsome_product_box_tools_top' ); ?>
			</div>
			<div class="image-tools is-small hide-for-small bottom left show-on-hover">
				<?php do_action( 'flatsome_product_box_tools_bottom' ); ?>
			</div>
			<div class="image-tools <?php echo flatsome_product_box_actions_class(); ?>">
				<?php do_action( 'flatsome_product_box_actions' ); ?>
			</div>
			<?php if ( $out_of_stock ) { ?><div class="out-of-stock-label"><?php _e( 'Out of stock', 'woocommerce' ); ?></div><?php } ?>
		</div><!-- box-image -->

		<div class="box-text <?php echo flatsome_product_box_text_class(); ?>">
			<?php
				do_action( 'woocommerce_before_shop_loop_item_title' );

				echo '<div class="title-wrapper">';
				do_action( 'woocommerce_shop_loop_item_title' );
				echo '</div>';


				echo '<div class="price-wrapper">';
				do_action( 'woocommerce_after_shop_loop_item_title' );
				echo '</div>';

				do_action( 'flatsome_product_box_after' );

			?>
		</div><!-- box-text -->
	</div><!-- box -->
	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div><!-- .col-inner -->
</div><!-- col -->
