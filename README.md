# mySetup.co

[![Website](https://img.shields.io/website-up-down-green-red/https/mysetup.co.svg?label=mySetup.co)](https://mysetup.co/)
[![Twitter Follow](https://img.shields.io/twitter/follow/mysetup_co.svg?style=social&label=Follow&style=flat-square)](https://twitter.com/mysetup_co)

## Installation

In order to deploy this website on your web server :

1. `# aptitude install git apache2 php7.0 php7.0-intl php7.0-mbstring php7.0-imagick php7.0-sqlite3 phpmyadmin composer`
2.
    1. `# nano /etc/apache2/site-available/mysetup.conf`
        ```apacheconf
            <VirtualHost *:80>

                DocumentRoot /var/www/html/mysetup/webroot
                <Directory /var/www/html/mysetup/webroot/>
                    Options FollowSymLinks
                    AllowOverride All
                </Directory>

            </VirtualHost>
        ```
    2. `# a2ensite mysetup`
    3. `# a2enmod expires headers rewrite deflate`
    4. `# service apache2 restart`
3. `$ cd /var/www/html/`
4. `$ git clone https://github.com/MikeScops/mysetup.git`
5. `$ cd mysetup/`
6. `$ mkdir -p webroot/uploads/files/pics/`
7. `$ cp webroot/img/profile-default.png webroot/uploads/files/pics/profile_picture_1.png`
8. `# chown -R www-data:www-data webroot/uploads/`
9. `$ bash bin/deployment.sh`
10. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and import the `config/schema/mySetup.sql` file into a new database.
11. Configure an user with required rights on this database, and set it up in the `config/app.php` file in the _Datasources_ section.
12. You're done. Go to [http://YOUR_SERVER_IP/](http://YOUR_SERVER_IP/), the page is supposed to appear !

## Notes to developers

### Administration

Will be 'administrators' users having :

* A `Users.verified` value equal to `125`
* An email address as `admin@admin.admin` (which cannot be verified), with `adminadmin` as default password

### Upgrades vendor packages

If you want to bump vendor packages version :

1. Execute `$ composer update` during development
2. Run some tests
3. Commit the `composer.lock` changes
4. Deploy normally to _production_ with `$ bash bin/deployment.sh`

### Translations

If you wanna add a translation for a foreign language :

1. Add _default.po_ and _core.po_ files into `src/Locale/xx_XX/` (or their binary `*.mo` format)
2. Add a corresponding test case into `src/Table/UsersTable.php@getLocaleByCountryID()` for the locale you added

So as to extract the strings from the source code, and edit them with _Poedit_, just follow this scenario :

1. `$ bin/cake i18n extract`
2. Keep the **default path** for extraction (`<...>/src/`)
3. **Do extract** the CakePHP core's strings
4. Keep the **default path** for output (`<...>/src/Locale`)
5. Replace existing files if needed
