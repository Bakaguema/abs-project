# boomerang_project_2
Aucun commit sur cette branche, les banches de travail sont les spN <br>

Le github provient du site beta a date du 02/01/2023. Celui ci est maintenant mise a jour correctement & liée au sous-domaine https://dwwm.generation-boomerang.com/

## Installation du projet <strong>PhP >=version 8.0.xx</strong>

Ces instructions vous permettront d'installer et d'exécuter l'application sur votre machine locale.

### Prérequis

Voici les éléments obligatoires pour pouvoir utiliser le projet 

Commencer par cloner le projet :

```
git clone https://github.com/Aleizy/boomrang_projet_2.0.git
```

Activer sodium & gd dans xampp php.ini

Installer les dépendances :

```
composer install
```

#### Reprendre le SQL directement sur Plesk > Domain > dwwm.generation-boomerang.com > Database > dwwm > phpmyadmin > exporter

Ou passer par les commandes Symfony :

```
php bin/console doctrine:database:create
```

```
php bin/console make:migration
```

```
php bin/console doctrine:migrations:migrate
```

## Procédure pour lancer le HUB Mercure

En SSH en "root", aller dans dans le dossier Mercure :

``` 
cd var/www/vhosts/generation-boomerang.com/mercure
```

Puis lancer cette commande :

```
SERVER_NAME=":3000" \
MERCURE_PUBLISHER_JWT_KEY='TW0SlJ2PXCdRXKTsgvTNEqnsBcSEsfxZb' \
MERCURE_SUBSCRIBER_JWT_KEY='TW0SlJ2PXCdRXKTsgvTNEqnsBcSEsfxZb' \
./mercure run
```
