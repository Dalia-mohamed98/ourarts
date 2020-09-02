<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

session_start();

defined( 'ABSPATH' ) || exit;

$row_classes     = array();
$main_classes    = array();
$sidebar_classes = array();

$auto_refresh  = get_theme_mod( 'cart_auto_refresh' );
$row_classes[] = 'row-large';
$row_classes[] = 'row-divided';

if ( $auto_refresh ) {
	$main_classes[] = 'cart-auto-refresh';
}


$row_classes     = implode( ' ', $row_classes );
$main_classes    = implode( ' ', $main_classes );
$sidebar_classes = implode( ' ', $sidebar_classes );


do_action( 'woocommerce_before_cart' ); ?>
<div class="woocommerce row <?php echo $row_classes; ?>">
<div class="col large-7 pb-0 <?php echo $main_classes; ?>">

<?php wc_print_notices(); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
<div class="cart-wrapper sm-touch-scroll">

	<?php do_action( 'woocommerce_before_cart_table' ); ?>
	
	<?php
		$vendors = array();
		$vendor_id = array();
		$products_array = array();
		$i =0;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$products_array[$i] = $_product;
				$vendor_id[$i] = get_post_field( 'post_author', $product_id );
				$vendors[$i] = get_userdata( $vendor_id[$i] )->user_login;
				$i++;
			}
		}
		
				//if multivendor, check for one has all products
 			//print_r(array_unique($vendor_id));
			if (count(array_unique($vendors)) !== 1 && count($vendors)>1) {
// 				print_r($products_array);
				$j=0;$k=4;
				global $wpdb;
				$vend_array = array();
				$results = array();
				
					foreach ( $products_array as $pro ) {
						$has_multivendor = get_post_meta( $pro->get_id(), '_has_multi_vendor', true );
						if ( !empty( $has_multivendor ) ) {
							$sql     = "SELECT * FROM {$wpdb->prefix}dokan_product_map WHERE map_id= $has_multivendor AND is_trash = 0";
							$results[$j] = $wpdb->get_results( $sql );
                            
							if ( $results[$j] ) {
							    $all_products = $results[$j] ;
								foreach ($all_products as $key => $one_pro){
									$vend_array[$j++]= $one_pro->seller_id;
									
								}
							}
						}
						else {
						    $vend_array[$j++]= get_post_field( 'post_author', $pro->get_id() );
						}
                     
						}
						
					}
						
						//count no. of times each vendor exists
						$count_v= array_count_values($vend_array);
				// 		print_r($count_v);
						
						$ven_id = "";
						$found = false;
						$max = max($count_v);
						// print(sizeof($count_v));
						$count_i=0;
						$not_all_pro = array();
					while(!$found && $count_i<sizeof($count_v)){
						if($max > 0) //=== count(WC()->cart->get_cart()))
							{
                            foreach($count_v as $key => $c){
								//vendor who has most of products in cart have not chose by user yet.
                                if($max === $c && !in_array($key,$not_all_pro) && in_array($key,$vendor_id) && array_count_values($vendor_id)[$key]!=$c)
									{	$ven_id = $key; break; }
                            }
							}

						if($ven_id !== ""){
							$countP=0;
							foreach($results as $pro_ven){
								foreach($pro_ven as $key => $pro_obj){
									$cartId = WC()->cart->generate_cart_id( $pro_obj->product_id);
									$cartItemKey = WC()->cart->find_product_in_cart( $cartId );
									$new_product = wc_get_product( $pro_obj->product_id );
									if( $ven_id == $pro_obj->seller_id && $new_product->is_in_stock()){
										$countP += 1;		
										// echo $ven_id." instock";					
									}
								}
							}
							if($countP == $max){
								echo $countP;
								echo"everything okay";
								$found=true;
								break;
							}
							else{
								echo"trying another vendor";
								array_push($not_all_pro,$ven_id);
								// break;
							}
						}
						$count_i++;
					}

					if(!$found){
						foreach($count_v as $key => $c){
							//vendor who has most of products in cart have not chose by user yet.
							if($max === $c  && in_array($key,$vendor_id) && array_count_values($vendor_id)[$key]!=$c)
								{	$ven_id = $key; break; }
						}
					}
					

				// 		print($ven_id);
				if($ven_id !== "" && $found){
					$once =0;
					 foreach($results as $pro_ven){
					 //   print_r($pro_ven);
						foreach($pro_ven as $key => $pro_obj){
							$cartId = WC()->cart->generate_cart_id( $pro_obj->product_id);
							$cartItemKey = WC()->cart->find_product_in_cart( $cartId );
							$new_product = wc_get_product( $pro_obj->product_id );
							if( $ven_id == $pro_obj->seller_id && empty($cartItemKey) && $once === 0 && $new_product->is_in_stock()){
								$once = 1;
								?>
								<div style="color:red;    font-weight: bold;"> انت اخترت المنتجات من اكثر من مخزن  </div>
								<div style="color:red;    font-weight: bold;">لتفادي اكثر من شحنة : اضغط   <span style="color:black;">( اضف الى السلة )</span> لكل من المنتجات التالية
								</div>
								 <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
									<tbody>
										   
							   <?php
									break;
							}
						}
					 }

					 $rmv_pid = [];
					 $added_pid= [];
					foreach($results as $pro_ven){
						//not enter loop at first time
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item  ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							
							foreach(array_combine($_SESSION["rmv_pid"] , $_SESSION["added_pid"]) as $dpid => $apid){
								// echo($dpid.' '. $apid.' ');
								// echo($product_id.' ' .$apid.' ');
								if($product_id == $apid){
									// echo"dalia";
									$cartId = WC()->cart->generate_cart_id( $dpid );
									$cartk = WC()->cart->find_product_in_cart( $cartId );
									WC()->cart->remove_cart_item( $cartk );
									break;
								}
							}
						}

						//   print_r($pro_ven);
						foreach($pro_ven as $key => $pro_obj){
							// echo  $pro_obj->product_id.' ';
							$cartId = WC()->cart->generate_cart_id( $pro_obj->product_id);
							$cartItemKey = WC()->cart->find_product_in_cart( $cartId );
							
							if( $ven_id == $pro_obj->seller_id && empty($cartItemKey)){
								echo  $pro_obj->product_id.' ';
								echo $ven_id;
								$_SESSION["cartItemKey"]= $cartItemKey;
								$new_product = wc_get_product( $pro_obj->product_id );
								$new_vend = get_userdata( $pro_obj->seller_id )->user_login;

								if($new_product->is_in_stock()){

									?>
									<tr>
										<td class="product-thumbnail">
											<?php
											$thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $new_product->get_image() );
											printf( '<a href="%s">%s</a>', esc_url( get_permalink( $pro_obj->product_id)  ), $thumb); 
											?>
										</td>
										<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
											<?php
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $pro_obj->product_id) ), $new_product->get_name() ) ) );
											// printf( '<p>VENDOR : %s</p>',$new_vend); 

											// Meta data.
											//  echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
											?>
										</td>
										<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
											<?php
												// echo apply_filters( 'woocommerce_cart_item_price',$new_product->get_price().'EGP' ); // PHPCS: XSS ok.
												echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $new_product ), $cartId, $cartItemKey ); // PHPCS: XSS ok.

											?>
										</td>
										
										<?php
														
										foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item  ) {
											$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
											$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
											$map_id = get_post_meta( $product_id, '_has_multi_vendor', true );
											
											// print($map_id." ".$pro_obj->map_id." ");

											if($map_id === $pro_obj->map_id ){
												array_push($rmv_pid,$product_id);
												array_push($added_pid,$pro_obj->product_id);
												$_SESSION["rmv_pid"] = $rmv_pid;
												$_SESSION["added_pid"] = $added_pid;
												?>
												<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
												<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
												} else {
													$cartItemKey['quantity']= $cart_item['quantity'];
													$product_quantity = woocommerce_quantity_input(
														array(
															'input_name'   => "cart[{$cart_item_key}][qty]",
															'input_value'  => $cart_item['quantity'],
															'max_value'    => $_product->get_max_purchase_quantity(),
															'min_value'    => '0',
															'product_name' => $_product->get_name(),
														),
														$_product,
														false
													);
												}

												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
												?>
												</td>
												<input type="hidden" value="<?php echo $product_id;?>" name="rmv_cart_item"/>
												
											<td>
											
											<button  type="submit"  id="single_add_to_cart_button" name="add-to-cart" value="<?php echo $pro_obj->product_id;?>" class="single_add_to_cart_button button alt" > 
											اضف الى السلة
										
											</button>
											
											<?php
											break;
											
											}
										}
										


										?>
				
											</td>
									</tr>
									<?php
								}

							}
						}


						
					}

					 ?>
		                   
					 </tbody>
				 </table>

				 <script src="http://localhost/ourarts/wp-content/plugins/mosaic_img/js/jquery.min.js"></script>
			 
			 <?php

				}	
		?>
			

	
	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-name" colspan="3"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			

			<?php
			

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				// 	$products_array[$i] = $_product;
				// 	$vendor_id = get_post_field( 'post_author', $product_id );
				// 	$vendors[$i++] = get_userdata( $vendor_id )->user_login;
			
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">x</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
						</td>

						<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . 'x' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						// echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}

						// Mobile price.
						?>
							<div class="show-for-small mobile-product-price">
								<span class="mobile-product-price__qty"><?php echo $cart_item['quantity']; ?> x </span>
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>
						</td>
						
						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
									'product_name' => $_product->get_name(),
								),
								$_product,
								false
							);
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}
	?>
			
						
			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions clear">

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<button type="submit" class="button primary mt-0 pull-left small" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php fl_woocommerce_version_check( '3.4.0' ) ? wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ) : wp_nonce_field( 'woocommerce-cart' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</div>
</form>
</div>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals large-5 col pb-0">
	<?php if ( get_theme_mod( 'cart_sticky_sidebar' ) ) { ?>
	<div class="is-sticky-column">
		<div class="is-sticky-column__inner">
	<?php } ?>

	<div class="cart-sidebar col-inner <?php echo $sidebar_classes; ?>">
		<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
		?>
		<?php if ( wc_coupons_enabled() ) { ?>
		<form class="checkout_coupon mb-0" method="post">
			<div class="coupon">
				<h3 class="widget-title"><?php echo get_flatsome_icon( 'icon-tag' ); ?> <?php esc_html_e( 'Coupon', 'woocommerce' ); ?></h3><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="is-form expand" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		</form>
		<?php } ?>
		<?php do_action( 'flatsome_cart_sidebar' ); ?>
	</div>
<?php if ( get_theme_mod( 'cart_sticky_sidebar' ) ) { ?>
	</div>
	</div>
<?php } ?>
</div>
</div>

<?php do_action( 'woocommerce_after_cart' );