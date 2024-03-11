#! /bin/bash

echo "Connexion à la base de donnée avec l'utilisateur root..."
sudo mysql -p < scriptTest.sql 

echo "----------------------------------FONCTION LABEL----------------------------------"
sudo phpunit $(readlink -f TestLabelFunc.php)
echo "----------------------------------CLASSE TICKET----------------------------------"
sudo phpunit $(readlink -f TestTicket.php)
echo "----------------------------------CLASSE USER----------------------------------"
sudo phpunit $(readlink -f TestUser.php)
echo "----------------------------------FONCTION LOGS----------------------------------"
sudo phpunit $(readlink -f TestLogsFunc.php)