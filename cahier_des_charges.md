# Cahier des charges – Plateforme de Recrutement en Ligne (IKBara)

## 1. Présentation générale

Le projet vise à développer une plateforme web de recrutement permettant aux entreprises de publier des offres d’emploi et aux candidats de postuler en ligne. Un administrateur supervise la plateforme, la sécurité, et la gestion des utilisateurs.

## 2. Objectifs

- Proposer un espace sécurisé pour la gestion des recrutements
- Permettre la gestion efficace des candidatures et des offres
- Automatiser les notifications et la gestion des statuts
- Faciliter la supervision par un administrateur

## 3. Fonctionnalités attendues

### 3.1 Pour les candidats

- Inscription, authentification (connexion, mot de passe oublié)
- Création et gestion du profil (nom, prénom, email, CV au format PDF, compétences…)
- Recherche d’offres par mots-clés, lieu, secteur d’activité
- Postulation à des offres
- Suivi des candidatures (statut : en cours, acceptée, refusée)
- Réception de messages (notification de changement de statut)
- Sécurité des accès (compte bloqué, réinitialisation mot de passe)

### 3.2 Pour les recruteurs

- Inscription, authentification (connexion, mot de passe oublié)
- Création et gestion du profil entreprise (nom, secteur, logo, description, contact…)
- Publication, modification, suppression d’offres d’emploi
- Consultation de la liste des candidatures reçues pour chaque offre
- Changement du statut d’une candidature (acceptée, refusée) + notification auto au candidat
- Sécurité des accès (compte bloqué, réinitialisation mot de passe)

### 3.3 Pour l’administrateur

- Connexion sécurisée
- Tableau de bord (statistiques : nombre de candidats, recruteurs, offres, candidatures)
- Gestion des utilisateurs (blocage, activation)
- Gestion des offres (masquage, publication)
- Supervision globale du site

## 4. Contraintes techniques

- Base de données MySQL
- Back-end en PHP (PDO pour la sécurité)
- Front-end responsive (Bootstrap 5)
- Sécurité : hashage des mots de passe, vérifications côté serveur, validation des fichiers uploadés, gestion des rôles et des sessions

## 5. Architecture des données principales

### Tables principales

- `candidats` (profil candidat, mot de passe hashé, statut)
- `recruteurs` (profil entreprise, logo, mot de passe hashé, statut)
- `offres_emploi` (titre, description, secteur, lieu, type de contrat, etc.)
- `candidatures` (id_offre, id_candidat, date, statut)
- `profils_candidats` (cv, compétences…)
- `messages` (messagerie interne candidats <-> recruteurs)
- `admins` (gestion admin)
- (Autres tables annexes selon besoin)

## 6. Livrables

- Code source complet du projet
- Script SQL d’installation (`recrutement_en_ligne.sql`)
- Documentation technique (README.md)
- Modèle UML (diagramme de classes)
- Ce cahier des charges au format Markdown

## 7. Planning prévisionnel

- Semaine 1 : Analyse, conception BDD, modélisation UML
- Semaine 2-3 : Développement back-end (auth, profils, offres, candidatures)
- Semaine 4 : Développement front-end, intégration, responsive
- Semaine 5 : Administration, sécurité, tests, documentation

## 8. Évolutions possibles

- Notifications par email
- Statistiques avancées pour recruteurs
- Ajout de tests automatisés
- API pour intégration mobile
