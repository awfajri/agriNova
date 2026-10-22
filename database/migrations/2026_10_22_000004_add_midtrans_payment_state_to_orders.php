<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('payment_reference');
            $table->timestamp('payment_expires_at')->nullable()->after('payment_token');
            $table->timestamp('stock_released_at')->nullable()->after('payment_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_token',
                'payment_expires_at',
                'stock_released_at',
            ]);
        });
    }
};
