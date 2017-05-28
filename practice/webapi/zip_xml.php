<?php

$url = 'http://zip2.cgis.biz/xml/zip.php?zn=1130033';
// xmlオブジェクトを変数に代入
$xml = simplexml_load_file($url);

// オブジェクトの中身確認
var_dump($xml);

// 個別の値を出力 (ここでは、「区」の名前)
// PHP内で扱いやすいように、stringでキャストしている
echo (string)$xml->ADDRESS_value->value[5]->attributes()->city;
