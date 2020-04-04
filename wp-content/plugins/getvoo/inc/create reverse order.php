<?php


// add_filter('woocommerce_thankyou_order_received_text', 'wdm_send_order_to_ext', 10, 2);

// function wdm_send_order_to_ext( $order_id ){
    
// 	    $order = new WC_Order( $order_id ); 
	    
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "http://www.getvoo.com/api/orders/reverse",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => false,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_POSTFIELDS =>"{\n\t\"shipment_code\": \"test1234\",\n\t\"name\": \"ahmed\",\n\t\"phone\": \"01234567890\",\n\t\"area_id\": 41,\n\t\"address\": \"sender address test\",\n\t\"item_description\": \"clothes\",\n\t\"item_cost\": \"200\",\n\t\"zero_cash_collection\": false,\n\t\"notes\": \"note test\"\n}",
//   CURLOPT_HTTPHEADER => array(
//     "Authorization: Bearer (eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjM1NDAsInN1YiI6MzU0MCwiaXNzIjoiaHR0cDovL2dldHZvby5jb20vbWVyY2hhbnRzL2RldmVsb3BlcnMvbmV3IiwiaWF0IjoxNTU1NDYzNzI4LCJleHAiOjE2ODg0MTQ3ODksIm5iZiI6MTU2MjI3MDc4OSwianRpIjoiOUZwTUNPdWliN3VGdG1BZSJ9.ZqzIk0wZ2FjJbRh63gbNkXOiZJI1UHWTWjkn90G4vHU)",
//     "Content-Type: application/json"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }
// }