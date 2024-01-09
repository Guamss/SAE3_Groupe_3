<?php
/**
 * Génère un message de log avec les informations spécifiées.
 *
 * @param string $date      La date du message de log.
 * @param string $level     Le niveau de gravité du message (ex. INFO, WARNING, ERROR).
 * @param string $cible     La cible ou le module associé au message.
 * @param string $details   Les détails ou le contenu spécifique du message.
 * @param string $ipAddress L'adresse IP associée au message.
 *
 * @return string Le message de log formaté.
 */
function getLogMessage($date, $level, $cible, $details, $ipAddress): string
{
    $logMessage = "[".$date."] [".$level."] ".$cible." - ".$details." | Adresse IP : ".$ipAddress."";
    return $logMessage;
}

/**
 * Écrit un message de log dans un fichier spécifié.
 *
 * Si le répertoire n'existe pas, il est créé avec des permissions 0744.
 *
 * @param string $dir      Le répertoire dans lequel enregistrer le fichier de log.
 * @param string $logfile  Le nom du fichier de log.
 * @param string $message  Le message de journal à enregistrer.
 *
 * @return void
 */
function write($dir, $logfile, $message): void
{
    if (!file_exists($dir))
    {
        mkdir($dir, 0744);
    }
    file_put_contents($dir.'/'.$logfile, $message. PHP_EOL, FILE_APPEND);
}
?>
