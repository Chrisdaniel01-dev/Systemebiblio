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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type', 50); // create, update, delete, login, logout, etc.
            $table->string('model_type', 100)->nullable(); // App\Models\Abonne, App\Models\Livre, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable(); // Support IPv4 and IPv6
            $table->text('user_agent')->nullable();
            $table->json('old_values')->nullable(); // Store old data before update
            $table->json('new_values')->nullable(); // Store new data after update
            $table->string('route', 200)->nullable(); // The route that was accessed
            $table->string('method', 10)->nullable(); // HTTP method (GET, POST, PUT, DELETE)
            $table->string('status', 20)->default('success'); // success, error, warning
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
