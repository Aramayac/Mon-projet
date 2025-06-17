# Plateforme de Recrutement en Ligne – IKBara

Ce projet est une plateforme web de recrutement en ligne permettant la mise en relation de candidats à l’emploi et de recruteurs. Elle offre une gestion centralisée des offres d’emploi, des candidatures, et dispose d’un espace d’administration.

## Fonctionnalités principales

- **Candidats**
  - Inscription, connexion, gestion du profil (CV, compétences, expériences…)
  - Parcours et recherche d’offres d’emploi (filtrage par mots-clés, lieu, secteur…)
  - Postulation à des offres, suivi des candidatures et réception de messages
  - Réinitialisation du mot de passe

- **Recruteurs**
  - Inscription, connexion, gestion du profil entreprise (logo, secteur, contact…)
  - Publication, modification, suppression et gestion d’offres d’emploi
  - Consultation des candidatures reçues et gestion du statut de chaque candidature (acceptée, refusée)
  - Notification automatique des candidats par messagerie interne
  - Réinitialisation du mot de passe

- **Administrateur**
  - Tableau de bord de monitoring (statistiques globales)
  - Gestion des utilisateurs (activation, blocage) et des offres (masquage, publication)
  - Connexion sécurisée

## Technologies utilisées

- PHP (Programmation côté serveur)
- MySQL (Base de données)
- Bootstrap 5, Bootstrap Icons (UI responsive)
- HTML5, CSS3, JavaScript
- PlantUML (modélisation UML)

## Structure du projet

```
/admin           : Interfaces d’administration
/authentification: Connexion (candidats/recruteurs)
/candidats       : Espace et logique métier pour les candidats
/recruteurs      : Espace et logique métier pour les recruteurs
/includes        : Entêtes, pieds de page, composants réutilisables
/configuration   : Connexion à la base de données
/igm, /img, /dossier, /cv : Images, logos et fichiers utilisateur
README.md        : Ce fichier
```

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Aramayac/Mon-projet.git
   ```

2. **Configurer la base de données**
   - Importer le fichier `recrutement_en_ligne.sql` dans MySQL.
   - Vérifier/adapter la configuration d’accès dans `configuration/connexionbase.php`.

3. **Lancer le projet**
   - Héberger les fichiers sur un serveur local (XAMPP, WAMP, MAMP…) ou un hébergement PHP/MySQL.
   - Accéder à l’URL de base du projet.

## Accès rapides

- Page d’accueil : `/index.php`
- Inscription candidat : `/inscription.php` ou `/candidats/inscription_candidat.php`
- Inscription recruteur : `/recruteurs/inscription_recruteur.php`
- Connexion : `/connexion.php`
- Tableau de bord admin : `/admin/tableau_administateur.php`

## Sécurité

- Mots de passe hashés (password_hash)
- Vérification rôle/session sur chaque page sensible
- Upload sécurisé (CV PDF, images)
- Gestion des statuts (actif/bloqué) pour chaque compte

## Crédits

Développé par Aramayac et collaborateurs, 2025.
