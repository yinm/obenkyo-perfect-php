<?php

$url = 'http://zip2.cgis.biz/xml/zip.php?zn=1130033';
$result = file_get_contents($url);
echo $result;

// 実際のサイトで使うときは、エスケープもする
echo htmlspecialchars($result, ENT_QUOTES, 'utf-8');
