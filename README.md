# MangaTrakr

![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![PhpStorm](https://img.shields.io/badge/phpstorm-143?style=for-the-badge&logo=phpstorm&logoColor=black&color=black&labelColor=darkorchid)
![Linux](https://img.shields.io/badge/Linux-FCC624?style=for-the-badge&logo=linux&logoColor=black)

Application web pour suivre la lecture des mangas. Suivre facilement sa progression pour une meilleure expérience de lecture.

## Fonctionnalités
- [ ] Import csv volumineux
- [ ] Authentification JWT
- [ ] Création des endpoints

## Pour commencer

### Pré-requis

- PHP >= 8.2
- Composer v2
- MariaDB

### Installation

Éxécutez les commandes ci-dessous pour installer le projet.

* Avec **Symfony CLI** +  **Commande make** :
```
- make first-install ## Pour installer les dépendences
```

## Démarrage

* Avec **Symfony CLI** + **Commande make** :
```
- make sf-start 
```

## Stack technique

* [Api Platform 3](https://api-platform.com/) - API-first Framework
* [Symfony 7](https://symfony.com/) - Framework PHP
* Doctrine ORM + MariaDB
* [doctrine/doctrine-fixtures-bundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)
* [fakerphp/faker](https://github.com/FakerPHP/Faker) - Librairie PHP

## Outils QA & Tests utilisés
* [friendsofphp/php-cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) - PHP Coding Standards Fixer
* [phpstan/phpstan](https://github.com/phpstan/phpstan) - PHP Static Analysis Tool
* [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights) - Static Analysis Tool
* [phpro/grumphp](https://github.com/phpro/grumphp) - 
* [symfony/test-pack](https://symfony.com/doc/6.4/testing.html#application-tests) - PHP Unit

## Ressources externes utilisées
* **ODRE (OPENDATA RÉSEAUX-ÉNERGIES)** : https://opendata.reseaux-energies.fr/