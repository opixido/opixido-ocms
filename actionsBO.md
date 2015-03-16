# L'ajout d'actions et de pages dans le Back-Office #

## Ajouter une action ##

### Ajouter une action dans le menu de droite ###
("Actions administrateur")

```
$_Gconfig['globalActions'][]= 'nomFonction';```

Vous pourrez ensuite créer la fonction, qui va par exemple charger une page :

```

function nomFonction()
{
//On peut inclure des fichiers
$GLOBALS['gb_obj']->includeFile('fichier.php','plugins/nomPlugin/');
//On affiche ce que doit contenir la page
echo'Ma nouvelle page';
}
```

### Ajouter une action sur une table ###

Pour ajouter une action **sur une table** :
```
$_Gconfig['tableActions']['nomTable'][] = 'nomFonction';```

### Ajouter une action sur les lignes d'une table ###

```

//Ajoute le lien de l'action/bouton
$_Gconfig['rowActions']['nomTable']['nomAction']= true;
//Ajoute une image pour ce lien/bouton
$admin_trads['src_nomAction']['fr'] = ADMIN_PICTOS_FOLDER."24x24/mimetypes/image.png";
```

Le nom de la classe doit obligatoirement commencer par "genAction" et implémenter la classe "ocms\_action".

```

class genActionNomAction extends ocms_action
{
//Vérification avant action
public function checkCondition() {  }

//Code de l'action
public function doIt() { }
}
```

### Ajouter une action "afficher/masquer" ###

Si votre table contient un champ "en\_ligne", vous pouvez ajouter facilement un lien/bouton pour masquer ou afficher une ligne :
```
$_Gconfig['hideableTable'][] = 'nomTable';```

### Ajouter une action "Valider" ###

Votre table doit contenir les champs : "fk\_version" et "en\_ligne" avant de pouvoir ajouter un bouton "Valider".
Puis ajoutez :

```
$_Gconfig['versionedTable'][] = 'nomTable';```

## Exécuter une action lors d'une modification ##

Actions POST edition :
```

//Après la création d'une ligne
$gr_on['insert']['nomTable'][] = 'nomFonction';
//Après suppression
$gr_on['beforeDelete']['nomTable'][]  = 'nomFonction';
//Après modification
$gr_on['update']['nomTable'][]  = 'nomFonction';
//Après enregistrement
$gr_on['save']['nomTable'][]  = 'nomFonction';
//Après validation
$gr_on['validate']['nomtable'][]  = 'nomFonction';
//Toutes les tables
$gr_on['save']['ANY_TABLE'][]  = 'nomFonction';
```

```

$gr_on['beforeDelete']['nomDuChamp'] = 'nomFonction';
$gr_on['insert']['nomDuChamp'] = 'nomFonction';
$gr_on['hideObject']['nomDuChamp'] = 'nomFonction';
$gr_on['showObject']['nomDuChamp'] = 'nomFonction';
$gr_on['recorded']['nomDuChamp'] = 'nomFonction';
$gr_on['fileUploaded']['nomDuChamp'] = 'nomFonction';

$_Gconfig['reloadOnChange'][] = 'nomChamp';
//La fonction doit retourner une chaîne de caractères comportant une condition SQL
$_Gconfig['specialListingWhereFullArbo']['nomTable'] = 'nomFonction';
```