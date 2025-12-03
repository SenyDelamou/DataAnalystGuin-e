<?php
require_once 'php/config.php';

// Récupérer les derniers projets
$stmt = $conn->prepare("
    SELECT p.*, u.nom, u.prenom, u.photo_profil, u.specialite,
           (SELECT COUNT(*) FROM likes WHERE projet_id = p.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE projet_id = p.id) as comments_count
    FROM projets p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.date_creation DESC
    LIMIT 6
");
$stmt->execute();
$projets = $stmt->fetchAll();

// Récupérer les dernières expériences
$stmt = $conn->prepare("
    SELECT e.*, u.nom, u.prenom, u.photo_profil, u.specialite,
           (SELECT COUNT(*) FROM likes WHERE experience_id = e.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE experience_id = e.id) as comments_count
    FROM experiences e
    JOIN users u ON e.user_id = u.id
    ORDER BY e.date_creation DESC
    LIMIT 6
");
$stmt->execute();
$experiences = $stmt->fetchAll();

// Compter les statistiques
$stmt = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $stmt->fetch()['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM projets");
$total_projets = $stmt->fetch()['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM experiences");
$total_experiences = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analystes Guinée - Communauté des Analystes de Données</title>
    <meta name="description" content="Plateforme de partage et de collaboration pour les data analystes de Guinée">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Communauté des Data Analystes de Guinée</h1>
            <p>Connectez-vous, partagez vos projets, échangez vos expériences et grandissez ensemble dans le domaine de l'analyse de données</p>
            <div class="cta-buttons">
                <?php if (!is_logged_in()): ?>
                    <a href="inscription.php" class="btn btn-primary">Rejoindre la communauté</a>
                    <a href="projets.php" class="btn btn-secondary">Explorer les projets</a>
                <?php else: ?>
                    <a href="nouveau-projet.php" class="btn btn-primary">Partager un projet</a>
                    <a href="nouvelle-experience.php" class="btn btn-secondary">Partager une expérience</a>
                <?php endif; ?>
            </div>
            
            <!-- Stats -->
            <div class="profile-stats" style="margin-top: 4rem;">
                <div class="stat">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Membres</div>
                </div>
                <div class="stat">
                    <div class="stat-number"><?php echo $total_projets; ?></div>
                    <div class="stat-label">Projets</div>
                </div>
                <div class="stat">
                    <div class="stat-number"><?php echo $total_experiences; ?></div>
                    <div class="stat-label">Expériences</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projets récents -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Projets Récents</h2>
            <div class="cards-grid">
                <?php if (empty($projets)): ?>
                    <p class="text-center" style="grid-column: 1/-1; color: var(--text-muted);">
                        Aucun projet pour le moment. Soyez le premier à partager !
                    </p>
                <?php else: ?>
                    <?php foreach ($projets as $projet): ?>
                        <div class="card">
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
                                    foreach (array_slice($techs, 0, 3) as $tech): 
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
                                <a href="projet-detail.php?id=<?php echo $projet['id']; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Voir plus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="projets.php" class="btn btn-secondary">Voir tous les projets</a>
            </div>
        </div>
    </section>

    <!-- Expériences récentes -->
    <section class="section" style="background: rgba(30, 41, 59, 0.3);">
        <div class="container">
            <h2 class="section-title">Expériences Partagées</h2>
            <div class="cards-grid">
                <?php if (empty($experiences)): ?>
                    <p class="text-center" style="grid-column: 1/-1; color: var(--text-muted);">
                        Aucune expérience partagée pour le moment.
                    </p>
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
                                <?php echo htmlspecialchars(substr($experience['contenu'], 0, 150)) . '...'; ?>
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
                                <a href="experience-detail.php?id=<?php echo $experience['id']; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Lire plus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="experiences.php" class="btn btn-secondary">Voir toutes les expériences</a>
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

    <script src="js/main.js"></script>
</body>
</html>
