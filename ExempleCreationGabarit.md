<h1><u>Un exemple de gabarit de page : Les actualités</u></h1>

Nous allons voir dans cet exemple comment créer un gabarit simple mais très utile : les actualités.

> ### Creation du dossier et des fichiers ###

  * Commencez par créer un dossier ("actualites" par exemple) dans le dossier des plugins.
  * Ajoutez dans ce dossier créé un fichier PHP nommé "genActualites".
  * Créez-y une classe genActualites héritant de la classe ocmsGen (class genActualites extends ocmsGen {})
    1. ...
  * Créez ensuite dans le dossier de votre plugin :
    1. un fichier "front.php" (contiendra la classe Actualite)
    1. un fichier "config.php" (fichier de config pour le back-office)
    1. un dossier "tpl" (contient le template)
    1. dans le dossier "tpl", un template "actualites.php"
    1. un dossier "forms"
    1. dans le dossier "forms", un fichier "actualites.forms.php" (contient les champs de la table à remplir dans le BO)

Vous devriez obtenir cette arborescence :

![http://www.opixido.com/ocms/screen/gabarit/gabarit-arbo-actualites.png](http://www.opixido.com/ocms/screen/gabarit/gabarit-arbo-actualites.png)

> ...


### Insertion du gabarit dans la base de donnees ###

  * Insérer dans la table s\_gabarit une ligne :
| gabarit\_titre | gabarit\_classe | gabarit\_plugin |
|:---------------|:----------------|:----------------|
| Actualités | genActualites | actualites |


### Ajouter le gabarit dans une rubrique ###

![http://www.opixido.com/ocms/screen/gabarit/gabarit-actus.png](http://www.opixido.com/ocms/screen/gabarit/gabarit-actus.png)


### Creation de la table actualites ###

  * Créez une nouvelle table (exemple : "a\_actualite")
  * Ajoutez ses attributs :
    1. Un identifiant : "actualite\_id" (INT (11) en auto-incrémente)
    1. Le nom de l'actualité : "actualite\_nom\_fr" (VARCHAR) (ajoutez de même "actualite\_nom\_en" pour la version anglaise. Plus d'infos sur la gestion multilingue [ici](http://code.google.com/p/opixido-ocms/wiki/GestionLangues))
    1. Le contenu de l'actualité : "actualite\_contenu\_fr" (TEXT)
    1. Une image ! "actualite\_img" (VARCHAR)
    1. Une date : "actualite\_date" (DATETIME)
    1. Un booléen pour la mise en ligne : "en\_ligne" (TINYINT(1))

| <u>actualite_id</u> | INT(11), auto-incremente |
|:--------------------|:-------------------------|
| actualite\_titre\_fr | VARCHAR(250) |
| actualite\_titre\_en | VARCHAR(250) |
| actualite\_contenu\_fr | TEXT |
| actualite\_contenu\_en | TEXT |
| actualite\_img | VARCHAR(250) |
| actualite\_date | DATETIME |
| en\_ligne | TINYINT(1) |

Les types de champs et attributs générés automatiquement par OCMS sont listés [sur cette page](http://code.google.com/p/opixido-ocms/wiki/configurationBO)


### Creation du template de la liste des actualites ###

  * Ouvrez votre template actualites.php qui se trouve dans le dossier "tpl"
  * Introduisez-y votre code HTML et vos variables ( sous la forme @@maVariable@@ )
    1. Si vous souhaitez boucler un bloc, placez celui-ci entre une balise XML : 

&lt;ACTUALITE&gt;

`<div>Votre code</div>`

&lt;/ACTUALITE&gt;


  * Voici à quoi devrait finalement ressembler votre template :
![http://www.opixido.com/ocms/screen/gabarit/gabarit-actualites-template.png](http://www.opixido.com/ocms/screen/gabarit/gabarit-actualites-template.png)


Plus d'infos sur la fonction _t()_ et les textes paramétrables [ICI](http://code.google.com/p/opixido-ocms/wiki/TextesParam)


### Creation de la classe Actualite ###

```php

class Actualite extends row
{
function __construct( $roworid) {
parent::__construct('a_actualite', $roworid);
}

function getTitle()
{
return $this->actualite_titre;
}

function getContenu()
{
return $this->actualite_contenu;
}

function getImg($w,$h)
{
if($w && $h)
return $this->actualite_img->getCropUrl($w,$h);
else
return $this->actualite_img;
}


}
```

Vous pouvez déclarer cette classe dans _genActualités.php_ ou même dans _config.php_

### Developpement de la liste des actualites ###

  * Ouvrez le fichier genActualites.php
  * Si ce n'est pas encore fait, définissez-y la classe _genActualites_ héritant d'_ocmsGen_
  * Créez une fonction _gen()_
C'est dans la fonction _gen()_ que nous développerons tout le traitement.
```php

class genActualites extends ocmsGen
{
public function gen(){

}
}
```
  * Chargez le template précédemment créé et générez-le :
```php

public function gen(){

$tpl = new genTemplate();
$tpl->loadTemplate('actualites', 'plugins/actualites/tpl');
return $tpl->gen();
}
```

  * Récupérez ensuite toutes vos actualités qui sont en ligne grâce à la fonction _doSql()_ :
```php

//la fonction doSql() renvoie le résultat sous forme de tableau
$actualites = doSql(' SELECT * FROM a_actualite WHERE en_ligne=1 ORDER BY actualite_date');
```


  * Si on obtient un résultat, on boucle. Sinon, on affiche qu'il n'y a aucune actualité.
```php

//La fonction recordCount() compte le nombre de lignes obtenues
if($actualites->recordCount()>0)
{
//On boucle
foreach($actualites as $row)
{
//On définit le bloc sur lequel on boucle
$bloc = $tpl->addBlock('ACTUALITE');
}
}
//Si on obtient aucun résultat, on supprime le bloc
else
{
$tpl->delBlock('ACTUALITE');
echo t('aucune_actualite');
}
```

  * On va ensuite déclarer l'actualité dans la boucle, puis ajouter dans le template ses valeurs

```php

// ...
foreach($actualites as $row)
{
//On définit le bloc sur lequel on boucle
$bloc = $tpl->addBlock('ACTUALITE');

//On déclare notre actualite : on lui envoie la ligne $row
//Cette déclaration équivaut à $actu = new row('a_actualite',$row);
$actu = new Actualite($row);

//On renvoie les valeurs des variables définies dans le template
// (image, titre, date et contenu)

//Le titre
//pas besoin de préciser le langage, ocmsGen s'en charge pour vous
$bloc->titre = $actu->actualite_titre;
//De même pour le contenu
$bloc->contenu = $actu->actualite_contenu;
//On affiche la date
$bloc->date = $actu->actualite_date;
//Le CMS renvoie automatiquement l'url complet de l'image
$bloc->img = $actu->actualite_img;
}
// ...
```

Le code complet du fichier :

```php

class genActualites extends ocmsGen
{
public function gen(){

//On charge le template
$tpl = new genTemplate();
$tpl->loadTemplate('actualites', 'plugins/actualites/tpl');

//On récupère les données de la table a_actualite
$actualites = doSql(' SELECT * FROM a_actualite WHERE en_ligne=1 ORDER BY actualite_date');

if($actualites->recordCount()>0)
{
//On boucle
foreach($actualites as $row)
{
//On définit le bloc sur lequel on boucle
$bloc = $tpl->addBlock('ACTUALITE');

//On déclare notre actualité
$actu = new Actualite($row);

//On ajoute les valeurs de l'actualité aux variables du template
$bloc->titre = $actu->actualite_titre;
$bloc->contenu = $actu->actualite_contenu;
$bloc->date = $actu->actualite_date;
$bloc->img = $actu->actualite_img;
}
}
//Si on obtient aucun résultat, on supprime le bloc
else
{
$tpl->delBlock('ACTUALITE');
echo t('aucune_actualite');
}

//On retourne et génère template
return $tpl->gen();
}
}
```

### Developpement de l'affichage d'une actualite ###

  * Créez un nouveau template "_actualite.php_" dans le dossier "tpl".
![http://www.opixido.com/ocms/screen/gabarit/gabarit-actualite-template.png](http://www.opixido.com/ocms/screen/gabarit/gabarit-actualite-template.png)

  * Rajoutez dans la classe Actualite déclarée précédemment une fonction permettant de récupérer l'url de l'actualité :

```php

class Actualite extends ocmsGen
{
//...

//Renvoie la lien pour visionner l'actualité
function getUrl()
{
return getUrlFromId(getRubriqueByGabarit('genActualites'), LG, array('actualite'=>$this->actualite_id));
}

//getUrlFromId permet de récupérer l'Url complet d'une page grâce à son Id. On peut rajouter la langue et d'autres paramètres.
//getRubriqueByGabarit permet de récupérer l'id de la page grâce au gabarit
//On ajoute la langue (constante LG) et une actualite en paramètres
}
```

  * Dans la classe genActualites, renommez la fonction _gen()_ en _genListeActualites()_ par exemple.
  * Ajoutez une fonction _genActualite()_ pour l'affichage d'une seule actualité

```PHP

public function genActualite()
{
//On vérifie que l'actualité est en paramètre et existe (explication plus bas)
if($this->actualite)
{
//On charge le nouvau template
$tpl = new genTemplate();
$tpl->loadTemplate('actualite', 'plugins/actualites/tpl');

//On affecte les valeurs aux variables du template
$tpl->img = $this->actualite->getImg();
$tpl->titre = $this->actualite->actualite_titre;
$tpl->date = $this->actualite->actualite_date;
$tpl->contenu = $this->actualite->actualite_contenu;
}
}
```

  * Redéclarez une fonction _gen()_ comme ceci :

```PHP

public function gen()
{
return $this->genListeActualites();
}
```

  * Définissez une fonction _init()_ dans votre classe genActualites. Cette fonction se lance avant toutes les autres (avant _gen()_). Elle va nous permettre de vérifier si l'Url contient en paramètre une actualité ou non. Si elle contient une actualité et qu'elle est valide, alors on chargera _genActualite()_.

```

class genActualites extends ocmsGen
{
//
public $actualite=false;

function init()
{
//Ajouter un fichier css (dans le dossier /css)
$this->site->g_headders->addCss('actualites.css');

//On regarde si l'Url contient un paramètre 'actualite'
if(!empty($_REQUEST['actualite']))
{
//On vérifie que l'actualite existe et est en ligne
//getSingle() retourne une seule ligne, et sous forme de tableau
//La fonction sql() permet de convertir proprement une variable en string
$res = getSingle('SELECT * FROM a_actualite WHERE en_ligne=1 AND actualite_id='.sql($_REQUEST['actualite']));
//Si on obtient un résultat, c'est qu'elle existe
if($res)
{
$this->actualite = new Actualite($res);
}
}
}

function gen()
{
//On vérifie si une actualite existe
if($this->actualite) return $this->genActualite();
else return $this->genListeActualites();
}

//...
}
```

Vous pouvez modifier le template de la page par un autre :
> - Créez un nouveau fichier php dans le dossier _include/exports_ portant le nom du nouveau template de page.
Puis, dans votre gabarit, déclarez-le dans la fonction _init()_ comme suit :
```

$this->site->g_url->TEMPLATE = 'monTemplate';
//l'extension et le chemin ne sont pas nécessaires.
```

Pour ajouter ensuite dans le back-office la gestion des Actualités, rendez-vous sur [cette page](GabaritBO.md)