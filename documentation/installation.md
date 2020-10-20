# Installation

In order to deploy this website on your web server :

1. `# aptitude install git apache2 mariadb-server php7.2 php-mysql php-xml php-intl php-mbstring php-sqlite3 php-curl php-apcu php-zip php-imagick unzip phpmyadmin composer gettext`
1.  1. `# nano /etc/apache2/sites-available/mysetup.conf`
        ```apacheconf
        <VirtualHost *:80>
            DocumentRoot /var/www/html/mysetup/webroot/
            <Directory /var/www/html/mysetup/webroot/>
                Options FollowSymLinks
                AllowOverride All
            </Directory>
        </VirtualHost>
        ```
        Starting Apache 2.4 needs `Require all granted`.
    2. `# a2ensite mysetup`
    3. `# a2enmod expires headers rewrite filter deflate`
    4. `# echo "apc.enable_cli = On" >> /etc/php/7.2/apache2/conf.d/20-apcu.ini`
    5. `# systemctl restart apache2`
1. `$ cd /var/www/html/`
1. `$ git clone https://github.com/MikeScops/mysetup.git`
1. `$ cd mysetup/`
1. `$ mkdir -p webroot/uploads/files/pics/`
1. `$ cp webroot/img/profile-default.png webroot/uploads/files/pics/profile_picture_1.png`
1. `# chown -R www-data:www-data webroot/uploads/`
1. `mkdir tmp/thumbs && chmod 775 tmp/thumbs` for thumber generator
1. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and create a new database with `utf8mb4_bin` as collation.
1. Configure a new user with required rights on this database (**Data** & **Structure**), and set it up in the `config/app.php` file, in the **Datasources** section.
1. You may also want to set several **third party services credentials** within the same file !
1. Now run our deployment script (a maintenance mode will be automatically enabled) : `$ sudo bash bin/deployment.sh`
1. If everything went fine, please run `# crontab -u www-data -e` and add the following line : `@daily php -d register_argc_argv=1 /var/www/html/mysetup/bin/cake.php clean_database -q`
1. You're done. Go to [http://YOUR_SERVER_IP/](http://YOUR_SERVER_IP/), the page is supposed to appear !

## Notes to developers

### Administration

Will be 'administrators' users having one of the below conditions verified :

-   A `Users.verified` value equal to `125`
-   An email address as `admin@admin.admin` (which cannot be verified), with `adminadmin` as default password

### Upgrades vendor packages

If you want to bump vendor packages version :

1. Execute `$ composer update` during development
2. Run some tests
3. Commit the `composer.lock` changes
4. Deploy normally to _production_ with `$ bash bin/deployment.sh`
5. On your _development_ setup, you may force installation of `require-dev` vendors with `$ bash bin/deployment.sh --dev`.

### Compiling assets

To compile .scss and .js files we use [Gulp](https://gulpjs.com/) software.

1. Install npm (Node.js)
2. Install required packages with `$ npm ci` (this follows the package.lock) or `$ npm install` (this will update package.lock)
3. [Wait for the full download of the Internet to be completed]
4. Run the automatic building pipeline with `$ npm run assets:build`

### Dump database weekly

Run `crontab -u www-data -e` and add the following line : `@weekly bash /root/scripts/save_mysetup_sql.sh`

And register this scrpt

```
#!/usr/bin/env bash

export MYSQL_PWD=ulSCV1LvrMDMSrtt3Dvx
mysqldump -u mysetup mysetup --ignore-table=mysetup.sessions --ignore-table=mysetup.phinxlog | gzip -c > /data/saves/mysetup/sqldumps/mysetup-`date +\%d-\%m-\%y`.sql.gz

exit 0
```

Don't forget to ignore any useless or temp tables.

### Issues you may have

1. Deployment script is failing at `PDOException: SQLSTATE[HY000]: General error: 1364 Field 'timeZone' doesn't have a default value` :
   **warning: this will erase your sql_mode**
   By default mysql don't allow null default value for fields, so you have to deactivate the security by adding
    ```
    [mysqld]
    sql_mode=''
    ```
    in your `/etc/mysql/mysql.conf.d/mysqld.cnf`
1. I need a domain to use Twitch login.
   Edit `/etc/apache2/sites-available/mysetup.conf` and add `Servername mysetup.net`.
   Then in your `/etc/hosts` add `127.0.0.1 mysetup.net` in the list.
   Make sure you have the correct credentials in your `app.php` file.
1. I have issues with node-sass when installing npm packages :
   This maybe due to your incompatible node version.
   You can use `--unsafe-perm`.
