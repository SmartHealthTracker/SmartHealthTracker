composer install
composer update
php artisan key:generate
php .\artisan serve    
php artisan migrate

php artisan migrate:rollback --step=1
php artisan migrate
