<?php




function wdm_send_order_to_ext($order,$items){
//    echo $order->get_shipping_address_1();
	
$curl = curl_init();
$product_disc="";
foreach($items as $item){
	$product_disc .= $item->{'description'} . ', ';
	
}	
	
$notes='';
if($order->get_customer_note()!=NULL)
	$notes='&notes='.$order->get_customer_note();

	
	
// 	echo $product;
	
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://www.getvoo.com/api/orders/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => false,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",

	
// CURLOPT_POSTFIELDS => "%7B=&shipment%20code=%20&name=%22$order->get_billing_first_name()%22%2C&phone=$order->get_billing_phone()&area%20id=11&address=%22$order->get_billing_address()%22%2C&item%20description=%22clothes%22%2C&item%20cost=200&zero%20cash=false%2C&notes=%22note%20test%22&%7D=&landmark=0",
	CURLOPT_POSTFIELDS => "area%20id=18&name=".$order->get_shipping_first_name().' '.$order->get_shipping_last_name()."&phone=".$order->get_billing_phone()."&address=".$order->get_shipping_address_1()."&item%20description=".$product_disc."&landmark=0&zero%20cash%20collection=0&cash%20collection=".$order->get_total()."&item%20cost=".$order->get_total().$notes ,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjM1NDAsInN1YiI6MzU0MCwiaXNzIjoiaHR0cDovL2dldHZvby5jb20vbWVyY2hhbnRzL2RldmVsb3BlcnMvbmV3IiwiaWF0IjoxNTU1NDYzNzI4LCJleHAiOjE2ODg0MTQ3ODksIm5iZiI6MTU2MjI3MDc4OSwianRpIjoiOUZwTUNPdWliN3VGdG1BZSJ9.ZqzIk0wZ2FjJbRh63gbNkXOiZJI1UHWTWjkn90G4vHU",
    "Content-Type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} //else {
//   echo $response;
//}	
}

add_action('woocommerce-thankyou-order-processing', 'wdm_send_order_to_ext', 10, 2);//voo
?>
