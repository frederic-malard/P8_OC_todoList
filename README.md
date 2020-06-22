# installez le projet

Pour installer le projet, pour créer une copie personnelle chez soit, suivez les étapes décrites ici. Pour un travail en groupe, suivez plutôt les étapes décrites dans le fichier "modifier.md".

## clonez le projet

### sur github

* rendez vous sur https://github.com/FredEtRick/P8_OC_todoList
* cliquez sur le bouton vert à droite "clone or download"
* copiez le lien fourni

### en lignes de commande

* ouvrez un terminal
* déplacez vous dans le dossier désiré
* tapez en ligne de commande : git clone [collez le lien ici]
* le projet est récupéré

## complétez le projet

Il vous reste encore à compléter le projet avec des dossiers habituels faciles à récupérer ou des fichiers sensibles.

* récupérez ce qu'il faut en ligne de commande : composer install
* modifiez le fichier .env.aModifier, notament la ligne DATABASE_URL en fin de fichier, en la renseignant avec les données adéquates
* (ligne de commande) php bin/console doctrine:database:create
* (ligne de commande) php bin/console doctrine:migrations:migrate



(migrations, créations BDD)