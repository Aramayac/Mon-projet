# Plateforme de Recrutement en Ligne – IKBara

Plateforme web complète pour la mise en relation entre candidats à l’emploi et recruteurs, avec gestion centralisée des offres, candidatures, et un espace d’administration sécurisé.

---

## Sommaire

- [Fonctionnalités principales](#fonctionnalités-principales)
- [Technologies utilisées](#technologies-utilisées)
- [Structure du projet](#structure-du-projet)
- [Installation](#installation)
- [Détail des dossiers et fichiers](#détail-des-dossiers-et-fichiers)
- [Sécurité](#sécurité)
- [Crédits](#crédits)

---

## Fonctionnalités principales

### Candidats
- Inscription, connexion, gestion du profil (CV, compétences, expériences…)
- Parcours, recherche et filtrage des offres d’emploi
- Postulation, suivi des candidatures, messagerie interne
- Réinitialisation du mot de passe

### Recruteurs
- Inscription, connexion, gestion du profil entreprise
- Publication, modification, suppression et gestion d’offres
- Consultation et gestion des candidatures reçues
- Notification des candidats (messagerie interne/email)
- Réinitialisation du mot de passe

### Administrateur
- Tableau de bord global (statistiques, monitoring)
- Gestion des utilisateurs (activation, blocage) et offres (publication, masquage)
- Connexion sécurisée

---

## Technologies utilisées

- **PHP** (Back-end)
- **MySQL** (Base de données)
- **Bootstrap 5** & **Bootstrap Icons** (UI responsive)
- **HTML5, CSS3, JavaScript**
- **Composer** (gestion des dépendances)
- **PHPMailer** (envoi d'emails)
- **PlantUML** (modélisation UML)
- **GitHub Actions** (workflow CI/CD)

---

## Structure du projet

```
.
│   auth.php
│   cahier_des_charges.md
│   candidatplan.png
│   composer.json / lock
│   connexion.php
│   contact.php
│   dbdiagramme.pdf / .png
│   deconnexion.php
│   faq.php
│   footer_contact.php
│   header_contact.php
│   Ikbaradiagrmme.pdf
│   index.php
│   inscri.php
│   inscription.php
│   insert.txt
│   mailer_config.php
│   README.md
│   recrutement_en_ligne.sql
├───.github/
│   └───workflows/      # Intégration continue (tests, build)
├───admin/              # Gestion admin (offres, users, stats)
├───authentification/   # Connexion candidats & recruteurs
├───candidats/          # Espace et logiques candidats
├───configuration/      # Connexion à la base de données
├───igm/                # Images, logos, ressources graphiques
├───includes/           # Entêtes, pieds de page, composants réutilisables
├───recruteurs/         # Espace et logiques recruteurs
└───vendor/             # Librairies tierces (Composer, PHPMailer)
```

---

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Aramayac/Mon-projet.git
   cd Mon-projet
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer la base de données**
   - Importer le fichier `recrutement_en_ligne.sql` dans MySQL/MariaDB.
   - Configurer l’accès DB dans `configuration/connexionbase.php`.

4. **Configurer l’e-mail**
   - Modifier `mailer_config.php` pour vos identifiants SMTP (PHPMailer).
   - Pour développement local, possible d’utiliser MailHog ou équivalent :
     ```
     [mail function]
     SMTP = 127.0.0.1
     smtp_port = 1025
     ```

5. **Lancer le projet**
   - Héberger sur serveur web local (XAMPP, WAMP, MAMP) ou PHP/MySQL en ligne.
   - Accéder à l’URL du projet (ex : http://localhost/Mon-projet/).

---

## Détail des dossiers et fichiers

### `/admin`
Gestion de la plateforme côté administrateur :
- **admin_auth.php** : Login admin
- **bloquer_offres.php**, **bloquer_users.php** : Blocage d’offres/utilisateurs
- **connexion_adminstrateur.php**, **deconnexion.php** : Connexion/Déconnexion admin
- **inscription_admin.php** : Création admin
- **offres.php**, **users.php** : Gestion des offres et utilisateurs
- **tableau_administateur.php** : Dashboard statistiques/monitoring

### `/authentification`
Pages de connexion dédiées pour chaque rôle :
- **connexion_candidat.php**
- **connexion_recruteur.php**

### `/candidats`
Espace candidat (fonctionnalités majeures) :
- Gestion du profil, messagerie interne, candidature, suivi, upload avatar/CV, réinitialisation mot de passe, etc.
- **avatars/** : Photos de profil
- **cv/** : CVs PDF des candidats

### `/recruteurs`
Espace entreprise/recruteur :
- Gestion du compte, publication/modification/suppression d’offres, réception des candidatures, notification des candidats, etc.
- **dossier/** : Logos des entreprises

### `/includes`
Composants réutilisables :
- **header.php**, **footer.php** (+ variantes)
- **secteurs.php** : Liste des secteurs proposés

### `/igm`
Ressources graphiques du site (logos, illustrations, fonds, etc).
- **silhouettes-modern-background/** : Images thème/design, licences

### `/configuration`
- **connexionbase.php** : Paramètres de connexion MySQL

### `/vendor`
Librairies tierces gérées par Composer.
- **phpmailer/** : Gestion des emails sortants

### Fichiers principaux à la racine
- **index.php** : Page d’accueil
- **inscription.php** : Formulaire d’inscription (raccourci)
- **connexion.php** : Page de connexion globale
- **contact.php**, **faq.php** : Support utilisateur
- **recrutement_en_ligne.sql** : Script SQL à importer
- **composer.json / lock** : Dépendances PHP/Composer
- **README.md** : Ce fichier
- **cahier_des_charges.md** : Spécifications détaillées du projet
- **dbdiagramme.pdf/.png** : Schéma de la base

---

## Sécurité

- Mots de passe hashés (`password_hash`)
- Contrôle de session/roles sur chaque page sensible
- Upload sécurisé des fichiers (CV/images)
- Gestion fine des statuts utilisateurs (actif/bloqué)
- RGPD : Données personnelles traitées confidentiellement

---

## Crédits

Développé par [Aramayac](https://github.com/Aramayac) et collaborateurs, 2025.

---

**Licence :** Open-source, [voir le dépôt](https://github.com/Aramayac/Mon-projet) pour plus d’infos.

---
