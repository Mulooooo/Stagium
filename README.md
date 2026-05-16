# Stagium

> Plateforme de recherche de stages pour étudiants
> Internship search platform for students

> Ce projet a été développé et testé sous **Arch Linux**. Les chemins système (`/etc/httpd/`, `/srv/http/`, etc.) sont spécifiques à Arch. Sur d'autres distros ces chemins peuvent différer.
> This project was developed and tested on **Arch Linux**. System paths (`/etc/httpd/`, `/srv/http/`, etc.) are Arch-specific. They may differ on other distributions.

---

## 🇫🇷 Français

### Présentation

Stagium est une application web MVC développée en PHP permettant aux étudiants de rechercher des offres de stage, de postuler, et de gérer leurs candidatures.

Les pilotes de promotion peuvent suivre les candidatures de leurs étudiants, et les administrateurs disposent d'un accès complet à la plateforme.

---

### Fonctionnalités

- **Authentification** : Connexion/déconnexion avec gestion des rôles (étudiant, pilote, administrateur)
- **Offres de stage** : Recherche multicritères, affichage, création, modification, suppression, compétences associées
- **Candidatures** : Dépôt de CV et lettre de motivation, suivi des candidatures
- **Entreprises** : Recherche, fiche détaillée, évaluations, sites
- **Wishlist** : Ajout/retrait d'offres en favoris
- **Gestion des utilisateurs** : CRUD étudiants et pilotes
- **Promotions** : Création et gestion des promotions, affectation des étudiants et pilotes
- **Statistiques** : Indicateurs clés sur les offres (total, moyennes, top wishlist, répartition)
- **Responsive** : Burger menu et sidebar pour mobile

---

### Stack technique


| Composant        | Technologie                                               |
| ------------------ | ----------------------------------------------------------- |
| Serveur          | Apache 2.4 + HTTPS                                        |
| Backend          | PHP 8.5, MVC maison, Twig, PDO                            |
| Base de données | MariaDB                                                   |
| Frontend         | HTML5, CSS3, JavaScript                                   |
| Sécurité       | HTTPS, CSRF, sessions sécurisées, requêtes préparées |

---

### Installation rapide (si Apache, PHP, MariaDB déjà configurés)

#### 1. Cloner le dépôt

```bash
git clone https://github.com/Mulooooo/stagium.git
cd stagium
```

#### 2. Configurer l'environnement

Créer le fichier `.env` :

```bash
cat <<EOF > .env
DB_HOST=127.0.0.1
DB_NAME=stagium
DB_USER=nom_utilisateur
DB_PASS=mot_de_passe
EOF
```

#### 3. Installer les dépendances

```bash
composer install
```

#### 4. Configurer Apache et initialiser la base de données

```bash
./setup.sh
./scripts/init_db.sh
```

#### 5. Déployer

```bash
./deploy.sh
```

---

### Guide d'installation complet — Arch Linux

#### 1. Installer les paquets nécessaires

```bash
sudo pacman -S apache php php-apache mariadb composer git openssl
```

#### 2. Configurer PHP

Dans `/etc/php/php.ini`, décommenter :

```ini
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=fileinfo
```

#### 3. Initialiser MariaDB

```bash
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
sudo systemctl start mariadb
sudo mariadb -u root
```

Dans MariaDB :

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
FLUSH PRIVILEGES;
EXIT;
```

#### 4. Créer le dossier projet

```bash
sudo mkdir -p /srv/http/stagium
sudo chown -R http:http /srv/http/stagium
```

#### 5. Cloner le projet

```bash
cd /srv/http/stagium
git clone https://github.com/Mulooooo/stagium.git .
```

#### 6. Créer le fichier .env

```bash
cat <<EOF > .env
DB_HOST=127.0.0.1
DB_NAME=stagium
DB_USER=root
DB_PASS=votre_mot_de_passe
EOF
```

#### 7. Générer le certificat SSL auto-signé

```bash
sudo mkdir -p /etc/httpd/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \   
  -keyout /etc/httpd/ssl/stagium.key \
  -out /etc/httpd/ssl/stagium.crt \
  -subj "/CN=stagium.fr" \
  -addext "subjectAltName=DNS:stagium.fr,DNS:assets.stagium.fr"
