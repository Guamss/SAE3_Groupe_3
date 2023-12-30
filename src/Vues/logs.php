<?php

$file_path = "log/". $logfile;

if (file_exists($file_path)) 
{
    echo "<h2>Voici le journal d'activité du site :</h2>";
    $log_content = file_get_contents($file_path);
    $log_lines = explode(PHP_EOL, $log_content);

    echo '<ul>';
    foreach ($log_lines as $line) 
    {
        if (!empty($line))
        {
            echo '<li>' . $line . '</li>';
        }
    }
    echo '</ul>';
} 
else 
{
    echo '<h2>Le fichier de journal n\'existe pas</h2>';
}
?>
