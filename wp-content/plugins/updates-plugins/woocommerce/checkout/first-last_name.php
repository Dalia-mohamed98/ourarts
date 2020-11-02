<?php

//merge first and last name in checkout
add_filter( 'woocommerce_billing_fields' , 'ced_remove_billing_fields' );
function ced_remove_billing_fields( $fields ) {
         unset($fields['billing_last_name']);
         unset($fields['shipping_last_name']);
         return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'ced_rename_checkout_fields' );
// Change placeholder and label text
function ced_rename_checkout_fields( $fields ) {
$fields['billing']['billing_first_name']['placeholder'] = 'الإسم';
$fields['billing']['billing_first_name']['label'] = 'Name';
$fields['shipping']['shipping_first_name']['placeholder'] = 'الإسم';
$fields['shipping']['shipping_first_name']['label'] = 'Name';
return $fields;
}


?>


