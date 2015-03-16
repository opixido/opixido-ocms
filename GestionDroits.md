# Gestion des droits des utilisateurs #

## Création d'un utilisateur ##

Comment créer un utilisateur [sur cette page](GestionUtilisateurs.md)

## Attribuer un rôle à un utilisateur ##

Lorsque vous créez un utilisateur, vous pouvez lui attribuer un ou plusieurs rôles d'administration du site.

![http://www.opixido.com/ocms/screen/droits/ajouter-role-utilisateur.png](http://www.opixido.com/ocms/screen/droits/ajouter-role-utilisateur.png)

## Créer un rôle ##

###  ###

Pour créer un rôle d'administration, cliquez sur l'îcone "Plus/Créer" ou ajoutez dans l'url le paramètre "_?curTable=s\_role_" et cliquez sur "Ajouter un élément" comme ci-dessous :
![http://www.opixido.com/ocms/screen/droits/role-table.png](http://www.opixido.com/ocms/screen/droits/role-table.png)

### Ajouter les droits pour chaque table ###

Par défaut, un nouveau role ne dispose d'aucun droit.
  * Indiquez un nom pour ce nouveau rôle
  * Pour ajouter des droits sur une table, cliquez sur le bouton "Nouveau" puis :
    1. Entrez le nom de la table sur laquelle vous souhaitez mettre les droits
    1. Indiquez le type de droit ("Table type") :
      * _all_ : l'utilisateur peut modifier toutes les lignes de la table
      * _per\_user_ : permet de spécifier pour chaque utilisateur quelles sont les lignes qu'il peut modifier (via l'onglet "Admin Droits spécifiques" lors de la création de l'utilisateur)
      * _specific_ : si la table contient le champ "_ocms\_creator_", alors seules les lignes créées par l'utilisateur seront modifiables par ce dernier.
    1. "Table specific" :
    1. "Table champs" : entrez tous les champs modifiables par l'utilisateur (séparés par des virgules, sans espace). Si vous n'indiquez rien, alors l'utilisateur pourra modifier tous les champs.
    1. "Table View" : l'utilisateur peut voir toutes les données.
    1. "Table Add" : l'utilisateur peut ajouter des données.
    1. "Table Edit" : l'utilisateur peut modifier les données.
    1. "Table Delete" : l'utilisateur peut supprimer des données.
    1. "Table Actions" : entrez le nom des actions (définies dans la config) utilisables par l'utilisateur. (ex : validate,unvalidatemoveRubrique). Laissez vide si l'utilisateur a tous les droits.

Ajoutez autant de tables que nécessaire.

### Ajouter des droits spécifiques (Type table"per\_user" sélectionné) ###

Vous pouvez ajouter des droits spécifiques lorsque vous créez ou modifiez un utilisateur.
  * Allez dans la partie "Gestion des utilisateurs".
  * Ajoutez ou modifiez un utilisateur.
  * Sélectionnez l'onglet "Admin Droits Spécifiques"
  * Si le type "per\_user" a été sélectionnez, vous pourrez dès lors sélectionnez les données modifiables par l'utilisateur. Exemple :
![http://www.opixido.com/ocms/screen/arborescence/droits.png](http://www.opixido.com/ocms/screen/arborescence/droits.png)