<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('identificacion')->unique();
            $table->string('sexo')->default('Masculino');
            $table->dateTime('fecha_nacimiento')->nullable();
            $table->string('celular')->nullable();

            $table->string('cargo')->nullable();
            $table->longText('firma')->nullable();
            $table->string('tipo_user')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
/*
php artisan db:seed --class=NombreDelSeeder
php artisan migrate --path=/database/migrations/nombre_de_archivo_migracion.php

 */
