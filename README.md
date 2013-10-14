slim-angular
===========

Example application(CRUD of articles) using angulajs with a php restfull  api(slim + doctrine2 + smarty)


```bash
cd /var/www/ or cd yourlocalhostfolder
$  git clone https://github.com/fmunoz92/slim-angular.git
$ curl -s https://getcomposer.org/installer | php
```
Configure db:

create an empty database: for example "angularphp"

set config in bootstrap.php
```php
define("DBHOST", '127.0.0.1');
define("DBDRIVER", 'pdo_mysql');
define("DBNAME", 'angularphp');
define("DBUSER", 'root');
define("DBPASS", '1');
define("DEBUG", 1);
```
then run
```bash
$ php composer.phar install
$ php vendor/bin/doctrine orm:schema-tool:update --force
```
finally run your browser in [http://localhost/slim-angular](http://localhost/slim-angular)

Files
===========

Server-side:

[Slim Routes definition](https://github.com/fmunoz92/slim-angular/blob/master/index.php)

[Articles doctrine entity](https://github.com/fmunoz92/slim-angular/blob/master/models/article.php)

[Index view](https://github.com/fmunoz92/slim-angular/blob/master/views/index.php)

Client-side

[Articles Controller](https://github.com/fmunoz92/slim-angular/blob/master/assets/js/controllers/articles.js)

[Routes definition](https://github.com/fmunoz92/slim-angular/blob/master/assets/js/config.js)

[Handlebarsjs views](https://github.com/fmunoz92/slim-angular/tree/master/assets/views)


