LOREM IPSUM
Ceci est la version en PHP avec SQLite de mon projet à présenter au jury pour le passage Titre Professionnel Développeur Web & Web Mobile

Configuration requise
Projet réalisé sous PHP 8.2, avec SQLite3 et Bootstrap 5.2.3.

Installation

Clonez ce dépôt : git clone https://github.com/Thor0lf/php-sqlite-blog.git
Placez-vous dans le répertoire du projet : cd php-sqlite-blog
Installez les dépendances avec Composer : composer install
créer un fichier data.db à la racine du projet
Configurez le fichier .env à la racine du projet en renseignant les informations suivantes:
    -EMAIL_SMTP_SERVER = string (ex: 'smtp.gmail.com')
    -PORT_SMTP_SERVER = int (ex: 465)
    -SMTP_AUTH = string (ex: 'true')
    -EMAIL_USER = string (ex: adresse@email.com)
    -EMAIL_PASSWORD = string (ex: 'password1')
    -EMAIL_TO = string (ex: contact@email.com)
    -EMAIL_FROM = string (ex: no_reply@email.com)

Dans un terminal, depuis le répertoire du projet : php -S localhost:8000 -d error_reporting=E_ALL -t public
Accédez au projet dans votre navigateur à l'adresse http://localhost:8000

Utilisation
Le premier utilisateur inscrit aura automatiquement le rôle d'administrateur. Les suivants seront de simples utilisateurs.
Avant de créer un article, il faut d'abord penser à créer la catégorie correspondante.

Auteurs
Thomas Audouin

Licence
Projet sous licence MIT

