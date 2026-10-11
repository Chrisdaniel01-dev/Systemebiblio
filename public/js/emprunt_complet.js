// Gestion des emprunts - JavaScript

// Données de démonstration (à remplacer par une vraie base de données)
let emprunts = JSON.parse(localStorage.getItem('emprunts')) || [];
let abonnes = JSON.parse(localStorage.getItem('abonnes')) || [];
let livres = JSON.parse(localStorage.getItem('livres')) || [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    chargerDonnees();
    initialiserFiltres();
    afficherEmprunts();
    initialiserFormulaires();
});

// Charger les données depuis le localStorage ou initialiser avec des données de démonstration
function chargerDonnees() {
    // Données de démonstration pour les abonnés
    if (abonnes.length === 0) {
        abonnes = [
            { id: 1, nom: 'Dupont', prenom: 'Jean', telephone: '0123456789', classe: 'Terminale A' },
            { id: 2, nom: 'Martin', prenom: 'Marie', telephone: '0987654321', classe: 'Première B' },
            { id: 3, nom: 'Bernard', prenom: 'Pierre', telephone: '0612345678', classe: 'Seconde C' }
        ];
        localStorage.setItem('abonnes', JSON.stringify(abonnes));
    }

    // Données de démonstration pour les livres
    if (livres.length === 0) {
        livres = [
            { id: 1, titre: 'Le Petit Prince', auteur: 'Antoine de Saint-Exupéry', categorie: 'Roman', statut: 'Disponible' },
            { id: 2, titre: '1984', auteur: 'George Orwell', categorie: 'Science-Fiction', statut: 'Disponible' },
            { id: 3, titre: 'Harry Potter', auteur: 'J.K. Rowling', categorie: 'Fantasy', statut: 'Disponible' },
            { id: 4, titre: 'Dune', auteur: 'Frank Herbert', categorie: 'Science-Fiction', statut: 'Disponible' }
        ];
        localStorage.setItem('livres', JSON.stringify(livres));
    }

    // Données de démonstration pour les emprunts
    if (emprunts.length === 0) {
        const aujourdhui = new Date();
        const dans15Jours = new Date();
        dans15Jours.setDate(aujourdhui.getDate() + 15);
        
        emprunts = [
            {
                id: 1,
                abonneId: 1,
                livreId: 1,
                dateEmprunt: aujourdhui.toISOString().split('T')[0],
                dateRetourPrevue: dans15Jours.toISOString().split('T')[0],
                dateRetourEffective: null,
                etatLivre: null,
                statut: 'actif'
            }
        ];
        localStorage.setItem('emprunts', JSON.stringify(emprunts));
    }
}

// Initialiser les filtres
function initialiserFiltres() {
    chargerAbonnesFiltre();
    chargerLivresFiltre();
    
    // Écouter les changements de filtres
    document.getElementById('abonneFilter').addEventListener('change', filtrerEmprunts);
    document.getElementById('livreFilter').addEventListener('change', filtrerEmprunts);
    document.getElementById('statusFilter').addEventListener('change', filtrerEmprunts);
    document.getElementById('searchInput').addEventListener('input', filtrerEmprunts);
}

// Charger les abonnés dans les filtres et formulaires
function chargerAbonnesFiltre() {
    const abonneFilter = document.getElementById('abonneFilter');
    const abonneSelect = document.getElementById('abonneSelect');
    
    // Vider les options existantes (garder la première option)
    while (abonneFilter.options.length > 1) abonneFilter.remove(1);
    while (abonneSelect.options.length > 1) abonneSelect.remove(1);
    
    abonnes.forEach(abonne => {
        const optionText = `${abonne.nom} ${abonne.prenom}`;
        
        // Pour le filtre
        const optionFilter = document.createElement('option');
        optionFilter.value = abonne.id;
        optionFilter.textContent = optionText;
        abonneFilter.appendChild(optionFilter);
        
        // Pour le formulaire
        const optionSelect = document.createElement('option');
        optionSelect.value = abonne.id;
        optionSelect.textContent = optionText;
        abonneSelect.appendChild(optionSelect);
    });
}

