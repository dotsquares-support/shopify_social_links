<?php
require_once("inc/functions.php");
require_once("inc/db.php");

define('SHOPIFY_APP_SECRET', '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'); // Replace with your SECRET KEY

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$res = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$topic_header = $_SERVER['HTTP_X_SHOPIFY_TOPIC'];
$shop_header = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');
$decoded_data = json_decode($data, true);

$verified = verify_webhook($data, $hmac_header);

if( $verified == true ) {
  if( $topic_header == 'app/uninstalled' || $topic_header == 'shop/update') {
    if( $topic_header == 'app/uninstalled' ) {

      $sql = "DELETE FROM token_table WHERE store_url='".$shop_header."' LIMIT 1";
      $result = mysqli_query($conn, $sql);

      $response->shop_domain = $decoded_data['shop_domain'];

      $res = $decoded_data['shop_domain'] . ' is successfully deleted from the database';
    } else {
      $res = $data;
    }
  }
} else {
  $res = 'The request is not from Shopify';
}

error_log('Response: '. $res); //check error.log to see the result


?>

 // delete setup
        $array = array(
        'webhook' => array(
            'topic' => 'app/uninstalled', 
            'address' => 'https://yourwebsite.com/myshopifyapp/webhooks/delete.php?shop=' . $shop_url,
            'format' => 'json'
        )
    );
    $parsedUrl = parse_url('https://' . $shop_url );
    $host = explode('.', $parsedUrl['host']);
    $subdomain = $host[0];

    $shop = $subdomain;
    $webhook = shopify_call($access_token, $shop, "/admin/api/2020-07/webhooks.json", $array, 'POST');
    $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);
