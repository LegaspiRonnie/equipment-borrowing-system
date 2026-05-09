<?php

// Replace these with your real Google reCAPTCHA v2 Checkbox keys before deployment.
// The defaults are Google's public test keys and are only meant for local testing.
define('RECAPTCHA_SITE_KEY', '6LfHjOEsAAAAAIX11W9saBoHWxg0rCcvsWxgdzXs');
define('RECAPTCHA_SECRET_KEY', '6LfHjOEsAAAAAJGj9nCUkFwOdKFO_4s8RJ9ZZ8Eh');


function verify_recaptcha($responseToken)
{
    if (empty($responseToken)) {
        return false;
    }

    $payload = http_build_query([
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $responseToken,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    if (function_exists('curl_init')) {
        $ch = curl_init($verifyUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 10,
            ],
        ]);
        $result = file_get_contents($verifyUrl, false, $context);
    }

    if ($result === false) {
        return false;
    }

    $data = json_decode($result, true);
    return isset($data['success']) && $data['success'] === true;
}
