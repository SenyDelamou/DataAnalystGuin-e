# 🚀 Guide de Démarrage Rapide

## Installation en 5 minutes

### Étape 1 : Préparer l'environnement
1. Installez XAMPP, WAMP ou MAMP (si ce n'est pas déjà fait)
2. Démarrez Apache et MySQL

### Étape 2 : Placer les fichiers
1. Copiez le dossier `dataanalystes-guinee` dans :
   - **XAMPP** : `C:\xampp\htdocs\`
   - **WAMP** : `C:\wamp64\www\`
   - **MAMP** : `/Applications/MAMP/htdocs/`

### Étape 3 : Créer la base de données
1. Ouvrez votre navigateur
2. Allez sur `http://localhost/phpmyadmin`
3. Cliquez sur "Nouveau" pour créer une base de données
4. Nommez-la `dataanalystes_guinee`
5. Sélectionnez `utf8mb4_unicode_ci` comme interclassement
6. Cliquez sur l'onglet "Importer"
7. Sélectionnez le fichier `database.sql`
8. Cliquez sur "Exécuter"

### Étape 4 : Configurer la connexion
Ouvrez `php/config.php` et vérifiez/modifiez :
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Laissez vide pour XAMPP par défaut
define('DB_NAME', 'dataanalystes_guinee');
```

### Étape 5 : Ajouter l'avatar par défaut
Deux options :

**Option A - Télécharger en ligne :**
1. Visitez : https://ui-avatars.com/api/?name=Data+Analyst&size=200&background=2563eb&color=fff
2. Clic droit > Enregistrer l'image sous...
3. Nommez-la `default-avatar.png`
4. Placez-la dans le dossier `uploads/`

**Option B - Utiliser votre propre image :**
1. Prenez n'importe quelle image carrée (200x200px recommandé)
2. Nommez-la `default-avatar.png`
3. Placez-la dans le dossier `uploads/`

### Étape 6 : Accéder au site
1. Ouvrez votre navigateur
2. Allez sur `http://localhost/dataanalystes-guinee/`
3. Cliquez sur "Inscription"
4. Créez votre premier compte !

## ✅ Vérification

Votre installation est réussie si vous voyez :
- ✅ La page d'accueil avec le design moderne
- ✅ Les statistiques affichent "0 Membres, 0 Projets, 0 Expériences"
- ✅ Vous pouvez créer un compte
- ✅ Vous pouvez vous connecter
- ✅ Vous pouvez partager un projet

## 🔧 Résolution de problèmes courants

### "Erreur de connexion à la base de données"
➡️ Vérifiez que MySQL est démarré dans XAMPP/WAMP
➡️ Vérifiez les identifiants dans `php/config.php`

### "Page blanche"
➡️ Activez l'affichage des erreurs PHP dans `php.ini`
➡️ Vérifiez les logs Apache

### "Les images ne s'affichent pas"
➡️ Vérifiez que `default-avatar.png` existe dans `uploads/`
➡️ Vérifiez les permissions du dossier `uploads/`

### "Impossible d'uploader des fichiers"
➡️ Vérifiez que le dossier `uploads/` a les permissions d'écriture
➡️ Sur Windows : Clic droit > Propriétés > Sécurité > Modifier
➡️ Sur Linux/Mac : `chmod 755 uploads/`

## 📱 Premier test

1. **Créez un compte** avec vos informations
2. **Partagez un projet** :
   - Titre : "Mon premier projet d'analyse"
   - Description : "Analyse des données de ventes..."
   - Technologies : "Python, Pandas, Matplotlib"
3. **Partagez une expérience** :
   - Titre : "Comment débuter en data analysis"
   - Catégorie : "Tutoriel"
   - Contenu : Vos conseils...
4. **Visitez votre profil** pour voir vos statistiques

## 🎉 Félicitations !

Votre plateforme est maintenant opérationnelle !

Vous pouvez maintenant :
- Inviter d'autres data analystes à rejoindre
- Partager vos projets et expériences
- Découvrir le travail des autres membres
- Recommander vos collègues

---

**Besoin d'aide ?** Consultez le fichier README.md complet pour plus de détails.
