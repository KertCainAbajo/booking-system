@echo off
rem Starts Dexter Auto: website, notification sender (emails + phone alerts) and daily jobs (reminders, archiving).
rem Keep this window open while the system is in use. Close it to stop everything.
cd /d "%~dp0"
start "Dexter Auto - notifications" /min cmd /k php artisan queue:work --tries=3
start "Dexter Auto - daily jobs" /min cmd /k php artisan schedule:work
php artisan serve
