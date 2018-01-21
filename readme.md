# Installation

**Require: PHP >= 7.1**

```
cp .env.development .env 
  
composer install
sudo chmod -R 0777 storage/
sudo chmod -R 0777 bootstrap/
  
docker-compose exec app bash
  
cd /home/www/app
  
php artisan cache:clear
php artisan config:cache
php artisan migrate
```

## Commands inside docker container 
```
cd /home/www/app
  
#Clear config cache
php artisan config:clear
  
#Clear app cache
php artisan cache:clear
  
#Show app routes
php artisan api:routes
  
#Clear cache for api routes
php artisan api:cache
  
#Clear cache for app routes
php artisan route:cache
  
#Make migrations for DB
php artisan migrate
  
#Rollback migrations for DB
php artisan migrate:rollback
```