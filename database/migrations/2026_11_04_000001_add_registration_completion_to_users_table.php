<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('profile_completed_at')->nullable()->after('address');
            $table->timestamp('terms_accepted_at')->nullable()->after('profile_completed_at');
            $table->boolean('promo_opt_in')->default(false)->after('terms_accepted_at');
        });

        // Pelanggan lama yang sudah punya nomor WhatsApp tidak dipaksa mengisi ulang.
        DB::table('users')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->update(['profile_completed_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_completed_at', 'terms_accepted_at', 'promo_opt_in']);
        });
    }
};