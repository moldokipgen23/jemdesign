<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // trending, new_arrivals, best_selling, category, collection, manual, testimonials
            $table->string('display_style')->default('grid'); // grid, carousel
            $table->unsignedSmallInteger('items_per_row')->default(3);
            $table->unsignedBigInteger('filter_value')->nullable(); // category_id or collection_id
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_sections');
    }
};
