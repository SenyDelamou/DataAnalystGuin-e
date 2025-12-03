<?php
require_once 'php/config.php';

if (!is_logged_in()) {
    redirect('connexion.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = clean_input($_POST['titre']);
    $description = clean_input($_POST['description']);
    $technologies = clean_input($_POST['technologies']);
    
    if (empty($titre) || empty($description)) {
        $error = "Le titre et la description sont obligatoires.";
    } else {
        $fichier = null;
        $image_projet = null;
        
        // Upload du fichier
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
            $allowed_files = ['pdf', 'zip', 'rar', 'doc', 'docx', 'xlsx', 'csv', 'ipynb', 'py', 'r'];
            $file_ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_files)) {
                $fichier = uniqid() . '_' . $_FILES['fichier']['name'];
                move_uploaded_file($_FILES['fichier']['tmp_name'], 'uploads/' . $fichier);
            }
        }
        
        // Upload de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $image_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($image_ext, $allowed_images)) {
                $image_projet = uniqid() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_projet);
            }
        }
        
        // Insérer le projet
        $stmt = $conn->prepare("
            INSERT INTO projets (user_id, titre, description, technologies, fichier, image_projet)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$_SESSION['user_id'], $titre, $description, $technologies, $fichier, $image_projet])) {
            $success = "Projet ajouté avec succès !";
            header("refresh:2;url=projets.php");
        } else {
            $error = "Une erreur est survenue lors de l'ajout du projet.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Projet - Data Analystes Guinée</title>
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
                <h2 class="text-center mb-4" style="font-size: 2rem; font-weight: 700;">Partager un projet</h2>
                <p class="text-center mb-4" style="color: var(--text-muted);">
                    Partagez votre travail avec la communauté
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titre">Titre du projet *</label>
                        <input type="text" id="titre" name="titre" class="form-control" 
                               placeholder="Ex: Analyse des ventes de 2024" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Décrivez votre projet en détail..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="technologies">Technologies utilisées</label>
                        <input type="text" id="technologies" name="technologies" class="form-control" 
                               placeholder="Ex: Python, Pandas, Matplotlib, Power BI">
                        <small style="color: var(--text-muted); font-size: 0.85rem;">
                            Séparez les technologies par des virgules
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="image">Image du projet</label>
                        <input type="file" id="image" name="image" class="form-control" 
                               accept="image/*">
                        <small style="color: var(--text-muted); font-size: 0.85rem;">
                            Formats acceptés: JPG, PNG, GIF, WebP
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="fichier">Fichier du projet</label>
                        <input type="file" id="fichier" name="fichier" class="form-control">
                        <small style="color: var(--text-muted); font-size: 0.85rem;">
                            Formats acceptés: PDF, ZIP, CSV, Excel, Python, R, Jupyter Notebook
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Publier le projet
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
