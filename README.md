# MySetup.co
Repo' of <https://mysetup.co>

Warning to developers: the `pages` tables in the SQL DB **IS NOT** a CakePHP entity.  
It's just a container for more or less "static" HTML content.

## Installation

In order to deploy this website on your web server:  

1. `cd /var/www/html/`

2. `git clone https://github.com/MikeScops/mysetup.git`

3. `cd mysetup/`

4. `curl -s https://getcomposer.org/installer | php`

5. `php composer.phar install && rm composer.phar`

6. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and import the `MySetup.sql` file into a new database.

7. Configure an user with required rights on this database, and set it up in the `config/app.php` file in the _Datasources_ section.

8. You're done. Go to [http://YOUR_SERVER_IP/GMCalendar/](http://YOUR_SERVER_IP/GMCalendar/), the page would be supposed to appear !
