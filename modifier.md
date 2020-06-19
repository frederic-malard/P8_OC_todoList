# MODIFIER LE PROJET EN GROUPE

## règles à suivre

Afin de travailler correctement, il y a plusieurs règles à suivre pour assurer la qualité du code :

* commentez votre code
* nommez vos variables et fonctions en camelCase et en anglais, afin de pouvoir intégrer n'importe qui, et de pouvoir demander de l'aide
* le texte destiné à être lu par les visiteurs doit être en français
* les routes sensibles doivent être protégées si nécessaire par l'annotation IsGranted vue précédement
* le projet a été fait en utilisant des migrations pour mettre à jour la base de données, nous vous conseillons donc de continuer sur cette lancée
* versionnez le projet grâce à git et github
* testez régulièrement le code.

## les tests

### 3 types de tests

Il existe 3 types de tests :
* tests unitaires : on teste une unité de code isolée, un morceau de notre application. Par exemple, une méthode.
* Tests fonctionnels : on teste notre code dans un contexte. Par exemple, en utilisant la base de données
* Tests end to end : on lance automatiquement un navigateur (sans interface graphique en générale) et on teste directement notre site automatiquement.

Nous ne faisons pas de test end to end dans ce projet, nous nous concentrons sur les deux premiers types de tests.

### 3 manières de tester au fil du projet

Il y a 3 façons possibles de mêler les tests au développement :
* Test Driver Development (TDD) : le développement par les tests. Il s'agit d'écrire un test, de vérifier qu'il échoue, d'implanter la fonctionnalité attendue, de relancer le test, et de modifier la fonctionnalité si besoin jusqu'à ce que le test se déroule avec succès. Il s'agit donc d'écrire les tests avant d'implémenter les fonctionnalités.
* Test first : écrivez un test, implémentez une fonctionnalité, puis recommencez avec la fonctionnalité suivante. Déconseillé en groupe.
* Tests pour vérifier : écrivez vos fonctionnalités, puis testez les à la fin.

Nous conseillons le TDD.

## git et github

* Pour travailler en groupe avec git, vous devez :
* Aller sur https://github.com/FredEtRick/P8_OC_todoList
* faire un fork
* cliquez sur « clone or download »
* copier l'url indiquée
* dans un terminal : git clone [coller l'URL ici]
* créer une branche : git branch [nom de la branche]
* se déplacer sur la branche créée : git checkout [nom de la branche]
* faire vos modifications
* si vous avez créé de nouveaux fichiers, et que vous voulez qu'ils soient versionnés, faites ceci :
..* git add [chemin]
* les comitter
..* git commit -am « message associé à votre commit »
* recommencer jusqu'à ce que la partie que vous étiez chargés de faire soit prête à être mélangée au reste du code source sur github
* envoyer les modification sur votre copie personnelle du projet sur github : git push origin [nom de la branche]
* se rendre sur la page de votre copie du projet sur github
* faire une pull request en cliquant sur le bouton "compare and pull request"
* renseigner les informations demandées par github
* cliquer sur le bouton "create pull request"

# Modifier le schéma de la base de données

## laissez la main à doctrine

Si vous souhaitez modifier le schéma de la base de données, ajouter une table, la modifier, supprimer, modifier des attributs, etc, ne modifiez jamais directement la table ! En symfony, c'est doctrine qui gère la table. Vous ne vous occupez que des entités, doctrine fait le reste.

## annotations

Ainsi, modifiez les entités, utilisez les annotations si nécessaire pour spécifier des choses relatives à la base de données (@UniqueEntity pour indiquer un champs qui doit être unique dans la table par exemple, cf la documentation de symfony pour plus d'informations).

## modifiez la base en lignes de commande

Une fois que c'est fait, vous pouvez ordonner à doctrine de modifier la base de données en conséquence, via les migrations, en procédent ainsi dans la CLI (command line interface) :
* php bin/console make:migration
..* créé un fichier de migration, accessible dans src > migrations
..* ce fichier peut être joué dans un sens ou dans l'autre (up et down) pour faire ou défaire les modifications
* php bin/console doctrine:migrations:migrate
..* compare le schéma en base de données aux migrations, puis joue toutes les migrations qui n'ont pas encore été prises en considération, pour mettre la base de données à jour
..* vous devrez confirmer les changements avec « Y »

## gare aux contraintes

Attention aux modifications que vous faites s'il existe déjà des données en base ! Elles seront refusées si elles impliquent une violation de contrainte. Par exemple, si un champs est vide pour certaines entrées en base, si vous le rendez obligatoire dans l'entité, puis que vous demandez à doctrine de prendre en compte les modifications, il y aura erreur.