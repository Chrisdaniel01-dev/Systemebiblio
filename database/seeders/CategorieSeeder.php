<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Science',
            'Histoire',
            'Geographie',
            'Roman',
            'Conte',
            'Encyclopédie'
        ];

        foreach ($categories as $category) {
            Categorie::create([
                'nom' => $category
            ]);
        }
    }
}
