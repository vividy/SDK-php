Payname PHP SDK
=========

Ce SDK est en cours d'écriture, il n'est pas encore prêt pour la production mais peut vous permettre de découvrir les API PAYNAME.

Pour plus de détail, n'hésitez pas à consulter la page [https://api.payname.fr]

Paramétrage
--------------

#### Authentification

Après avoir créé un compte sur [https://api.payname.fr/] vous pourrez récupérer vos identifiants directement sur votre compte utilisateur [https://api.payname.fr/compte].

![Identifiants d'authentification](https://api.payname.fr/images/api_authentification.png)

le secret que vous choisirez vous permettra de choisir votre environement (developpement / production).

Ces identifiants doivent etre placé de la manière suivante dans un fichier "config.inc.php" placé à la racine du projet.
``` php
define("PAYNAME_ID","Votre_ID");
define("PAYNAME_SECRET","Votre_Secret");
```

#### URL de retour (token d'authentification)

Il faut tout d'abord paramétrer sur votre compte [https://api.payname.fr/compte] l'url retour pour la faire pointer vers le fichier [token.php](public/token.php).

> ###### On voit immédiatement que l'API ne peut pas tourner en local ! 
> Il faut que nos serveurs puissent vous renvoyer une adresse sur un URL public 
> ( Adresse IP public et port peuvent etre pris en compte ) 

#### Stockage des tokens

l'API doit stocker les tokens d'authentification sur le serveur.

Pour cette exemple nous les stockons dans un fichier temporaire.

Il faut donc spécifier ce fichier avec son chemin dans le fichier "config.inc.php" placé à la racine du projet.
``` php
define('PAYNAME_TEMP_FILE', __DIR__."/token.tmp");
```
> ###### Attention, ce fichier temporaire doit etre accesible en lecture et en écriture
> par l'utilisteur qui exécute votre script PHP !

Si vous souhaitez stocker les tokens par un autre biais (SBDD) vous pouvez ré-écrire les fonction du model [payname_model.inc.php](sdk/payname_model.inc.php)

#### Accès

Enfin, vous devez faire pointer votre serveur web directement dans le dossier [public](public/) du projet.