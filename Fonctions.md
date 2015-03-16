# Fonctions #

## Urls ##

  * Récupérer l'url du serveur :
```
getServeurUrl();```

  * Récupérer l'identifiant de la rubrique du gabarit correspondant
```
getRubFromGabarit('genActualites')```

  * Récupérer l'identifiant de la première rubrique qui a comme gabarit la classe passée en paramètre
```
getRubriqueByGabarit('genActualites')```

  * Récupérer l'url d'une rubrique ayant pour gabarit 'genActualites' :
```

//Récupère l'url de la rubrique ayant pour gabarit 'genActualites'
getUrlFromId(getRubFromGabarit('genActualites'));
//Récupère l'url précédente, en y rajoutant des paramètres :
LG étant une constante récupérant le code de la langue actuelle, et actu un paramètre
getUrlFromId(getRubFromGabarit('genActualites'), LG, array('actu'=>5));
```

## Gestion des erreurs ##

Vous pouvez utiliser la fonction _debug()_  ou _error()_pour afficher du contenu dans une infobulle;
```

//Affichera simplement "TEST"
derror('TEST');
//Affichera 'TEST' puis la liste des fichiers inclus et des appels aux fonctions
debug('TEST');
//Idem que debug()
error('TEST');
//Afficher une infobulle verte
dinfo('TEST');
```

## Fichiers ##

### Images ###

  * Récupérer l'url complet d'une image :
```

//function getImg(table, champ, id, row)
getImg('a_actualite', 'actualite_img', $idActu, '');
//Equivaut à :
$actu = new row('a_actualite',$idActu);
$actu->actualite_img;
```

  * Récupérer l'url d'une image en _Thumbnail_ :
```

//function getThumb($table, $champ, $id, $row = array(), $w, $h)
//Vous pouvez ne pas spécifier la hauteur en mettant 0
getThumb('a_actualite', 'actualite_img', $idActu, '', 300, 400);
```

  * Récupérer l'url d'une image en _Crop_ :
```

//function getCrop($table, $champ, $id, $row = array(), $w, $h)
getCrop('a_actualite', 'actualite_img', $idActu, '', 300, 400);
//Vous pouvez ne pas spécifier la hauteur en mettant 0
```


## Contenu ##

  * Limiter le nombre de mots et rajouter une chaine
```

//function limitWords($str, $nbwords = 30, $tpp = ' ...')
echo limitWords($str, 50, '...');
```

  * Limiter le nombre de caractères et rajouter "..."
```

//function limit($str, $nchars = 30)
echo limit($str, 50);
```

## E-Mails et formulaires ##

  * Vérifier la validitié d'un mail :
```

CheckEmail('email@opixido.com');
//Equivaut à :
isMail('email@opixido.com');
```

  * Envoyer un mail
```
sendMail($to, $subject, $message, $headers)```

  * Déterminer si le fichier est une image
```
isImage($str);```