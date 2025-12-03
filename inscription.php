<?php
require_once 'php/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = clean_input($_POST['nom']);
    $prenom = clean_input($_POST['prenom']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $specialite = clean_input($_POST['specialite']);
    $ville = clean_input($_POST['ville']);
    
    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Tous les champs obligatoires doivent être remplis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Cette adresse email est déjà utilisée.";
        } else {
            // Créer le compte
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("
                INSERT INTO users (nom, prenom, email, password, specialite, ville)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([$nom, $prenom, $email, $hashed_password, $specialite, $ville])) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                // Redirection après 2 secondes
                header("refresh:2;url=connexion.php");
            } else {
                $error = "Une erreur est survenue lors de la création du compte.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Data Analystes Guinée</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">DataAnalystes GN</a>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php" class="btn">Inscription</a></li>
            </ul>
        </div>
    </nav>

    <!-- Formulaire d'inscription -->
    <section class="section">
        <div class="container">
            <div class="form-container">
                <h2 class="text-center mb-4" style="font-size: 2rem; font-weight: 700;">Rejoignez la communauté</h2>
                <p class="text-center mb-4" style="color: var(--text-muted);">
                    Créez votre compte et commencez à partager vos projets
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" 
                               value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" class="form-control" 
                               value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="specialite">Spécialité</label>
                        <input type="text" id="specialite" name="specialite" class="form-control" 
                               placeholder="Ex: Data Analyst, Data Scientist, Business Analyst..."
                               value="<?php echo isset($_POST['specialite']) ? htmlspecialchars($_POST['specialite']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" class="form-control" 
                               placeholder="Ex: Conakry, Kankan, Labé..."
                               value="<?php echo isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               placeholder="Au moins 6 caractères" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe *</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Créer mon compte
                    </button>
                </form>

                <p class="text-center mt-3" style="color: var(--text-muted);">
                    Vous avez déjà un compte ? 
                    <a href="connexion.php" style="color: var(--primary-light); text-decoration: none;">
                        Connectez-vous
                    </a>
                </p>
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
