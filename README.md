# MySetup.co
Repo' of <https://mysetup.co>

## Installation

In order to deploy this website on your web server:  

1. `cd /var/www/html/`

2. `git clone https://github.com/MikeScops/mysetup.git`

3. `cd mysetup/`

4. `chmod -R 777 webroot/uploads/`

5. `curl -s https://getcomposer.org/installer | php`

6. `php composer.phar install && rm composer.phar`

7. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and import the `MySetup.sql` file into a new database.

8. Configure an user with required rights on this database, and set it up in the `config/app.php` file in the _Datasources_ section.

9. You're done. Go to [http://YOUR_SERVER_IP/mysetup/](http://YOUR_SERVER_IP/mysetup/), the page would be supposed to appear !

### Notes to developers

* Warning: the `pages` tables present in the SQL DB **IS NOT** a CakePHP entity. It's just a container for more or less "static" HTML content.

* Will be 'administrator' the users having a `verified` value equal to `125`, and the account with an email address and password as (`admin@admin.admin` / `adminadmin`) (which cannot be verified...).

* During development, you may get an error into the console: `TypeError: a.result is undefined`. Don't bother, this is due to the JS social module.

* The plugin _loadsys/cakephp\_sitemap_ has been re-coded by @Mike Scops. **/!\ If you clone and install this repository, please take care of having the correct sources. /!\\**

* If you wanna add a translation for a foreign language, just add _default.po_ / _default.mo_ files into `src/Locale/xx_XX/`, and authorize this new locale in `src/Application.php`.

* During development, you won't be able to add a comment to a setup (Google's invisible CAPTCHA checks the domain name environment).
