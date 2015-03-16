<h1>Créer la gestion d'un gabarit/plugin dans le back-office</h1>

Doc : _include/config/config.base.php_

<h2>Fonctionnement</h2>

<h3>Ajout dans le back-office</h3>

  * Ouvrez le fichier de configuration "_config.php_".

  * Déclarez toutes ces variables globales qui nous seront utiles plus tard :
```

global $tabForms,$uploadFields,$_Gconfig,$relinv,$orderFields,$admin_trads, $gs_roles, $rteFields, $relations, $tablerel, $basePath, $admin_trads,$searchField;
```

  * Pour ajouter un lien/bouton vers la gestion dans le back-office :

```

$_Gconfig['bigMenus']['nomPlugin'][] = 'nomTable';
```

  * Pour modifier le titre du bouton et ajouter un icône :
```

//traduction du texte
$admin_trads["cp_txt_nomTable"]["fr"] = "Nom de mon gabarit";

//Ajout de l'icône
$tabForms["nomTable"]["picto"] = ADMIN_PICTOS_FOLDER."32x32/apps/internet-news-reader.png";
```

**Important: n'oubliez pas de rajouter le plugin dans le back-office et de l'installer!**

<h3>Afficher la liste</h3>

  * Cliquez sur le bouton pour afficher la liste.

  * Pour définir quels champs afficher dans le back-office :

```

//Listez tous les champs de la table que vous souhaitez afficher
$tabForms['nomTable']['titre'] = array('nomTable_titre', 'nomTable_contenu');
//'titre' : pour définir les champs à lister
```

<h3>Créer le formulaire d'ajout</h3>

  * Dans le dossier "_forms_", créez, si ce n'est pas déjà fait, un fichier PHP nommé "_nomPlugin.form.php_".(_include/plugins/nomPlugin/forms/nomPlugin.form.php_).
Il contiendra la liste des attributs de la base de données à générer dans le formulaire

  * Faites appel à ce fichier dans le _config.php_ :

```

$tabForms['nomTable']['pages']['Nom'] = array('../plugins/nomPlugin/forms/nomPlugin.form.php');
//'pages' : pour définir où se trouve le fichier de formulaire
//Le tableau doit contenir le chemin vers votre fichier de formulaire
```

  * Dans le fichier créé (_nomPlugin.form.php_), générez chaque attribut de la table :

```

<?php
$this->gen('nomTable_date');
// ...
$this->genlg('nomTable_titre');
//genlg va servir à générer le contenu multilingue
$this->genlg('nomTable_contenu');
?>
```

<h3>Options</h3>

<h4> Les attributs gérés automatiquement par OCMS </h4>

| **ocms\_date\_modif** | Date de modification |
|:----------------------|:---------------------|
| **ocms\_date\_crea** | Date de création de la ligne |
| **ocms\_date\_online** | Date de mise en ligne (date à partir de laquelle la ligne est prise en compte) |
| **ocms\_date\_offline** | Date à partir de laquelle la ligne n'est plus prise en compte |
| **ocms\_creator** | Identifiant de l'administrateur qui a créé la ligne, [exemple ici](GestionDroits#Ajouter_les_droits_pour_chaque_table.md) |

#### Les types de champs ####
| **Type** | **Fin** |
|:---------|:--------|
| Mail | `_mail`, `_couriel`, `_email` |
| Url | `_url`, `_url_`, `_link_1`, `_link`, `_lien_` |

  * Pour ajouter un WYSIWYG au champ contenu, insérez la ligne suivante dans le _config.php_ :

```
$rteFields[] = 'nomTable_contenu';```

  * Pour ajouter l'upload d'image au champ 'nomTable\_img' :

```
$uploadFields[] = 'nomTable_img';```

  1. Pour recadrer automatiquement une image (en pixels) :

> ```
$_Gconfig['imageAutoResize'] = array('nomChamp_img'=>array($maxWidth,$maxHeight));```

  * Pour ajouter un champ de couleur :

```
$_Gconfig['colorFields'][] = 'nomChamp_color';```

  * Pour ajouter des champs de recherche :

```

//Listez tous les champs de recherche
$searchField['nomTable'] = array('nomTable_titre');
```

  * Les traductions :

```

$admin_trads["nomTable.nomTable_titre_fr"]["fr"] = "Titre";
// ...
```

  * Les champs obligatoires :

```

$neededFields[] = "nomTable_titre";
```

  * Les champs de type url :
```
$urlFields[] = 'nomChamp_url';```

  * Les champs de type mail  :
```
$mailFields[] = 'nonChamp_mail'```

  * Les champs de type Password :

```
$_Gconfig['passwordFields'][] = 'nomChamp_password';```

  * Les champs de type ordre :

```
$orderFields['nomTable'] = array('nomChamp_ordre');```

  * Afficher/Masquer une ligne de table (attribut _en`_ligne`_) :

```
$_Gconfig['hideableTable'][] = 'nomTable';```

  * Rendre visible

  * Recharger la page lors de la sélection d'un champ :

```
$_Gconfig['reloadOnChange'] = array('nomChamp');```

<h3>Les relations entre tables</h3>

  * Pour ajouter une relation de simple clé externe :

```

$relations['nomTable']['fk_champ'] = 'fk_nomTable';

//Exemple :
$relations['a_actualite']['fk_image_id'] = 'a_image';
```

  * Les tables de relation (n, n) :

