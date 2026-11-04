<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('registration.complete'))->assertRedirect(route('auth.login'));
    }

    public function test_user_without_completed_profile_is_sent_to_registration_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertRedirect(route('registration.complete'));
    }

    public function test_completed_user_can_open_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '081234567890',
            'profile_completed_at' => now(),
        ]);

        $this->actingAs($user)->get(route('profile.show'))->assertOk();
    }

    public function test_page_shows_both_steps(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('registration.complete'))
            ->assertOk()
            ->assertSee('Lengkapi pendaftaran')
            ->assertSee('Privasi dan Ketentuan')
            ->assertSee('action="' . route('registration.complete.store') . '"', false);
    }

    public function test_invalid_name_keeps_user_on_first_step(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('registration.complete'))
            ->post(route('registration.complete.store'), [
                'nama' => '',
                'whatsapp' => '081234567890',
                'setuju' => '1',
            ])
            ->assertSessionHasErrors('nama');

        $this->assertNull($user->fresh()->profile_completed_at);
    }

    public function test_invalid_whatsapp_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('registration.complete.store'), [
                'nama' => 'Budi Santoso',
                'whatsapp' => '12345',
                'setuju' => '1',
            ])
            ->assertSessionHasErrors('whatsapp');
    }

    public function test_missing_agreement_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('registration.complete.store'), [
                'nama' => 'Budi Santoso',
                'whatsapp' => '081234567890',
            ])
            ->assertSessionHasErrors('setuju');
    }

    public function test_valid_submission_completes_registration(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('registration.complete.store'), [
            'nama' => 'Budi Santoso',
            'whatsapp' => '+62 812-3456-7890',
            'promo' => '1',
            'setuju' => '1',
        ])->assertRedirect(route('profile.show'));

        $user->refresh();

        $this->assertSame('Budi Santoso', $user->name);
        $this->assertSame('+6281234567890', $user->phone);
        $this->assertTrue($user->promo_opt_in);
        $this->assertNotNull($user->terms_accepted_at);
        $this->assertTrue($user->hasCompletedRegistration());
    }

    public function test_completed_user_is_redirected_away_from_registration_page(): void
    {
        $user = User::factory()->create([
            'phone' => '081234567890',
            'profile_completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('registration.complete'))
            ->assertRedirect(route('profile.show'));
    }
}