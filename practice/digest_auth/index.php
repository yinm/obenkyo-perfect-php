<?php

function digestAuth($authList, $realm='Restricted Area', $failed_text='認証に失敗しました')
{
    if (!$_SERVER['PHP_AUTH_DIGEST']) {
        $headers = getallheaders();
        if ($headers['Authorization']) {
            $_SERVER['PHP_AUTH_DIGEST'] = $headers['Authorization'];
        }
    }

    if ($_SERVER['PHP_AUTH_DIGEST']) {
        $neededParts = [
            'nonce'    => true,
            'nc'       => true,
            'cnonce'   => true,
            'qop'      => true,
            'username' => true,
            'uri'      => true,
            'response' => true,
        ];
        $data = [];

        $matches = [];
        preg_match_all('/(\w+)=("([^"]+)"|([\w=.\/\_-]+))/', $_SERVER['PHP_AUTH_DIGEST'], $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            if ($m[3]) {
                $data[$m[1]] = $m[3];
            } else {
                $data[$m[1]] = $m[4];
            }
            unset($neededParts[$m[1]]);
        }

        if ($neededParts) {
            $data = [];
        }

        if ($authList[$data['username']]) {
            $a1 = md5($data['username'] . ':' . $realm . ':' . $authList[$data['username']]);
            $a2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
            $validResponse = md5($a1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $a2);

            if ($data['response'] != $validResponse) {
                unset($_SERVER['PHP_AUTH_DIGEST']);
            } else {
                return $data['username'];
            }
        }
    }
    header('HTTP/1.1 401 Authorization Required');
    header('WWW-Authenticate: Digest realm="' . $realm . '", nonce="' . uniqid(rand(), true) . '", algorithm=MD5, qop="auth"');
    header('Content-type: text/html; charset='.mb_internal_encoding());

    die($failed_text);
}

digestAuth(['yinm' => 'password']);

echo '認証通過しましたよ！';
