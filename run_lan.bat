echo OFF
php artisan db:seed BusinessBrand
php artisan db:seed Permission
php artisan db:seed Role
php artisan db:seed RolePermission
php artisan db:seed User
php artisan serve --host=192.168.88.173 --port=80

echo ON