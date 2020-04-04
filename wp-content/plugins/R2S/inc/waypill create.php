<?php

function wdm_send_order_to_external($order,$items){
//    echo $order->get_shipping_address_1();


Service Unique Name: CreateWaybill
Request Method: POST
Request URL : https://webservice.logixerp.com/webservice/v2/CreateWaybill?secureKey= A3604E505DB24D118B9A2D48BDC336B3

Content-Type : application/json
AccessKey : logixerp

{
"waybillRequestData": {
"SecureKey":"9BA05777B57441AA9DCFCA33781332B8",
"FromOU": "JEDDAH",
"WaybillNumber": "",
"DeliveryDate": "",
"CustomerCode": "00000",
"ConsigneeCode": "",
"ConsigneeAddress": ".$order->get_billing_address()",
"ConsigneeCountry": "EG",
"ConsigneeState": "EG",
"ConsigneeCity": "customer_city",
"ConsigneePincode": "postal_code",
"ConsigneeName": ".$order->get_billing_first_name()",
"ConsigneePhone": ".$order->get_billing_phone()",
"ClientCode": "customer_id",
"NumberOfPackages": 1,
"CreateWaybillWithoutStock": "true",
"ActualWeight": 0.0,
"ChargedWeight": 112.8,
"CargoValue": 68.537,
"ReferenceNumber": "invoice_number",
"InvoiceNumber": "invoice_number",
"PaymentMode": "TBB",
"ServiceCode": "LASTMILEDELIVERY",
"reverseLogisticActivity": "",
"reverseLogisticRefundAmount": "",
"WeightUnitType": "KILOGRAM",
"Description": "VZXC",
"COD": ".$order->get_total()",
"CODPaymentMode": "CASH",
"DutyPaidBy": "Receiver",
"packageDetails": {
"packageJsonString":[
{
"barCode": "",
"packageCount": 1,
"length": 20.0,
"width": 50.0,
"height": 60.0,
"weight": 10.0,
"chargedWeight": 12.0,
"selectedPackageTypeCode": "NON DOCUMENT"
},
{
"barCode": "",
"packageCount": 2,
"length": 20.0,
"width": 50.0,
"height": 60.0,
"weight": 10.0,
"chargedWeight": 12.0,
"selectedPackageTypeCode": "NON DOCUMENT"
}
]
}
}
}



When customer code is of 00000:
Example 2:
{
"waybillRequestData":{
"SecureKey":"9BA05777B57441AA9DCFCA33781332B8",
"consigneeGeoLocation":"25.89,78.5",
"FromOU":"Delhi",
"DeliveryDate":"2019-06-03",
"WaybillNumber":"",
"CustomerCode":"00000",
"CustomerName":"CDCD TEST",
"CustomerAddress":"XC CSS",
"CustomerCity":"Gurgaon",
"CustomerCountry":"IN",
"CustomerPhone":"",
"CustomerState":"HR",
"CustomerPincode":""
"ConsigneeCode":"00000",
"ConsigneeName":"xyzsc",
"ConsigneePhone":"7888",
"ConsigneeAddress":"test address",
"ConsigneeCountry":"IN",
"ConsigneeState":"HR",
"ConsigneeCity":"Faridabad",
"ConsigneePincode":"440012",
"ConsigneeWhat3Words":"word.exact.replace",
"StartLocation":"Agra",
"EndLocation":"Manesar",
"ClientCode":"HERO",
"NumberOfPackages":"1",
"ActualWeight":"78",
"ChargedWeight":"78",
"CargoValue":"",
"ReferenceNumber":"",
"InvoiceNumber":"",
"PaymentMode":"PAID",
"ServiceCode":"PARTLOAD",
"WeightUnitType":"KILOGRAM",
"Description":"",
"COD":"",
"CODPaymentMode":"",
"PackageDetails":"",
"CreateWaybillWithoutStock":"true"
}
}


add_action('woocommerce-thankyou-order-processing', 'wdm_send_order_to_external', 10, 2);//voo
?>
