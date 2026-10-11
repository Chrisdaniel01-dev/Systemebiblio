<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class EnsureCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Science',
                'description' => 'Livres scientifiques, recherche, découvertes'
            ],
            [
                'nom' => 'Histoire',
                'description' => 'Histoire générale, événements historiques, biographies'
            ],
            [
                'nom' => 'Geographie',
                'description' => 'Géographie, atlas, voyages, exploration'
            ],
            [
                'nom' => 'Roman',
                'description' => 'Romans, fiction, littérature contemporaine'
            ],
            [
                'nom' => 'Conte',
                'description' => 'Contes, fables, histoires pour enfants'
            ],
            [
                'nom' => 'Encyclopédie',
                'description' => 'Ouvrages de référence, encyclopédies, dictionnaires'
            ]
        ];

        foreach ($categories as $category) {
            // Vérifier si la catégorie existe déjà
            $existingCategory = Categorie::where('nom', $category['nom'])->first();

            if (!$existingCategory) {
                Categorie::create($category);
                $this->command->info("Catégorie '{$category['nom']}' créée avec succès.");
            } else {
                $this->command->info("Catégorie '{$category['nom']}' existe déjà.");
            }
        }

        $totalCategories = Categorie::count();
        $this->command->info("Total des catégories dans la base de données: {$totalCategories}");
    }
}
