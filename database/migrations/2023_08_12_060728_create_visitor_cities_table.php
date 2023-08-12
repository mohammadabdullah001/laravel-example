<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitor_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_url_id')
                ->constrained('short_urls')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('city');
            $table->date('visit_at')->index();
            $table->integer('total_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_cities');
    }
};