```

$tablerel['r_table_relation'] = array('FK_CHAMP1'=>'FK_TABLE1','FK_CHAMP2'=>'FK_TABLE2');

//Exemple :
$tablerel['actualite_theme'] = array('fk_actualite_id'=>'a_actualite','fk_theme_id'=>'a_theme');
```

  * Relation inverse (les entrées de x tables qui pointent vers ma table) :

```

$relinv['table_parente']['NOM_DU_FAUX_CHAMP'] = array('table_fille','CLEF EXTERNE');
//Ou la même chose, mais en AJAX
$_Gconfig['ajaxRelinv']['TABLE']['NOM_DU_FAUX_CHAMP'] = array('SOUS_TABLE','CLEF EXTERNE',array('LISTE DES CHAMPS A AFFICHER'));

//Exemple :
$relinv['a_actualite']['PARAGRAPHES'] = array('s_paragraphe','fk_actualite_id');
//Version AJAX
$_Gconfig['ajaxRelinv']['a_actualite']['PARAGRAPHES'] = array('s_paragraphe','fk_actualite_id',array('paragraphe_titre','fk_para_type_id','paragraphe_contenu'));
```



<h2><u>Un exemple : gestion des actualités</u></h2>

<h3>Ajouter les actualités dans le back-office</h3>

  * Ouvrez le fichier de configuration "_config.php_".

  * Déclarez toutes ces variables globales qui nous seront utiles plus tard :
```

global $tabForms,$uploadFields,$_Gconfig,$relinv,$orderFields,$admin_trads, $gs_roles, $rteFields, $relations, $tablerel, $basePath, $admin_trads,$searchField;
```

  * Pour ajouter un lien vers la gestion des actualités dans le back-office :

```

$_Gconfig['bigMenus']['Actualités'][] = 'a_actualite';
```

  * pour modifier le titre du bouton et ajouter un icône :
```

//traduction du texte
$admin_trads["cp_txt_a_actualite"]["fr"] = "Actualités";

//Ajout de l'icône
$tabForms["a_actualite"]["picto"] = ADMIN_PICTOS_FOLDER."32x32/apps/internet-news-reader.png";
```

**Important: n'oubliez pas de rajouter le plugin dans le back-office et de l'installer!**

<h3>Afficher la liste des actualités</h3>

  * Cliquez sur le bouton pour afficher la liste des actualités.

  * Pour définir quels champs afficher dans le back-office :

```

$tabForms['a_actualite']['titre'] = array('actualite_date', 'actualite_titre', 'actualite_img','actualite_contenu','en_ligne');
//'a_actualite' : nom de la table
//'titre' : pour définir les champs à lister
```

<h3>Créer le formulaire d'ajout</h3>

  * Dans le dossier "_forms_", créez, si ce n'est pas déjà fait, un fichier PHP nommé "_actualite.form.php_".(_include/plugins/actualites/forms/actualite.form.php_).
Il contiendra la liste des attributs de la base de données à générer dans le formulaire

  * Faites appel à ce fichier dans le _config.php_ :

```

$tabForms['a_actualite']['pages']['Actualité'] = array('../plugins/actualites/forms/actualite.form.php');
//'a_actualite' : nom de la table
//'pages' : pour définir où se trouve le fichier de formulaire
//'Actualité' : nom pour définir l'onglet (modifiable)
//Le tableau doit contenir le chemin vers notre fichier de formulaire
```

  * Dans le fichier créé (_actualite.form.php_), générez chaque champ utile comme ceci :

```

<?php
//Le CMS reconnaîtra automatiquement la date
$this->gen('actualite_date');
//genlg va servir à générer le contenu multilingue
$this->genlg('actualite_titre');
$this->genlg('actualite_contenu');
$this->gen('actualite_img');
$this->gen('en_ligne');
?>
```

  * Pour ajouter un WYSIWYG au champ contenu, insérez la ligne suivante dans le _config.php_ :

```
$rteFields[] = 'actualite_contenu';```

  * Pour ajouter l'upload d'image au champ 'actualite\_img' :

```
$uploadFields[] = 'actualite_img';```

  * Pour ajouter des champs de recherche :

```
$searchField['a_actualite'] = array('actualite_date', 'actualite_titre', 'actualite_img','actualite_contenu','en_ligne');```


  * Afficher/Masquer l'actualité (grâce à l'attribut _en`_ligne`_) :

```
$_Gconfig['hideableTable'][] = 'a_actualite';```

  * Les traductions :

```

$admin_trads["a_actualite.actualite_titre_fr"]["fr"] = "Titre de l'actualité (FR)";
$admin_trads["a_actualite.actualite_titre_en"]["fr"] = "Titre de l'actualité (EN)";
$admin_trads["a_actualite.actualite_contenu_fr"]["fr"] = "Contenu de l'actualité (FR)";
$admin_trads["a_actualite.actualite_contenu_en"]["fr"] = "Contenu de l'actualité (EN)";
$admin_trads["a_actualite.actualite_img"]["fr"] = "Image de l'actualité";
$admin_trads["a_actualite.actualite_date"]["fr"] = "Date de l'actualité";
$admin_trads["a_actualite.en_ligne"]["fr"] = "En ligne";
```

  * les champs obligatoires :

```

$neededFields[] = "actualite_titre";
$neededFields[] = "actualite_contenu";
$neededFields[] = "en_ligne";
```