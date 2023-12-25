<?php
function rc4Encrypt($text) 
{
    $json = file_get_contents('json/key.json');
    $data = json_decode($json, true);
    $key = $data["key"];

    $keyStream = initializeKeyStream($key);
    $encryptedText = '';

    $i = $j = 0;
    $textLength = strlen($text);

    for ($k = 0; $k < $textLength; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $keyStream[$i]) % 256;

        $temp = $keyStream[$i];
        $keyStream[$i] = $keyStream[$j];
        $keyStream[$j] = $temp;

        $char = ord($text[$k]) ^ $keyStream[($keyStream[$i] + $keyStream[$j]) % 256];
        $encryptedText .= chr($char);
    }

    return bin2hex($encryptedText);
}

function rc4Decrypt($key, $encryptedText) {
    $keyStream = initializeKeyStream($key);
    $encryptedText = hex2bin($encryptedText);
    $decryptedText = '';

    $i = $j = 0;
    $textLength = strlen($encryptedText);

    for ($k = 0; $k < $textLength; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $keyStream[$i]) % 256;

        $temp = $keyStream[$i];
        $keyStream[$i] = $keyStream[$j];
        $keyStream[$j] = $temp;

        $char = ord($encryptedText[$k]) ^ $keyStream[($keyStream[$i] + $keyStream[$j]) % 256];
        $decryptedText .= chr($char);
    }

    return $decryptedText;
}

function initializeKeyStream($key) {
    $keyLength = strlen($key);
    $keyStream = range(0, 255);
    $j = 0;

    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $keyStream[$i] + ord($key[$i % $keyLength])) % 256;

        $temp = $keyStream[$i];
        $keyStream[$i] = $keyStream[$j];
        $keyStream[$j] = $temp;
    }

    return $keyStream;
}

?>
