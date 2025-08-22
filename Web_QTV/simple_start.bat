@echo off
echo Simple Laravel Server Start...
cd /d "d:\hoanle\Face-Recognition\Web_QTV"

echo Killing any existing PHP processes...
taskkill /f /im php.exe 2>nul

echo.
echo Starting server on port 8080...
php artisan serve --host=127.0.0.1 --port=8080

pause
