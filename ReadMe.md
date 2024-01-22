# Makers-management-system

API ayant pour but de gérer le matériel et les utilisateurs de Makers.

## Prérequis

Assurez-vous d'avoir installé les éléments suivants sur votre machine avant de commencer :

- [PHP](https://www.php.net/downloads.php) (version 7.4 ou supérieure)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)

## Installation

1. Clonez le projet sur votre machine :

```bash
git clone https://github.com/nnaova/cours-API.git
```

2. Accédez au dossier du projet :

```bash
cd cours-API
```

3. Installez les dépendances du projet :

```bash
composer install
```

4. Configurez les variables d'environnement :

Dupliquez le fichier `.env` en le renommant `.env.local` et modifiez les variables d'environnement selon votre configuration.   

5. Créez la base de données :

```bash
symfony console doctrine:database:create
```

6. Créez les tables de la base de données :

```bash
symfony console doctrine:migrations:migrate
```

7. Installez les fixtures (optionnel) :

```bash
symfony console doctrine:fixtures:load
```

8. Lancez le serveur :

```bash
symfony serve
```

## Documentation

La documentation de l'API est disponible à l'adresse suivante : [http://localhost:8000/api/doc](http://localhost:8000/api/doc)

## Contributeurs

- [Naova](github.com/nnaova)