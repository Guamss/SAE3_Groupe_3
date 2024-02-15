<?php
/**
 * Fonction: getLogMessage
 *
 * Génère un message de journal avec les informations spécifiées.
 *
 * @param string $date      La date du message de journal.
 * @param string $level     Le niveau de gravité du message (ex. INFO, WARNING, ERROR).
 * @param string $cible     La cible ou le module associé au message.
 * @param string $details   Les détails ou le contenu spécifique du message.
 * @param string $ipAddress L'adresse IP associée au message.
 *
 * @return array Le message de journal formaté.
 */
function getLogMessage($date, $level, $cible, $details, $ipAddress): array
{
    $logMessage = array("[".$date."]",  "[".$level."]", $cible, $details, $ipAddress);
    return $logMessage;
}

/**
 * Fonction: write
 *
 * Écrit un message de journal dans un fichier spécifié.
 *
 * Si le répertoire n'existe pas, il est créé avec des permissions 0744.
 *
 * @param string $dir      Le répertoire dans lequel enregistrer le fichier de journal.
 * @param string $file     Le nom du fichier de journal.
 * @param array $message   Le message de journal à enregistrer.
 *
 * @return void
 * 
 * @throws Exception Si le répertoire ne peut pas être créé ou s'il est impossible d'écrire dedans.
 */
function write($dir, $file, $message): void
{
    if (!file_exists($dir))
    {
        mkdir($dir, 0744, true);
        if (!is_dir($dir) || !is_writable($dir)) 
        {
            throw new Exception("Impossible de créer le répertoire ou d'écrire dedans.");
        }
    }
    if (!file_exists($dir."/".$file))
    {
        touch($dir."".$file);
    }
    $fp = fopen($dir."".$file, 'a');
    fputcsv($fp, $message);
    fclose($fp);
}

/**
 * Fonction: list_dir
 *
 * Liste les fichiers dans un répertoire spécifié.
 *
 * @param string $path Le chemin du répertoire à lister.
 *
 * @return array Un tableau contenant les noms des fichiers dans le répertoire.
 */
function list_dir($path): array
{
    if (scandir($path))
    {
        $arr = scandir($path);
        return array_diff($arr, array('.', '..')); // enlève les fichiers . et ..
    }
    else
    {
        return array();
    }
}
?>
