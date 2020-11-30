# Snowtricks

Création d'un site communautaire de partage de figures de snowboard en utilisant le framework Symfony, dans le cadre de la formation OpenClassroom Développeur d'application - PHP / Symfony.

## Environnement de développement

*   Docker version 19.03.12
    - PHP7.4-fpm
    - nginx
    - Mariadb
*   Linux
*   Composer 1.6.3
*   git 2.17.1
*   nodejs 8.10.0

## Installation

Clonez le repository Github

```
git clone https://github.com/ampueropierre/Snowboard.git
```

À l'intérieur du dossier, créer deux dossiers pour la base de donnée et les logs

```
mkdir database logs
```

Créer un fichier `.env.local` à la racine du projet en réalisant une copie du fichier `.env` afin de configurer les variables d'environnement

Installer les dépendances

```
composer install
```

Lancer les containers

```
docker-compose up -d
```

Entrer dans le container php afin de lancer les commandes pour la BDD

```
docker exec -it [le nom du container php] bash
```

Dans le container lancer la commande

```
bin/console doctrine:schema:create
```

Et installer la fixture (démo de données fictives)

```
bin/console doctrine:fixtures:load --append
```

Quitter le container

```
exit
```

Installez les dépendances front-end du projet avec Yarn dans le dossier symfony

```
yarn install
```

Créer un build d'assets (grâce à Webpack Encore) avec Yarn

```
yarn encore dev
```

Tester l'administration du site avec le compte admin

> login: admin@snowtricks.com
> 
> mdp: admin

Les mails seront captés par le container Maildev, pour s'y rendre il faut rentrer l'adresse http://localhost:8002/

Enjoy!
