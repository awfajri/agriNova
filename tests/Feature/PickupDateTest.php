<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickupDateTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::query()->create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function makeProduct(): Product
    {
        $category = Category::query()->create([
            'name' => 'Sayur',
            'slug' => 'sayur',
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Tomat Segar',
            'slug' => 'tomat-segar',
            'sku' => 'TM-01',
            'price' => 10000,
            'stock' => 10,
            'min_order_quantity' => 1,
            'is_preorder' => false,
            'is_active' => true,
        ]);
    }

    public function test_checkout_menyimpan_tanggal_dan_jam_ambil(): void
    {
        $product = $this->makeProduct();

        $date = now()->addDays(2)->toDateString();

        $response = $this->actingAs($this->makeUser())
            ->withSession(['cart' => [(string) $product->id => 2]])
            ->post(route('checkout.store'), [
                'customer_name' => 'Budi',
                'customer_whatsapp' => '081234567890',
                'pickup_date' => $date,
                'pickup_time' => '09.00',
                'notes' => 'Tes',
            ]);

        $order = Order::query()->firstOrFail();

        $response->assertRedirect(route('checkout.payment', $order->order_number));
        $this->assertSame('09.00', $order->pickup_time);
        $this->assertSame($date, $order->pickup_date->toDateString());
        $this->assertStringContainsString('09.00 WIB', $order->pickupScheduleLabel());
    }

    public function test_tanggal_ambil_di_luar_batas_ditolak(): void
    {
        $product = $this->makeProduct();

        $response = $this->actingAs($this->makeUser())
            ->withSession(['cart' => [(string) $product->id => 1]])
            ->post(route('checkout.store'), [
                'customer_name' => 'Budi',
                'customer_whatsapp' => '081234567890',
                'pickup_date' => now()->addDays(60)->toDateString(),
                'pickup_time' => '09.00',
            ]);

        $response->assertSessionHasErrors('pickup_date');
        $this->assertSame(0, Order::query()->count());
    }

    public function test_opsi_fleksibel_sudah_dihapus(): void
    {
        $this->assertArrayNotHasKey('fleksibel', Order::pickupTimeOptions());
    }

    public function test_halaman_checkout_menampilkan_kalender_dan_tanpa_fleksibel(): void
    {
        $product = $this->makeProduct();

        $response = $this->actingAs($this->makeUser())
            ->withSession(['cart' => [(string) $product->id => 1]])
            ->get(route('checkout.create'));

        $response->assertOk();
        $response->assertSee('Tanggal dan jam ambil');
        $response->assertSee('agr-cal-days', false);
        $response->assertSee('name="pickup_date"', false);
        $response->assertDontSee('Fleksibel');
    }
}
