<?php 
require_once 'config.php'; 

// 1. Récupération des filtres (Recherche et Catégorie)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$cat_filter = isset($_GET['cat']) ? $_GET['cat'] : '';

// 2. Récupérer les catégories avec l'ordre spécifique
$cat_query = $pdo->query("SELECT DISTINCT categorie FROM plantes 
    ORDER BY FIELD(categorie, 'Fleurs & Décoratives', 'Grimpantes & Fruits', 'Conifères & Arbustes', 'Déco Extérieurs') ASC");
$categories = $cat_query->fetchAll(PDO::FETCH_COLUMN);

// 3. Requête SQL pour la liste des plantes
$sql = "SELECT * FROM plantes WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND nom LIKE :nom";
    $params['nom'] = "%$search%";
}
if (!empty($cat_filter)) {
    $sql .= " AND categorie = :cat";
    $params['cat'] = $cat_filter;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$plantes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BioData - Ma Serre Numérique</title>
    <link rel="stylesheet" href="style.css">
    <style>

    </style>
</head>
<body>

    <header>

        <div class="header-spacer"></div>
        <h1>🌿 Ma Serre Numérique</h1>
        <div class="top-nav">
            <button id="fav-filter-btn" class="btn-ui btn-fav-filter" onclick="toggleFavFilter()">
                Favoris ❤️ <span id="fav-count">0</span>
            </button>
            <a href="contact.php" class="btn-ui btn-contact">📩 Contact</a>
        </div>
    </header>

    <div class="search-box">
        <form action="index.php" method="GET">
            <input type="text" name="search" placeholder="Chercher une plante..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍</button>
        </form>
    </div>

    <div class="filter-bar">
        <a href="index.php" class="filter-link <?php echo $cat_filter == '' ? 'active' : ''; ?>">Tous</a>
        <?php foreach ($categories as $cat): ?>
            <?php if($cat): ?>
                <a href="index.php?cat=<?php echo urlencode($cat); ?>" class="filter-link <?php echo $cat_filter == $cat ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="container" id="plant-grid">
        <?php if (count($plantes) > 0): ?>
            <?php foreach ($plantes as $p): ?>
                <div class="plant-card" data-id="<?php echo $p['id']; ?>">
                    <div style="position: relative;">
                        <img src="<?php echo htmlspecialchars($p['image_path']); ?>" class="plant-img" alt="Plante">
                        <button class="fav-btn" onclick="toggleFavorite(<?php echo $p['id']; ?>)">❤</button>
                    </div>
                    <div class="plant-info">
                        <span style="font-size: 0.75em; color: #2d5a27; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px; opacity: 0.8;">
                            <?php echo htmlspecialchars($p['categorie']); ?>
                        </span>
                        <span class="plant-name"><?php echo htmlspecialchars($p['nom']); ?></span>
                        <a href="details.php?id=<?php echo $p['id']; ?>" style="background:#2d5a27; color:white; border:none; padding:8px 15px; border-radius:5px; text-decoration:none; display:inline-block; font-size: 0.8em; margin-top:10px;">Détails</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; grid-column: 1 / -1;">Désolé, aucune plante trouvée. 🌵</p>
        <?php endif; ?>
    </div>

    <footer style="margin-top: 50px; padding: 40px 20px; text-align: center; border-top: 1px solid #eee; color: #666;">
        <p>© 2025 | Gianfranco Aresi | BUT Science des Données</p>
        <p style="font-size: 0.8em;">Projet réalisé avec Passion 🌿</p>
    </footer>

<script src="script.js"></script>
</body>
</html>