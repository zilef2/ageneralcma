@echo on
set "folder=C:\laragon\www\ageneralcma"

cd %folder%
start /b php artisan serve
start /b npm run dev
