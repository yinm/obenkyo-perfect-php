<?php

/**
 * @param array $auth_list
 * @param string $realm
 * @param string $failed_text
 * @return string | void
 */
function basic_auth($auth_list, $realm="Restricted Area", $failed_text="認証に失敗しました")
{
    if (isset($_SERVER['PHP_AUTH_USER']) and isset($auth_list[$_SERVER['PHP_AUTH_USER']])) {
        if ($_SERVER['PHP_AUTH_PW'] === $auth_list[$_SERVER['PHP_AUTH_USER']]) {
            return $_SERVER['PHP_AUTH_USER'];
        }
    }

    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    header('HTTP/1.1 401 Unauthorized');
    header('Content-type: text/html; charset=' . mb_internal_encoding());

    die($failed_text);
}

basic_auth(array('yinm' => 'password'));

echo '認証を通過しました！！！';