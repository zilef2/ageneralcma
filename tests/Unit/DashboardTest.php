<?php

namespace Tests\Unit;

use App\Models\Inspeccion;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function it_displays_the_dashboard_with_correct_data()
    {
        // Crear usuarios y roles de prueba
        $user = User::factory()->count(5)->create();
        $user->assignRole('superadmin');
//        // Crear una inspección de prueba
        $inspeccion = Inspeccion::factory()->create(['updated_at' => now()]);
//
//        // Mockear el método estático MyModels::getPermissionToNumber
        $this->mock(MyModels::class, function ($mock) {
            $mock->shouldReceive('getPermissionToNumber')->andReturn(10);
        });
//
//        // Mockear el método estático Myhelp::EscribirEnLog
//        $this->mock(Myhelp::class, function ($mock) {
//            $mock->shouldReceive('EscribirEnLog')->andReturn('mocked log');
//        });
//
//        // Mockear el método estático MyGlobalHelp::formatFechaColombia
//        $this->mock(MyGlobalHelp::class, function ($mock) use ($inspeccion) {
//            $mock->shouldReceive('formatFechaColombia')->andReturn($inspeccion->updated_at->format('d/m/Y'));
//        });
//
//        // Hacer la petición a la ruta del dashboard
//        $response = $this->get(route('dashboard'));
//
//        // Verificar que la respuesta sea exitosa
//        $response->assertStatus(200);
//
//        // Verificar que los datos sean correctos
//        $response->assertInertia(fn (Assert $page) => $page
//            ->component('Dashboard')
//            ->has('users', 5)
//            ->has('roles', 3)
//            ->where('rolesNameds', Role::where('name', '<>', 'superadmin')->pluck('name')->toArray())
//            ->where('numberPermissions', 10)
//            ->where('ultimaInspeccion', $inspeccion->updated_at->format('d/m/Y'))
//        );
    }//php artisan test --filter=DashboardTest

}
