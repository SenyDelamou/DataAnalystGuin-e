<?php
require_once 'php/config.php';

if (!is_logged_in()) {
    redirect('connexion.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = clean_input($_POST['titre']);
    $contenu = clean_input($_POST['contenu']);
    $categorie = clean_input($_POST['categorie']);
    
    if (empty($titre) || empty($contenu)) {
        $error = "Le titre et le contenu sont obligatoires.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO experiences (user_id, titre, contenu, categorie)
            VALUES (?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$_SESSION['user_id'], $titre, $contenu, $categorie])) {
            $success = "Expérience partagée avec succès !";
            header("refresh:2;url=experiences.php");
        } else {
            $error = "Une erreur est survenue lors du partage de l'expérience.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Expérience - Data Analystes Guinée</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">DataAnalystes GN</a>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="projets.php">Projets</a></li>
                <li><a href="experiences.php">Expériences</a></li>
                <li><a href="membres.php">Membres</a></li>
                <li><a href="profil.php">Mon Profil</a></li>
                <li><a href="php/logout.php" class="btn">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Formulaire -->
    <section class="section">
        <div class="container">
            <div class="form-container" style="max-width: 700px;">
                <h2 class="text-center mb-4" style="font-size: 2rem; font-weight: 700;">Partager une expérience</h2>
                <p class="text-center mb-4" style="color: var(--text-muted);">
                    Partagez vos connaissances et aidez la communauté
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="titre">Titre *</label>
                        <input type="text" id="titre" name="titre" class="form-control" 
                               placeholder="Ex: Comment j'ai optimisé mes requêtes SQL" required>
                    </div>

                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie" class="form-control">
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="Tutoriel">Tutoriel</option>
                            <option value="Astuce">Astuce</option>
                            <option value="Retour d'expérience">Retour d'expérience</option>
                            <option value="Conseil">Conseil</option>
                            <option value="Problème résolu">Problème résolu</option>
                            <option value="Ressources">Ressources</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu *</label>
                        <textarea id="contenu" name="contenu" class="form-control" 
                                  style="min-height: 250px;"
                                  placeholder="Partagez votre expérience en détail..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Publier l'expérience
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-bottom">
            <p>&copy; 2025 Data Analystes Guinée. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
