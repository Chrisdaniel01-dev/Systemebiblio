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
        // Add foreign key to livres table
        Schema::table('livres', function (Blueprint $table) {
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Add foreign keys to emprunt table
        Schema::table('emprunt', function (Blueprint $table) {
            $table->foreign('abonne_id')->references('id')->on('abonne')->onDelete('cascade');
            $table->foreign('livre_id')->references('id')->on('livres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys from livres table
        Schema::table('livres', function (Blueprint $table) {
            $table->dropForeign(['categorie_id']);
        });

        // Drop foreign keys from emprunt table
        Schema::table('emprunt', function (Blueprint $table) {
            $table->dropForeign(['abonne_id']);
            $table->dropForeign(['livre_id']);
        });
    }
};
