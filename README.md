# MySetup.co

[![Website](https://img.shields.io/website-up-down-green-red/https/mysetup.co.svg?label=mySetup.co)](https://mysetup.co/)
[![Twitter Follow](https://img.shields.io/twitter/follow/mysetup_co.svg?style=social&label=Follow&style=flat-square)](https://twitter.com/mysetup_co)

## Installation

In order to deploy this website on your web server:  

1. `# aptitude install git apache2 php7.0 php7.0-intl php7.0-mbstring php7.0-imagick php7.0-sqlite3 phpmyadmin composer`

2.
	1. `# nano /etc/apache2/site-available/mysetup.conf`
	2. ```apacheconf
		<VirtualHost *:80>

			DocumentRoot /var/www/html/mysetup/webroot
			<Directory /var/www/html/mysetup/webroot/>
				Options FollowSymLinks
				AllowOverride All
			</Directory>

		</VirtualHost>
		```
	3. `# a2ensite mysetup`
	4. `# a2enmod rewrite`
	5. `# service apache2 restart`

3. `$ cd /var/www/html/`

4. `$ git clone https://github.com/MikeScops/mysetup.git`

5. `$ cd mysetup/`

6. `$ mkdir webroot/uploads && chmod -R 777 webroot/uploads/`

7. `$ composer install`

8. Go to [http://YOUR_SERVER_IP/phpmyadmin/](http://YOUR_SERVER_IP/phpmyadmin/), and import the `MySetup.sql` file into a new database.

9. Configure an user with required rights on this database, and set it up in the `config/app.php` file in the _Datasources_ section.

10. You're done. Go to [http://YOUR_SERVER_IP/](http://YOUR_SERVER_IP/), the page would be supposed to appear !

### Notes to developers

* Will be 'administrators' users having a `verified` value equal to `125`, AND the account with an email address and password as (`admin@admin.admin` / `adminadmin`) (which cannot be verified...).

* On the first connection as `admin`, your profile picture will be broken. Please fix this by uploading one.

* During development, you may get an error into the console: `TypeError: a.result is undefined`. Don't bother, this is due to the JS social module.

* The plugin _loadsys/cakephp\_sitemap_ has been re-coded by [**@Mikescops**](https://github.com/Mikescops). **:warning: Do not update this dependence, and take care of having the correct sources (present on our repository) :warning:**.

* If you wanna add a translation for a foreign language, just add _default.po_ / _default.mo_ and _core.po_ / _core.mo_ files into `src/Locale/xx_XX/`, and authorize this new locale in `src/Application.php`. In order to extract the strings from the source code, and edit them with _Poedit_, just follow this scenario :
	
	> [user@localhost:/var/www/html/mysetup]$ **bin/cake i18n extract**  
	>  
	> Welcome to CakePHP v3.4.7 Console  
	> \---------------------------------------------------------------  
	> App : src  
	> Path: /var/www/html/mysetup/src/  
	> PHP : 7.0.19-1  
	> \---------------------------------------------------------------  
	> Current paths: None  
	> What is the path you would like to extract?  
	> [Q]uit [D]one  
	> [/var/www/html/mysetup/src/] >   
	>  
	> Current paths: /var/www/html/mysetup/src/  
	> What is the path you would like to extract?  
	> [Q]uit [D]one  
	> [D] >   
	>  
	> Would you like to extract the messages from the CakePHP core? (y/n)  
	> [n] > **y**  
	> What is the path you would like to output?  
	> [Q]uit  
	> [/var/www/html/mysetup/src/Locale] >  
	>  
	> Would you like to merge all domain strings into the default.pot file? (y/n)   
	> [n] >  
	>  
	>  
	> Extracting...  
	> \---------------------------------------------------------------  
	> Paths:  
	> /var/www/html/mysetup/src/  
	> /var/www/html/mysetup/vendor/cakephp/cakephp/src/  
	> Output Directory: /var/www/html/mysetup/src/Locale/  
	> \---------------------------------------------------------------  
	> ==========================================================================> 100%  
	> Done.
