<?php

$pattern = '/ab(cd)ef/';
$string = 'abcdefghij';
preg_match($pattern, $string, $matches);

var_dump($matches);
// array(2) {
//     [0]=>
//   string(6) "abcdef"
//     [1]=>
//   string(2) "cd"
// }
