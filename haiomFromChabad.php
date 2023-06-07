<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 28.07.2022
 * Time: 21:34
 */
// Create DOM from URL or file
$url = 'https://ru.chabad.org/dailystudy/hayomyom_cdo/jewish/-.htm';
$url = 'https://moshiach.ru/yom-yom.php';
$libxml_previous_state = libxml_use_internal_errors(true);
//$html = file_get_contents($url);
$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:33.0) Gecko/20100101 Firefox/33.0');
$html = curl_exec($curl_handle);
curl_close($curl_handle);
//print $html;

$dom = new DOMDocument;
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$nodes = $xpath->query("//article[contains(@class, 'yomyom')]/p");
//print count($nodes);
if(count($nodes)<=2){
    for($i=0; $i<count($nodes);$i++) {
        echo "<p>" . $nodes[$i]->nodeValue . "</p>";
    }
}else{
    for($i=2; $i<count($nodes);$i++) {
        echo "<p>" . $nodes[$i]->nodeValue . "</p>";
    }
}
// handle errors
libxml_clear_errors();
// restore
libxml_use_internal_errors($libxml_previous_state);