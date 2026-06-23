<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');                              // created, updated, deleted, login, logout
            $table->string('auditable_type')->nullable();         // Need, Refugee, User
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable(); // staff user id
            $table->unsignedBigInteger('refugee_actor_id')->nullable(); // when refugee performs action
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
