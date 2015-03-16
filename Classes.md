<h1>Classes</h1>

## Classes ##

### Headers de page (genHeaders) _(include/global/genHeaders.php)_ ###

**Seulement dans le _init()_ ou _afterinit()_ du gabarit/plugin :**

  * Ajouter un nouveau "niveau" de titre
```
$this->site->g_headers->addTitle('Sous-titre');```

  * Ajouter un fichier CSS (dossier _css/_) :
```
$this->site->g_headers->addCss('fichier.css');```

  * Ajouter du texte CSS :
```
$this->site->g_headers->addCssText('border:none;');```

  * Ajouter un fichier Javasript (dossier _js/_) :
```
$this->site->g_headders->addScript('monScript.js')```

  * Ajouter du texte HTML :
```
$this->site->g_headers->addHtmlHeaders($text);```

  * Modifier le titre de la page
```
$this->site->g_headers->setTitle('Nouveau Titre');```

  * Modifier la balise Meta de description :
```
$this->site->g-headers->setMetaDescription('Nouvelle description');```

  * Modifier la balise Meta de mots-clés :
```
$this->site->g_headers->setMetaKeywords('nouveau, mot, clé');```

  * Récupérer le titre de la page, la description, les mots-clés, le HTML du header et les chemins CSS et JS de fichiers :
```php

$this->site->g_headers->getTitle();
$this->site->g_headers->getMetaDescription();
$this->site->g_headers->getMetaKeywords();
$this->site->g_headers->getHtmlHeaders();
$this->site->g_headers->getCssPath($listeFichiers);
$this->site->g_headers->getJsPath($listeFichiers);```


### Rubrique : genRubrique _(include/global/genRubrique.php)_ ###

  * Récupérer l'Id de la rubrique :
```
$this->site->g_rubrique->rubrique_id;```

  * Récupérer les paragraphes de la rubrique :
```
$this->site->g_rubrique->getParagraphes();```

  * Récupérer le chemin complet et le titre de la rubrique :
```
$this->site->g_rubrique->getFullTitle();```

  * Récupérer toutes les sous-rubriques de la rubrique (ligne de la table _s\_rubrique_) :
```
$this->site->g_rubrique->getSubRubs()```

  * Afficher ou masquer les paragraphes de la rubrique :
```

$this->site->g_rubrique->showParagraphes=false;
$this->site->g_rubrique->showParagraphes=true;```

  * Savoir si le plugin est actif ( retourne true ou false):
```
$this->site->g_rubrique->isActivePlugin()```

  * Savoir si on se trouve sur la rubrique modifiable, ou sur la rubrique en ligne :
```
$this->site->g_rubrique->isRealRubrique()```

  * Définir si on affiche la boîte lexique, de téléchargement, ou la boite "En savoir plus" :
```

$this->site->g_rubrique->showBoxLexique=false;
$this->site->g_rubrique->showBoxDwl=false;
$this->site->g_rubrique->showBoxLinks=false;
```

### Générer un template : genTemplate _(include/global/genTemplate.php)_ ###

Pour savoir comment créer un template dans un gabarit, rendez-vous sur [cette page](CreationGabarit#La_vue.md)

Commençons par créer un template html/PHP basique _(chemin/Du/Fichier/tpl.php)_
```html

<div id="monTemplate">

<h1>@@titre@@

Unknown end tag for &lt;/h1&gt;



<?php echo '<p>'.$this->contenu.'

Unknown end tag for &lt;/p&gt;

' ?>

<div id="items">

<ITEM>

<div id="@@id@@">@@nom@@

Unknown end tag for &lt;/div&gt;





Unknown end tag for &lt;/ITEM&gt;





Unknown end tag for &lt;/div&gt;





Unknown end tag for &lt;/div&gt;


```

Pour déclarer une variable dans votre template, vous pouvez utiliser ```
@@maVariable@@``` ou ```
<?php echo $this->maVariable ?>```

Implémentons maintenant notre template

```

//Déclaration du template
$tpl = new genTemplate();
//On charge le template html
$tpl->loadTemplate('tpl', 'chemin/Du/Fichier');

//On affecte des valeurs aux variables du template
//Un titre et du contenu par exemple;
$tpl->titre = $this->getTitre();
$tpl->contenu = $this->getDescription();

//On boucle sur les informations
$i=0;
//Si on a des résultats
if($results)
{
foreach($results as $resultat)
{
//On déclare le bloc ITEM
$item = $tpl->addBlock('ITEM');
//On affecte les valeurs
$item->id = $i;
$item->nom = 'Item n°'.$i;
}
}
//Sinon on supprime le bloc
else $tpl->delBlock('ITEM');
```

Autres opérations possibles :

```

//Définir un bloc : ceci évite de devoir le supprimer lorsqu'il n'est pas implémenté
$tpl->defineBlocks('ITEM');
//Remplacer le contenu du bloc
$tpl->remplaceBlock('ITEM', $replace);
//Equivaut à implémenter $tpl->titre
$tpl->setVar('titre', $this->getTitle());
//Savoir si une variable est définie dans le template : return true ou false
$tpl->isDefined('contenu');
```

### Créer un cache : genCache _(include/global/gencache.php)_ ###

Chaque rubrique possède un cache. Vous pouvez aussi créer un cache pour votre application.

```

//Déclaration du cache
$cache = new genCache($idDuCache, $derniereDateModif);
//Si le cache existe et si le contenu n'a pas été dernièrement modifié
if($cache->cacheExits())
echo $cache->getCache();
else
{
//On récupère le contenu
$html= doSomething();
//On le met en cache
$cache->saveCache($html);
echo $html;
}
```

### genParagraphes _(include/global/genParagraphes.php)_ ###

Vous pouvez affecter des paragraphes à une table. Vous pouvez par exemple afficher plusieurs paragraphes dans une actualité ([tutoriel de création sur cette page](ExempleCreationGabarit.md)).

Commencez par rajouter dans la table _s\_paragraphe_ une colonne faisant référence à votre table. (exemple : _fk\_actualite\_id_)

```

//On sélectionne tous les paragraphes de l'actualité
$paras = getAll('SELECT * FROM s_paragraphe, s_para_type WHERE fk_actualite_id = ' . sql($this->actualite->id) . ' AND fk_para_type_id = para_type_id ORDER BY paragraphe_ordre ASC');
//Il est nécessaire d'indiquer une date de modification par rapport au cache
$this->site->g_rubrique->rubrique['rubrique_date_publi'] = $this->actualite->ocms_date_modif;
//On déclare le genParagraphe
$gp = new genParagraphes($this->site, $paras);
//On donne un id pour le cache et pour les paragraphes
$gp->id = $this->actualite->id;
//on ajoute à la variable du template tous les paragraphes
$tpl->text = $gp->gen();

```



### Urls (genUrl) _(include/autoload/genUrlV2.php)_ ###