```

#### 8. Configurer les hôtes locaux

Ajouter dans `/etc/hosts` :

```
127.0.0.1   stagium.fr assets.stagium.fr
```

#### 9. Configurer Apache, initialiser la BDD et déployer

```bash
./setup.sh
./scripts/init_db.sh
./deploy.sh
```

#### 10. Démarrer les services

```bash
sudo systemctl enable --now httpd mariadb
```

#### 11. Accepter le certificat auto-signé

Aller sur `https://stagium.fr` et `https://assets.stagium.fr` dans le navigateur et accepter l'exception de sécurité.



---

## 🇬🇧 English

### Overview

Stagium is an MVC web application built in PHP that allows students to search for internship offers, apply, and manage their applications.

Promotion pilots can track their students' applications, and administrators have full access to the platform.

---

### Features

- **Authentication**: Login/logout with role management (student, pilot, administrator)
- **Internship offers**: Multi-criteria search, display, create, edit, delete, associated skills
- **Applications**: CV and cover letter upload, application tracking
- **Companies**: Search, detailed profile, evaluations, sites
- **Wishlist**: Add/remove offers from favorites
- **User management**: Student and pilot CRUD
- **Promotions**: Create and manage promotions, assign students and pilots
- **Statistics**: Key indicators on offers (total, averages, top wishlist, distribution)
- **Responsive**: Burger menu and sidebar for mobile

---

### Tech stack


| Component | Technology                                        |
| ----------- | --------------------------------------------------- |
| Server    | Apache 2.4 + HTTPS                                |
| Backend   | PHP 8.5, custom MVC, Twig, PDO                    |
| Database  | MariaDB                                           |
| Frontend  | HTML5, CSS3, JavaScript                           |
| Security  | HTTPS, CSRF, secure sessions, prepared statements |

---

### Quick install (if Apache, PHP, MariaDB already configured)

#### 1. Clone the repository

```bash
git clone https://github.com/Mulooooo/stagium.git
cd stagium
```

#### 2. Configure the environment

Create the `.env` file:

```bash
cat <<EOF > .env
DB_HOST=127.0.0.1
DB_NAME=stagium
DB_USER=your_username
DB_PASS=your_password
EOF
```

#### 3. Install dependencies

```bash
composer install
```

#### 4. Configure Apache and initialize the database

```bash
./setup.sh
./scripts/init_db.sh
```

#### 5. Deploy

```bash
./deploy.sh
```

---

### Full installation guide — Arch Linux

#### 1. Install required packages

```bash
sudo pacman -S apache php php-apache mariadb composer git openssl
```

#### 2. Configure PHP

In `/etc/php/php.ini`, uncomment:

```ini
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=fileinfo
```

#### 3. Initialize MariaDB

```bash
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
sudo systemctl start mariadb
sudo mariadb -u root
```

In MariaDB:

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_password';
FLUSH PRIVILEGES;
EXIT;
```

#### 4. Create the project directory

```bash
sudo mkdir -p /srv/http/stagium
sudo chown -R http:http /srv/http/stagium
```

#### 5. Clone the project

```bash
cd /srv/http/stagium
git clone https://github.com/Mulooooo/stagium.git .
```

#### 6. Create the .env file

```bash
cat <<EOF > .env
DB_HOST=127.0.0.1
DB_NAME=stagium
DB_USER=root
DB_PASS=your_password
EOF
```

#### 7. Generate a self-signed SSL certificate

```bash
sudo mkdir -p /etc/httpd/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/httpd/ssl/stagium.key \
  -out /etc/httpd/ssl/stagium.crt \
  -subj "/CN=stagium.fr" \
  -addext "subjectAltName=DNS:stagium.fr,DNS:assets.stagium.fr"
```

#### 8. Configure local hosts

Add to `/etc/hosts`:

```
127.0.0.1   stagium.fr assets.stagium.fr
```

#### 9. Configure Apache, initialize DB and deploy

```bash
./setup.sh
./scripts/init_db.sh
./deploy.sh
```

#### 10. Start services

```bash
sudo systemctl enable --now httpd mariadb
```

#### 11. Accept the self-signed certificate

Go to `https://stagium.fr` and `https://assets.stagium.fr` in your browser and accept the security exception.
