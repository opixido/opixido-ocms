# Ocms Shared login #

## Descriptif ## 

Ce plugin permet d'avoir un mot de passe unique pour une rubrique donnée et ses
sous-rubriques.

Le mot de passe se définit sur la rubrique et toutes ses sous-rubriques sont
automatiquement protégées également.

Il est possible d'avoir plusieurs rubriques protégées par des mots de passe 
différents dans un même site web.

## Configuration ##

Une variable de configuration est ajoutée :

```
$_Gconfig['ocms_sharedlogin']['blocs_to_hide']  = array();
```

Elle permet de définir la liste des blocs à masquer en plus du gen() principal
lorsque la page est masquée.

### Exemple : 

```php
$_Gconfig['ocms_sharedlogin']['blocs_to_hide']  = 
    array(
        'gauche','droite','bas'
    );
```