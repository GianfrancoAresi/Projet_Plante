<?php
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM plantes WHERE id = :id");
$stmt->execute(['id' => $id]);
$p = $stmt->fetch();

if (!$p) { header('Location: index.php'); exit; }

// Fonction pour afficher les gouttes d'eau
function afficherEau($niveau) {
    $gouttes = str_repeat('💧', $niveau);
    $grises = str_repeat('<span style="opacity:0.2">💧</span>', 3 - $niveau);
    return $gouttes . $grises;
}

// Fonction pour l'icône soleil
function afficherSoleil($type) {
    if (stripos($type, 'Plein') !== false) return '☀️ Plein Soleil';
    if (stripos($type, 'Mi') !== false) return '⛅ Mi-ombre';
    return '☁️ Ombre';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($p['nom']); ?> - BioData</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #f4f7f4; margin: 0; padding-bottom: 80px; }
        
        .details-wrapper { max-width: 950px; margin: 40px auto; display: flex; gap: 40px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .details-img { width: 450px; border-radius: 15px; object-fit: cover; height: 500px; }
        
        /* Zone Besoins */
        .care-card { background: #f9fbf9; padding: 15px; border-radius: 12px; margin-top: 20px; display: flex; gap: 20px; align-items: center; border: 1px solid #eef2ee; }
        .care-item { flex: 1; text-align: center; }
        .care-label { display: block; font-size: 0.75em; color: #666; margin-bottom: 5px; text-transform: uppercase; font-weight: bold; }
        .care-value { font-weight: bold; color: #2d5a27; }

        /* Zone Calendrier */
        .calendar-box { margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .cal-item { background: #fff; border: 1px dashed #2d5a27; padding: 12px; border-radius: 10px; display: flex; align-items: center; gap: 15px; }
        .cal-icon { font-size: 1.8em; }
        .cal-text strong { display: block; color: #2d5a27; font-size: 0.8em; text-transform: uppercase; margin-bottom: 2px; }
        .cal-text span { font-size: 0.95em; color: #444; }

        /* Légende fixe */
        .footer-legend {
        position: fixed;
        bottom: 0; left: 0; width: 100%;
        background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid #ddd;
        padding: 10px 0;
        font-size: 0.8em; /* Plus petit pour gagner de la place */
        color: #444;
        text-align: center;
        backdrop-filter: blur(10px);
        z-index: 1000;
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap; /* S'adapte sur mobile */
}
        .legend-group { margin: 0 20px; display: inline-block; }

        .back-link { margin-top: 30px; display: inline-block; color: #2d5a27; text-decoration: none; font-weight: bold; border-bottom: 2px solid transparent; transition: 0.3s; }
        .back-link:hover { border-bottom: 2px solid #2d5a27; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .legend-item strong { color: #2d5a27; }
    </style>
</head>
<body>

<div class="details-wrapper">
    <img src="<?php echo htmlspecialchars($p['image_path']); ?>" class="details-img">
    
    <div class="details-content">
        <span style="color:#2d5a27; font-weight:bold; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px;">
            <?php echo htmlspecialchars($p['categorie'] ?? 'Plante'); ?>
        </span>
        <h1 style="margin:10px 0; font-size: 2.2em;"><?php echo htmlspecialchars($p['nom']); ?></h1>
        
        <p style="color:#666; line-height:1.6; margin-bottom: 25px;">
            Découvrez les besoins et le cycle de vie de cette plante pour assurer son épanouissement dans votre jardin.
        </p>

        <div class="care-card">
            <div class="care-item">
                <span class="care-label">Exposition</span>
                <span class="care-value"><?php echo afficherSoleil($p['soleil'] ?? 'Plein soleil'); ?></span>
            </div>
            <div class="care-item" style="border-left: 1px solid #ddd;">
                <span class="care-label">Arrosage</span>
                <span class="care-value"><?php echo afficherEau($p['eau'] ?? 2); ?></span>
            </div>
        </div>

        <div class="calendar-box">
            <div class="cal-item">
                <span class="cal-icon">🪴</span>
                <div class="cal-text">
                    <strong>Plantation</strong>
                    <span><?php echo htmlspecialchars($p['plantation'] ?? 'Automne / Printemps'); ?></span>
                </div>
            </div>
            <div class="cal-item">
                <span class="cal-icon">
                    <?php 
                        // Ton code pour changer l'icône selon le texte de floraison
                        echo (stripos($p['categorie'], 'Conifère') !== false || stripos($p['floraison'], 'Persistant') !== false) ? '🌲' : '🌸'; 
                    ?>
                </span>
                <div class="cal-text">
                    <strong>Floraison / Intérêt</strong>
                    <span><?php echo htmlspecialchars($p['floraison'] ?? 'Saisonnière'); ?></span>
                </div>
            </div>
        </div>

        <a href="index.php" class="back-link">← Retour au catalogue</a>
    </div>
</div>

<div class="footer-legend">
    <div class="legend-group">
        <strong>☀️</strong> +6h/j | <strong>⛅</strong> 4-6h/j | <strong>☁️</strong> ombre
    </div>
    <div class="legend-group" style="border-left: 1px solid #ddd; padding-left: 20px; border-right: 1px solid #ddd; padding-right: 20px;">
        <strong>💧</strong> Laisser sécher totalement | 
        <strong>💧💧</strong> Sécher en surface | 
        <strong>💧💧💧</strong> Toujours humide
    </div>
    <div class="legend-group">
        <strong>🌸</strong> Fleurs | <strong>🌲</strong> Persistant
    </div>
</div>
<script src="script.js"></script>
</body>
</html>