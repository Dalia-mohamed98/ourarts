

<?php


// get the parameter from URL
$products = $_POST["products"];
print_r($products);
// $products = $_POST["products"];
$quantities = $_POST["quantities"];

// {pid : qty}
$success=0;
foreach ($products as $pid => $p){
    print($pid);
    WC()->cart->add_to_cart( $pid );
    $success=1;
}

echo $success;


?>