# Eventbrite Clone - Gestion avancée d'événements

## 🌟 Aperçu du projet
Les plateformes de gestion d'événements comme Eventbrite permettent aux organisateurs de créer, gérer et promouvoir des événements en ligne ou en présentiel.

Ce projet vise à concevoir un clone avancé d’Eventbrite en respectant les meilleures pratiques en **PHP MVC** avec **PostgreSQL**, et en intégrant **AJAX** pour une expérience utilisateur fluide et interactive.

## 🌟 Objectifs
- 🔢 **Organisateurs** : Publier et gérer des événements
- 🎉 **Participants** : Réserver des billets en ligne
- 👥 **Administrateurs** : Gérer utilisateurs et événements via un back-office
- 📊 **Statistiques avancées** : Suivi précis des ventes et performances des événements

---


## 📚 Fonctionnalités principales

### 👤 Gestion des utilisateurs
- ✔ Inscription et connexion sécurisée (email, mot de passe hashé avec bcrypt)
- ✔ Gestion des rôles : Organisateur, Participant, Admin
- ✔ Profil utilisateur avec avatar et historique des événements
- ✔ Système de notifications (email et alertes sur site)

### 🌟 Gestion des événements
- ✔ Création et modification d’un événement (titre, description, date, lieu, prix, capacité)
- ✔ Gestion des catégories et tags (Conférence, Concert, Sport, etc.)
- ✔ Ajout d’images et vidéos promotionnelles
- ✔ Validation des événements par un administrateur
- ✔ Système de mise en avant (sponsoring)

### 💳 Réservation et paiement
- ✔ Achat de billets (gratuit, payant, VIP, early bird)
- ✔ Paiement sécurisé via **Stripe** ou **PayPal** (sandbox mode)
- ✔ Génération de **QR Code** pour validation à l’entrée
- ✔ Système de remboursement et annulation
- ✔ Téléchargement de billets en **PDF**

### 📈 Tableau de bord organisateur
- ✔ Liste des événements avec état (actif, en attente, terminé)
- ✔ Statistiques des ventes et des réservations en temps réel
- ✔ Export des participants en **CSV/PDF**
- ✔ Gestion des promotions et réductions (codes promo, early bird)

### 📝 Back-office Admin
- ✔ Gestion des utilisateurs (bannissement, modification)
- ✔ Gestion des événements (validation, suppression, modification)
- ✔ Statistiques globales (nombre d’utilisateurs, billets vendus, revenus)
- ✔ Système de modération des commentaires et signalements

### 🔄 Interactions dynamiques avec AJAX
- ✔ Chargement dynamique des événements (pagination sans rechargement)
- ✔ Recherche et filtres avancés (catégorie, prix, date, lieu)
- ✔ Autocomplétion des recherches avec suggestions
- ✔ Validation de formulaire en temps réel

---

## 💻 Technologies utilisées

### 🛠️ Backend (PHP MVC & PostgreSQL)
- 👉 **PHP 8.x** – Gestion du backend
- 👉 **PostgreSQL** – Base de données relationnelle optimisée
- 👉 **PDO** – Requêtes SQL sécurisées (requêtes préparées)
- 👉 **Twig** – Moteur de templates
- 👉 **Composer** – Gestionnaire de dépendances

### 🌟 Frontend (AJAX & UI/UX)
- 👉 **HTML5, CSS3, JavaScript (ES6)** – Interface utilisateur
- 👉 **Bootstrap 5** ou **TailwindCSS** – Design responsive
- 👉 **AJAX (Fetch API & jQuery)** – Chargement dynamique

### 🔒 Sécurité et Outils
- 👉 **.htaccess** – Sécurisation et réécriture d’URL
- 👉 **Session Based Authentication** – Authentification sécurisée
- 👉 **Classes Validator & Security** – Protection XSS, CSRF, SQL Injection
- 👉 **Gestion des sessions sécurisées**

---

## 🔍 User Stories

### 👥 En tant que **Participant**
- Je veux créer un compte et me connecter via Google/Facebook.
- Je veux parcourir et filtrer les événements.
- Je veux réserver un billet et recevoir un QR Code.
- Je veux annuler ma réservation et demander un remboursement.
- Je veux recevoir des notifications pour mes événements.

### 👤 En tant qu'**Organisateur**
- Je veux publier un événement et gérer mes ventes.
- Je veux voir les statistiques des inscriptions.
- Je veux offrir des codes promo et gérer les remises.
- Je veux exporter la liste des participants en CSV/PDF.

### 🔒 En tant qu'**Administrateur**
- Je veux gérer les utilisateurs et les événements.
- Je veux modérer les contenus et suivre les statistiques globales.

---

## 📊 Optimisation & Sécurité
- 🛠️ Hashage des mots de passe avec **bcrypt**
- 🛠️ Protection contre **XSS, CSRF, et SQL Injection**
- 🛠️ Optimisation des requêtes PostgreSQL avec **indexation**
- 🛠️ Chargement des événements par **lazy loading avec AJAX**

