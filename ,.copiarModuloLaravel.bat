@echo off
setlocal
set controllerName=genericController

rem Definir las rutas de origen y destino
set origen="C:\laragon\www\ageneralcma\app\Http\Controllers\%controllerName%.php"
set destino="C:\laragon\www\ejemplo\app\Http\Controllers\"

if exist %origen% (
    copy %origen% %destino%
    echo El controlador %controllerName%.php ha sido copiado a la carpeta de destino.

    echo AHORA CON EL FRONTED
    set origen="C:\laragon\www\ageneralcma\resources\js\Pages\generic"

    if exist %origen% (
        set destino="C:\laragon\www\ejemplo\resources\js\Pages\generic"
        xcopy %origen% %destino% /E /I /Y
    ) else (
        echo Error: No se pudo copiar el archivo de frontend.
    )

    @REM robocopy %origen% %destino% /E /COPYALL /R:0 /W:0
) else (
    echo Error: El archivo de frontend %controllerName%.vue no existe en la ruta especificada.
)

echo ========================================
echo           PROCESO TERMINADO
echo ========================================
pause


