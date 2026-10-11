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
        Schema::create('emprunt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('abonne_id');
            $table->unsignedBigInteger('livre_id');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_retour')->nullable();
            $table->enum('statut', ['en cours', 'retard', 'retourné'])->default('en cours');
            $table->timestamps();

            // Foreign keys will be added in a separate migration after all tables are created
            // $table->foreign('abonne_id')->references('id')->on('abonne')->onDelete('cascade');
            // $table->foreign('livre_id')->references('id')->on('livres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprunt');
    }
};
