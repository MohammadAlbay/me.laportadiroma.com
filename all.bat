echo OFF
php artisan db:seed BusinessBrand
php artisan db:seed Permission
php artisan db:seed Role
php artisan db:seed RolePermission
php artisan db:seed User
php artisan serve

echo ON