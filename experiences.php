<?php
require_once 'php/config.php';

// Récupérer toutes les expériences
$stmt = $conn->prepare("
    SELECT e.*, u.nom, u.prenom, u.photo_profil, u.specialite,
           (SELECT COUNT(*) FROM likes WHERE experience_id = e.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE experience_id = e.id) as comments_count
    FROM experiences e
    JOIN users u ON e.user_id = u.id
    ORDER BY e.date_creation DESC
");
$stmt->execute();
$experiences = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expériences - Data Analystes Guinée</title>
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
            <h1 style="font-size: 2.5rem;">Expériences partagées</h1>
            <p>Apprenez des expériences et conseils de la communauté</p>
            <?php if (is_logged_in()): ?>
                <div class="cta-buttons" style="margin-top: 2rem;">
                    <a href="nouvelle-experience.php" class="btn btn-primary">Partager mon expérience</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Expériences -->
    <section class="section">
        <div class="container">
            <div class="cards-grid">
                <?php if (empty($experiences)): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">Aucune expérience partagée pour le moment</h3>
                        <p style="color: var(--text-muted);">Soyez le premier à partager votre expérience !</p>
                        <?php if (is_logged_in()): ?>
                            <a href="nouvelle-experience.php" class="btn btn-primary" style="margin-top: 1.5rem;">Partager une expérience</a>
                        <?php else: ?>
                            <a href="inscription.php" class="btn btn-primary" style="margin-top: 1.5rem;">Rejoindre la communauté</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($experiences as $experience): ?>
                        <div class="card">
                            <div class="card-header">
                                <img src="uploads/<?php echo htmlspecialchars($experience['photo_profil']); ?>" 
                                     alt="Avatar" class="card-avatar">
                                <div class="card-user-info">
                                    <h3><?php echo htmlspecialchars($experience['prenom'] . ' ' . $experience['nom']); ?></h3>
                                    <p><?php echo htmlspecialchars($experience['specialite'] ?? 'Data Analyst'); ?></p>
                                </div>
                            </div>
                            
                            <h3 class="card-title"><?php echo htmlspecialchars($experience['titre']); ?></h3>
                            <p class="card-description">
                                <?php echo htmlspecialchars(substr($experience['contenu'], 0, 200)) . '...'; ?>
                            </p>
                            
                            <?php if (!empty($experience['categorie'])): ?>
                                <div class="card-tags">
                                    <span class="tag"><?php echo htmlspecialchars($experience['categorie']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-footer">
                                <div class="card-stats">
                                    <span>❤️ <?php echo $experience['likes_count']; ?></span>
                                    <span>💬 <?php echo $experience['comments_count']; ?></span>
                                </div>
                                <a href="experience-detail.php?id=<?php echo $experience['id']; ?>" 
                                   class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    Lire plus
                                </a>
                            </div>
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
