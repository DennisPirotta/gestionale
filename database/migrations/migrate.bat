cd C:\Users\denni\Desktop\gestionale
php artisan migrate
php artisan migrate --path=database/migrations/after/2014_10_12_000000_create_users_table.php
php artisan migrate --path=database/migrations/after/2022_08_03_144409_create_holidays_table.php
php artisan migrate --path=database/migrations/after/2022_08_01_072117_create_orders_table.php
php artisan db:seed
