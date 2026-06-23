<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refugee_id')->constrained('refugees')->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict');
            $table->string('category'); // food, shelter, healthcare, education, protection
            $table->text('description');
            $table->unsignedTinyInteger('urgency_level'); // 1–5
            $table->boolean('has_disability')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('has_critical_health')->default(false);
            $table->unsignedInteger('family_size')->default(1);
            $table->decimal('priority_score', 8, 2)->default(0);
            $table->string('status')->default('pending'); // pending, in_progress, resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needs');
    }
};
