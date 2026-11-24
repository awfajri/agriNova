<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReviewReplyTest extends TestCase
{
    use RefreshDatabase;

    private function createReview(): array
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $category = Category::create([
            'name' => 'Sayuran',
            'slug' => 'sayuran',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Melon Hidroponik',
            'slug' => 'melon-hidroponik',
            'price' => 35000,
            'stock' => 10,
            'min_order' => 1,
            'unit' => 'kg',
            'status' => 'ready',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'user_id' => $customer->id,
            'total' => 35000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'customer_name' => $customer->name,
            'customer_whatsapp' => '08123456789',
        ]);

        $review = ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Melonnya sangat manis dan segar!',
            'is_approved' => true,
        ]);

        return [$admin, $customer, $product, $review];
    }

    public function test_admin_can_reply_to_a_customer_review(): void
    {
        [$admin, $customer, $product, $review] = $this->createReview();

        $response = $this->actingAs($admin)
            ->post(route('admin.reviews.reply', $review), [
                'admin_reply' => 'Terima kasih banyak atas pesanannya kak!',
            ]);

        $response->assertSessionHas('success');

        $review->refresh();
        $this->assertEquals('Terima kasih banyak atas pesanannya kak!', $review->admin_reply);
        $this->assertNotNull($review->admin_reply_at);
    }

    public function test_admin_can_delete_reply_from_a_review(): void
    {
        [$admin, $customer, $product, $review] = $this->createReview();

        $review->update([
            'admin_reply' => 'Balasan awal',
            'admin_reply_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.reviews.reply.delete', $review));

        $response->assertSessionHas('success');

        $review->refresh();
        $this->assertNull($review->admin_reply);
        $this->assertNull($review->admin_reply_at);
    }

    public function test_non_admin_cannot_reply_to_review(): void
    {
        [$admin, $customer, $product, $review] = $this->createReview();

        $response = $this->actingAs($customer)
            ->post(route('admin.reviews.reply', $review), [
                'admin_reply' => 'Mencoba reply sebagai customer',
            ]);

        $response->assertForbidden();
    }

    public function test_reply_is_displayed_on_product_page(): void
    {
        [$admin, $customer, $product, $review] = $this->createReview();

        $review->update([
            'admin_reply' => 'Terima kasih atas ulasan positifnya!',
            'admin_reply_at' => now(),
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertSee('Terima kasih atas ulasan positifnya!');
        $response->assertSee('Balasan Toko Agrinova');
    }
}
