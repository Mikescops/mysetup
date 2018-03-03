# mySetup.co

[![Website](https://img.shields.io/website-up-down-green-red/https/mysetup.co.svg?label=mySetup.co)](https://mysetup.co/)
[![Twitter Follow](https://img.shields.io/twitter/follow/mysetup_co.svg?style=social&label=Follow&style=flat-square)](https://twitter.com/mysetup_co)

## Installation

In order to deploy this website on your web server :

1. `# aptitude install git apache2 php7.0 php7.0-intl php7.0-mbstring php7.0-imagick php7.0-sqlite3 phpmyadmin composer gettext`
2.
    1. `# nano /etc/apache2/site-available/mysetup.conf`
        ```apacheconf
        <VirtualHost *:80>

            DocumentRoot /var/www/html/mysetup/webroot/
            <Directory /var/www/html/mysetup/webroot/>
                Options FollowSymLinks
                AllowOverride All
            </Directory>

        </VirtualHost>
        ```
    2. `# a2ensite mysetup`
    3. `# a2enmod expires headers rewrite filter deflate`
    4. `# service apache2 restart`
3. `$ cd /var/www/html/`
4. `$ git clone https://github.com/MikeScops/mysetup.git`
5. `$ cd mysetup/`
6. `$ mkdir -p webroot/uploads/files/pics/`
7. `$ cp webroot/img/profile-default.png webroot/uploads/files/pics/profile_picture_1.png`
8. `# chown -R www-data:www-data webroot/uploads/`
9. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and create a new database with `utf8mb4_bin` as collation.
10. Configure a new user with required rights on this database (**Data** & **Structure**), and set it up in the `config/app.php` file, in the **Datasources** section.
11. Now run our deployment script : `$ bash bin/deployment.sh`
12. You're done. Go to [http://YOUR_SERVER_IP/](http://YOUR_SERVER_IP/), the page is supposed to appear !

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

### Translations

If you wanna add a translation for a foreign language :

1. Add `default.po` and `core.po` files into `src/Locale/xx_XX/`
2. Add a corresponding test case into `src/Table/UsersTable.php@getLocaleByCountryID()` for the locale you added

So as to extract the strings from the source code, and edit them with _Poedit_, just run this command :

`$ bin/cake i18n extract --paths src --output src/Locale --extract-core yes --merge no --overwrite`

Output files will be under `src/Locale/`, as : `{cake,default}.pot`

## Authors

| [![twitter/mikescops](https://avatars0.githubusercontent.com/u/4266283?s=100&v=4)](http://twitter.com/mikescops "Follow @mikescops on Twitter") | [![mastodon/horlogeskynet](https://avatars1.githubusercontent.com/u/5331869?s=100&v=4)](https://mastodon.social/@HorlogeSkynet)
|---|---|
| [Corentin Mors](https://pixelswap.fr/) | [Samuel Forestier](https://horlogeskynet.github.io/) |
