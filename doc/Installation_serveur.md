# <center>Installation du serveur</center> 

## Plan 
1. Installation du système d'exploitation
2. Installation du serveur en lui même
    * 2.1. Service SSH
    * 2.2. Service HTTP
    * 2.3. Installation de PHP
    * 2.4. Service MySQL
    * 2.5. Installation de PhpMyAdmin
3. Comment mettre le site sur le serveur

## <u>1. Installation du système d'exploitation</u>

Il faut d'abord installer `Raspberry Pi Imager`, étant sur un environnemen linux (Arch Linux) j'ai effectué une installation sur `Flatpak` : 
```bash
flatpak install flathub org.raspberrypi.rpi-imager
```
une fois l'application installé j'ai flashé la carte SD avec une une version de Debian `Raspberry Pi OS (64-bit)`
![rpi-imager](image.png)

<br>

## <u>2. Installation du serveur en lui même</u>

Une fois le système d'exploitation j'ai renommé le nom de la machine en accord avec les membres de mon groupe
dans les fichiers `/etc/hostname` et `/etc/hosts`

### 2.1. Service ssh

L'installation d'un service SSH nous permettra d'accéder au serveur à distance afin d'effectuer des maintenances ou de pouvoir mettre sur le serveur les nouvelles version du site web grâce au protocole SFTP

```bash
sudo apt install ssh 
```
une fois installé : 
```bash
sudo systemctl start ssh
sudo systemctl enable ssh
```
cela aura pour effet d'éxecuter le service grâce au paramètre `start` mais aussi de l'éxecuter à chaque redémarrage grâce au paramètre `enable`.

Une fois sur un autre poste il suffit d'entrer la commande suivante pour accéder au serveur à distance :
```bash
ssh pisae@192.168.1.57
```

### 2.2 Service HTTP

Il suffit d'installer le service `apache2` qui sera le serveur HTTP :
```bash
sudo apt install apache2
```
Une fois installé :
```bash
sudo systemctl start http
sudo systemctl enable http
```
cela aura pour effet d'éxecuter le service grâce au paramètre `start` mais aussi de l'éxecuter à chaque redémarrage grâce au paramètre `enable`.
<br>
<br>
<br>
<br>
<br>
Pour vérifier si l'installation s'est bien faite il faut aller sur `localhost` ou `127.0.0.1` et cette page est censée être affichée :
![http_installation_site](image-1.png)

### 2.3 Installation de PHP

Une fois le service http installé il faut aussi installer `php` grâce à la commande :
```bash
sudo apt install php libapache2-mod-php php-mysql
```
Pour vérifier que php est bien installé : 
```bash
php -v
```

### 2.4 Service MySQL

La commande suivante permet l'installation du service MySQL : 
```bash
sudo apt install mariadb-server
```
une fois installé : 
```bash
sudo systemctl start mysql
sudo systemctl enable mysql
```
cela aura pour effet d'éxecuter le service grâce au paramètre `start` mais aussi de l'éxecuter à chaque redémarrage grâce au paramètre `enable`.

Puis pour finaliser l'installation de la base de donnée il faut éxecuter le programme `mysql_secure_installation`. Une fois l'installation terminée on peut 
utiliser la commande `mysql` :
```bash
mysql -u root
```

### 2.5 Installation de PhpMyAdmin

