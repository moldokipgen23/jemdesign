<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_section_id')->constrained()->cascadeOnDelete();
            $table->morphs('itemable'); // product_id or testimonial_id
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_section_items');
    }
};
