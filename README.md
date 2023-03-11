# Test technique pour Artmajeur


Comme demandé, j'ai réalisé un formulaire de contact basé sur Symfony.

Il se compose simplement d'un formulaire de contact avec une vérification coté back de l'input email.
Ce formulaire n'est pas directement lié à une entité, car il existe deux entités : Client et Message.
Ces deux entités sont liées en ManyToOne/OneToMany, et pour chaque nouvelle adresse mail détecter, un nouveau client est alors crée, sinon, seulement un nouveau message.

Un message peut avoir 3 statuts différents : non lu, lu et terminé. Ces 3 statuts sont des constante et donc il est tout à fait possible d'en rajouter ultérieurement sans grand changement dans le code.
Depuis la partie administrateur, il est alors possible de modifier ce statut.
Comme demandé, nous retrouvons distinctement tous les messages pour chaque client.


Pour finir, a chaque nouveau message, un fichier json est crée contenant les informations de ce dernier.
Ce fichier json est crée à la racine des fichier Symfony, plus précisément dans le dossier ressources/messages.
Cela permet qu'il ne soit pas directement accessible depuis le serveur web.
Depuis la partie administration, il est possible de télécharger tout les fichier json dans un zip.


Concernant le visuel, j'ai simplement utilisé Bootstrap par praticité et rapidité. Il reste cependant rudimentaire.

# Installation

- 1 . cloner le répo
- 2 . composer install
- 3 . yarn install
- 4 . yarn build 
- 5 . configurer la bdd dans le .env.local
- 6 . php bin/console doctrine:database:create
- 7 . php bin/console doctrine:migration:migrate 
- 8 . php bin/console doctrine:fixture:load (mail : admin@amdin.fr ; mdp : bonjour)
- 9 . lancer le serveur local 
- 10 . tout est prêt 
