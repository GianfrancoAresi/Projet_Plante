<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - BioData</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; 
        }
        .contact-form button {
            background: #2d5a27;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
        }
        .contact-form button:hover {
            background: #24491f;
        }
    </style>
</head>
<body>

    <h1 style="text-align:center;">📩 Nous contacter</h1>

    <div class="contact-container">
        <?php
        // Petit message de confirmation si le formulaire est envoyé
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = htmlspecialchars($_POST['nom']);
            echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px;'>
                    Merci $nom ! Votre message a bien été reçu (simulation).
                  </div>";
        }
        ?>

        <form action="contact.php" method="POST" class="contact-form">
            <label>Votre Nom</label>
            <input type="text" name="nom" placeholder="Ex: Jean Dupont" required>

            <label>Votre Email</label>
            <input type="email" name="email" placeholder="Ex: jean@email.com" required>

            <label>Sujet</label>
            <input type="text" name="sujet" placeholder="Ex: Question sur un Rosier">

            <label>Message</label>
            <textarea name="message" rows="5" placeholder="Votre message ici..." required></textarea>

            <button type="submit">Envoyer le message</button>
        </form>

        <p style="text-align:center; margin-top:20px;">
            <a href="index.php" style="color:#666; text-decoration:none;">← Retour au catalogue</a>
        </p>
    </div>

</body>
</html>