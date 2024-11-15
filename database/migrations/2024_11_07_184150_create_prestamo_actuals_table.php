<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
php artisan migrate --path=/database/migrations/2024_11_07_184150_create_prestamo_actuals_table.php
php artisan db:seed --class = NombreDelSeeder
     */

    public function up(): void
    {
        Schema::create('prestamo_historico', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('docenteId');
            $table->BigInteger('aulaId');
            $table->smallInteger('devuelto');
            $table->date('fecha');
            $table->integer('horafin');
            $table->integer('horainicio');
            $table->text('observaciones');
            $table->string('llave');
            $table->integer('personalId');
            $table->integer('cuentaId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamo_actuals');
    }
};
