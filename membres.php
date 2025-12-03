<?php
require_once 'php/config.php';

// Récupérer tous les membres
$stmt = $conn->prepare("
    SELECT u.*,
           (SELECT COUNT(*) FROM projets WHERE user_id = u.id) as projets_count,
           (SELECT COUNT(*) FROM experiences WHERE user_id = u.id) as experiences_count,
           (SELECT COUNT(*) FROM recommandations WHERE user_id_to = u.id) as recommandations_count
    FROM users u
    ORDER BY u.date_inscription DESC
");
$stmt->execute();
$membres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membres - Data Analystes Guinée</title>
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
                <?php if (is_logged_in()): ?>
                    <li><a href="profil.php">Mon Profil</a></li>
                    <li><a href="php/logout.php" class="btn">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php" class="btn">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Header -->
    <section class="hero" style="padding: 4rem 2rem;">
        <div class="container">
            <h1 style="font-size: 2.5rem;">Membres de la communauté</h1>
            <p>Connectez-vous avec les data analystes de Guinée</p>
        </div>
    </section>

    <!-- Membres -->
    <section class="section">
        <div class="container">
            <div class="cards-grid">
                <?php if (empty($membres)): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">Aucun membre pour le moment</h3>
                        <p style="color: var(--text-muted);">Soyez le premier à rejoindre la communauté !</p>
                        <a href="inscription.php" class="btn btn-primary" style="margin-top: 1.5rem;">S'inscrire</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($membres as $membre): ?>
                        <div class="card" style="text-align: center;">
                            <img src="uploads/<?php echo htmlspecialchars($membre['photo_profil']); ?>" 
                                 alt="<?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>"
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; border: 3px solid var(--primary-color);">
                            
                            <h3 class="card-title" style="margin-bottom: 0.5rem;">
                                <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>
                            </h3>
                            
                            <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                                <?php echo htmlspecialchars($membre['specialite'] ?? 'Data Analyst'); ?>
                            </p>
                            
                            <?php if ($membre['ville']): ?>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                                    📍 <?php echo htmlspecialchars($membre['ville']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($membre['bio']): ?>
                                <p class="card-description" style="text-align: left;">
                                    <?php echo htmlspecialchars(substr($membre['bio'], 0, 100)) . (strlen($membre['bio']) > 100 ? '...' : ''); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="profile-stats" style="margin: 1.5rem 0; flex-direction: row; gap: 1.5rem;">
                                <div class="stat">
                                    <div class="stat-number" style="font-size: 1.5rem;"><?php echo $membre['projets_count']; ?></div>
                                    <div class="stat-label">Projets</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-number" style="font-size: 1.5rem;"><?php echo $membre['experiences_count']; ?></div>
                                    <div class="stat-label">Expériences</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-number" style="font-size: 1.5rem;"><?php echo $membre['recommandations_count']; ?></div>
                                    <div class="stat-label">Recommandations</div>
                                </div>
                            </div>
                            
                            <a href="profil.php?id=<?php echo $membre['id']; ?>" 
                               class="btn btn-primary" style="width: 100%; padding: 0.7rem;">
                                Voir le profil
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>Plateforme dédiée aux data analystes de Guinée pour partager, apprendre et grandir ensemble.</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <a href="projets.php">Projets</a>
                <a href="experiences.php">Expériences</a>
                <a href="membres.php">Membres</a>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@dataanalystes-gn.com</p>
                <p>Conakry, Guinée</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Data Analystes Guinée. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
