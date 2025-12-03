<?php
require_once 'php/config.php';

// Récupérer tous les projets
$stmt = $conn->prepare("
    SELECT p.*, u.nom, u.prenom, u.photo_profil, u.specialite,
           (SELECT COUNT(*) FROM likes WHERE projet_id = p.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE projet_id = p.id) as comments_count
    FROM projets p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.date_creation DESC
");
$stmt->execute();
$projets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Data Analystes Guinée</title>
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
            <h1 style="font-size: 2.5rem;">Projets de la communauté</h1>
            <p>Découvrez les projets d'analyse de données réalisés par nos membres</p>
            <?php if (is_logged_in()): ?>
                <div class="cta-buttons" style="margin-top: 2rem;">
                    <a href="nouveau-projet.php" class="btn btn-primary">Partager mon projet</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Projets -->
    <section class="section">
        <div class="container">
            <div class="cards-grid">
                <?php if (empty($projets)): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">Aucun projet pour le moment</h3>
                        <p style="color: var(--text-muted);">Soyez le premier à partager un projet !</p>
                        <?php if (is_logged_in()): ?>
                            <a href="nouveau-projet.php" class="btn btn-primary" style="margin-top: 1.5rem;">Partager un projet</a>
                        <?php else: ?>
                            <a href="inscription.php" class="btn btn-primary" style="margin-top: 1.5rem;">Rejoindre la communauté</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($projets as $projet): ?>
                        <div class="card">
                            <?php if ($projet['image_projet']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($projet['image_projet']); ?>" 
                                     alt="<?php echo htmlspecialchars($projet['titre']); ?>"
                                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 1.5rem;">
                            <?php endif; ?>
                            
                            <div class="card-header">
                                <img src="uploads/<?php echo htmlspecialchars($projet['photo_profil']); ?>" 
                                     alt="Avatar" class="card-avatar">
                                <div class="card-user-info">
                                    <h3><?php echo htmlspecialchars($projet['prenom'] . ' ' . $projet['nom']); ?></h3>
                                    <p><?php echo htmlspecialchars($projet['specialite'] ?? 'Data Analyst'); ?></p>
                                </div>
                            </div>
                            
                            <h3 class="card-title"><?php echo htmlspecialchars($projet['titre']); ?></h3>
                            <p class="card-description">
                                <?php echo htmlspecialchars(substr($projet['description'], 0, 150)) . '...'; ?>
                            </p>
                            
                            <?php if (!empty($projet['technologies'])): ?>
                                <div class="card-tags">
                                    <?php 
                                    $techs = explode(',', $projet['technologies']);
                                    foreach (array_slice($techs, 0, 4) as $tech): 
                                    ?>
                                        <span class="tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-footer">
                                <div class="card-stats">
                                    <span>❤️ <?php echo $projet['likes_count']; ?></span>
                                    <span>💬 <?php echo $projet['comments_count']; ?></span>
                                    <span>👁️ <?php echo $projet['vues']; ?></span>
                                </div>
                                <a href="projet-detail.php?id=<?php echo $projet['id']; ?>" 
                                   class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    Voir détails
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
