# Création d'un nouveau plugin #

## <u>Creation du gabarit ou plugin</u> ##

<h3>Création des fichiers et des dossiers</h3>

<ul>
<blockquote><li> Commencez par vous rendre dans le dossier <i>plugins</i> ("<i>nomDuProjet/include/plugins</i>") de votre projet.</li>
<li> Créez-y un dossier, avec pour nom le nom de votre futur plugin(pas d'espace ni de caractère spécial). <b>Le nom du plugin doit, de préférence, être le même que celui de la table utilisée (utile pour le moteur de recherche)</b>.</li>
<img src='http://www.opixido.com/ocms/screen/gabarit/gabarit-arbo.png' />
<li> Créez-y le fichier PHP qui va générer le gabarit (controlleur). De préférence un nom évocateur comme "genNomDuPlugin".</li>
</ul></blockquote>

<h4>Le controlleur</h4>

Dans ce fichier, déclarez votre classe. La classe créée doit étendre la classe _ocmsGen_ ou la classe _ocmsPlugin_.
La fonction _init()_ et _afterInit()_ seront automatiquement appelées avant la fonction _gen()_. Elles ne doivent rien retourner et ne rien afficher.
La fonction _gen()_ retournera le code du gabarit/plugin à afficher.

<b>ATTENTION : le fichier PHP générant le gabarit doit porter le même nom que la classe déclarée et aucune fonction ne doit porter le même nom que la classe</b>

Créez dans la classe les fonctions _init()_ (facultatif) et _gen()_.

Vous devriez obtenir quelque chose comme :
```

class genTest extends ocmsGen
{
public function init() {
//Do something
}
public function gen() {
//Return something
}
}
```

#### Le modèle ####

<ul>
<li>Créez un fichier PHP <i>front.php</i> dans le dossier courant (<i>include/plugins/nomDeVotrePlugin</i>). Ce fichier sera automatiquement inclu. Vous pourrez y déclarer vos classes utiles au fonctionnement du plugin.<br>
</li>
<li>
Vous y déclarerez vos classes utiles au plugin. Chaque classe utilisant la base de données devra étendre la classe <i>row</i>.<br>
<br>
<pre><code><br>
class maClasse extends row<br>
{<br>
//Constructeur<br>
//Paramètre : nom de la table et id ou array de la ligne de la table<br>
function __construct( $roworid) {<br>
parent::__construct('nomTable', $roworid);<br>
}<br>
<br>
function getTitle() {<br>
return 'Hello world !';<br>
}<br>
}<br>
</code></pre>
<p>La classe <i>row</i> permet d'obtenir une base de données orientée objet de à partir d'une base de données relationnelle.</p>
<p>Il est nécessaire de déclarer dans le fichier <i>config.php</i> du plugin toutes les relations de la table en question ($relations, $relinv, $tablerel).</p>
<p>Elle permet en plus de :<br>
<blockquote><ul>
<li>Générer directement l'url d'un fichier enregistré ($uploadFields)</li>
<li>Générer la traduction d'un champ</li>
</ul>
</p>
</li></blockquote>

</ul>

Exemple avec une classe "Evenement" :

```

class Evenement extends row
{
//Constructeur
//Paramètres : table "evenement" + id de la ligne ou array
function __construct( $roworid) {
parent::__construct('evenement', $roworid);
}

function getTitle() {
//Si l'évènement est un champ multilingue, alors la fonction retournera la traduction en fonction de
//la langue en question ("evenement_titre_fr", "evenement_titre_en", ...)
return $this->evenement_titre;
}

function getUrlImg(){
//Retournera l'url de l'image
//J'ai au préalablement défini le champ en tant qu'uploadField
return $this->evenement_img;
}

}
```

**Plus d'informations sur la configuration du fichier _config.php_ sur [cette page](GabaritBO#Les_types_de_champs.md)**

#### La vue ####

Pour intégrer la vue au controlleur, veuillez procéder comme suit :

<ul>
<li>Créez dans le dossier courant un dossier qui va contenir le template du gabarit (<i>/tpl</i> par exemple).</li>
<li>Créez dans le nouveau dossier le template PHP (<i>nomDuTemplate.php</i>)<br>
<pre><code><br>
&lt;!-- Template test.php --&gt;<br>
&lt;div id="test"&gt;<br>
&lt;h2&gt;Hello world<br>
<br>
Unknown end tag for &lt;/h2&gt;<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/div&gt;<br>
<br>
<br>
</code></pre>
</li>
<li>Pour implémenter la vue dans le controller, vous devrez déclarer un objet de type <i>genTemplate</i> et lui associer le template précédemment créé.<br>
<pre><code><br>
public function gen(){<br>
//Déclaration du nouveau template<br>
$tpl = new genTemplate();<br>
//Affectation du template. Attention : ne pas rajouter l'extension au nom du template lors du chargement<br>
$tpl-&gt;loadTemplate('nomDuTemplate', 'plugins/nomDuPlugin/cheminVersTemplate');<br>
<br>
//On retourne le template généré<br>
return $tpl-&gt;gen();<br>
}<br>
</code></pre>
</li>
</ul>

Documentation de la classe genTemplate [ici](Classes.md).

<u>Insérer des variables dans le template :</u>

<ul>
<li>Commencez par insérer les noms des variables dans le template. Pour cela, insérez des noms entourés par des arobases.<br>
<pre><code><br>
&lt;!-- Template test.php --&gt;<br>
&lt;div id="test"&gt;<br>
&lt;h2&gt;@@titre@@<br>
<br>
Unknown end tag for &lt;/h2&gt;<br>
<br>
<br>
&lt;!-- Cette manière fonctionne aussi : --&gt;<br>
&lt;p&gt;&lt;?php echo $this-&gt;text; ?&gt;<br>
<br>
Unknown end tag for &lt;/p&gt;<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/div&gt;<br>
<br>
<br>
</code></pre>
</li>
<li>Puis , lors de la déclaration du template via la classe <i>genTemplate</i>, implémentez ces variables :<br>
<pre><code><br>
public function gen(){<br>
$tpl = new genTemplate();<br>
$tpl-&gt;loadTemplate('test', 'plugins/pluginTest/tpl');<br>
//Affectation des valeurs<br>
$tpl-&gt;titre = 'Hello world';<br>
$tpl-&gt;text = 'lorem ipsum';<br>
<br>
return $tpl-&gt;gen();<br>
}<br>
</code></pre>
</li>
</ul>

<u>Boucler dans un template</u>

<ul>
<li><b>1e méthode :</b>

<ul>
<li>Dans le template, insérez deux balises XML comme ceci :<br>
<img src='http://www.opixido.com/ocms/screen/gabarit/vue-template2.png' />
<br />Elles vont nous servir à définir l'endroit où boucler.<br>
</li>
<li>
Ensuite, dans le controlleur, après avoir déclaré votre template, ajoutez-lui des bloc comme ceci :<br>
<pre><code><br>
public function gen(){<br>
$tpl = new genTemplate();<br>
$tpl-&gt;loadTemplate('test', 'plugins/pluginTest/tpl');<br>
//Affectation des valeurs<br>
$tpl-&gt;titre = 'Hello world';<br>
$table = array('text1', 'test2', 'text3');<br>
foreach($table as $row){<br>
//On ajoute un nouveau bloc<br>
//Veillez à lui donner le même nom que la balise<br>
$tplItem = $tpl-&gt;addBlock('ITEM');<br>
$tplItem-&gt;text = $row;<br>
}<br>
<br>
return $tpl-&gt;gen();<br>
}<br>
</code></pre>
</li>
</ul></li>

<li><b>Deuxième méthode :</b>
<ul>
<li>Dans le template :<br>
<img src='http://www.opixido.com/ocms/screen/gabarit/vue-template3.png' />
</li>
<li>Dans le controlleur :<br>
<pre><code><br>
public function gen(){<br>
$tpl = new genTemplate();<br>
$tpl-&gt;loadTemplate('test', 'plugins/pluginTest/tpl');<br>
//Affectation des valeurs<br>
$tpl-&gt;titre = 'Hello world';<br>
$table = array('text1', 'test2', 'text3');<br>
$tpl-&gt;textes = $table;<br>
<br>
return $tpl-&gt;gen();<br>
}<br>
</code></pre>
</li>
</ul>
</li>

</ul>

#### Mise en place de la configuration BO/FO ####

  * Crééz un fichier _config.php_ qui sera automatiquement inclu.

[Plus de détails ici](GabaritBO.md)


<h4>Arborescence finale</h4>

![http://www.opixido.com/ocms/screen/gabarit/gabarit-arbo2.png](http://www.opixido.com/ocms/screen/gabarit/gabarit-arbo2.png)


## <u>Installation du gabarit ou du plugin</u> ##

<h3>Enregistrement dans la table "s_gabarit"</h3>

Trois méthodes sont possibles :

<ul>

<li>Directement dans le système de gestion de BD :<br>
<ul>
<li>Dans la table "s_gabarit" de votre base de données, créez une nouvelle ligne<br>
</li>
<li>Insérez les champs suivants :<br>
<table><thead><th> <i>gabarit_id</i> </th><th> <i>gabarit_titre</i> </th><th> ... </th><th> <i>gabarit_classe</i> </th><th> ... </th><th> <i>gabarit_plugin</i> </th></thead><tbody>
<tr><td> ... </td><td> titre du gabarit </td><td> ... </td><td> titre de la classe </td><td> ... </td><td> nom du dossier </td></tr></tbody></table>

</li>
</ul></li>

<li>A partir du Back-Office<br>
<ul>
<li>Ajouter le paramètre "&curTable=s_gabarit" à la fin de l'url</li>
<li>Cliquez sur "Ajouter un élément"</li>
<li><p>Renseignez le titre du gabarit/plugin, le nom de la classe, des paramètres (facultatif), et le nom du dossier contenant le gabarit/plugin.</p><img src='http://www.opixido.com/ocms/screen/plugins/rss-creation_gabarit.png' /></li>
</ul></li>

<li>Insérer automatiquement :<br>
<ul>
<li>Créer un fichier XML <i>"datas.xml"</i></li>
<li>Insérer :<br>
<pre><code><br>
&lt;?xml version="1.0" encoding="utf-8" ?&gt;<br>
<br>
&lt;opixidoocms&gt;<br>
<br>
&lt;!-- Table s_gabarit --&gt;<br>
<br>
&lt;s_gabarit&gt;<br>
<br>
&lt;gabarit_id&gt;/* Id */<br>
<br>
Unknown end tag for &lt;/gabarit_id&gt;<br>
<br>
<br>
<br>
&lt;gabarit_titre&gt;/* Titre */<br>
<br>
Unknown end tag for &lt;/gabarit_titre&gt;<br>
<br>
<br>
<br>
&lt;gabarit_para_crea&gt;<br>
<br>
Unknown end tag for &lt;/gabarit_para_crea&gt;<br>
<br>
<br>
<br>
&lt;gabarit_para_include&gt;<br>
<br>
Unknown end tag for &lt;/gabarit_para_include&gt;<br>
<br>
<br>
<br>
&lt;gabarit_full_template&gt;<br>
<br>
Unknown end tag for &lt;/gabarit_full_template&gt;<br>
<br>
<br>
<br>
&lt;gabarit_bdd_deco&gt;0<br>
<br>
Unknown end tag for &lt;/gabarit_bdd_deco&gt;<br>
<br>
<br>
<br>
&lt;gabarit_classe&gt;/* Nom de la classe */<br>
<br>
Unknown end tag for &lt;/gabarit_classe&gt;<br>
<br>
<br>
<br>
&lt;gabarit_classe_param&gt;<br>
<br>
Unknown end tag for &lt;/gabarit_classe_param&gt;<br>
<br>
<br>
<br>
&lt;gabarit_plugin&gt;/* Nom du plugin (dossier) */<br>
<br>
Unknown end tag for &lt;/gabarit_plugin&gt;<br>
<br>
<br>
<br>
&lt;gabarit_index_table&gt;/* Table utilisée */<br>
<br>
Unknown end tag for &lt;/gabarit_index_table&gt;<br>
<br>
<br>
<br>
&lt;gabarit_index_url&gt;news=news_id<br>
<br>
Unknown end tag for &lt;/gabarit_index_url&gt;<br>
<br>
<br>
<br>
&lt;fk_default_rubrique_id&gt;0<br>
<br>
Unknown end tag for &lt;/fk_default_rubrique_id&gt;<br>
<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/s_gabarit&gt;<br>
<br>
<br>
<br>
<br>
<br>
Unknown end tag for &lt;/opixidoocms&gt;<br>
<br>
<br>
</code></pre>
</li>
<li>Ce fichier sera automatiquement appelé lors de l'installation du gabarit. Il permettra d'enregistrer automatiquement les informations du gabarit dans la table <i>"s_gabarit"</i></li>
</ul>
</li>

</ul>

<h3>Installer le plugin/gabarit</h3>

<ul>
<li>Rendez-vous sur la page d'administration de votre site</li>
<li>Cliquez sur le lien "Plugins" dans "Dev"</li>
<li>Sélectionnez "Ajouter un élément", sélectionnez votre nouveau plugin, puis enregistrez. Cliquez ensuite sur "Installer"</li>
</ul>
Lors de l'installation, les fichiers _"datas.xml"_ et _"install.sql"_ seront automatiquement appelés (facultatif).
Le fichier _"install.sql"_ doit contenir du SQL pouvant créer la ou les table(s) utilisée(s) dans le plugin.

### Ajouter un gabarit dans une page ###

<ul>
<li>Rendez-vous dans la gestion du contenu et sélectionnez la page où vous souhaitez insérer votre nouveau gabarit</li>
<li>Cliquez sur "Modifier"</li>
<li><p>Dans l'onglet "Paramètres", sélectionnez votre nouveau gabarit (champ "gabarit spécial"), puis enregistrez et validez.</p>
Pou afficher un icône, il suffit d'enregistrer cette fonction dans le contrôleur :<br>
<pre><code><br>
public static function ocms_getPicto()<br>
{<br>
//Remplacez la fin par le nom de l'image souhaitée<br>
return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/media-eject.png';<br>
}<br>
</code></pre>
</li>
</ul>

<h2>Exemples de plugins et gabarits</h2>

<ul>
<li><a href='pluginsDeBase.md'>Plugins de base</a></li>
<li><a href='ExempleCreationGabarit.md'>Gabarit actualités</a></li>
</ul>