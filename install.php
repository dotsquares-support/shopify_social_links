<?php
$shop = $_GET['shop'];

$api_key = "################";
$scopes = "read_orders, read_content, write_content, write_products, write_script_tags, read_themes, write_themes";
$redirect_uri = "https://example.com/sociallinks/token.php";

$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

header("Location: " . $install_url);
die();
?> 

