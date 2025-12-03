/*
 * CONFIGURATION EXEMPLE
 * 
 * Copiez ce fichier et renommez-le en 'config.php'
 * Puis modifiez les valeurs selon votre environnement
 */

<?php
// ====================================
// CONFIGURATION DE LA BASE DE DONNÉES
// ====================================

// Hôte de la base de données
// Par défaut : 'localhost'
// Pour un serveur distant : 'votre-serveur.com'
define('DB_HOST', 'localhost');

// Nom d'utilisateur MySQL
// Par défaut XAMPP/WAMP : 'root'
define('DB_USER', 'root');

// Mot de passe MySQL
// Par défaut XAMPP : '' (vide)
// Par défaut WAMP : '' (vide)
// Pour un serveur de production : utilisez un mot de passe fort
define('DB_PASS', '');

// Nom de la base de données
// Assurez-vous que cette base existe et que le fichier database.sql a été importé
define('DB_NAME', 'dataanalystes_guinee');

// ====================================
// CONFIGURATION DU SITE
// ====================================

// URL de base du site (sans slash à la fin)
// Exemple : 'http://localhost/dataanalystes-guinee'
// Exemple production : 'https://www.dataanalystes-gn.com'
define('SITE_URL', 'http://localhost/dataanalystes-guinee');

// Nom du site
define('SITE_NAME', 'Data Analystes Guinée');

// Email de contact
define('CONTACT_EMAIL', 'contact@dataanalystes-gn.com');

// ====================================
// CONFIGURATION DES UPLOADS
// ====================================

// Taille maximale des fichiers en Mo
define('MAX_FILE_SIZE', 10); // 10 Mo

// Extensions de fichiers autorisées pour les projets
define('ALLOWED_FILE_EXTENSIONS', 'pdf,zip,rar,doc,docx,xlsx,csv,ipynb,py,r');

// Extensions d'images autorisées
define('ALLOWED_IMAGE_EXTENSIONS', 'jpg,jpeg,png,gif,webp');

// ====================================
// CONFIGURATION DE SÉCURITÉ
// ====================================

// Activer le mode debug (à désactiver en production)
define('DEBUG_MODE', true);

// Durée de session en secondes (2 heures par défaut)
define('SESSION_LIFETIME', 7200);

// ====================================
// NE PAS MODIFIER EN DESSOUS
// ====================================

// Connexion à la base de données
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    if (DEBUG_MODE) {
        die("Erreur de connexion : " . $e->getMessage());
    } else {
        die("Erreur de connexion à la base de données. Veuillez contacter l'administrateur.");
    }
}

// Configuration de session
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// Définir le fuseau horaire
date_default_timezone_set('Africa/Conakry');

// ====================================
// FONCTIONS UTILITAIRES
// ====================================

/**
 * Nettoyer les données d'entrée
 */
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Rediriger vers une page
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit();
    }
}

/**
 * Obtenir l'utilisateur connecté
 */
function get_current_user() {
    if (!is_logged_in()) {
        return null;
    }
    
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Formater une date
 */
function format_date($date) {
    return date('d/m/Y à H:i', strtotime($date));
}

/**
 * Générer un nom de fichier unique
 */
function generate_unique_filename($original_filename) {
    $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

/**
 * Vérifier si un fichier est une image valide
 */
function is_valid_image($file) {
    $allowed = explode(',', ALLOWED_IMAGE_EXTENSIONS);
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowed)) {
        return false;
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    return in_array($mime, $allowed_mimes);
}

/**
 * Vérifier si un fichier est valide
 */
function is_valid_file($file) {
    $allowed = explode(',', ALLOWED_FILE_EXTENSIONS);
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($extension, $allowed);
}

/**
 * Afficher un message d'erreur
 */
function show_error($message) {
    return '<div class="alert alert-error">' . htmlspecialchars($message) . '</div>';
}

/**
 * Afficher un message de succès
 */
function show_success($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}
?>
