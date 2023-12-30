<?php

function getLogMessage($date, $level, $cible, $details, $ipAddress): string
{
    $logMessage = "[".$date."] [".$level."] ".$cible." - ".$details." | Adresse IP : ".$ipAddress."";
    return $logMessage;
}

function write($dir, $logfile, $message): void
{
    if (!file_exists($dir))
    {
        mkdir ($dir, 0744);
    }
    file_put_contents ($dir.'/'.$logfile, $message. PHP_EOL, FILE_APPEND);
}
?>