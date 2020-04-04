<?php 
/**
 * @link              http://our-arts.com
 * @since             1.0.0
 * @package           calc
 *
 * @wordpress-plugin
 * Plugin Name:       img-caclulator
 * Plugin URI:        http://our-arts.com/upload_img
 * Description:       Mosaic Calculator
 * Version:           1.0.0
 * Author:            Dalia Mohamed
 * Author URI:        http://our-arts.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dalia
 * Domain Path:       /languages
 */


function colorDiff($rgb1,$rgb2)
{
    // do the math on each tuple
    // could use bitwise operates more efficiently but just do strings for now.
    $red1   = hexdec(substr($rgb1,0,2));
    $green1 = hexdec(substr($rgb1,2,2));
    $blue1  = hexdec(substr($rgb1,4,2));

    $red2   = hexdec(substr($rgb2,0,2));
    $green2 = hexdec(substr($rgb2,2,2));
    $blue2  = hexdec(substr($rgb2,4,2));

    return abs($red1 - $red2) + abs($green1 - $green2) + abs($blue1 - $blue2) ;

}



 $result='';
	function Upload_img(){
      
        $result.= '<form action="" method="post" enctype="multipart/form-data">
                        <h1 class="uploadOuter">احسب وجهز خاماتك</h1>
                        <h4 class="rightUpload">برجاء رفع التصميم المناسب مع مراعاة :</h4>
                        <p class="rightUpload">*تحميل التصميم بمقاسات التنفيذ الحقيقية </p>
                        <p class="rightUpload">*وعدم تحميل التصميم المصور بالموبايل وانما</p>
                       
                        <div class="uploadOuter row">
                            <div class="col-lg-3 col-sm-12">
                                <label for="uploadFile" class="btn">UPLOAD IMAGE</label>
                                <strong>OR</strong>
                            </div> 
                            <div class="col-lg-4 col-sm-12">
                                <span class="dragBox" >
                                    Darg and Drop image here
                                    <input type="file" name="fileToUpload" onChange="dragNdrop(event)"  ondragover="drag()" ondrop="drop()" id="uploadFile"  />
                                </span>
                            </div>
                        </div>
                        <div id="preview"></div>
                        <div class="row">
                            <div class="process col">
                                <input type="submit" onclick="processImg()" class="btn processbtn" value="تحميل التصميم" name="upload_mosaic_img">
                                <p id="wait"></p>
                            </div>
                        </div>
                        
                    </form>';
                   
//=============================backend==============================================
           
            $uploadOk = 1;
           
            // Check if image file is a actual image or fake image
        if(isset($_POST["upload_mosaic_img"])) {
            
            //check[0]=width,check[1]=height
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            
            if($check !== false) { 
                //check img type and create it
                
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
                if($check["mime"] == "image/jpg" || $check["mime"] == "image/jpeg")
                    $imgCreate = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
                else if($check["mime"] == "image/png")
                    $imgCreate = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
                else if($check["mime"] == "image/gif")
                    $imgCreate = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
                else {
                    $result.= '<div> Only JPG, JPEG, PNG & GIF files are allowed.</div>';
                    return $result;
                }
                        
               
            } else {
                // echo "File is not an image.";
                $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $result.=
                '<div> Sorry, your file was not uploaded. It is not an image.</div>';
            
            // if everything is ok, try to upload file
            } else {
                if(!function_exists('wp_handle_upload'))
                    {
                        require_once(ABSPATH .'wp-admin/includes/file.php');
                    }
                $move_logo = wp_handle_upload( $_FILES["fileToUpload"], array('test_form' => false) );
                if ( $move_logo && !isset($move_logo['error']) ) {
                    
                    $wp_upload_dir = wp_upload_dir();
                    $attachment = array(
                        'guid' => $wp_upload_dir['url'] . '/' . basename($move_logo['file']),
                        'post_mime_type' => $move_logo['type'],
                        'post_title' => preg_replace( '/\.[^.]+$/', '', basename($move_logo['file']) ),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $logo_attach_id = wp_insert_attachment($attachment, $move_logo['file']);
                    $image_attributes = wp_get_attachment_image_src( $logo_attach_id );
                    $imageFileType = strtolower(pathinfo($wp_upload_dir['url'] . '/' . basename($move_logo['file']),PATHINFO_EXTENSION));
                    if ( $image_attributes ) {
                        
                        //start calculate area and materials
                        //pixel2cm => 1 px = 0.026458333 cm
                        
                            $px2cm = 0.026458333;
                          
                            $width = $check[0]; //width in px
                            $height = $check[1]; //height in px
                            // echo ($width.' '.$height.' ');
                            $px2 = 0.5*28.346;//convert 0.5 cm to pixel to loop on img
                            
                            // $w_cm = $width * $px2cm; //width in cm
                            // $h_cm = $height * $px2cm; //height in cm
                            
                            $ratio_wh = $width/$height;
                            
                            $w_cm = $width / 28.346; //pixel2cm
                            $h_cm = $height / 28.346;
                            
                            // echo ($w_cm.' '.$h_cm.' ');
                            $area_cm = $w_cm * $h_cm;
                            
                            $sheet_area = 30*30;//cm
                            $no_sheets = ($area_cm)/($sheet_area);
                            $no_sheets = number_format($no_sheets, 1, ',', ' ');
                            
                            $no_tile = $area_cm/0.25; //area_px
                        
                            // $no_tile = $area_cm * 16; // tile = 1/4cm
                            $w_new_px = sqrt($no_tile*$ratio_wh);
                            $h_new_px = $no_tile/$w_new_px;
                            
                        //end calculate area and materials
                            
//start resize img==========================================================================================
                            $newImg = imagecreatetruecolor(round($w_new_px),round($h_new_px));
                            imagecopyresized($newImg,$imgCreate,0,0,0,0,round($w_new_px),round($h_new_px),$width,$height);
                            
                            # Create 100% version ... blow it back up to it's initial size:
                            $newImg2 = imagecreatetruecolor($width,$height);
                            imagecopyresized($newImg2,$newImg,0,0,0,0,$width,$height,round($w_new_px),round($h_new_px));
//end resize img==========================================================================================
                        
//show pixeled img==========================================================================================
                            ob_start();
                            imagepng($newImg2);
                            $png = ob_get_clean();
                            $uri = "data:image/png;base64," . base64_encode($png);
                            
                            // $result.= '
                            // <h4 class="rightUpload" style="margin-bottom:20px">هذا هو الشكل النهائي لتصميمك</h4>
                            // <div style="width: 55%;margin: auto;" >
                            //     <img src="'.$uri.'" style="width:100%;" >
                            // </div>
                            // <h4 class="rightUpload" style="margin-top:20px">انت تحتاج حوالي '.$no_sheets.' شيت من الخامات لتنفيذ تصميمك</h4>';
//end show pixeled img==========================================================================================
                
//start find products==========================================================================================
            
                            
                            $prv=[];$prv_r = [];$prv_g = [];$prv_b = [];
                            $close_color=[];
                            $nearest_col = 35;
                            
                            // $palette=[];
                           
                        
//get all products from db==========================================================================================
                            global $wpdb;
                            $queryout = $wpdb->get_results( 
                                    "SELECT product_id, sku FROM 3bb_wc_product_meta_lookup WHERE stock_status = 'instock' and sku LIKE '%#%' "
                                     );
                            
//end get all products from db==========================================================================================
                            
                          
                                    
                            $prv_products=[];
                            $prv_cols=[];
                            
                            // imagetruecolortopalette($newImg2, false, 255);
                            
                            $largestDiff = 8;
                            $closestProduct= [];
                            
                          //loop on image
                            for($x=0;$x<$w_new_px;$x++)
                            {
                                for($y=0;$y<$h_new_px;$y++)
                                {   
                                    
                                    $rgb = imagecolorat($newImg, $x, $y);
                                    $r = ($rgb >> 16) & 0xFF;
                                    $g = ($rgb >> 8) & 0xFF;
                                    $b = $rgb & 0xFF;
                                    // array_push($palette,[$r,$g,$b]);
                                    
                                    $hexC = hexColor([$r,$g,$b]);
                                    
                                    //get diff between col of img and each product
                                    
                                    foreach( $queryout as $qps):
                                        $valid= true;
                                        
                                        $pid = $qps->product_id;
                                        $sku = $qps->sku;
                                        $col = substr($sku, -7); 
                                        if (colorDiff($col,$hexC) < $largestDiff)
                                        {
                                            // $largestDiff = colorDiff($rgbColor,$rgb);
                                            foreach($closestProduct as $prd):
                                                if($pid == $prd):
                                                    $valid = false;
                                                    break;
                                                endif;
                                            endforeach;
                                            if($valid):
                                                array_push($closestProduct,$pid);
                                            endif;
                                            
                                        }
                                    
                                    endforeach;
                                
                                }
                            }
                                
//show products===========================================================================================


        $result.= '


        <div class="packages">
        <div class="row category- " style="overflow: auto;margin-top:10px;">
            <div class="col-sm-3 mt-3">
                <div >
                    <img src="'.$uri.'" style="width:100%;" >
                </div>
            </div>
            <div class="col-1 ml-5 mt-4">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-mosaic-tab" data-toggle="pill" href="#v-pills-mosaic" role="tab" aria-controls="v-pills-mosaic" aria-selected="true">موزاييك</a>
                    <a class="nav-link" id="v-pills-stones-tab" data-toggle="pill" href="#v-pills-stones" role="tab" aria-controls="v-pills-stones" aria-selected="false">أحجار</a>
                    <a class="nav-link" id="v-pills-glass-tab" data-toggle="pill" href="#v-pills-glass" role="tab" aria-controls="v-pills-glass" aria-selected="false">زجاج</a>
                </div>
            </div>
            <div class="col-7 mt-3 ">
                <div class="tab-content" id="v-pills-tabContent">

                    <table class="tab-pane fade show active shop_table shop_table_responsive cart woocommerce-cart-form__contents" id="v-pills-mosaic" role="tabpanel" aria-labelledby="v-pills-mosaic-tab" cellspacing="0">
                    <tbody class="prodcuts-checkbox">

            ';
                        
            foreach($closestProduct as $prd):
                
                $_productQ = wc_get_product( $prd );
                if($_productQ):
                    $cartId = WC()->cart->generate_cart_id( $_productQ->product_id);
                    $cartItemKey = WC()->cart->find_product_in_cart( $cartId );
                    
                    $thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $_productQ->get_image() );

                    $result.= '
                            <tr class="TB-'.$prd.'">
                                <form class="cart" action="'. esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $_productQ->get_permalink() ) ).'" method="post" enctype="multipart/form-data">
                                
                                <td class="product-thumbnail">
                                    
                                    <a href="'.get_permalink( $prd).'">'.$thumb.'</a>
                                
                                    
                                </td>
                                
                                <td class="product-name" >';
                                    
                                    $result.= wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( get_permalink($prd) ), $_productQ->get_name() ) ) );
                                    
                                $result.='</td>
                                    
                                <td class="product-price price-'.$prd.'" >'.
                                        
                                    apply_filters( "woocommerce_cart_item_price",$_productQ->get_price()." جنيه" ).'
                                        
                                </td>';
                                
                
                                $result.= 
                                '<td>
                                    <div class="quantity qty buttons_added">
                                        <input type="button" value="-" class="minus button is-form">		
                                        <label class="screen-reader-text" for="quantity_'. $prd.'">الكمية</label>
                                        <input type="number" id="quantity_'. $prd.'" class="input-text text qty quantity-box quantity-box'. $prd.'" step="1" min="1" max="9999" name="quantity" value="1" title="الكمية" size="4" inputmode="numeric">
                                        <input type="button" value="+" class="plus button is-form">	
                                        <input type="hidden" name="product_id" value="'. $prd.'">
                                        <div class="clear"></div>
                                        
                                    </div>

                                    <input type="checkbox" id="product-checkbox-'. $prd.'" name="product['. $prd.']" value="'. $prd.'" style="display:none;" />
                                    <span class="checkmark">أضف</span>
                                    
                                </td>';
                
                            //     <button  type="submit" class="single_add_to_cart_button button alt" > 
                            //     اضف
                            //  </button>
                            //  <input type="hidden" name="add-to-cart" value="'. $prd.'">
                            //  <input type="hidden" name="product_id" value="'. $prd.'">

                                $result.= 
                                '
                            </form>
                        </tr>
                            ';
                                endif;
                            endforeach;
                    $result.='
                        </tbody>
                        </table>
                    

                        



                        <table class="tab-pane fade show activ shop_table shop_table_responsive cart woocommerce-cart-form__contents" id="v-pills-stones" role="tabpanel" aria-labelledby="v-pills-stones-tab" cellspacing="0">
                        <tbody class="prodcuts-checkbox">
                ';
                foreach($closestProduct as $prd):
                    
                    $_productQ = wc_get_product( $prd );
                    if($_productQ):
                        $cartId = WC()->cart->generate_cart_id( $_productQ->product_id);
                        $cartItemKey = WC()->cart->find_product_in_cart( $cartId );
                        
                        $thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $_productQ->get_image() );
    
                        $result.= '
                                <tr class="TB-'.$prd.'">
                                    <form class="cart" action="'. esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $_productQ->get_permalink() ) ).'" method="post" enctype="multipart/form-data">
                                    
                                    <td class="product-thumbnail">
                                        
                                        <a href="'.get_permalink( $prd).'">'.$thumb.'</a>
                                    
                                        
                                    </td>
                                    
                                    <td class="product-name" >';
                                        
                                        $result.= wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( get_permalink($prd) ), $_productQ->get_name() ) ) );
                                        
                                    $result.='</td>
                                        
                                    <td class="product-price price-'.$prd.'" >'.
                                            
                                        apply_filters( "woocommerce_cart_item_price",$_productQ->get_price()." جنيه" ).'
                                            
                                    </td>';
                                    
                    
                                    $result.= 
                                    '<td>
                                        <div class="quantity qty buttons_added">
                                            <input type="button" value="-" class="minus button is-form">		
                                            <label class="screen-reader-text" for="quantity_'. $prd.'">الكمية</label>
                                            <input type="number" id="quantity_'. $prd.'" class="input-text text qty quantity-box quantity-box'. $prd.'" step="1" min="1" max="9999" name="quantity" value="1" title="الكمية" size="4" inputmode="numeric">
                                            <input type="button" value="+" class="plus button is-form">	
                                            <input type="hidden" name="product_id" value="'. $prd.'">
                                            <div class="clear"></div>
                                            
                                        </div>
    
                                        <input type="checkbox" id="product-checkbox-'. $prd.'" name="product['. $prd.']" value="'. $prd.'" style="display:none;" />
                                        <span class="checkmark">أضف</span>
                                        
                                    </td>';
                    
                                    
                                //     <button  type="submit" class="single_add_to_cart_button button alt" > 
                                //     اضف
                                //  </button>
                                //  <input type="hidden" name="add-to-cart" value="'. $prd.'">
                                //  <input type="hidden" name="product_id" value="'. $prd.'">
    
                                    $result.= 
                                    '
                                    
                                </form>
                            </tr>
                        
                                ';
                                    
                                    endif;
                                endforeach;
                            
                        $result.='
                            </tbody>
                            </table>
                    </div>




                </div>
                <div class="text-center purchase-block fixed" >
                    <button class="btn btn-md btn-success cart-btn" data-loading-text="جاري تأكيد الشراء">أكمل الشراء <span class="sum-price">0.00 جنيه</span></button>
                    <br>
                    <strong><span style="font-size:10px" class="" color="#000">تأكيد كوبونات الخصم و سعر التوصيل تكون فى الخطوة القادمة</span></strong>
                    <a class="contact-link" href="https://our-arts.com/contact" target="_blank"><strong><span style="font-size:10px" class="m-0" color="#FF0000">اذا واجهتك اى مشكلة اتصل بنا </span></strong></a>
                </div>
            
            </div>   
            <div>';
                           
                    }
                    else 
                         $result.= '<div> Sorry, there was an error uploading your file.</div>';
                }
                    
            }
        
        
        }
        $result.= '
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="http://localhost/ourarts/wp-content/plugins/mosaic_img/js/jquery.min.js"></script>
        <script src="http://localhost/ourarts/wp-content/plugins/mosaic_img/js/bootstrap.min.js"></script>
        <script src="http://localhost/ourarts/wp-content/plugins/mosaic_img/js/calc.js"></script>


        <script>
       

        



            var change_check = false;
            var cart = {};
            $(\'.checkmark\').on(\'click\',function(){
                
                if( change_check == true){
                    change_check = false;
                    $(this).closest(\'td\').find(\'input[name^="product["]\').prop(\'checked\', true).trigger(\'change\');
                }
                else {
                    $(this).closest(\'td\').find(\'input[name^="product["]\').prop(\'checked\', false).trigger(\'change\');
                    change_check = true;
                }
            });

            
            $(\'.quantity-box\').on(\'change\', function(){
                var quantity = $(this).val();
                var max_quantity = parseInt($(this).attr(\'data-max\'));
                if(quantity > max_quantity){
                    alert(\'أقصى كمية لهذا المنتج هي: \' + max_quantity);
                    $(this).val(max_quantity)
                }

                if(change_check && quantity > 0){
                    $(this).closest(\'td\').find(\'input[name^="product["]\').prop(\'checked\', true).trigger(\'change\');
                }
                else{
                    $(this).closest(\'td\').find(\'input[name^="product["]\').prop(\'checked\', false).trigger(\'change\');
                }
            });

            $(\'input[name^="product["]\').on(\'change\', function(){
                if($(this).prop(\'checked\')){
                    $(this).closest(\'tr\').addClass(\'checked-block\').find(\'.checkmark\').html(\'إلغاء\');
            
                    var product_id = $(this).val();
                    var price = $(\'.price-\'+product_id).html().replace(/[^0-9.]/g,\'\');
                    var quantity = $(\'.quantity-box\'+product_id).val();
                    if(price > 0 && quantity > 0){
                        
                        cart[product_id] = quantity;
                        console.log(cart);
                    }
                }
                else{
                    $(this).closest(\'tr\').removeClass(\'checked-block\').find(\'.checkmark\').html(\'أضف\');
                    
                }
            });


            $(\'input[name^="product["], input.quantity-box\').on(\'change\', function(){
                var sum = 0;
                $(\'input[name^="product["]:checked\').each(function(){
                  var product_id = $(this).val();
                  var price = $(\'.price-\'+product_id).html().replace(/[^0-9.]/g,\'\');
                  var quantity = $(\'.quantity-box\'+product_id).val();
                  sum += (price * quantity);
                });
                $(\'span.sum-price\').html(sum.toFixed(2) + \' جنيه\');
            });
              
            $(\'input[name^="product["]\').trigger(\'change\');
            $(\'.quantity-box\').eq(0).trigger(\'change\');
            change_check = true;
            
            
        </script>
        ';
       
        return $result;
    }
    

    function enqueue_script(){
        wp_enqueue_script( 'script', plugins_url( '/js/cart.js' , __FILE__ ), '1.0b', array('car'), true );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_script' );

    function ajax_add_ToCart() {
        $products = $_POST["products"];
        // print_r($products);
        $quantities = $_POST["quantities"];
        
        // {pid : qty}
        $success=0;
        foreach ($products as $pid => $p){
            try{
            $cartId2 = WC()->cart->generate_cart_id( $pid);
            // $add = WC()->cart->add_to_cart( $pid, absint($p));
            // $add = WC()->cart->get_cart();
            $cart_item_key = $cartId2;
            $product_data = wc_get_product($pid);
            $quantity = apply_filters( 'woocommerce_add_to_cart_quantity', $p, $pid );;
            	// Stock check - only check if we're managing stock and backorders are not allowed.
			// if ( ! $product_data->is_in_stock() ) {
            //     /* translators: %s: product name */
            //     echo sprintf( __('لا يمكنك إضافة %s للسلة لأنه غير متوافر بالمخزون.', 'woocommerce' ), $product_data->get_name() );
            //     // return;
            //     // throw new Exception( sprintf( __( 'لا يمكنك إضافة  &quot;%s&quot; للسلة لأنه غير متوافر بالمخزون.', 'woocommerce' ), $product_data->get_name() ) );
			// }
			if ( ! $product_data->has_enough_stock( $quantity ) ) {
				/* translators: 1: product name 2: quantity in stock */
				echo sprintf( __( 'لا يمكنك إضافة هذه الكمية من %s للسلة لأنه غير متوافر بالمخزون (%2$s المتبقي).', 'woocommerce' ), $product_data->get_name(), wc_format_stock_quantity_for_display( $product_data->get_stock_quantity(), $product_data ) ) ;
                return;
            }
			// Stock check - this time accounting for whats already in-cart.
			// if ( $product_data->managing_stock() ) {
			// 	$products_qty_in_cart = WC()->cart->get_cart_item_quantities();

			// 	if ( isset( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ] ) && ! $product_data->has_enough_stock( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ] + $quantity ) ) {
			// 		throw new Exception(
			// 			sprintf(
			// 				'<a href="%s" class="button wc-forward">%s</a> %s',
			// 				wc_get_cart_url(),
			// 				__( 'View cart', 'woocommerce' ),
			// 				/* translators: 1: quantity in stock 2: current quantity */
			// 				sprintf( __( 'You cannot add that amount to the cart &mdash; we have %1$s in stock and you already have %2$s in your cart.', 'woocommerce' ), wc_format_stock_quantity_for_display( $product_data->get_stock_quantity(), $product_data ), wc_format_stock_quantity_for_display( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ], $product_data ) )
			// 			)
			// 		);
			// 	}
			// }

            // Add item after merging with $cart_item_data - hook to allow plugins to modify cart item.
            WC()->cart->cart_contents[ $cart_item_key ] = apply_filters(
                'woocommerce_add_cart_item',
                array_merge(
                    array(),
                    array(
                        'key'          => $cart_item_key,
                        'product_id'   => $pid,
                        'variation_id' => 0,
                        'variation'    => array(),
                        'quantity'     => absint($quantity),
                        'data'         => $product_data,
                        'data_hash'    => wc_get_cart_item_data_hash( $product_data ),
                    )
                ),
                $cart_item_key
            );
            
            WC()->cart->cart_contents = apply_filters( 'woocommerce_cart_contents_changed', WC()->cart->cart_contents );

			do_action( 'woocommerce_add_to_cart', $cart_item_key, $pid, absint($p), 0, array(), array() );

            // print_r( $add);
            $success=1;
            echo $success;
            }
            catch ( Exception $e ) {
                if ( $e->getMessage() ) {
                    wc_add_notice( $e->getMessage(), 'error' );
                }
                $success['error']=$e->getMessage() ;
            }
        }
        
        
        die();
    }
    add_action( 'wp_ajax_add_ToCart', 'ajax_add_ToCart' );
    add_action( 'wp_ajax_nopriv_add_ToCart', 'ajax_add_ToCart' );

  
  function hexColor($color) {
      
    $R = dechex($color[0]);
    if (strlen($R)<2)
        $R = '0'.$R;

    $G = dechex($color[1]);
    if (strlen($G)<2)
        $G = '0'.$G;

    $B = dechex($color[2]);
    if (strlen($B)<2)
        $B = '0'.$B;

    return '#' . $R . $G . $B;
    
//   return '#'.dechex(($color[0]<<16)|($color[1]<<8)|$color[2]);
}
  
   
    add_shortcode('mosaic_view','Upload_img');
?>