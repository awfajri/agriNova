<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBannerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_view_banner_management(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.preorders.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Multi-Banner Slider');
    }

    public function test_admin_can_create_a_new_banner(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $file = UploadedFile::fake()->image('promo-banner.jpg', 1200, 500);

        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'banner_image_file' => $file,
            'title' => 'Promo Spesial Musim Panen',
            'eyebrow' => 'Penawaran Spesial',
            'cta_text' => 'Beli Sekarang',
            'cta_url' => '/catalog',
            'order' => 5,
            'is_active' => '1',
            'is_countdown' => '1',
            'countdown_end_at' => '2026-12-31 23:59:00',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('banners', [
            'title' => 'Promo Spesial Musim Panen',
            'eyebrow' => 'Penawaran Spesial',
            'cta_text' => 'Beli Sekarang',
            'order' => 5,
            'is_active' => true,
            'is_countdown' => true,
        ]);
    }

    public function test_admin_can_update_a_banner(): void
    {
        $admin = $this->createAdmin();

        $banner = Banner::create([
            'title' => 'Banner Lama',
            'image_path' => 'images/banner-1.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.banners.update', $banner), [
            'title' => 'Banner Baru Diperbarui',
            'banner_image_path' => 'images/banner-2.jpg',
            'order' => 2,
            'is_active' => '1',
        ]);

        $response->assertSessionHas('success');

        $banner->refresh();
        $this->assertEquals('Banner Baru Diperbarui', $banner->title);
        $this->assertEquals('images/banner-2.jpg', $banner->image_path);
        $this->assertEquals(2, $banner->order);
    }

    public function test_admin_can_toggle_banner_status(): void
    {
        $admin = $this->createAdmin();

        $banner = Banner::create([
            'title' => 'Banner Uji Coba',
            'image_path' => 'images/banner-1.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.banners.toggle', $banner));

        $response->assertSessionHas('success');
        $banner->refresh();
        $this->assertFalse($banner->is_active);

        $this->actingAs($admin)->patch(route('admin.banners.toggle', $banner));
        $banner->refresh();
        $this->assertTrue($banner->is_active);
    }

    public function test_admin_can_delete_a_banner(): void
    {
        $admin = $this->createAdmin();

        $banner = Banner::create([
            'title' => 'Banner Akan Dihapus',
            'image_path' => 'images/banner-3.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.banners.destroy', $banner));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
    }

    public function test_public_catalog_displays_active_banners(): void
    {
        Banner::create([
            'title' => 'Slide Banner Tokopedia 1',
            'image_path' => 'images/banner-1.jpg',
            'is_active' => true,
            'order' => 1,
        ]);

        Banner::create([
            'title' => 'Slide Banner Tokopedia 2',
            'image_path' => 'images/banner-2.jpg',
            'is_active' => true,
            'order' => 2,
        ]);

        $response = $this->get(route('catalog'));

        $response->assertStatus(200);
        $response->assertSee('Slide Banner Tokopedia 1');
        $response->assertSee('Slide Banner Tokopedia 2');
        $response->assertSee('data-catalog-slider', false);
    }
}
