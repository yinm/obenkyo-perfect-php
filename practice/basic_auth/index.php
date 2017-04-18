<?php

/**
 * @param array $authList
 * @param string $realm
 * @param string $failedText
 * @return string | void
 */
function basicAuth($authList, $realm="Restricted Area", $failedText="認証に失敗しました")
{
    if (isset($_SERVER['PHP_AUTH_USER'])
        && isset($authList[$_SERVER['PHP_AUTH_USER']])
        && $_SERVER['PHP_AUTH_PW'] === $authList[$_SERVER['PHP_AUTH_USER']]) {
            return $_SERVER['PHP_AUTH_USER'];
    }

    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    header('HTTP/1.1 401 Unauthorized');
    header('Content-type: text/html; charset=' . mb_internal_encoding());

    die($failedText);
}

basicAuth(array('yinm' => 'password'));

echo '認証を通過しました！！！';