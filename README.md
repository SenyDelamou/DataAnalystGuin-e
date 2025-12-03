# 📊 Data Analystes Guinée - Plateforme Communautaire

Bienvenue sur la plateforme communautaire des Data Analystes de Guinée ! Ce site web permet aux analystes de données de se rencontrer, partager leurs projets, échanger leurs expériences et se recommander mutuellement.

## ✨ Fonctionnalités

### Pour tous les visiteurs
- 🏠 **Page d'accueil** avec statistiques de la communauté
- 📁 **Parcourir les projets** partagés par les membres
- 📚 **Lire les expériences** et conseils de la communauté
- 👥 **Découvrir les membres** et leurs profils

### Pour les membres connectés
- ✍️ **Partager des projets** avec fichiers et images
- 💡 **Partager des expériences** et tutoriels
- 👤 **Gérer son profil** personnel
- 💬 **Commenter** les projets et expériences
- ❤️ **Liker** les contenus
- ⭐ **Recommander** d'autres membres
- 📊 **Suivre ses statistiques** (projets, expériences, recommandations)

## 🛠️ Technologies utilisées

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Base de données**: MySQL 5.7+
- **Design**: Design moderne avec dark mode, gradients et animations

## 📋 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache, Nginx, ou XAMPP/WAMP/MAMP)
- Extension PHP PDO activée

## 🚀 Installation

### 1. Cloner ou télécharger le projet

Placez tous les fichiers dans le répertoire de votre serveur web (par exemple `htdocs` pour XAMPP).

### 2. Créer la base de données

1. Ouvrez phpMyAdmin ou votre client MySQL
2. Importez le fichier `database.sql` qui se trouve à la racine du projet
3. Cela créera automatiquement :
   - La base de données `dataanalystes_guinee`
   - Toutes les tables nécessaires (users, projets, experiences, recommandations, etc.)

### 3. Configurer la connexion à la base de données

Ouvrez le fichier `php/config.php` et modifiez les paramètres de connexion si nécessaire :

```php
define('DB_HOST', 'localhost');      // Hôte de la base de données
define('DB_USER', 'root');           // Nom d'utilisateur MySQL
define('DB_PASS', '');               // Mot de passe MySQL
define('DB_NAME', 'dataanalystes_guinee'); // Nom de la base de données
```

### 4. Créer le dossier uploads

Assurez-vous que le dossier `uploads/` existe et a les permissions d'écriture :

```bash
chmod 755 uploads/
```

### 5. Ajouter une image d'avatar par défaut

Placez une image nommée `default-avatar.png` dans le dossier `uploads/`. Cette image sera utilisée pour les nouveaux utilisateurs.

Vous pouvez utiliser n'importe quelle image carrée (recommandé : 200x200px minimum).

### 6. Démarrer le serveur

- **Avec XAMPP/WAMP/MAMP** : Démarrez Apache et MySQL
- **Avec PHP intégré** : 
  ```bash
  php -S localhost:8000
  ```

### 7. Accéder au site

Ouvrez votre navigateur et accédez à :
- `http://localhost/dataanalystes-guinee/` (avec XAMPP)
- `http://localhost:8000/` (avec serveur PHP intégré)

## 👤 Première utilisation

1. Cliquez sur **"Inscription"** dans la navigation
2. Remplissez le formulaire avec vos informations :
   - Prénom et Nom
   - Email (unique)
   - Mot de passe (minimum 6 caractères)
   - Spécialité (optionnel)
   - Ville (optionnel)
3. Connectez-vous avec vos identifiants
4. Commencez à partager vos projets et expériences !

## 📁 Structure du projet

```
dataanalystes-guinee/
├── css/
│   └── style.css              # Styles CSS principaux
├── js/
│   └── main.js                # JavaScript pour interactions
├── php/
│   ├── config.php             # Configuration et connexion DB
│   └── logout.php             # Script de déconnexion
├── uploads/                   # Dossier pour fichiers uploadés
├── images/                    # Images du site
├── index.php                  # Page d'accueil
├── inscription.php            # Page d'inscription
├── connexion.php              # Page de connexion
├── projets.php                # Liste des projets
├── nouveau-projet.php         # Ajouter un projet
├── experiences.php            # Liste des expériences
├── nouvelle-experience.php    # Ajouter une expérience
├── membres.php                # Liste des membres
├── profil.php                 # Page de profil
├── database.sql               # Script SQL de création
└── README.md                  # Ce fichier
```

## 🎨 Personnalisation

### Modifier les couleurs

Éditez le fichier `css/style.css` et modifiez les variables CSS dans `:root` :

```css
:root {
    --primary-color: #2563eb;
    --secondary-color: #10b981;
    --accent-color: #f59e0b;
    /* ... autres couleurs ... */
}
```

### Modifier le logo

Dans `css/style.css`, cherchez `.navbar .logo::before` et changez l'emoji ou ajoutez une image.

## 🔒 Sécurité

Le site implémente plusieurs mesures de sécurité :
- ✅ Hashage des mots de passe avec `password_hash()`
- ✅ Protection contre les injections SQL avec PDO et requêtes préparées
- ✅ Nettoyage des données avec `htmlspecialchars()`
- ✅ Validation des types de fichiers uploadés
- ✅ Gestion sécurisée des sessions

## 📝 Fonctionnalités à venir

- [ ] Système de messagerie privée
- [ ] Notifications en temps réel
- [ ] Recherche avancée de projets et membres
- [ ] Filtres par technologies et compétences
- [ ] Export de profil en PDF
- [ ] API REST pour applications mobiles
- [ ] Mode clair/sombre avec switch

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifiez que MySQL est démarré
- Vérifiez les identifiants dans `php/config.php`
- Assurez-vous que la base de données existe

### Les images ne s'affichent pas
- Vérifiez les permissions du dossier `uploads/`
- Assurez-vous que `default-avatar.png` existe

### Erreur lors de l'upload de fichiers
- Vérifiez `upload_max_filesize` et `post_max_size` dans `php.ini`
- Vérifiez les permissions du dossier `uploads/`

## 📧 Support

Pour toute question ou problème :
- Email : contact@dataanalystes-gn.com
- Créez une issue sur le dépôt du projet

## 📄 Licence

Ce projet est sous licence MIT. Vous êtes libre de l'utiliser, le modifier et le distribuer.

## 👏 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- Signaler des bugs
- Proposer de nouvelles fonctionnalités
- Améliorer la documentation
- Soumettre des pull requests

---

**Développé avec ❤️ pour la communauté des Data Analystes de Guinée**
