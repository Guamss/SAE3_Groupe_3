<?php
/**
 * Chiffre le texte donné en utilisant l'algorithme RC4 avec la clé spécifiée.
 *
 * @param string $text Le texte à chiffrer.
 *
 * @return string Le texte chiffré au format hexadécimal.
 */
function rc4Encrypt($text) 
{
    // Lire la clé de cryptage depuis le fichier JSON
    $json = file_get_contents('json/key.json');
    $data = json_decode($json, true);
    $key = $data["key"];

    $keyStream = initializeKeyStream($key);
    $textChiffre = '';

    $i = $j = 0;
    $longueurTexte = strlen($text);

    for ($k = 0; $k < $longueurTexte; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $keyStream[$i]) % 256;

        $temp = $keyStream[$i];
        $keyStream[$i] = $keyStream[$j];
        $keyStream[$j] = $temp;

        $char = ord($text[$k]) ^ $keyStream[($keyStream[$i] + $keyStream[$j]) % 256];
        $textChiffre .= chr($char);
    }

    return bin2hex($textChiffre);
}

/**
 * Déchiffre le texte chiffré donné en utilisant l'algorithme RC4 avec la clé spécifiée.
 *
 * @param string $key           La clé utilisée pour le déchiffrement.
 * @param string $texteChiffre Le texte chiffré au format hexadécimal.
 *
 * @return string Le texte déchiffré.
 */
function rc4Decrypt($key, $texteChiffre) {
    $keyStream = initializeKeyStream($key);
    $texteChiffre = hex2bin($texteChiffre);
    $texteDechiffre = '';

    $i = $j = 0;
    $longueurTexte = strlen($texteChiffre);

    for ($k = 0; $k < $longueurTexte; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $keyStream[$i]) % 256;

        $temp = $keyStream[$i];
        $keyStream[$i] = $keyStream[$j];
        $keyStream[$j] = $temp;

        $char = ord($texteChiffre[$k]) ^ $keyStream[($keyStream[$i] + $keyStream[$j]) % 256];
        $texteDechiffre .= chr($char);
    }

    return $texteDechiffre;
}

/**
 * Initialise le flux de clé RC4 en fonction de la clé fournie.
 *
 * @param string $key La clé utilisée pour initialiser le flux de clé RC4.
 *
 * @return array Le flux de clé RC4 initialisé.
 */
function initializeKeyStream($key) {
    $longueurCle = strlen($key);
    $fluxCle = range(0, 255);
    $j = 0;

    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $fluxCle[$i] + ord($key[$i % $longueurCle])) % 256;

        $temp = $fluxCle[$i];
        $fluxCle[$i] = $fluxCle[$j];
        $fluxCle[$j] = $temp;
    }

    return $fluxCle;
}
?>
