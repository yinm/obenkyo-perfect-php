<?php

$url = 'http://illustphp.com/5/write.php';
$data = array(
    'name' => 'テストさん',
    'comment' => 'schooのスライドの動作テスト',
    'submit' => 1
);

// curlのセッションを初期化
$ch = curl_init();

// curlの設定
// リクエストメソッド
curl_setopt($ch, CURLOPT_POST, true);
// 送信先のURL
curl_setopt($ch, CURLOPT_URL, $url);
// 送信データ
// (http_build_queryで、データをURLエンコード)
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

// curlのセッションを実行
curl_exec($ch);

// curlのセッションを終了
curl_close($ch);
