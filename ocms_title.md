<h1>Ocms_title</h1>

<h2>Introduction</h2>

Le Plugin _"ocms\_title"_ permet d'afficher automatiquement sur chaque page du site le nom de la rubrique courante.

<h2>Installation du plugin</h2>

[Installation d'un gabarit ou plugin dans le Back-Office](CreationGabarit#Installation_du_gabarit_ou_du_plugin.md)

<h2>Affichage</h2>

```php

<div id="content">

<?php
//Première manière (fonctionne si la fonction appelée a un nom unique)
echo $this->g_rubrique->execute('genPageTitle');
//Seconde manière
$this->plugins['ocms_title']->genPageTitle();
?>


Unknown end tag for &lt;/div&gt;


```

<h2>Modification</h2>

**Important** : toutes ces modifications sont à faire dans la fonction _init()_ ou _afterInit()_ d'un plugin ou gabarit.

<ul>
<li>
Afficher ou masquer le titre<br>
<pre><code><br>
//Masquer le titre<br>
$this-&gt;site-&gt;plugins['ocms_title']-&gt;hide();<br>
//Afficher le titre<br>
$this-&gt;site-&gt;plugins['ocms_title']-&gt;show();<br>
</code></pre>
</li>

<li>
Modifier le titre<br>
<pre><code><br>
$this-&gt;site-&gt;plugins['ocms_title']-&gt;forceTitre = 'Mon nouveau titre';<br>
</code></pre>
</li>

</ul>