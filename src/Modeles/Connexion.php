<?php
/**
 * Classe Connexion
 *
 * Une classe pour gérer la connexion à la base de données en utilisant MySQLi.
 */
class Connexion
{

    /**------------------------DÉFINITION DES CHAMPS------------------------------- */

    /**
     * @var string|null L'adresse du serveur de la base de données.
     */
    private static $adresse;

    /**
     * @var string|null Le nom de la base de données.
     */
    private static $bd;

    /**
     * @var string|null Le nom d'utilisateur de la base de données.
     */
    private static $user;

    /**
     * @var string|null Le mot de passe de la base de données.
     */
    private static $password;

    /**
     * @var mysqli|null L'instance de connexion à la base de données MySQLi.
     */
    private static $conn;

    /**
     * Obtient l'instance de connexion à la base de données.
     *
     * Lit la configuration de la base de données à partir d'un fichier JSON
     * et établit une connexion à la base de données en utilisant MySQLi.
     * Si le fichier JSON est absent ou comporte des configurations manquantes,
     * il utilise des valeurs par défaut.
     *
     * @return mysqli L'instance de connexion à la base de données MySQLi.
     */
    public static function getConn()
    {
        // Lire la configuration de la base de données depuis le fichier JSON
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
        if (self::$conn->connect_error) 
        {
            die("Erreur de connexion à la base de données");
        }
        self::$conn->select_db(self::$bd);
        return self::$conn;
    }
}
?>
