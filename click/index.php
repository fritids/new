<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.clickbank.com/rest/1.2/orders/list");
curl_setopt($ch, CURLOPT_HEADER, true); 
curl_setopt($ch, CURLOPT_GET, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/xml", "Authorization:DEV-FBF19F6F46415AC735EA6ABE17D13062C559
:API-C51ADF3E2B447E6232C9550D074841FBAC68"));
$result = curl_exec($ch);
curl_close($ch);

print $result;

?>