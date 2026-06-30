<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('homepage_sections')->where('section_key', 'testimonials')->exists();
        if (!$exists) {
            DB::table('homepage_sections')->insert([
                'section_key' => 'testimonials',
                'is_enabled'  => true,
                'sort_order'  => 6,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            // Shift instagram down to sort_order 7
            DB::table('homepage_sections')->where('section_key', 'instagram')->update(['sort_order' => 7]);
        }
    }

    public function down(): void
    {
        DB::table('homepage_sections')->where('section_key', 'testimonials')->delete();
        DB::table('homepage_sections')->where('section_key', 'instagram')->update(['sort_order' => 6]);
    }
};
