<?php
require_once 'php/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_photo'] = $user['photo_profil'];
            
            redirect('index.php');
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Data Analystes Guinée</title>
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

    <!-- Formulaire de connexion -->
    <section class="section">
        <div class="container">
            <div class="form-container">
                <h2 class="text-center mb-4" style="font-size: 2rem; font-weight: 700;">Bon retour !</h2>
                <p class="text-center mb-4" style="color: var(--text-muted);">
                    Connectez-vous pour accéder à votre compte
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Se connecter
                    </button>
                </form>

                <p class="text-center mt-3" style="color: var(--text-muted);">
                    Pas encore de compte ? 
                    <a href="inscription.php" style="color: var(--primary-light); text-decoration: none;">
                        Inscrivez-vous
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
