# Bibliothèque — Application de gestion de bibliothèque

Application web de gestion de bibliothèque développée avec **Laravel 12**.
Elle permet aux utilisateurs de parcourir un catalogue de livres, de les ajouter
à un panier, de les emprunter (avec une date de retour) et de gérer leurs
favoris. Une interface d'administration permet de gérer le catalogue, les
utilisateurs et le suivi des emprunts.

## Fonctionnalités

### Côté utilisateur
- **Catalogue & recherche** : consultation et recherche des livres disponibles.
- **Panier** : ajout de livres, modification des quantités, paiement.
- **Emprunts** : emprunt d'un ou plusieurs exemplaires avec une **date de retour
  fixée à 14 jours**, dans la limite du stock disponible.
- **Favoris** : ajout/retrait de livres dans une liste personnelle.
- **Historique** : suivi des emprunts passés et du nombre total de livres
  empruntés.
- **Profil** : consultation et modification des informations personnelles.

### Côté administrateur
- **Tableau de bord** d'administration.
- **Gestion des livres** (CRUD complet) avec image, prix et stock.
- **Gestion des utilisateurs** (CRUD complet).
- **Suivi des emprunts** de l'ensemble des utilisateurs.

### Authentification & sécurité
- Inscription / connexion / déconnexion.
- **Vérification d'email obligatoire** pour activer un compte.
- **Limitation du nombre de tentatives** de connexion (rate limiting).
- **Gestion des rôles** (`admin` / `user`) via middleware.
- Mots de passe hachés et protection CSRF (Blade).

## Stack technique

| Élément        | Technologie                          |
| -------------- | ------------------------------------ |
| Framework      | Laravel 12 (PHP 8.2+)                |
| Base de données| Eloquent ORM (SQLite / MySQL)        |
| Vues           | Blade                                |
| Front-end      | Tailwind CSS 4, Vite, Axios          |
| Tests          | PHPUnit                              |

## Modèle de données

- **User** — utilisateur (champ `role` pour distinguer admin / user).
- **Book** — livre (titre, auteur, description, prix, stock `available`, image).
- **Borrow** — emprunt (`user_id`, `book_id`, `quantity`, `borrowed_at`,
  `due_at`, `returned_at`, `paid`).
- **Cart** — ligne de panier (`user_id`, `book_id`, `quantity`).
- **Favorite** — favori liant un utilisateur à un livre (table pivot).

Relations principales :
- `User` 1—N `Borrow`, `User` N—N `Book` (favoris), `User` 1—N `Cart`.
- `Book` N—N `User` (favoris), `Book` 1—N `Borrow`.

## Installation

### Prérequis
- PHP **8.2+** avec les extensions `mbstring`, `xml`, `curl`, `sqlite3` (ou
  `mysql`).
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm

### Étapes

```bash
# 1. Installer les dépendances PHP et JS
composer install
npm install

# 2. Créer le fichier d'environnement et générer la clé d'application
cp .env.example .env
php artisan key:generate

# 3. (SQLite) Créer le fichier de base de données
touch database/database.sqlite
# puis dans .env :  DB_CONNECTION=sqlite  et  DB_DATABASE=database/database.sqlite

# 4. Lancer les migrations
php artisan migrate

# 5. Compiler les assets front-end
npm run build      # ou `npm run dev` en développement

# 6. Démarrer le serveur
php artisan serve
```

L'application est alors accessible sur http://localhost:8000.

> Pour que la vérification d'email fonctionne en local, configurez `MAIL_*`
> dans `.env` (par défaut, les emails sont écrits dans les logs via
> `MAIL_MAILER=log`).

## Tests

Les tests utilisent une base de données **SQLite en mémoire**
(`DB_DATABASE=:memory:`, configurée dans `phpunit.xml`) : ils **ne modifient
jamais la base de données réelle**.

```bash
# Tous les tests
php artisan test

# Uniquement les tests unitaires
php artisan test --testsuite=Unit
```

Les tests unitaires (`tests/Unit/`) couvrent la logique des modèles :
casts d'attributs, champs assignables, hachage des mots de passe, masquage des
champs sensibles et relations Eloquent.

## Structure du projet

```
app/
  Http/Controllers/   Logique (auth, livres, emprunts, panier, admin)
  Models/             Book, Borrow, Cart, Favorite, User
database/
  migrations/         Schéma de la base de données
  factories/          Factories pour les tests
resources/views/      Templates Blade (home, auth, cart, user, admin)
routes/web.php        Définition des routes
tests/                Tests unitaires et fonctionnels
```
