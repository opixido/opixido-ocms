<h1>Ocms_road</h1>

<h2>Introduction</h2>

Il permet d'afficher un fil d'Ariane généré automatiquement, représentant l'arborescence des rubriques traversées par la visiteur.

<h2>Installation du Plugin</h2>

[Installation d'un plugin dans le Back-Office](CreationGabarit#Installation_du_gabarit_ou_du_plugin.md)

<h2>Affichage</h2>

Pour afficher le fil d'ariane dans le layout, modifiez le fichier _default.html.php_ du dossier _include/exports/_

```php

<div id="fil">

<?=
$this->plugins['ocms_road']->genBeforePara();
?>



Unknown end tag for &lt;/div&gt;


```

Il est possible d'ajouter des chemins dans le fil d'Ariane
```

/* @param string $titre titre de la fausse rubrique
* @param string $url fin url
*/
addRoad($titre, $url);
//Exemple :
$this->site->g_url->addRoad('Nouvelle actualité', 'new_actu');
```
Plus d'informations sur la classe genUrl [ici](Classes#Urls_(genUrl)_(include/autoload/genUrlV2.php).md).

<h2>Modifications</h2>

Il est possible de modifier le template du fil d'Ariane.

Il suffit de modifier le fichier _"template.php"_ se trouvant dans le dossier _"include/plugins/ocms\_road/"_