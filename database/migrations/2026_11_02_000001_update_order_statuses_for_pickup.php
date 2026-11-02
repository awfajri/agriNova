<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')->where('status', 'shipped')->update(['status' => 'ready_for_pickup']);
        DB::table('orders')->where('status', 'completed')->update(['status' => 'picked_up']);
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'ready_for_pickup')->update(['status' => 'shipped']);
        DB::table('orders')->where('status', 'picked_up')->update(['status' => 'completed']);
    }
};
