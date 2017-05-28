<?php

// sampleのAPIキーを使う
$url = 'http://samples.openweathermap.org/data/2.5/weather?q=London,uk&appid=b1b15e88fa797225412429c1c50c122a1';
$json = file_get_contents($url);

// PHPで扱えるようにデコードする
$result = json_decode($json);

var_dump($result);

echo $result->weather[0]->main;