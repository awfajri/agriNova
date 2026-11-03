<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->string('reference_number', 24)->nullable()->unique()->after('id');
        });

        Schema::table('eduwisata_reservations', function (Blueprint $table) {
            $table->string('reference_number', 24)->nullable()->unique()->after('id');
        });

        DB::table('training_registrations')
            ->whereNull('reference_number')
            ->orderBy('id')
            ->eachById(function (object $registration): void {
                DB::table('training_registrations')
                    ->where('id', $registration->id)
                    ->update(['reference_number' => $this->reference('TRN')]);
            });

        DB::table('eduwisata_reservations')
            ->whereNull('reference_number')
            ->orderBy('id')
            ->eachById(function (object $reservation): void {
                DB::table('eduwisata_reservations')
                    ->where('id', $reservation->id)
                    ->update(['reference_number' => $this->reference('EDU')]);
            });

        foreach (['training_registrations', 'eduwisata_reservations'] as $table) {
            DB::table($table)
                ->select(['id', 'whatsapp'])
                ->orderBy('id')
                ->eachById(function (object $record) use ($table): void {
                    $normalized = preg_replace('/\D+/', '', (string) $record->whatsapp);

                    if ($normalized !== '' && $normalized !== $record->whatsapp) {
                        DB::table($table)
                            ->where('id', $record->id)
                            ->update(['whatsapp' => $normalized]);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::table('eduwisata_reservations', function (Blueprint $table) {
            $table->dropUnique(['reference_number']);
            $table->dropColumn('reference_number');
        });

        Schema::table('training_registrations', function (Blueprint $table) {
            $table->dropUnique(['reference_number']);
            $table->dropColumn('reference_number');
        });
    }

    private function reference(string $prefix): string
    {
        return $prefix . '-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
    }
};