// Charger les livres dans les filtres et formulaires
function chargerLivresFiltre() {
    const livreFilter = document.getElementById('livreFilter');
    const livreSelect = document.getElementById('livreSelect');
    
    // Vider les options existantes (garder la première option)
    while (livreFilter.options.length > 1) livreFilter.remove(1);
    while (livreSelect.options.length > 1) livreSelect.remove(1);
    
    livres.forEach(livre => {
        if (livre.statut === 'Disponible') {
            // Pour le filtre
            const optionFilter = document.createElement('option');
            optionFilter.value = livre.id;
            optionFilter.textContent = livre.titre;
            livreFilter.appendChild(optionFilter);
            
            // Pour le formulaire (seulement les livres disponibles)
            const optionSelect = document.createElement('option');
            optionSelect.value = livre.id;
            optionSelect.textContent = livre.titre;
            livreSelect.appendChild(optionSelect);
        }
    });
}

// Afficher les emprunts dans le tableau
function afficherEmprunts(empruntsFiltres = emprunts) {
    const tbody = document.getElementById('empruntsTableBody');
    tbody.innerHTML = '';
    
    empruntsFiltres.forEach(emprunt => {
        const abonne = abonnes.find(a => a.id === emprunt.abonneId);
        const livre = livres.find(l => l.id === emprunt.livreId);
        
        if (!abonne || !livre) return;
        
        const tr = document.createElement('tr');
        
        // Déterminer le statut
        let statut = emprunt.statut;
        let statutClass = 'status-' + statut;
        let statutText = statut.charAt(0).toUpperCase() + statut.slice(1);
        
        if (statut === 'actif') {
            const aujourdhui = new Date();
            const dateRetour = new Date(emprunt.dateRetourPrevue);
            if (aujourdhui > dateRetour) {
                statut = 'retard';
                statutClass = 'status-retard';
                statutText = 'En retard';
            }
        }
        
        tr.innerHTML = `
            <td>${abonne.nom} ${abonne.prenom}</td>
            <td>${livre.titre}</td>
            <td>${formaterDate(emprunt.dateEmprunt)}</td>
            <td class="${statut === 'retard' ? 'date-retard' : ''}">${formaterDate(emprunt.dateRetourPrevue)}</td>
            <td>${emprunt.dateRetourEffective ? formaterDate(emprunt.dateRetourEffective) : '-'}</td>
            <td><span class="status-badge ${statutClass}">${statutText}</span></td>
            <td>
                ${emprunt.statut === 'actif' ? 
                    `<button class="action-btn retour-btn" onclick="preparerRetour(${emprunt.id})">Retour</button>` : 
                    `<button class="action-btn details-btn" onclick="afficherDetails(${emprunt.id})">Détails</button>`
                }
                <button class="action-btn delete-btn" onclick="supprimerEmprunt(${emprunt.id})">Supprimer</button>
            </td>
        `;
        
        tbody.appendChild(tr);
    });
}

// Filtrer les emprunts
function filtrerEmprunts() {
    const abonneId = document.getElementById('abonneFilter').value;
    const livreId = document.getElementById('livreFilter').value;
    const statut = document.getElementById('statusFilter').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    
    let empruntsFiltres = emprunts.filter(emprunt => {
        const abonne = abonnes.find(a => a.id === emprunt.abonneId);
        const livre = livres.find(l => l.id === emprunt.livreId);
        
        if (!abonne || !livre) return false;
        
        // Filtre par abonné
        if (abonneId && emprunt.abonneId != abonneId) return false;
        
        // Filtre par livre
        if (livreId && emprunt.livreId != livreId) return false;
        
        // Filtre par statut
        let currentStatut = emprunt.statut;
        if (currentStatut === 'actif') {
            const aujourdhui =
