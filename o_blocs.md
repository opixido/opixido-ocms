<h1>Plugin o_blocs</h1>

<h2>Introduction</h2>

Il permet d'afficher et gérer dynamiquement des blocs de contenu sur une page.

<h2>Installation du plugin</h2>

[Installation d'un gabarit ou plugin dans le Back-Office](CreationGabarit#Installation_du_gabarit_ou_du_plugin.md)

<h2>Créer un bloc</h2>

Se rendre dans l'administration et rajouter dans l'url le paramètre _"?curTable=s\_bloc"_.
Cliquez sur _Ajouter un élément_.

![http://www.opixido.com/ocms/screen/plugins/o_bloc-creer.png](http://www.opixido.com/ocms/screen/plugins/o_bloc-creer.png)
<ul>
<li>Donnez un nom au bloc</li>
<li>Écrire une condition quand à son affichage (facultatif)</li>
<li>Donnez un nom de classe (facultatif)</li>
<li>Rendez-le visible</li>
</ul>

## Affichage ##

Pour afficher dans le layout ce que contient le bloc, modifiez le fichier _default.html.php_ du dossier _include/exports/_, et faites appel à pa fonction _genBloc()_ à l'endroit voulu :
```php

<div id="bloc-droite">

<?php
//Remplacez "nomBloc" par le nom que vous lui avez donné lors de sa création
echo $this->plugins['o_blocs']->blocs['nomBloc']->genBloc();
//$this->plugins est un tableau contenant la liste des plugins
//$this->plugins['o_blocs'] est un objet o_blocsFront
//$this->plugins['o_blocs']->blocs est un tableau contenant la liste des blocs
//$this->plugins['o_blocs']->blocs['nomBloc'] est un objet bloc
?>



Unknown end tag for &lt;/div&gt;


```

<h2>Ajouter un contenu</h2>

Vous pouvez ajouter du contenu dans ce bloc en appelant la fonction _add()_ **dans la fonction _init()_** sur le bloc :

```php

/* @param string $nom
* @param string $html
* @param string $class
*/
function add($nom, $html, $class);
```

Exemple, dans le plugin _"ocms\_downloads"_ (version raccourcie) :

```php

class ocms_downloadFront extends ocmsPlugin
{
public function afterInit()
{
$this->downloads = $this->getDownloads();

//Si il y a des téléchargements, alors on ajoute du contenu au bloc
if($this->downloads)
{
$this->site->plugins['o_blocs']->blocs['droite']->add('downloads',$this->genDownloads());
}
}

public function genDownloads()
{
$tpl = new genTemplate();
$tpl->loadTemplate('download_box','plugins/ocms_download/tpl');

foreach($this->downloads as $row)
{
$stpl = $tpl->addBlock('DOWNLOAD');
$d = new row('p_download',$row);
$stpl->nom = $d->download_titre;
$stpl->url = $d->download_fichier;
}

//Retourne la génération HTML du template
return $tpl->gen();
}
}
```

<h2>Les fonctions de la classe bloc</h2>

<ul>

<li>
<b>Générer le bloc</b> (cf. <a href='o_blocs#Affichage.md'>Affichage</a> ) :<br>
<pre><code><br>
//Générer le contenu du bloc<br>
function genBloc();<br>
</code></pre>
</li>

<li>
<b>Ajouter du contenu au bloc</b>
<pre><code><br>
/* @param string $id nom de la boite<br>
* @param string $html contenu de la boite<br>
* @param string $class classe de la boite<br>
*/<br>
function add($id, $html, $class);<br>
</code></pre>
</li>

<li>
<b>Afficher ou cacher un bloc</b>
<pre><code><br>
//Cacher le bloc<br>
function hide();<br>
//Afficher le bloc<br>
function show();<br>
</code></pre>
</li>

<li>
<b>Ajouter du contenu après une boite</b>
<pre><code><br>
/* @param string $first nom de la première boite<br>
* @param string $nom nom de la deuxième boite<br>
* @param string $html de la deuxième boite<br>
*/<br>
function addAfter($first, $nom, $html);<br>
</code></pre>
</li>

<li>
<b>Ajouter du contenu avant une boite</b>
<pre><code><br>
/* @param string $second de la seconde boite<br>
* @param string $nom nom de la première boite<br>
* @param string $html de la deuxième boite<br>
*/<br>
function addBefore($second, $nom, $html);<br>
</code></pre>
</li>

<li>
<b>Ajouter une boite en premier</b> (avant toutes les autres)<br>
<pre><code><br>
/* @param string $nom<br>
* @param string $html<br>
*/<br>
function addAtTop($nom, $html);<br>
</code></pre>
</li>

<li>
<b>Tout supprimer</b>
<pre><code><br>
function clean();<br>
</code></pre>
</li>

<li>
<b>Supprimer une boite</b>
<pre><code><br>
/* string $nom nom de la boite à supprimer<br>
*/<br>
function remove($nom);<br>
</code></pre>
</li>

</ul>