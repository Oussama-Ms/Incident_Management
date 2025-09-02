# Système de Gestion d'Incidents

Un système complet de gestion d'incidents développé avec Laravel, offrant une interface moderne et intuitive pour la gestion des incidents, des équipes, des projets et des SLA.

## 🚀 Fonctionnalités

### 👥 Gestion des Utilisateurs
- **Rôles multiples** : Admin, Client, Employé
- **Authentification sécurisée** avec middleware personnalisé
- **Profils utilisateurs** avec gestion des informations
- **Gestion des équipes** et spécialisations

### 📋 Gestion des Incidents
- **Création d'incidents** par les clients
- **Suivi des statuts** : Nouveau, En cours, Résolu, Fermé
- **Assignation d'équipes** par les administrateurs
- **Système de commentaires** en temps réel
- **Upload de fichiers** attachés aux incidents
- **Recherche et filtrage** avancés

### 📊 Tableau de Bord
- **Statistiques en temps réel** : incidents, utilisateurs, équipes
- **Graphiques interactifs** : incidents par mois, par équipe
- **Export de données** en Excel et PDF
- **Interface responsive** adaptée à tous les écrans

### ⏰ Système SLA
- **Définition de SLA** par projet
- **Compteurs de temps** en temps réel
- **Alertes automatiques** : 48h, 24h, dépassement
- **Notifications in-app** avec système de cloche

### 🔔 Notifications
- **Notifications en temps réel** via AJAX
- **Système de cloche** avec compteur de notifications
- **Marquage lu/non-lu**
- **Alertes SLA** automatiques

### 📈 Export et Rapports
- **Export Excel** (CSV) pour tous les types de données
- **Export PDF** avec mise en forme professionnelle
- **Rapports détaillés** par période, équipe, statut

## 🛠️ Technologies Utilisées

- **Backend** : Laravel 10.x
- **Frontend** : Blade Templates, CSS3, JavaScript ES6+
- **Base de données** : MySQL
- **Graphiques** : Chart.js
- **Icons** : SVG (Feather Icons)
- **Responsive** : CSS Grid, Flexbox

## 📋 Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js (optionnel, pour la compilation des assets)
- Serveur web (Apache/Nginx) ou serveur de développement

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone <repository-url>
cd test
```

### 2. Installer les dépendances
```bash
composer install
npm install
```

### 3. Configuration de l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configuration de la base de données
Éditez le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

### 5. Exécuter les migrations
```bash
php artisan migrate
```

### 6. Créer le lien symbolique pour le stockage
```bash
php artisan storage:link
```

### 7. Compiler les assets (optionnel)
```bash
npm run dev
# ou pour la production
npm run build
```

### 8. Démarrer le serveur
```bash
php artisan serve
```

## 📁 Structure du Projet

### Organisation des Fichiers
```
test/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Contrôleurs Laravel
│   │   ├── Middleware/           # Middleware personnalisé
│   │   └── Requests/             # Validation des formulaires
│   ├── Models/                   # Modèles Eloquent
│   ├── Services/                 # Services métier
│   └── Console/Commands/         # Commandes Artisan
├── resources/
│   └── views/
│       ├── layouts/              # Layouts principaux
│       ├── partials/             # Composants réutilisables
│       ├── dashboards/           # Pages de tableau de bord
│       ├── admin/                # Pages administrateur
│       └── pages/                # Pages organisées par rôle
├── public/
│   ├── css/                      # Styles CSS
│   │   └── dashboard.css         # Styles du tableau de bord
│   └── js/                       # JavaScript
│       └── dashboard.js          # Logique du tableau de bord
└── routes/
    └── web.php                   # Routes de l'application
```

### Organisation CSS/JS

#### CSS Structure
- **`public/css/dashboard.css`** : Styles spécifiques au tableau de bord
  - Layout et grilles
  - Cartes de statistiques
  - Graphiques et export
  - Responsive design
  - Animations et transitions

#### JavaScript Structure
- **`public/js/dashboard.js`** : Logique du tableau de bord
  - Gestion des graphiques Chart.js
  - Système d'export de données
  - Notifications en temps réel
  - Compteurs SLA

### Bonnes Pratiques
- **Séparation des préoccupations** : CSS/JS externalisés
- **Styles inline** : Utilisés pour la flexibilité et la rapidité de développement
- **JavaScript modulaire** : Fonctions organisées par fonctionnalité
- **Responsive design** : Mobile-first approach
- **Performance** : Chargement optimisé des assets

## 🗄️ Structure de la Base de Données

### Tables Principales
- **user** : Utilisateurs du système
- **employee** : Employés avec équipes
- **team** : Équipes de support
- **projet** : Projets clients
- **sla** : Contrats de niveau de service
- **incident** : Incidents créés
- **comment** : Commentaires sur les incidents
- **file** : Fichiers attachés
- **notification** : Notifications système

### Relations
- Un utilisateur peut être client ou employé
- Un employé appartient à une équipe
- Un projet a un SLA associé
- Un incident appartient à un projet et un utilisateur
- Les commentaires et fichiers sont liés aux incidents

## 🔧 Commandes Artisan

### Gestion des SLA
```bash
# Vérifier les délais SLA
php artisan sla:check-deadlines

