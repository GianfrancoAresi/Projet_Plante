document.addEventListener('DOMContentLoaded', () => {
    // --- INITIALISATION DES FAVORIS ---
    // On restaure l'état des cœurs et le compteur dès le chargement
    updateFavUI();
});

/**
 * Gère le clic sur le cœur d'une plante
 * Appelé par onclick="toggleFavorite(<?php echo $p['id']; ?>)" dans ton HTML
 */
function toggleFavorite(planteId) {
    const key = 'fav_' + planteId;
    const isFav = localStorage.getItem(key) === 'true';
    
    // Inversion de l'état dans le stockage local
    localStorage.setItem(key, !isFav);

    // Mise à jour visuelle immédiate (cœurs + compteur)
    updateFavUI();
}

/**
 * Met à jour tous les cœurs et le compteur global dans le header
 */
function updateFavUI() {
    let count = 0;
    
    // On parcourt toutes les cartes de plantes présentes dans la grille
    document.querySelectorAll('.plant-card').forEach(card => {
        const id = card.getAttribute('data-id');
        const btn = card.querySelector('.fav-btn');
        const isFav = localStorage.getItem('fav_' + id) === 'true';

        if (btn) {
            if (isFav) {
                btn.classList.add('active');
                count++;
            } else {
                btn.classList.remove('active');
            }
        }
    });

    // Mise à jour du chiffre dans le bouton "Favoris ❤️" du header
    const countDisplay = document.getElementById('fav-count');
    if (countDisplay) {
        countDisplay.innerText = count;
    }
}

/**
 * Filtre l'affichage de la grille pour ne montrer que les favoris
 * Appelé par onclick="toggleFavFilter()" dans ton header
 */
function toggleFavFilter() {
    const btnFilter = document.getElementById('fav-filter-btn');
    if (btnFilter) {
        btnFilter.classList.toggle('active-filter');
        const showOnlyFavs = btnFilter.classList.contains('active-filter');

        document.querySelectorAll('.plant-card').forEach(card => {
            const id = card.getAttribute('data-id');
            const isFav = localStorage.getItem('fav_' + id) === 'true';

            if (showOnlyFavs) {
                // Si le filtre est actif, on cache ce qui n'est pas favori
                card.style.display = isFav ? 'block' : 'none';
            } else {
                // Sinon, on affiche tout
                card.style.display = 'block';
            }
        });
    }
}