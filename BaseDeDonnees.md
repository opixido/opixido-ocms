# Base de données #

<h2>Pré-requis</h2>
Vous devez configurer la connexion à la base de données dans le fichier _config.server.php_ se situant dans le dossier _include/config/_ :

```

//Nom d'utilisateur pour se connecter
$_bdd_user = 'root';
//Mot de passe de l'utilisateur pour se connecter
$_bdd_pwd = '';
//Nom de la base de données
$_bdd_bdd = 'database';
//Chemin vers le serveur
$_bdd_host = 'localhost';
//Nom du SGBD
$_bdd_type = 'mysqli';
```

OCMS utilise les fonctions de la librairie PHP ADOdb pour se connecter au SGBD.

<h2>Les fonctions</h2>

<ul>
<li>
<b>Exécuter une requête SQL</b>
<pre><code><br>
/* @param string $sql<br>
* @param string $msg Message en cas d'erreur<br>
* @return ADORecordSet $res<br>
*/<br>
doSql($sql, $msg='');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$res = doSql(' UPDATE user SET age=30 WHERE user_id=2 ');<br>
if($res) echo 'Réussi';<br>
</code></pre>

</li>

<li>
<b>Récupérer l'ensemble des résultats d'une requête sous forme de tableau</b>
<pre><code><br>
/* @param string $sql<br>
* @param bool $cache<br>
* @param string $connexion<br>
* @return array<br>
*/<br>
getAll($sql, $cache=0, $connexion='');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$users = getAll(' SELECT * FROM user ');<br>
foreach($users as $user)<br>
{<br>
echo $user['nom'];<br>
}<br>
</code></pre>

</li>

<li>
<b>Récupérer le premier enregistrement d'une requête</b>
<pre><code><br>
/* @param string  $sql<br>
* @param bool $cache<br>
* @param string $connexion<br>
* @return array<br>
*/<br>
getSingle($sql, $cache=0, $connexion='');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$user = getSingle(' SELECT * FROM user WHERE user_id=2 ');<br>
echo $user['nom'];<br>
</code></pre>
</li>

<li>
<b>Ajouter un paramètre dans une requête SQL</b>
<pre><code><br>
/* @param string $param<br>
* @param string $type int ou string<br>
* @return string<br>
*/<br>
sql($param, $type='string');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$user = getSingle(' SELECT * FROM user WHERE user_id = '.sql($_REQUEST['id'], 'int'));<br>
//Valeur de retour : 'SELECT * FROM user WHERE user_id = "2" '<br>
</code></pre>
</li>

<li>
<b>Récupérer le dernier identifiant inséré</b>
<pre><code><br>
/* @return mixed<br>
*/<br>
InsertId();<br>
</code></pre>
Exemple :<br>
<pre><code><br>
$res = doSql('INSERT INTO user(nom) VALUES('.sql($nom).') ');<br>
if($res) echo 'Correctement enregistré sous l\'id '.InsertId();<br>
</code></pre>
</li>

<li>
<b>Récupérer un enregistrement à partir de la table et de l'identifiant de la ligne</b>
<pre><code><br>
/* @param string $table<br>
* @param string $type int ou string<br>
* @param boolean $onlyOnline<br>
* @return array<br>
*/<br>
getRowFromId($table, $id, $onlyOnline=false);<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$row = getRowFromId('user', 2);<br>
//Equivalent : getSingle(' SELECT * FROM user WHERE user_id = 2 ');<br>
</code></pre>

</li>

<li>
<b>Récupérer un enregistrement à partir de la table, du champ et d'une valeur</b>
<pre><code><br>
/* @param string $table<br>
* @param string $champ<br>
* @param string $val<br>
* @return array<br>
*/<br>
getRowFromFieldLike($table, $champ, $val);<br>
</code></pre>

Exemple :<br>
<pre><code><br>
$row = getRowFromFieldLike('user', 'nom', 'Martin');<br>
//Equivalent : getSingle(' SELECT * FROM user WHERE user_nom LIKE '.sql('Martin'));<br>
</code></pre>

</li>

<li>
<b>Ne récupérer que les enregistrements en ligne.</b>
Pré-requis : la table doit posséder l'attribut <i>"en_ligne"</i>
<pre><code><br>
/* @param string $table<br>
* @param string $alias<br>
* @return string<br>
*/<br>
sqlOnlyOnline($table, $alias='');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
//Ne récupérer que les news en ligne<br>
$res = getAll('SELECT * FROM news WHERE 1 '.sqlOnlyOnline('news'));<br>
</code></pre>

</li>

<li>
<b>Ne récupérer que les enregistrements de version validée</b>.<br>
Pré-requis : la table doit posséder les attributs <i>"en_ligne"</i> et <i>"fk_version"</i>
<pre><code><br>
sqlVersionOline($table, $alias='');<br>
</code></pre>

Exemple :<br>
<pre><code><br>
//Ne récupérer que les rubriques validées et en ligne<br>
$res = getAll('SELECT * FROM s_rubrique WHERE 1'.sqlVersionOnline('s_rubrique'));<br>
</code></pre>
</li>

<li>
<b>Récupérer la liste des champs d'une table</b>
<pre><code><br>
/* @param string $table<br>
* @return array<br>
*/<br>
getTableField($table);<br>
</code></pre>
</li>

<li>
<b>Récupérer le nombre d'enregistrements MySQL affectés par la dernière requête</b>
<pre><code><br>
/* @return array<br>
*<br>
*/<br>
Affected_Rows();<br>
</code></pre>
</li>

<li>
<b>Tester une requête SQL.</b>
La fonction exécute la requête et retourne un message d'erreur si elle n'aboutit pas.<br>
<pre><code><br>
/* @param string $sql<br>
* @return mixed<br>
*/<br>
TrySql($sql);<br>
</code></pre>
</li>

<li>
<b>Récupérer la liste des tables</b>
<pre><code><br>
/* @return array<br>
*<br>
*/<br>
getTables();<br>
</code></pre>
</li>

</ul>