# Types de paragraphe #

## Fonctionnement ##


## Création ##

  1. Rendez-vous dans l'onglet "Contenu" de la page où vous souhaitez insérer le nouveau type de paragraphe
  1. Dans la liste des paragraphes constituant la rubrique, cliquez sur le bouton "Nouveau" pour ajouter un paragraphe

![http://www.opixido.com/ocms/screen/paragraphe/nouveau.png](http://www.opixido.com/ocms/screen/paragraphe/nouveau.png)

Vous vous trouvez à présent dans l'onglet de création d'un paragraphe.
Cliquez sur le bouton "Ajouter" à droite du premier champ pour créer un nouveau type de paragraphe

![http://www.opixido.com/ocms/screen/paragraphe/nouveau-type.png](http://www.opixido.com/ocms/screen/paragraphe/nouveau-type.png)

  1. Rentrez un titre
  1. Dans le champ "Type Template", ajoutez le code source HTML/PHP du nouveau type de paragraphe **(1)**
  1. Définissez ensuite avec les boutons si ce nouveau type de paragraphe utilisera une image, un fichier, une table, du text ou un lien


**(1)**
Dans le template, rentrez toute les balises HTML qui composeront votre paragraphe.
Pour connaître tous les champs (et leur nom) que possède un paragraphe, reportez-vous à la section [Base de données](http://code.google.com/p/opixido-ocms/wiki/Database)

Pour ajouter par exemple le champ _paragraphe\_titre_ , affichez dans le template le code : @@titre@@

Faites de même pour les autres champs :
| **Attribut BD** | **nom dans le template** |
|:----------------|:-------------------------|
| _paragraphe\_titre_ | @@titre@@ |
| _paragraphe\_contenu_ | @@text@@ |
| _paragraphe\_img\_1_ | ##1## |
| _paragraphe\_img\_1\_alt_ | @@alt\_1@@ |
| ... | ... |
| _paragraphe\_file\_1\_legend\_1_ | @@legend\_1@@ |
| ... | ... |
| _paragraphe\_img\_1\_copyright\_1_ | @@copyright\_1@@ |
| ... | ... |
| _paragraphe\_link\_1_ | @@link1@@ |
| _paragraphe\_file\_1_ | @@file1@@ |

Autres : @@file1\_url@@, @@file1\_size@@, @@file1\_type@@, @@file1\_name@@, @@file1\_legend@@, @@lien\_popup@@

Exemple de template :

![http://www.opixido.com/ocms/screen/paragraphe/nouveau-template.png](http://www.opixido.com/ocms/screen/paragraphe/nouveau-template.png)

### Paragraphes de base ###

  * Titre + texte
  * Titre + texte + image à gauche