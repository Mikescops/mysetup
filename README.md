# mySetup.co

[![Website](https://img.shields.io/website-up-down-green-red/https/mysetup.co.svg?label=mySetup.co)](https://mysetup.co/)
[![Twitter Follow](https://img.shields.io/twitter/follow/mysetup_co.svg?style=social&label=Follow&style=flat-square)](https://twitter.com/mysetup_co)

## Installation

In order to deploy this website on your web server :

1. `# aptitude install git apache2 mariadb-server php7.1 php7.1-mysql php7.1-xml php7.1-intl php7.1-mbstring php7.1-sqlite3 php7.1-curl php7.1-apcu php7.1-zip php-imagick unzip phpmyadmin composer gettext`
2.
    1. `# nano /etc/apache2/sites-available/mysetup.conf`
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
    4. `# echo "apc.enable_cli = On" >> /etc/php/7.1/apache2/conf.d/20-apcu.ini`
    5. `# systemctl restart apache2`
3. `$ cd /var/www/html/`
4. `$ git clone https://github.com/MikeScops/mysetup.git`
5. `$ cd mysetup/`
6. `$ mkdir -p webroot/uploads/files/pics/`
7. `$ cp webroot/img/profile-default.png webroot/uploads/files/pics/profile_picture_1.png`
8. `# chown -R www-data:www-data webroot/uploads/`
9. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and create a new database with `utf8mb4_bin` as collation.
10. Configure a new user with required rights on this database (**Data** & **Structure**), and set it up in the `config/app.php` file, in the **Datasources** section.
11. You may also want to set several **third party services credentials** within the same file !
12. Now run our deployment script (a maintenance mode will be automatically enabled) : `$ sudo bash bin/deployment.sh`
13. If everything went fine, please run `# crontab -u www-data -e` and add the following line : `@daily php -d register_argc_argv=1 /var/www/html/mysetup/bin/cake.php clean_database -q`
14. You're done. Go to [http://YOUR_SERVER_IP/](http://YOUR_SERVER_IP/), the page is supposed to appear !

## Notes to developers

### Administration

Will be 'administrators' users having one of the below conditions verified :

* A `Users.verified` value equal to `125`
* An email address as `admin@admin.admin` (which cannot be verified), with `adminadmin` as default password

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
2. Install required packages with `$ npm install`
3. [Wait for the full download of the Internet to be completed]
4. Run the automatic building pipeline with `$ npm run assets:build`

### Translations

If you wanna add a translation for a foreign language :

1. Add `default.po` and `core.po` files into `src/Locale/xx_XX/`
2. Add a corresponding test case into `src/Table/UsersTable.php@getLocaleByCountryID()` for the locale you added

So as to extract the strings from the source code, and edit them with _Poedit_, just run this command :

`$ bin/cake i18n extract --paths ./src --output ./src/Locale --extract-core yes --merge no --overwrite`

Output files will be under `src/Locale/`, as : `{cake,default}.pot`

### Issues you may have 

1. Deployment script is failing at `PDOException: SQLSTATE[HY000]: General error: 1364 Field 'timeZone' doesn't have a default value` :
    By default mysql don't allow null default value for fields, so you have to deactivate the security by adding 
    ```
    [mysqld]
    sql_mode=''
    ```
    in your `/etc/mysql/mysql.conf.d/mysqld.cnf `
1. I need a domain to use Twitch login.
    Edit `/etc/apache2/sites-available/mysetup.conf` and add `Servername mysetup.net`.
    Then in your `/etc/hosts` add `127.0.0.1    mysetup.net` in the list.
    Make sure you have the correct credentials in your `app.php` file.

## Authors

| [![twitter/mikescops](https://avatars0.githubusercontent.com/u/4266283?s=100&v=4)](http://twitter.com/mikescops "Follow @mikescops on Twitter") | [![mastodon/horlogeskynet](https://avatars1.githubusercontent.com/u/5331869?s=100&v=4)](https://mastodon.social/@HorlogeSkynet)
|---|---|
| [Corentin Mors](https://pixelswap.fr/) | [Samuel Forestier](https://blog.samuel.domains/) |
