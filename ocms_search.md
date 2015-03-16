<h1>Ocms_search</h1>

<h2>Introduction</h2>

Il permet de créer un gabarit pour la recherche. Par défaut, la recherche s'effectue dans les rubriques et dans les tables créées pour des gabarits de page ou plugin.

<h2>Installation du plugin</h2>

[Installation d'un gabarit ou d'un plugin dans le Back-Office](CreationGabarit#Installation_du_gabarit_ou_du_plugin.md)

<h2>Front-Office</h2>

<h3>Insertion de la barre de recherche</h3>

Pour afficher la barre de recherche dans le layout, il suffit de modifier le fichier _default.html.php_ du dossier _include/exports/_ et d'y insérer le formulaire comme ceci :
```php

<div id="recherche">

<form id="form1" action="<?php echo getUrlFromId(getRubFromGabarit('genOcmsSearch')) ?>" method="get">

<input type="text" placeholder="Rechercher" value=<?= alt(akev($_GET, 'q') )?> id="rechercher_input" name="q" />

<button value="OK" id="recherche_btn">

Unknown end tag for &lt;/button&gt;




Unknown end tag for &lt;/form&gt;





Unknown end tag for &lt;/div&gt;


```
<p>L'action doit pointer vers la rubrique ayant pour gabarit <i>"ocms_search"</i>.<br>
La fonction <i>getUrlFromId(getRubFromGabarit('genOcmsSearch'))</i> permet de récupérer cet url. </p>
<p>Plus d'informations sur les urls dans <a href='Fonctions#Urls.md'>cette page</a>.</p>

<h3>Affichage des résultats</h3>



<h3>Attributs des objets</h3>

Pour chaque objet affiché dans la liste de résultats, le moteur regarde si cet objet possède ou non les fonctions suivantes définies dans les fichiers _front.php_ ([CreationGabarit#Le\_modèle modèle]) :
<ul>
<li><i>getUrl()</i> : retourne l'url de l'objet</li>
<li><i>getImg()</i> : retourne l'image de l'objet</li>
<li><i>getTitle()</i> : retourne le titre de l'objet</li>
<li><i>getDesc()</i> : retourne la description/résumé de l'objet</li>
</ul>

**Attention : il est nécessaire de nommer les classes des objets avec le nom de la table utilisée par ce dernier.
De cette façon, une actualité enregistrée dans la table _"p\_actualite"_ possèdera une classe "p\_actualite".**

Exemple type :
```php

class p_actualite extends row
{
//Constructeur
//La table correspondant est ici "p_actualite"
function __construct( $roworid) {
parent::__construct('p_actualite', $roworid);
}

function getTitle() {
return $this->actualite_titre;
}

function getDesc() {
return $this->actualite_desc;
}

function getImg() {
return $this->actualite_img;
}

function getUrl() {
//Retourne une url reconstruite
return getUrlFromId(getRubriqueByGabarit('genActualites'), LG, array('actualite'=>$this->actualite_id));
}

}
```

<h2>Back-Office</h2>

<h3>Indexation</h3>

<p>Le plugin a ajouté un lien "Ré-indexer le site" dans les "Actions administrateur", sur la partie droite.</p>
![http://www.opixido.com/ocms/screen/plugins/search-bo.png](http://www.opixido.com/ocms/screen/plugins/search-bo.png)
<p>Dans cette partie est affichée la liste des gabarits. Il est possible d'indexer manuellement chaque gabarit, ou alors de tout indexer en même temps en sélectionnant "Ensemble".</p>

<p>L'indexation se fait automatiquement lors de la création ou de la modification d'un élément.</p>
<p>Pour vider toute la recherche, il suffit de sélectionner, dans la page d'accueil du back-office le lien "Vider la recherche"</p>