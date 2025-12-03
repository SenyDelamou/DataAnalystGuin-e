<?php
require_once 'php/config.php';

// Déterminer quel profil afficher
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : (is_logged_in() ? $_SESSION['user_id'] : 0);

if (!$user_id) {
    redirect('connexion.php');
}

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect('index.php');
}

$is_own_profile = is_logged_in() && $_SESSION['user_id'] == $user_id;

// Récupérer les projets de l'utilisateur
$stmt = $conn->prepare("
    SELECT p.*,
           (SELECT COUNT(*) FROM likes WHERE projet_id = p.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE projet_id = p.id) as comments_count
    FROM projets p
    WHERE p.user_id = ?
    ORDER BY p.date_creation DESC
");
$stmt->execute([$user_id]);
$projets = $stmt->fetchAll();

// Récupérer les expériences de l'utilisateur
$stmt = $conn->prepare("
    SELECT e.*,
           (SELECT COUNT(*) FROM likes WHERE experience_id = e.id) as likes_count,
           (SELECT COUNT(*) FROM commentaires WHERE experience_id = e.id) as comments_count
    FROM experiences e
    WHERE e.user_id = ?
    ORDER BY e.date_creation DESC
");
$stmt->execute([$user_id]);
$experiences = $stmt->fetchAll();

// Récupérer les recommandations
$stmt = $conn->prepare("
    SELECT r.*, u.nom, u.prenom, u.photo_profil, u.specialite
    FROM recommandations r
    JOIN users u ON r.user_id_from = u.id
    WHERE r.user_id_to = ?
    ORDER BY r.date_creation DESC
");
$stmt->execute([$user_id]);
$recommandations = $stmt->fetchAll();

// Compter les statistiques
$total_projets = count($projets);
$total_experiences = count($experiences);
$total_recommandations = count($recommandations);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> - Profil</title>
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

    <!-- Profil Header -->
    <section class="section">
        <div class="container">
            <div class="profile-header">
                <img src="uploads/<?php echo htmlspecialchars($user['photo_profil']); ?>" 
                     alt="Photo de profil" class="profile-avatar">
                
                <h1 class="profile-name">
                    <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
                </h1>
                
                <p class="profile-specialty">
                    <?php echo htmlspecialchars($user['specialite'] ?? 'Data Analyst'); ?>
                </p>
                
                <?php if ($user['ville']): ?>
                    <p style="color: var(--text-muted); margin-bottom: 1rem;">
                        📍 <?php echo htmlspecialchars($user['ville']); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($user['bio']): ?>
                    <p class="profile-bio">
                        <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                    </p>
                <?php endif; ?>
                
                <div class="profile-stats">
                    <div class="stat">
                        <div class="stat-number"><?php echo $total_projets; ?></div>
                        <div class="stat-label">Projets</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?php echo $total_experiences; ?></div>
                        <div class="stat-label">Expériences</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?php echo $total_recommandations; ?></div>
                        <div class="stat-label">Recommandations</div>
                    </div>
                </div>
                
                <?php if ($is_own_profile): ?>
                    <div style="margin-top: 2rem;">
                        <a href="modifier-profil.php" class="btn btn-secondary">Modifier mon profil</a>
                    </div>
                <?php elseif (is_logged_in()): ?>
                    <div style="margin-top: 2rem;">
                        <a href="recommander.php?id=<?php echo $user_id; ?>" class="btn btn-primary">
                            Recommander ce membre
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Projets -->
            <?php if (!empty($projets)): ?>
                <h2 class="section-title" style="margin-top: 4rem;">Projets</h2>
                <div class="cards-grid">
                    <?php foreach ($projets as $projet): ?>
                        <div class="card">
                            <?php if ($projet['image_projet']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($projet['image_projet']); ?>" 
                                     alt="<?php echo htmlspecialchars($projet['titre']); ?>"
                                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 1.5rem;">
                            <?php endif; ?>
                            
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
                </div>
            <?php endif; ?>

            <!-- Expériences -->
            <?php if (!empty($experiences)): ?>
                <h2 class="section-title" style="margin-top: 4rem;">Expériences partagées</h2>
                <div class="cards-grid">
                    <?php foreach ($experiences as $experience): ?>
                        <div class="card">
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
                </div>
            <?php endif; ?>

            <!-- Recommandations -->
            <?php if (!empty($recommandations)): ?>
                <h2 class="section-title" style="margin-top: 4rem;">Recommandations reçues</h2>
                <div class="cards-grid">
                    <?php foreach ($recommandations as $reco): ?>
                        <div class="card">
                            <div class="card-header">
                                <img src="uploads/<?php echo htmlspecialchars($reco['photo_profil']); ?>" 
                                     alt="Avatar" class="card-avatar">
                                <div class="card-user-info">
                                    <h3><?php echo htmlspecialchars($reco['prenom'] . ' ' . $reco['nom']); ?></h3>
                                    <p><?php echo htmlspecialchars($reco['specialite'] ?? 'Data Analyst'); ?></p>
                                </div>
                            </div>
                            
                            <?php if ($reco['competence']): ?>
                                <div class="card-tags" style="margin-bottom: 1rem;">
                                    <span class="tag"><?php echo htmlspecialchars($reco['competence']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <p class="card-description">
                                "<?php echo nl2br(htmlspecialchars($reco['message'])); ?>"
                            </p>
                            
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 1rem;">
                                <?php echo date('d/m/Y', strtotime($reco['date_creation'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
