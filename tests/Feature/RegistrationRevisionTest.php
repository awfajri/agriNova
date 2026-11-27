<?php

namespace Tests\Feature;

use App\Models\EduwisataReservation;
use App\Models\TrainingClass;
use App\Models\TrainingRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationRevisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_accepts_training_number_in_08_format(): void
    {
        $registration = TrainingRegistration::query()->create([
            'reference_number' => 'TRN-261127-TEST01',
            'class_title' => 'Pelatihan Hidroponik',
            'name' => 'Nadia',
            'whatsapp' => '628123456789',
            'email' => 'nadia@example.com',
            'participants' => 2,
            'preferred_date' => '2026-12-10',
        ]);

        $this->post(route('registrations.lookup'), [
            'reference_number' => strtolower($registration->reference_number),
            'whatsapp' => '08123456789',
        ])->assertRedirect(route('registrations.show', [
            'pelatihan',
            $registration->reference_number,
        ]));
    }

    public function test_tracking_accepts_eduwisata_number_in_62_format(): void
    {
        $reservation = EduwisataReservation::query()->create([
            'reference_number' => 'EDU-261127-TEST02',
            'contact_name' => 'Rina',
            'group_name' => 'SDN Satu',
            'package_name' => 'Paket A (Basic)',
            'whatsapp' => '628123456789',
            'visit_date' => '2026-12-10',
            'session' => 'Sesi Pagi – 08.00 WIB',
            'participants' => 20,
        ]);

        $this->post(route('registrations.lookup'), [
            'reference_number' => $reservation->reference_number,
            'whatsapp' => '62 812-3456-789',
        ])->assertRedirect(route('registrations.show', [
            'eduwisata',
            $reservation->reference_number,
        ]));
    }

    public function test_tracking_rejects_wrong_phone_number(): void
    {
        EduwisataReservation::query()->create([
            'reference_number' => 'EDU-261127-TEST03',
            'contact_name' => 'Rina',
            'group_name' => 'SDN Satu',
            'package_name' => 'Paket A (Basic)',
            'whatsapp' => '628123456789',
            'visit_date' => '2026-12-10',
            'session' => 'Sesi Pagi – 08.00 WIB',
            'participants' => 20,
        ]);

        $this->from(route('registrations.track'))
            ->post(route('registrations.lookup'), [
                'reference_number' => 'EDU-261127-TEST03',
                'whatsapp' => '08199999999',
            ])
            ->assertRedirect(route('registrations.track'))
            ->assertSessionHasErrors('reference_number');
    }

    public function test_training_confirmation_displays_selected_date(): void
    {
        $class = TrainingClass::query()->create([
            'slug' => 'pelatihan-hidroponik',
            'category' => 'Hidroponik',
            'title' => 'Pelatihan Hidroponik',
            'price' => 100000,
            'duration' => '1 hari',
            'schedule' => 'Fleksibel',
            'capacity' => 20,
            'status' => 'Tersedia',
            'is_available' => true,
        ]);

        $registration = TrainingRegistration::query()->create([
            'training_class_id' => $class->id,
            'reference_number' => 'TRN-261127-TEST04',
            'class_title' => $class->title,
            'name' => 'Nadia',
            'whatsapp' => '628123456789',
            'email' => 'nadia@example.com',
            'participants' => 2,
            'preferred_date' => '2026-12-10',
        ]);

        $this->get(route('registrations.show', ['pelatihan', $registration->reference_number]))
            ->assertOk()
            ->assertSee('Tanggal pelatihan')
            ->assertSee('10 Des 2026');
    }

    public function test_public_pages_include_revision_navigation_and_session_options(): void
    {
        $this->get(route('eduwisata.create', 'paket-a-basic'))
            ->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('Pelatihan budidaya')
            ->assertSee('Pre-order panen')
            ->assertSee('Eduwisata');
    }

    public function test_eduwisata_reservation_form_contains_four_session_options(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get(route('eduwisata.create', 'paket-a-basic'));

        $response->assertOk()
            ->assertSee('07.30 WIB')
            ->assertSee('08.00 WIB')
            ->assertSee('08.30 WIB')
            ->assertSee('15.30 WIB');
    }

    public function test_admin_layout_contains_dashboard_fab_outside_dashboard(): void
    {
        $admin = \App\Models\User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.eduwisata.index'))
            ->assertOk()
            ->assertSee('Kembali ke dashboard')
            ->assertSee('Dashboard');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('class="admin-fab"', false);
    }
}
