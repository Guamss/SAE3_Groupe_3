<?php
class Connexion
{
    private static $adresse;
    private static $bd;
    private static $user;
    private static $password;
    private static $conn;

    public static function getConn()
    {
        $json = file_get_contents('json/logins.json');
        $config = json_decode($json, true);

        if ($config !== null && is_array($config)) 
        {
            self::$adresse = $config['adresse'] ?? self::$adresse;
            self::$bd = $config['database'] ?? self::$bd;
            self::$user = $config['user'] ?? self::$user;
            self::$password = $config['password'] ?? self::$password;
        }

        self::$conn = new mysqli(self::$adresse, self::$user, self::$password);

        if (self::$conn->connect_error) {
            die("Erreur de connexion à la base de données");
        }

        self::$conn->select_db(self::$bd);
        return self::$conn;
    }
}
?>
