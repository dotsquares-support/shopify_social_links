<?php
require_once("inc/functions.php");
require_once("inc/db.php");
$api_key = "1e69a73193cec5e4046fb3bc50589954";
$shared_secret = "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";

$params = $_GET; 
$hmac = $_GET['hmac']; 

$params = array_diff_key($params, array('hmac' => '')); 
ksort($params); 
$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

if (hash_equals($hmac, $computed_hmac)) {
	
	$query = array(
		"client_id" => $api_key, 
		"client_secret" => $shared_secret, 
		"code" => $params['code']
	);
	
	$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);	
	$result = json_decode($result, true);
	$access_token = $result['access_token'];
	echo $access_token;
	$shop = $_GET['shop'];

	$sqlQry = mysqli_query($conn, "SELECT * FROM token_table  WHERE store_url ='".$shop."'");
	$rows = $sqlQry->fetch_assoc();
	
	$shopname = substr($shop, 0, strpos($shop, ".myshopify.com"));

		if(empty($rows)){

		$sql = "INSERT INTO token_table (store_name, store_url, access_token, install_date)
		VALUES ('".$shopname."', '".$params['shop']."', '".$access_token."', NOW())";
			if (mysqli_query($conn, $sql)) {
				header('Location: https://'.$params['shop'].'/admin/apps');
				die();

			} else {
				echo "Error inserting new record: " . mysqli_error($conn);
			}

		}else {
			header('Location: https://'.$params['shop'].'/admin/apps');
				die();
		}
	}
	?>