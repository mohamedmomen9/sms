<?php

namespace Modules\Services\Tests\Feature\Filament;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Services\Filament\Resources\AppointmentResource;
use Modules\Services\Filament\Resources\AppointmentResource\Pages\ListAppointments;
use Modules\Services\Models\Appointment;

class AppointmentResourceTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_admin_can_render_index_page()
    {
        $admin = $this->createSuperAdmin();
        $this->actingAs($admin);

        $response = $this->get(AppointmentResource::getUrl('index'));
        $response->assertSuccessful();
    }

    public function test_admin_can_list_records()
    {
        $admin = $this->createSuperAdmin();
        $this->actingAs($admin);

        $appointment = Appointment::factory()->create();

        Livewire::test(ListAppointments::class)
            ->assertCanSeeTableRecords([$appointment]);
    }

    public function test_admin_can_render_create_page()
    {
        $admin = $this->createSuperAdmin();
        $this->actingAs($admin);

        $response = $this->get(AppointmentResource::getUrl('create'));
        $response->assertSuccessful();
    }
}
