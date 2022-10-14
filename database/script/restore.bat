php artisan db:wipe
php artisan migrate
php artisan migrate --path=database/migrations/after/2014_10_12_000000_create_users_table.php
php artisan migrate --path=database/migrations/after/2022_08_22_063440_create_where_am_i_table.php
php artisan migrate --path=database/migrations/after/2022_08_01_072117_create_orders_table.php
php artisan migrate --path=database/migrations/after/2022_08_10_134013_create_technical_reports_table.php
php artisan migrate --path=database/migrations/after/2022_08_08_114154_create_hour_types_table.php
php artisan migrate --path=database/migrations/after/2022_08_05_122923_create_hours_table.php
php artisan migrate --path=database/migrations/after/2022_08_03_144409_create_holidays_table.php
php artisan migrate --path=database/migrations/after/2022_08_16_144530_create_order_details_table.php
php artisan migrate --path=database/migrations/after/2022_09_21_124145_create_technical_reports_details_table.php
php artisan migrate --path=database/migrations/after/2022_09_30_064252_create_business_hours_table.php
php artisan migrate --path=database/migrations/after/2022_10_14_102643_create_bug_reports_table.php
php artisan db:seed
