<?php
include 'arabicdate.php';

add_filter('woocommerce_thankyou_order_received_text', 'ash2osh_faw_woo_change_order_received_text', 10, 2);

/**
 * change the thank you text 
 *
 * @param  string $str 
 * @param  WC_Order $order
 */
function ash2osh_faw_woo_change_order_received_text($str, $order) {
    //  $new_str = sprintf( esc_html__( 'Please Pay for the order using the below Button', 'ash2osh_faw' ), $count );
    if ($order->get_payment_method() == ASH2OSH_FAW_PAYMENT_METHOD && $order->get_status() == 'on-hold') {
        //if taken fawry number 
        if ($order->get_meta('_rec_faw_pay', TRUE) == 1) {
			header( "refresh:5; url=http://our-arts.com" );
            return $str;
        }

//continue 
    } else {//other payment methods or already paid
        
        $date = date('Y-m-d'); // The Current Date
        $check_off_days= date('D', strtotime($date));
        $two_days = '';
        $four_days = '';

        if($check_off_days == "Thu"){
            $two_days = date('Y-m-d', strtotime($date. ' + 5 days'));
            $four_days = date('Y-m-d', strtotime($date. ' + 6 days'));

        }
        else if($check_off_days == "Fri"){
            $two_days = date('Y-m-d', strtotime($date. ' + 4 days'));
            $four_days = date('Y-m-d', strtotime($date. ' + 5 days'));
            
        }
        else{
            $two_days = date('Y-m-d', strtotime($date. ' + 3 days'));
            $four_days = date('Y-m-d', strtotime($date. ' + 4 days'));

        }
        // echo ArabicDate($two_days).'          '.ArabicDate($four_days);

        // $curl = curl_init();
        // $message = urlencode('شكرا لقد تم استلام طلبك رقم '. $order->get_id() . '، وسيصلك خلال '.ArabicDate($two_days).' الى '.ArabicDate($four_days).'.');
        // $url = "https://smsmisr.com/api/webapi/?username=Kucqb6oA&password=0CeG8jZ0R2&language=2&sender=Our%20Arts&mobile=".$order->get_billing_phone()."&message=".$message;
        
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_HTTPHEADER => array('Content-Length: 0'),
        // ));
        // $response = curl_exec($curl);

        // curl_close($curl);

       //send completed msg after five days
// 		$curl = curl_init();
//         $completed = urlencode("شكرا لطلبكم من أور آرتس،
// ساعدنا بإبداء رأيك في تقييم خدماتنا من خلال اللينك : https://our-arts.com/survey
// سيتم إرسال بروموكود خصم بعد إبداء رأيك
// فريق أور آرتس");
//         $time = date('Y-m-d', strtotime($four_days. ' + 1 days')).'-19-00';
// // 		$time= "2020-06-15-17-53";
//         // echo $time;
//         $url = "https://smsmisr.com/api/webapi/?username=Kucqb6oA&password=0CeG8jZ0R2&language=2&sender=Our%20Arts&mobile=".$order->get_billing_phone()."&message=".$completed."&DelayUntil=".$time;
        
//         curl_setopt_array($curl, array(
//             CURLOPT_URL => $url,
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => "",
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => "POST",
//             CURLOPT_HTTPHEADER => array('Content-Length: 0'),
//         ));
//         $response = curl_exec($curl);

//         curl_close($curl);

        // echo $response;

        $str.= " وجاري تحضيره ليصلك خلال ".ArabicDate($two_days). " الى ".ArabicDate($four_days).".";
		// header( "refresh:5; url=http://our-arts.com" );
        return $str;
    }
    $new_str = __('<h2>Please Pay for the order using the below Button</h2>', 'ash2osh_faw');
    //  $new_str .= '<br>' . getProductsJson($order->get_items());
//get the options //returns array 
    $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');
    $expire_hours = $options['unpaid_expire'];
    if (!trim($expire_hours)) {
        $expire_hours = '48';
    }
    $new_str .= '<script> '
            . 'var merchant= \'Zgy+s91LCyG9rcmQouPl/w==\';'
            . 'var merchantRefNum= "' . $order->get_id() . '";'
            . 'var productsJSON=JSON.stringify(' . getProductsJson($order,$options) . ');'
            . 'var customerName= "' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . '";'
            . 'var  mobile = "' . $order->get_billing_phone() . '";'
            . 'var  email = "' . $order->get_billing_email() . '";'
            . 'var  customerId = "' . $order->get_customer_id() . '";'
            . 'var  orderExpiry = "' . $expire_hours . '";'
            . 'var  locale = "' . ( (strpos(get_locale(), 'ar') !== false) ? 'ar-eg' : 'en-gb') . '";'
            . '</script>';

    if (wp_get_theme() == 'Avada') {
        $new_str .= do_shortcode('[fusion_button link="#" text_transform="" title="" target="_self" link_attributes="" alignment="center" modal="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="faw_checkout" color="custom" button_gradient_top_color="#ffd205" button_gradient_bottom_color="#eac804" button_gradient_top_color_hover="#ffd205" button_gradient_bottom_color_hover="#ffd205" accent_color="" accent_hover_color="" type="3d" bevel_color="#049bce" border_width="" size="large" stretch="default" shape="pill" icon="" icon_position="left" icon_divider="no" animation_type="shake" animation_direction="left" animation_speed="0.3" animation_offset=""]'
                . '  <img  src="' . ASH2OSH_FAW_URL . 'images/logo_small.png">'
                . '[/fusion_button]');
    } else {
        $new_str .= '<br>' . '<button id="faw_checkout" style="background-color: #ffd205;border: 1px solid #e7bf08;">
          <img  src="' . ASH2OSH_FAW_URL . 'images/logo_small.png"></button>';
    }

   

    return $new_str;
    //TODO send mail with payment url (just in case ??)
}

/**
 * return the products as JSON array
 * 
 * @param WC_Order $order
 */
function getProductsJson($order,$options) {
    $stupid_mode = $options['stupid_mode'];
    if($stupid_mode=='yes'){
         $arr[] = [
            'productSKU' => $order->get_id(),
            'description' => $order->get_id(),
            'quantity' => 1,
            'price' => $order->get_total()
        ];
    }else{
    $items=$order->get_items();
    $arr = [];
    foreach ($items as $item) {
        $data = $item->get_data();
    
        $arr[] = [
            'productSKU' => $data['product_id'],
            'description' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => $data['total'] / $data['quantity'],
//"width":"1",
//      "height":"2",
//      "length":"3",
//      "weight":"600"
        ];
    }
        }
    return json_encode($arr);
}

//add fawry js
function ash2osh_faw_scripts() {
    $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');
    $isStaging = $options['is_staging'] == 'no' ? FALSE : TRUE;
    $php_vars = array(
        'siteurl' => get_option('siteurl'),
        'ajaxurl' => admin_url('admin-ajax.php'),
    );

    if (is_page("إنهاء الطلب")) {
        if ($isStaging) {
            wp_enqueue_script('fawry_js', 'https://atfawry.fawrystaging.com/ECommercePlugin/scripts/fawryPlugin.js');
        } else {
            wp_enqueue_script('fawry_js', 'https://www.atfawry.com/ECommercePlugin/scripts/fawryPlugin.js');
        }


        wp_enqueue_script('faw_checkout', plugin_dir_url(__DIR__) . 'scripts/faw_checkout.js', array('jquery', 'fawry_js'));
        wp_localize_script('faw_checkout', 'FAW_PHPVAR', $php_vars); //FAW_PHPVAR name must be unqiue
    }
}

add_action('wp_enqueue_scripts', 'ash2osh_faw_scripts');
