```
composer install
sudo chmod -R 0777 storage/
sudo chmod -R 0777 bootstrap/
php artisan migrate
```

```
php artisan config:clear
php artisan cache:clear
php artisan api:routes
php artisan api:cache
php artisan route:cache

php artisan migrate
php artisan migrate:rollback
```