# Tester les alertes SLA
php artisan sla:test-alerts
```

### Cache et Optimisation
```bash
# Vider le cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Base de Données
```bash
# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Créer un utilisateur admin
php artisan make:admin
```

## 🎨 Interface Utilisateur

### Design System
- **Couleurs** : Palette purple/white avec accents gold
- **Typographie** : Système de tailles cohérent
- **Espacement** : Grille 8px pour l'alignement
- **Composants** : Cards, boutons, formulaires réutilisables

### Responsive Design
- **Mobile First** : Optimisé pour les petits écrans
- **Breakpoints** : 768px, 1024px, 1440px
- **Navigation** : Sidebar adaptative avec scroll

### Composants Principaux
- **Sidebar** : Navigation par rôle
- **Cards** : Affichage des données
- **Forms** : Validation en temps réel
- **Modals** : Confirmations et détails
- **Notifications** : Système de cloche

## 🔐 Sécurité

### Authentification
- **Middleware personnalisé** par rôle
- **Protection CSRF** sur tous les formulaires
- **Validation stricte** des données
- **Hachage sécurisé** des mots de passe

### Autorisation
- **Contrôle d'accès** basé sur les rôles
- **Validation des permissions** par action
- **Protection des routes** sensibles

### Données
- **Validation côté serveur** obligatoire
- **Échappement automatique** des données
- **Protection contre l'injection SQL**

## 📊 API Endpoints

### Notifications
```http
GET /api/notifications/count
GET /api/notifications
POST /api/notifications/mark-read
POST /api/notifications/mark-all-read
DELETE /api/notifications/{id}
```

### Export
```http
GET /admin/export/{type}/{format}
```

## 🧪 Tests

### Exécuter les tests
```bash
# Tests unitaires
php artisan test --testsuite=Unit

# Tests fonctionnels
php artisan test --testsuite=Feature

# Tous les tests
php artisan test
```

### Structure des tests
```
tests/
├── Feature/
│   ├── Admin/
│   ├── Client/
│   ├── Employee/
│   └── Auth/
├── Unit/
│   ├── Services/
│   ├── Repositories/
│   └── Models/
└── Browser/
    └── Components/
```

## 🚀 Déploiement

### Production
1. **Configuration** : `APP_ENV=production`
2. **Optimisation** : Cache des configurations
3. **Sécurité** : `APP_DEBUG=false`
4. **Performance** : Queue workers pour les tâches asynchrones

### Serveur Web
- **Apache** : Configuration .htaccess incluse
- **Nginx** : Configuration recommandée disponible
- **SSL** : Certificat obligatoire en production

## 📝 Documentation

- [Système d'Alerte SLA](SLA_ALERT_SYSTEM.md)

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
- Créer une issue sur GitHub
- Consulter la documentation
- Vérifier les logs dans `storage/logs/`

## 🔄 Changelog

### Version 4.1
- ✅ Nettoyage de la structure du projet : Suppression des dossiers vides
- ✅ Amélioration des contours : Design plus visible et cohérent
- ✅ Suppression des ombres : Interface plus épurée
- ✅ Optimisation des performances : Structure simplifiée

### Version 4.0
- ✅ Interface utilisateur modernisée
- ✅ Système SLA avec alertes automatiques
- ✅ Export de données Excel/PDF
- ✅ Gestion des équipes et assignations
- ✅ Système de commentaires en temps réel
- ✅ Notifications in-app avec système de cloche

### Version 3.0
- ✅ Authentification multi-rôles
- ✅ Gestion des incidents
- ✅ Tableau de bord admin
- ✅ Upload de fichiers

### Version 2.0
- ✅ Structure de base Laravel
- ✅ Modèles et migrations
- ✅ Interface utilisateur de base

---

**Développé avec ❤️ en utilisant Laravel**
