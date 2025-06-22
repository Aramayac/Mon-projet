# Plateforme de Recrutement en Ligne – IKBara

Plateforme web complète pour la mise en relation entre candidats à l’emploi et recruteurs, avec gestion centralisée des offres, des candidatures et un espace d’administration sécurisé.

---

## Sommaire

- [Présentation](#présentation)
- [Fonctionnalités principales](#fonctionnalités-principales)
- [Technologies utilisées](#technologies-utilisées)
- [Structure du projet](#structure-du-projet)
- [Installation](#installation)
- [FAQ Installation et Utilisation](#faq-installation-et-utilisation)
- [Détail des dossiers et fichiers](#détail-des-dossiers-et-fichiers)
- [Sécurité](#sécurité)
- [Contribution](#contribution)
- [Licence](#licence)
- [Crédits](#crédits)

---

## Présentation

Ce projet est une plateforme web de recrutement en ligne développée dans un contexte universitaire et destinée à illustrer la conception d’applications web modernes : gestion multi-rôles (candidat, recruteur, administrateur), espaces personnels, notifications, et sécurité des données.

---

## Fonctionnalités principales

### Pour les candidats
- Inscription, connexion, gestion du profil (CV, compétences, expériences)
- Recherche et filtrage d’offres d’emploi
- Postulation rapide, suivi de candidatures
- Messagerie interne et notifications
- Réinitialisation du mot de passe

### Pour les recruteurs
- Création de compte entreprise, gestion du profil
- Publication, modification, suppression d’offres d’emploi
- Consultation et gestion des candidatures reçues
- Notifications automatiques aux candidats

### Pour l’administration
- Tableau de bord global (statistiques, monitoring)
- Gestion centralisée des utilisateurs (activation, blocage)
- Modération des offres d’emploi
- Connexion sécurisée

---

## Technologies utilisées

- **PHP** : back-end, logique métier, sécurité
- **MySQL/MariaDB** : base de données relationnelle
- **Bootstrap 5, Bootstrap Icons** : interface responsive
- **HTML5, CSS3, JavaScript** : front-end dynamique
- **Composer** : gestion des dépendances PHP
- **PHPMailer** : gestion des emails
- **PlantUML** : modélisation de la base de données
- **GitHub Actions** : intégration continue et déploiement

---

## Structure du projet

```
.
├── admin/              # Interface et logique d’administration
├── authentification/   # Connexion candidats/recruteurs
├── candidats/          # Espace candidats (profil, candidatures, CV)
├── configuration/      # Paramétrage base de données
├── igm/                # Images, logos, ressources graphiques
├── includes/           # Composants réutilisables (headers, footers…)
├── recruteurs/         # Espace entreprises/recruteurs
├── vendor/             # Librairies tierces (Composer, PHPMailer)
├── .github/workflows/  # Workflows CI/CD
├── README.md           # Ce fichier
├── recrutement_en_ligne.sql # Script SQL de la base
├── … (autres fichiers principaux)
```
Chaque dossier/fichier est détaillé dans la section suivante.

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

3. **Créer la base de données**
   - Importer le fichier `recrutement_en_ligne.sql` dans MySQL/MariaDB.
   - Adapter les accès dans `configuration/connexionbase.php`.

4. **Configurer l’envoi d’emails**
   - Editer `mailer_config.php` avec vos paramètres SMTP.
   - Pour un environnement local : utiliser [MailHog](https://github.com/mailhog/MailHog) :
     ```
     [mail function]
     SMTP = 127.0.0.1
     smtp_port = 1025
     ```

5. **Démarrer le serveur**
   - Héberger le projet sur un serveur compatible PHP/MySQL (XAMPP, WAMP, MAMP, LAMP ou hébergement web).
   - Accéder au projet via un navigateur (`http://localhost/Mon-projet/`).

---

## FAQ Installation et Utilisation

**Q : Je n’ai pas PHP/Composer/MySQL sur ma machine. Que faire ?**  
R : Installez un package comme XAMPP, WAMP, MAMP (Windows/Mac) ou LAMP (Linux). Ces solutions regroupent PHP, MySQL/MariaDB et un serveur web.

**Q : J’ai une erreur de connexion à la base de données.**  
R : Vérifiez que MySQL est bien démarré, que la base a été créée, et que `configuration/connexionbase.php` contient les bons accès (host, user, password, dbname).

**Q : Les emails n’arrivent pas.**  
R : En local, utilisez MailHog (voir section installation). En production, configurez `mailer_config.php` avec vos identifiants SMTP réels.

**Q : Comment accéder directement aux fonctionnalités principales ?**  
- Accueil : `index.php`
- Inscription candidat : `inscription.php` ou `candidats/inscription_candidat.php`
- Inscription recruteur : `recruteurs/inscription_recruteur.php`
- Connexion : `connexion.php`
- Dashboard admin : `admin/tableau_administateur.php`

**Q : Comment réinitialiser un mot de passe ?**  
R : Utiliser le lien « mot de passe oublié » sur la page de connexion adaptée (candidat ou recruteur).

**Q : Peut-on déployer ce projet en ligne ?**  
R : Oui, sur tout hébergeur PHP/MySQL acceptant Composer et la configuration SMTP.

---

## Détail des dossiers et fichiers

### `/admin`
- **Gestion administrateur** : login, dashboard, gestion des offres et utilisateurs, blocage, statistiques.

### `/authentification`
- **Connexion dédiée** pour candidats et recruteurs.

### `/candidats`
- **Espace candidat** : gestion du profil, candidatures, messagerie, upload CV/avatar, réinitialisation mdp.
- **avatars/** : Photos de profil.
- **cv/** : CV PDF des candidats.

### `/recruteurs`
- **Espace recruteur** : compte entreprise, gestion des offres, suivi des candidatures, notifications.
- **dossier/** : Logos des entreprises.

### `/includes`
- **Composants réutilisables** : headers, footers, menus dynamiques, secteurs.

### `/igm`
- **Images et ressources graphiques** : logos, illustrations, fonds, licences.

### `/configuration`
- **connexionbase.php** : accès à la base de données.

### `/vendor`
- **Librairies tierces** : PHPMailer, Composer.

### **Autres fichiers clés**
- **index.php** : Page d’accueil
- **inscription.php**, **connexion.php** : Accès rapide
- **contact.php**, **faq.php** : Support utilisateur
- **recrutement_en_ligne.sql** : Script SQL à importer
- **cahier_des_charges.md** : Spécifications détaillées
- **dbdiagramme.pdf/.png** : Schéma de la base
- **README.md** : Ce fichier

---

## Sécurité

- Mots de passe hashés (`password_hash`)
- Contrôle systématique des sessions et rôles
- Upload sécurisé (CV, images…)
- Statut actif/bloqué pour chaque compte
- Conformité RGPD : données personnelles protégées

---

## Contribution

Les contributions sont encouragées !  
Pour participer :
1. **Forkez** le projet et créez une branche dédiée à votre amélioration/bugfix.
2. **Soumettez une Pull Request** détaillant votre modification.
3. Pour signaler un bug ou suggérer une amélioration, ouvrez une **issue**.

Merci de respecter la structure du code, la clarté des commits, et d’ajouter des commentaires si nécessaire.

---

## Licence

Ce projet est publié sous licence MIT.  
Cela signifie :
- **Liberté d’utilisation** (usage personnel, académique, commercial…)
- **Modification/distribution autorisée**
- **Aucune garantie** : utilisation à vos risques et périls

Voir le fichier `LICENSE` ou [opensource.org/licenses/MIT](https://opensource.org/licenses/MIT) pour les détails.

---

## Crédits

Développé par **Arama Yacouba** Etudiants en Reseaux Informatique, 2025.  
Projet réalisé dans le cadre universitaire / Projet D'examen de la semestre 4.

---
