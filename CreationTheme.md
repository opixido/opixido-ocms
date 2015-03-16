# Création d'un thème/template #

## Modification du layout ##

Le template HTML (layout) se situe dans le dossier _include/exports/default.html.php_

Il est possible de modifier le layout d'une page.
Le code à insérer dans le controlleur du plugin où vous souhaitez modifier le layout :

```

class genTest extends ocmsGen
{
//
public function init() {
$this->site->g_url->TEMPLATE = 'test';
}
}
```

**Le template doit se trouver dans le dossier _include/exports_ et doit avoir comme extension _.html.php_.**

![http://www.opixido.com/ocms/screen/arborescence/arbo-layout.png](http://www.opixido.com/ocms/screen/arborescence/arbo-layout.png)

## Style et disposition ##

### Ajout d'un CSS ###

<ul>
<li>
<p><pre><code>$this-&gt;site-&gt;g_headers-&gt;addCss($name, $group='page');</code></pre>
La fonction <i>addCss()</i> va permettre d'ajouter une feuille de style. Si aucun chemin n'est spécifié, le fichier doit être enregistré dans le dossier <i>/css</i>.</p>
<p>Le paramètre <i>group</i> permet de regrouper des feuilles de style pour la compression.<br>
<b>Pour activer la compression : le paramètre du fichier <i>include/config/config.app.php</i> doit être mis à <i>"true"</i> :</b><pre><code>$_Gconfig['compressCssFiles']=true</code></pre>
</p>
<p><b>Exemple d'utilisation : </b>
<pre><code>$this-&gt;site-&gt;g_headers-&gt;addCss('contact.css');</code></pre></p>
</li>
<li>
<p><pre><code>$this-&gt;site-&gt;g_headers-&gt;addCssText($text);</code></pre>
La fonction <i>addCsstext()</i> permet d'ajouter directement du CSS dans la balise <i>style</i> de la page.</p>
<p>Exemple : <pre><code>$this-&gt;site-&gt;g_url-addCssText('img{max-width:100%;}');</code></pre></p>
</li>
</ul>

### Ajout d'un fichier Javascript ###

<ul>
<li>
<p><pre><code>$this-&gt;site-&gt;g_headers-&gt;addScript($name, $addBase=true, $group='page');</code></pre>
La fonction <i>addScript()</i> va permettre d'ajouter un fichier Javascript. Si aucun chemin n'est spécifié, le fichier doit être enregistré dans le dossier <i>/js</i>. Sinon, il faut mettre le paramètre <i>$addBase</i> à <i>true</i> et insérer dans le paramètre <i>$name</i> le chemin complet</p>
<p>Le paramètre <i>group</i> permet de regrouper des feuilles de style pour la compression.<br>
<b>Pour activer la compression : le paramètre du fichier <i>include/config/config.app.php</i> doit être mis à <i>"true"</i> :</b><pre><code>$_Gconfig['compressCssFiles']=true</code></pre>
</p>
<p><b>Exemple d'utilisation : </b>
<pre><code><br>
$this-&gt;site-&gt;g_headers-&gt;addScript('jquery.js');<br>
//Autre méthode :<br>
$this-&gt;site-&gt;g_headers-&gt;addHtmlHeaders('&lt;script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" &gt;<br>
<br>
Unknown end tag for &lt;/script&gt;<br>
<br>
');<br>
</code></pre></p>
</li>
</ul>