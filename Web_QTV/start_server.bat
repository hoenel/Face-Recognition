@echo off
echo Starting Laravel Server with optimized PHP settings...
cd /d "d:\hoanle\Face-Recognition\Web_QTV"

echo Checking available ports...
netstat -an | findstr "8000\|8080\|3000"

echo.
echo Clearing Laravel caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo Starting server on port 8080 (if 8000 is busy)...
php -c php.ini artisan serve --host=127.0.0.1 --port=8080

pause
