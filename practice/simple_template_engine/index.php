<?php

$list = array(
    'key1' => 'val1',
    'key2' => 'val2',
    'key3' => 'val3',
);

ob_start();
include('./template.php');
$body = ob_get_contents();
ob_end_clean();

echo $body;