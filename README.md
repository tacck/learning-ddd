README
===

## Database

SQLiteを使用する。

``` .env
#DB_CONNECTION=mysql
#DB_HOST=127.0.0.1
#DB_PORT=3306
#DB_DATABASE=laravel
#DB_USERNAME=root
#DB_PASSWORD=
DB_CONNECTION=sqlite
```

``` shell script
$ touch database/database.sqlite
$ php artisan migrate
```
