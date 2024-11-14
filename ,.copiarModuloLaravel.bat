@echo off
setlocal

set controllerName=genericController

rem Definir las rutas de origen y destino
set origen="C:\laragon\www\laragon aa no uso\MDMascotas\app\Http\Controllers\%controllerName%.php"
set destino="C:\laragon\www\moduloNomina\app\Http\Controllers\"

if exist %origen% (
    copy %origen% %destino%
    echo El controlador %controllerName%.php ha sido copiado a la carpeta de destino.
    
    echo AHORA CON EL FRONTED

    set origen="C:\laragon\www\laragon aa no uso\MDMascotas\resources\js\Pages\generic"
    if exist %origen% (

        set destino="C:\laragon\www\moduloNomina\resources\js\Pages\generic"
        xcopy %origen% %destino% /E /I /Y
    ) else (
        echo El frontend de ORIGEN no existe.
    )

    @REM robocopy %origen% %destino% /E /COPYALL /R:0 /W:0
) else (
    echo %controllerName%.php no existe en la ruta de origen.
)

pause