Le paquet `wget` qui permettra de récupérer les archives nécessaire à la mise en place des pages PhpMyAdmin
```bash
sudo apt install wget
```
J'ai choisi de télecharger la dernière version en date de PhpMyAdmin (la `5.2.1`) :
```bash
wget https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-english.tar.gz
```
dans le répertoire de télechargement, extraire l'archive :
```bash
tar xvf phpMyAdmin-5.2.1-all-languages.tar.gz
```
puis déplacer cette extraction dans le dossier `/usr/share/phpmyadmin` : 
```bash
sudo mv phpMyAdmin-*/ /usr/share/phpmyadmin
```
créer un dossier temporaire qui nous servira plus tard :
```bash
sudo mkdir -p /var/lib/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/lib/phpmyadmin
```
créer un répertoire pour les fichiers de configuration de phpMyAdmin tels que le fichier htpass :
```bash
sudo mkdir /etc/phpmyadmin/
sudo cp /usr/share/phpmyadmin/config.sample.inc.php  /usr/share/phpmyadmin/config.inc.php
```
editer le fichier `/usr/share/phpmyadmin/config.inc.php` :
```bash
sudo nano /usr/share/phpmyadmin/config.inc.php
```
et il rajouter la ligne qui indique notre répertoire temporaire créer précédement :
```php
$cfg['TempDir'] = '/var/lib/phpmyadmin/tmp';
```
puis il faut créer un fichier configuration pour qu'elle soit répertoriée sur le serveur apache à l'adresse `localhost/phpmyadmin` :
```bash
sudo nano /etc/apache2/conf-enabled/phpmyadmin.conf
```
pour cela j'ai choisis un script trouvé sur internet, je l'ai évidemment modifié pour qu'il convienne à mon serveur :
```conf
Alias /phpmyadmin /usr/share/phpmyadmin

<Directory /usr/share/phpmyadmin>
    Options SymLinksIfOwnerMatch
    DirectoryIndex index.php

    <IfModule mod_php5.c>
        <IfModule mod_mime.c>
            AddType application/x-httpd-php .php
        </IfModule>
        <FilesMatch ".+\.php$">
            SetHandler application/x-httpd-php
        </FilesMatch>

        php_value include_path .
        php_admin_value upload_tmp_dir /var/lib/phpmyadmin/tmp
        php_admin_value open_basedir /usr/share/phpmyadmin/:/etc/phpmyadmin/:/var/lib/phpmyadmin/:/usr/share/php/php-gettext/:/usr/share/php/php-php-gettext/:/usr/share/javascript/:/usr/share/php/tcpdf/:/usr/share/doc/phpmyadmin/:/usr/share/php/phpseclib/
        php_admin_value mbstring.func_overload 0
    </IfModule>
    <IfModule mod_php.c>
        <IfModule mod_mime.c>
            AddType application/x-httpd-php .php
        </IfModule>
        <FilesMatch ".+\.php$">
            SetHandler application/x-httpd-php
        </FilesMatch>

        php_value include_path .
        php_admin_value upload_tmp_dir /var/lib/phpmyadmin/tmp
        php_admin_value open_basedir /usr/share/phpmyadmin/:/etc/phpmyadmin/:/var/lib/phpmyadmin/:/usr/share/php/php-gettext/:/usr/share/php/php-php-gettext/:/usr/share/javascript/:/usr/share/php/tcpdf/:/usr/share/doc/phpmyadmin/:/usr/share/php/phpseclib/
        php_admin_value mbstring.func_overload 0
    </IfModule>

</Directory>

# Authorize for setup
<Directory /usr/share/phpmyadmin/setup>
    <IfModule mod_authz_core.c>
        <IfModule mod_authn_file.c>
            AuthType Basic
            AuthName "phpMyAdmin Setup"
            AuthUserFile /etc/phpmyadmin/htpasswd.setup
        </IfModule>
        Require valid-user
    </IfModule>
</Directory>

# Disallow web access to directories that don't need it
<Directory /usr/share/phpmyadmin/templates>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/libraries>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/setup/lib>
    Require all denied
</Directory>
Alias /phpmyadmin /usr/share/phpmyadmin

<Directory /usr/share/phpmyadmin>
    Options SymLinksIfOwnerMatch
    DirectoryIndex index.php

    <IfModule mod_php5.c>
        <IfModule mod_mime.c>
            AddType application/x-httpd-php .php
        </IfModule>
        <FilesMatch ".+\.php$">
            SetHandler application/x-httpd-php
        </FilesMatch>

        php_value include_path .
        php_admin_value upload_tmp_dir /var/lib/phpmyadmin/tmp
        php_admin_value open_basedir /usr/share/phpmyadmin/:/etc/phpmyadmin/:/var/lib/phpmyadmin/:/usr/share/php/php-gettext/:/usr/share/php/php-php-gettext/:/usr/share/javascript/:/usr/share/php/tcpdf/:/usr/share/doc/phpmyadmin/:/usr/share/php/phpseclib/
        php_admin_value mbstring.func_overload 0
    </IfModule>
    <IfModule mod_php.c>
        <IfModule mod_mime.c>
            AddType application/x-httpd-php .php
        </IfModule>
        <FilesMatch ".+\.php$">
            SetHandler application/x-httpd-php
        </FilesMatch>

        php_value include_path .
        php_admin_value upload_tmp_dir /var/lib/phpmyadmin/tmp
        php_admin_value open_basedir /usr/share/phpmyadmin/:/etc/phpmyadmin/:/var/lib/phpmyadmin/:/usr/share/php/php-gettext/:/usr/share/php/php-php-gettext/:/usr/share/javascript/:/usr/share/php/tcpdf/:/usr/share/doc/phpmyadmin/:/usr/share/php/phpseclib/
        php_admin_value mbstring.func_overload 0
    </IfModule>

</Directory>

# Authorize for setup
<Directory /usr/share/phpmyadmin/setup>
    <IfModule mod_authz_core.c>
        <IfModule mod_authn_file.c>
            AuthType Basic
            AuthName "phpMyAdmin Setup"
            AuthUserFile /etc/phpmyadmin/htpasswd.setup
        </IfModule>
        Require valid-user
    </IfModule>
</Directory>

# Disallow web access to directories that don't need it
<Directory /usr/share/phpmyadmin/templates>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/libraries>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/setup/lib>
    Require all denied
</Directory>
```
enfin il suffit de redémarrer le service `apache2` :
```bash
sudo systemctl restart apache2
```
Maintenant que `PhpMyAdmin` est fonctionnel il suffit de s'y connecter à `localhost/phpmyadmin` avec les login de la base de donnée qui a été installée précedement.
![Alt text](image-2.png)
## 3. Comment mettre le site sur le serveur

Il suffit d'utiliser le protocole SFTP grâce à un logiciel comme FileZilla ou WinSCP.
Une fois la connexion avec le serveur établi avec le protocole SFTP le répertoire du serveur se situe dans `/var/www/html/src`.
Il suffit de glisser/déposer les fichiers nécessaire :
![Alt text](image-3.png)
