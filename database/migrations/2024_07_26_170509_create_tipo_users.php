<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_users', function (Blueprint $table) {
            $table->id();
            $table->SoftDeletes();
			$table->string('nombre');
			$table->string('descripcion');
            $table->timestamps();
        });

        /*
         * EL seeder AQUI MISMO!!!
         */
        $nombresGenericos = [
            ["nombre" => "SST","descripcion" => "Seguridad y salud en el trabajo"],
            ["nombre" => "SGA","descripcion" => "Sistema globalmente armonizado"],
        ];
        foreach ($nombresGenericos as $value) {
            DB::table('tipo_users')->insert($value);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_users');
    }
};
