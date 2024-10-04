

onBeforeMount: Similar to beforeMount.
onMounted: Similar to mounted.
onBeforeUpdate: Similar to beforeUpdate.
onUpdated: Similar to updated.
onBeforeUnmount: Similar to beforeDestroy.
onUnmounted: Similar to destroyed.
//! ************************* npm *************************

npm install vue-select


//! ************************* BUGS *************************

    //?helps
        //memory limit
            C:\laragon\bin\php\php-7.4.30-Win32-vc15-x64\php.ini

        //permissions deined
            mv /home/aplicativoswebco/public_html/modulonom/bootstrap/cache /home/aplicativoswebco/public_html/modulonom/bootstrap/cache_2
            mkdir /home/aplicativoswebco/public_html/modulonom/storage/framework/cache/data
    // FIN helps


//! ************************* MODELS - MIDDLE - CORREO *************************

    //middleware
        php artisan make:middleware IsAdmin

    //#--  correo - EXPORT AND IMPORTS
        php artisan make:mail ExampleMail

        //#-- excel
        php artisan make:export ExampleExport --model=Empresa




//! ************************* DESPLIEGUE (viejo) *************************

    composer dump-autoload
    php artisan key:generate

    rm -r /public_html/modulonom/moduloNomina
    rm -r /home/aplicativoswebco/public_html/modulonom/moduloNomina
    rm -r /home/aplicativoswebco/public_html/modulonom/storage/logs/laravel.log

    // <!-- borrar -->
        rm -r "direccion"
    // <!-- permisos recursivamente -->
        chmod a+rwx folder_name -R
        chmod -R 555 /home/aplicativoswebco/public_html/modulonom/config
        chmod -R 555 /home/aplicativoswebco/public_html/modulonom/app
        chmod -R 707 /home/aplicativoswebco/public_html/modulonom/storage
        chmod -R 775 /home/aplicativoswebco/public_html/modulonom/bootstrap
        sudo chmod -R ugo+rw /home/aplicativoswebco/public_html/modulonom/storage
        sudo chmod -R ugo+rw /home/aplicativoswebco/public_html/modulonom/bootstrap
        chmod -R 555 /home/aplicativoswebco/public_html/modulonom/*
    */

    mv /home/aplicativoswebco/public_html/modulonom/bootstrap/cache /home/aplicativoswebco/public_html/modulonom/bootstrap/cache_2
    mkdir /home/aplicativoswebco/public_html/modulonom/storage/framework/cache/data


//! ************************* COMMANDS *************************
php artisan make:command customcommand
php artisan make:command LanguageCopyU

//! ************************* PERMISOS *************************
