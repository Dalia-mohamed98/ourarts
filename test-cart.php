<?php


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
								//echo"everything okay";
								$found=true;
								break;
							}
							else{
								//echo"trying another vendor";
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
				// 			<?php
			             
							 $rmv_pid = [];
							 $added_pid= [];
			                foreach($results as $pro_ven){

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
			                        $cartId = WC()->cart->generate_cart_id( $pro_obj->product_id);
									$cartItemKey = WC()->cart->find_product_in_cart( $cartId );
									
			                        if( $ven_id == $pro_obj->seller_id && empty($cartItemKey)){
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
                            								//echo apply_filters( 'woocommerce_cart_item_price',$new_product->get_price().'EGP' ); // PHPCS: XSS ok.
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
